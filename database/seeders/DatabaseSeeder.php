<?php

namespace Database\Seeders;

use App\Models\Chamber;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\SensorReading;
use App\Models\User;
use App\Models\VitalityLog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Users (Owner & Buyer)
        $owner = User::firstOrCreate(
            ['email' => 'owner@smartpalao.com'],
            [
                'name' => 'Don Palao (Farm Owner)',
                'password' => Hash::make('password123'),
                'role' => 'owner',
                'phone' => '+63 917 888 2026',
                'address' => 'Palao Farmstead, Gloria, Oriental Mindoro, Philippines',
            ]
        );

        $buyer = User::firstOrCreate(
            ['email' => 'buyer@gmail.com'],
            [
                'name' => 'Maria Santos (Egg Buyer)',
                'password' => Hash::make('password123'),
                'role' => 'buyer',
                'phone' => '+63 928 555 7788',
                'address' => 'Villa de Oro Beach Resort, Station 2, Boracay Island, Malay, Aklan',
            ]
        );

        // 2. Chambers
        $chamber1 = Chamber::firstOrCreate(
            ['name' => 'Incubator Alpha (Gloria Main)'],
            [
                'location' => 'Gloria (Main Farm)',
                'setpoint' => 37.50,
                'critical_temp' => 38.50,
                'heater_state' => true,
                'fan_pwm' => 45,
                'mode' => 'AUTO',
                'batch_name' => 'BATCH-2026-D01',
                'batch_day' => 14,
                'duck_breed' => 'Pateros Mallard (Itik Pinas)',
                'egg_count' => 120,
                'api_key' => 'palao_esp32_secret_key_2026',
            ]
        );

        $chamber2 = Chamber::firstOrCreate(
            ['name' => 'Hatcher Beta (Gloria Unit 2)'],
            [
                'location' => 'Gloria (Main Farm)',
                'setpoint' => 37.00,
                'critical_temp' => 38.20,
                'heater_state' => false,
                'fan_pwm' => 70,
                'mode' => 'AUTO',
                'batch_name' => 'BATCH-2026-D02',
                'batch_day' => 26,
                'duck_breed' => 'Pateros Mallard (Itik Pinas)',
                'egg_count' => 96,
                'api_key' => 'palao_esp32_beta_secret',
            ]
        );

        // 3. Products per branch (Gloria & Boracay)
        $catalog = [
            // Gloria Branch
            [
                'branch_location' => 'Gloria',
                'size' => 'Large',
                'price' => 180.00, // per dozen
                'stock' => 180,
                'weight_range' => '65g - 75g per egg',
                'description' => 'Grade-A jumbo duck eggs from free-range Gloria farm paddocks. Superb yolk density, ideal for gourmet cooking, salting, or premium balut.',
                'image_url' => '/images/egg-large.png',
            ],
            [
                'branch_location' => 'Gloria',
                'size' => 'Medium',
                'price' => 150.00,
                'stock' => 260,
                'weight_range' => '55g - 64g per egg',
                'description' => 'Standard premium duck eggs carefully calibrated in our IoT incubation hatchery for peak membrane freshness and fertility.',
                'image_url' => '/images/egg-medium.png',
            ],
            [
                'branch_location' => 'Gloria',
                'size' => 'Small',
                'price' => 120.00,
                'stock' => 110,
                'weight_range' => '45g - 54g per egg',
                'description' => 'Nutrient-rich pullet duck eggs from young hens. Excellent value pack for daily farm-to-table breakfast and baking.',
                'image_url' => '/images/egg-small.png',
            ],
            // Boracay Branch
            [
                'branch_location' => 'Boracay',
                'size' => 'Large',
                'price' => 210.00,
                'stock' => 85,
                'weight_range' => '65g - 75g per egg',
                'description' => 'Island-fulfilled premium jumbo duck eggs delivered fresh daily to Boracay resorts, restaurants, and residents.',
                'image_url' => '/images/egg-large.png',
            ],
            [
                'branch_location' => 'Boracay',
                'size' => 'Medium',
                'price' => 180.00,
                'stock' => 140,
                'weight_range' => '55g - 64g per egg',
                'description' => 'Fresh island stock of medium duck eggs with rich golden yolk. Sourced directly from our Gloria incubation hub.',
                'image_url' => '/images/egg-medium.png',
            ],
            [
                'branch_location' => 'Boracay',
                'size' => 'Small',
                'price' => 145.00,
                'stock' => 60,
                'weight_range' => '45g - 54g per egg',
                'description' => 'Fresh pullet duck eggs available for express island drop-off across Boracay Stations 1, 2, and 3.',
                'image_url' => '/images/egg-small.png',
            ],
        ];

        foreach ($catalog as $item) {
            Product::updateOrCreate(
                ['branch_location' => $item['branch_location'], 'size' => $item['size']],
                $item
            );
        }

        // 4. Initial Sensor Readings (30 past records)
        SensorReading::where('chamber_id', $chamber1->id)->delete();
        $baseTemp = 37.5;
        $now = now();
        for ($k = 30; $k >= 0; $k--) {
            $ambient = round($baseTemp + (sin($k / 3) * 0.25) + (mt_rand(-10, 10) / 100), 2);
            $humidity = round(65.0 + (cos($k / 4) * 1.5) + (mt_rand(-10, 10) / 10), 1);
            $grid = [];
            for ($i = 0; $i < 64; $i++) {
                $row = intdiv($i, 8);
                $col = $i % 8;
                $dist = sqrt(pow($row - 3.5, 2) + pow($col - 3.5, 2));
                $cellTemp = $ambient + (0.22 - ($dist * 0.08)) + (mt_rand(-12, 12) / 100);
                $grid[] = round($cellTemp, 2);
            }

            SensorReading::create([
                'chamber_id' => $chamber1->id,
                'thermal_grid_json' => $grid,
                'ambient_temp' => $ambient,
                'humidity' => $humidity,
                'heater_active' => $ambient < $baseTemp,
                'fan_pwm' => $ambient < $baseTemp ? 35 : 55,
                'is_thermal_runaway' => false,
                'created_at' => (clone $now)->subMinutes($k * 2),
                'updated_at' => (clone $now)->subMinutes($k * 2),
            ]);
        }

        // 5. Vitality Logs (Embryo candling results)
        VitalityLog::where('chamber_id', $chamber1->id)->delete();
        $candlingData = [
            ['grid_position' => 'Row 1, Col 1', 'result' => 'viable', 'notes' => 'Distinct heart pulsation and dark eye spot visible.'],
            ['grid_position' => 'Row 1, Col 4', 'result' => 'viable', 'notes' => 'Strong spider vascular web filling 60% of egg.'],
            ['grid_position' => 'Row 2, Col 3', 'result' => 'viable', 'notes' => 'Active embryo movement under high-intensity LED.'],
            ['grid_position' => 'Row 2, Col 7', 'result' => 'non-viable', 'notes' => 'Blood ring detected. Early embryonic cessation.'],
            ['grid_position' => 'Row 3, Col 2', 'result' => 'viable', 'notes' => 'Dense vascularization, clear air cell boundary.'],
            ['grid_position' => 'Row 3, Col 5', 'result' => 'viable', 'notes' => 'Normal 14-day duck embryo size, clear allantois.'],
            ['grid_position' => 'Row 4, Col 4', 'result' => 'viable', 'notes' => 'Core cluster egg showing excellent thermal emission.'],
            ['grid_position' => 'Row 5, Col 2', 'result' => 'non-viable', 'notes' => 'Clear / Infertile egg. Transferred out of chamber.'],
            ['grid_position' => 'Row 5, Col 6', 'result' => 'viable', 'notes' => 'Vigorous vascular tree, well-defined air pocket.'],
            ['grid_position' => 'Row 6, Col 3', 'result' => 'viable', 'notes' => 'Excellent embryo development, responsive to candling.'],
            ['grid_position' => 'Row 7, Col 5', 'result' => 'viable', 'notes' => 'Optimal development corresponding to Day 14.'],
            ['grid_position' => 'Row 8, Col 2', 'result' => 'viable', 'notes' => 'Embryo active with robust blood vessels.'],
        ];

        foreach ($candlingData as $cd) {
            VitalityLog::create([
                'chamber_id' => $chamber1->id,
                'batch_id' => 'BATCH-2026-D01',
                'grid_position' => $cd['grid_position'],
                'result' => $cd['result'],
                'candling_day' => 14,
                'notes' => $cd['notes'],
            ]);
        }

        // 6. Sample Orders across Kanban stages
        Order::whereIn('customer_email', ['buyer@gmail.com', 'hotel@boracayhaven.com', 'chef@gloriabistro.ph'])->delete();

        $sampleOrders = [
            [
                'order_number' => 'SP-GLO-20261103-A101',
                'user_id' => $buyer->id,
                'branch_location' => 'Gloria',
                'customer_name' => 'Maria Santos',
                'customer_email' => 'buyer@gmail.com',
                'customer_phone' => '+63 928 555 7788',
                'delivery_address' => 'Barangay Maligaya, Gloria, Oriental Mindoro',
                'payment_method' => 'GCash',
                'status' => 'Pending',
                'total_amount' => 510.00,
                'delivery_fee' => 50.00,
                'notes' => 'Please leave with security if not home. Call upon arrival.',
                'items' => [
                    ['size' => 'Large', 'unit_price' => 180.00, 'quantity' => 2, 'subtotal' => 360.00],
                    ['size' => 'Medium', 'unit_price' => 150.00, 'quantity' => 1, 'subtotal' => 150.00],
                ]
            ],
            [
                'order_number' => 'SP-BOR-20261103-B202',
                'user_id' => null,
                'branch_location' => 'Boracay',
                'customer_name' => 'Boracay Haven Resort Kitchen',
                'customer_email' => 'hotel@boracayhaven.com',
                'customer_phone' => '+63 919 444 8822',
                'delivery_address' => 'Station 2 Beachfront, Boracay Island',
                'payment_method' => 'Bank Transfer',
                'status' => 'Processing',
                'total_amount' => 1260.00,
                'delivery_fee' => 75.00,
                'notes' => 'For morning breakfast buffet service. Deliver by 7:00 AM.',
                'items' => [
                    ['size' => 'Large', 'unit_price' => 210.00, 'quantity' => 6, 'subtotal' => 1260.00],
                ]
            ],
            [
                'order_number' => 'SP-BOR-20261102-C303',
                'user_id' => null,
                'branch_location' => 'Boracay',
                'customer_name' => 'Chef Alejandro Cruz',
                'customer_email' => 'chef@boracaygourmet.ph',
                'customer_phone' => '+63 917 333 1199',
                'delivery_address' => 'Diniwid Road, Barangay Yapak, Boracay',
                'payment_method' => 'Cash on Delivery',
                'status' => 'Shipped',
                'total_amount' => 705.00,
                'delivery_fee' => 75.00,
                'notes' => 'Rider is on electric trike courier route.',
                'items' => [
                    ['size' => 'Medium', 'unit_price' => 180.00, 'quantity' => 2, 'subtotal' => 360.00],
                    ['size' => 'Small', 'unit_price' => 145.00, 'quantity' => 2, 'subtotal' => 290.00],
                ]
            ],
            [
                'order_number' => 'SP-GLO-20261101-D404',
                'user_id' => $buyer->id,
                'branch_location' => 'Gloria',
                'customer_name' => 'Maria Santos',
                'customer_email' => 'buyer@gmail.com',
                'customer_phone' => '+63 928 555 7788',
                'delivery_address' => 'Barangay Maligaya, Gloria, Oriental Mindoro',
                'payment_method' => 'Cash on Delivery',
                'status' => 'Delivered',
                'total_amount' => 330.00,
                'delivery_fee' => 50.00,
                'notes' => 'Successfully delivered to front gate.',
                'items' => [
                    ['size' => 'Medium', 'unit_price' => 150.00, 'quantity' => 1, 'subtotal' => 150.00],
                    ['size' => 'Large', 'unit_price' => 180.00, 'quantity' => 1, 'subtotal' => 180.00],
                ]
            ],
        ];

        foreach ($sampleOrders as $so) {
            $items = $so['items'];
            unset($so['items']);
            $order = Order::create($so);

            foreach ($items as $it) {
                $product = Product::where('branch_location', $order->branch_location)
                    ->where('size', $it['size'])
                    ->first();

                $it['product_id'] = $product ? $product->id : null;
                $order->items()->create($it);
            }
        }
    }
}
