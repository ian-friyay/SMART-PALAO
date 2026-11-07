<?php

namespace Tests\Feature;

use App\Models\Chamber;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\SensorReading;
use App\Models\User;
use App\Models\VitalityLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SmartPalaoSystemTest extends TestCase
{
    use RefreshDatabase;

    protected User $owner;
    protected User $buyer;
    protected Chamber $chamber;
    protected Product $productLarge;

    protected function setUp(): void
    {
        parent::setUp();

        $this->owner = User::create([
            'name' => 'Don Palao',
            'email' => 'owner@smartpalao.com',
            'password' => Hash::make('password123'),
            'role' => 'owner',
        ]);

        $this->buyer = User::create([
            'name' => 'Maria Santos',
            'email' => 'buyer@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'buyer',
            'phone' => '+63 928 555 7788',
            'address' => 'Barangay Paclasan, Roxas, Oriental Mindoro',
        ]);

        $this->chamber = Chamber::create([
            'name' => 'Incubator Alpha (Roxas Main)',
            'location' => 'Roxas (Main Farm)',
            'setpoint' => 37.50,
            'critical_temp' => 38.50,
            'mode' => 'AUTO',
            'fan_pwm' => 50,
            'heater_state' => true,
            'batch_name' => 'BATCH-2026-D01',
            'batch_day' => 14,
            'duck_breed' => 'Pateros Mallard',
            'egg_count' => 120,
        ]);

        $this->productLarge = Product::create([
            'branch_location' => 'Roxas',
            'size' => 'Large',
            'price' => 180.00,
            'stock' => 50,
            'weight_range' => '65g - 75g',
            'description' => 'Premium selection',
        ]);

        Product::create([
            'branch_location' => 'Roxas',
            'size' => 'Medium',
            'price' => 150.00,
            'stock' => 60,
            'weight_range' => '55g - 64g',
            'description' => 'Standard eggs',
        ]);

        Product::create([
            'branch_location' => 'Roxas',
            'size' => 'Small',
            'price' => 120.00,
            'stock' => 40,
            'weight_range' => '45g - 54g',
            'description' => 'Pullet eggs',
        ]);
    }

    /**
     * Test Role-Based Access Control: Buyers cannot access IoT admin telemetry.
     */
    public function test_buyer_is_forbidden_from_iot_incubation_dashboard(): void
    {
        $response = $this->actingAs($this->buyer)->get('/admin/dashboard');

        // Should redirect to store with warning
        $response->assertRedirect(route('store'));
        $response->assertSessionHas('error');
    }

    /**
     * Test Role-Based Access Control: Owner can access IoT incubation dashboard.
     */
    public function test_owner_can_access_iot_incubation_dashboard(): void
    {
        $response = $this->actingAs($this->owner)->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Technical Incubation Dashboard');
        $response->assertSee('ESP32-CAM');
    }

    /**
     * Test ESP32 lightweight telemetry push endpoint.
     */
    public function test_esp32_lightweight_push_endpoint_records_reading_and_returns_pid_controls(): void
    {
        $grid = array_fill(0, 64, 37.52);

        $payload = [
            'chamber_id' => $this->chamber->id,
            'ambient_temp' => 37.55,
            'humidity' => 65.2,
            'thermal_grid' => $grid,
            'heater_active' => 1,
            'fan_pwm' => 45,
        ];

        $response = $this->postJson('/api/telemetry/push', $payload);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'reading_id',
            'alert',
            'actuators' => [
                'heater_state',
                'fan_pwm',
                'setpoint',
                'mode',
            ],
            'timestamp',
        ]);
        $response->assertJson(['status' => 'ok', 'alert' => false]);

        $this->assertDatabaseHas('sensor_readings', [
            'chamber_id' => $this->chamber->id,
            'ambient_temp' => 37.55,
            'humidity' => 65.2,
            'is_thermal_runaway' => false,
        ]);
    }

    /**
     * Test thermal runaway trigger when temperatures breach critical ceiling.
     */
    public function test_thermal_runaway_triggers_safety_override_in_auto_mode(): void
    {
        $grid = array_fill(0, 64, 39.4);

        $payload = [
            'chamber_id' => $this->chamber->id,
            'ambient_temp' => 39.2,
            'humidity' => 58.0,
            'thermal_grid' => $grid,
        ];

        $response = $this->postJson('/api/telemetry/push', $payload);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'ok',
            'alert' => true,
            'actuators' => [
                'heater_state' => false, // Killed heater
                'fan_pwm' => 100, // Max exhaust fan
            ],
        ]);

        $this->chamber->refresh();
        $this->assertFalse((bool) $this->chamber->heater_state);
        $this->assertEquals(100, $this->chamber->fan_pwm);
    }

    /**
     * Test Storefront Checkout: Deducts inventory and assigns unique branch order reference.
     */
    public function test_checkout_creates_order_and_deducts_stock(): void
    {
        $initialStock = $this->productLarge->stock;

        $payload = [
            'branch_location' => 'Roxas',
            'customer_name' => 'Maria Santos',
            'customer_email' => 'buyer@gmail.com',
            'customer_phone' => '+63 928 555 7788',
            'delivery_address' => 'Barangay Paclasan, Roxas, Oriental Mindoro',
            'payment_method' => 'Cash on Delivery',
            'notes' => 'Handle with care',
            'items' => [
                [
                    'product_id' => $this->productLarge->id,
                    'quantity' => 2,
                ]
            ]
        ];

        $response = $this->actingAs($this->buyer)->postJson('/checkout', $payload);

        $response->assertStatus(201);
        $response->assertJsonStructure(['success', 'order_number', 'order']);

        $this->productLarge->refresh();
        $this->assertEquals($initialStock - 2, $this->productLarge->stock);

        $this->assertDatabaseHas('orders', [
            'customer_email' => 'buyer@gmail.com',
            'branch_location' => 'Roxas',
            'status' => 'Pending',
        ]);
    }

    /**
     * Test Order Kanban status advancement.
     */
    public function test_owner_can_advance_kanban_order_status(): void
    {
        $order = Order::create([
            'order_number' => 'SP-ROX-TEST-001',
            'user_id' => $this->buyer->id,
            'branch_location' => 'Roxas',
            'customer_name' => 'Maria Santos',
            'customer_email' => 'buyer@gmail.com',
            'customer_phone' => '+63 928 555 7788',
            'delivery_address' => 'Roxas, Oriental Mindoro',
            'payment_method' => 'Cash on Delivery',
            'status' => 'Pending',
            'total_amount' => 360.00,
            'delivery_fee' => 50.00,
        ]);

        $response = $this->actingAs($this->owner)->postJson("/admin/orders/{$order->id}/status", [
            'status' => 'Processing',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $order->refresh();
        $this->assertEquals('Processing', $order->status);
    }

    /**
     * Test Vitality Candling Log.
     */
    public function test_owner_can_log_vitality_classification(): void
    {
        $payload = [
            'chamber_id' => $this->chamber->id,
            'batch_id' => 'BATCH-2026-D01',
            'grid_position' => 'Day 14 Mid-Term Candling',
            'result' => 'viable',
            'candling_day' => 14,
            'notes' => 'Clear embryo motion detected under camera stream.',
        ];

        $response = $this->actingAs($this->owner)->postJson('/api/vitality/logs', $payload);

        $response->assertStatus(201);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('vitality_logs', [
            'grid_position' => 'Day 14 Mid-Term Candling',
            'result' => 'viable',
        ]);
    }
}
