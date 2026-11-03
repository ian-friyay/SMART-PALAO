<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chambers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('Incubator Alpha');
            $table->string('location')->default('Gloria (Main Farm)');
            $table->decimal('setpoint', 4, 2)->default(37.50); // Target temperature in °C
            $table->decimal('critical_temp', 4, 2)->default(38.50); // Thermal runaway threshold in °C
            $table->boolean('heater_state')->default(false);
            $table->unsignedTinyInteger('fan_pwm')->default(50); // 0-100%
            $table->string('mode')->default('AUTO'); // 'AUTO' | 'MANUAL'
            $table->string('batch_name')->nullable()->default('BATCH-2026-D01');
            $table->unsignedSmallInteger('batch_day')->default(14); // Incubation day (1-28)
            $table->string('duck_breed')->default('Pateros Mallard (Itik Pinas)');
            $table->unsignedInteger('egg_count')->default(120);
            $table->string('api_key')->nullable()->default('palao_esp32_secret_key_2026');
            $table->timestamps();
        });

        Schema::create('sensor_readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chamber_id')->constrained('chambers')->cascadeOnDelete();
            $table->json('thermal_grid_json'); // 8x8 AMG8833 array (64 float values)
            $table->decimal('ambient_temp', 4, 2); // SHT31 temperature
            $table->decimal('humidity', 4, 2); // SHT31 relative humidity %
            $table->boolean('heater_active')->default(false);
            $table->unsignedTinyInteger('fan_pwm')->default(0);
            $table->boolean('is_thermal_runaway')->default(false);
            $table->timestamps();

            $table->index(['chamber_id', 'created_at']);
        });

        Schema::create('vitality_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chamber_id')->constrained('chambers')->cascadeOnDelete();
            $table->string('batch_id')->default('BATCH-2026-D01');
            $table->string('grid_position'); // e.g. "Row 3, Col 4" or "C4"
            $table->enum('result', ['viable', 'non-viable']);
            $table->unsignedSmallInteger('candling_day')->nullable()->default(14);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->enum('branch_location', ['Gloria', 'Boracay']);
            $table->enum('size', ['Large', 'Medium', 'Small']);
            $table->decimal('price', 8, 2); // Price per dozen
            $table->unsignedInteger('stock')->default(0);
            $table->string('weight_range')->nullable(); // e.g., "65g - 75g"
            $table->text('description')->nullable();
            $table->string('image_url')->nullable();
            $table->timestamps();

            $table->unique(['branch_location', 'size']);
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('branch_location', ['Gloria', 'Boracay']);
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->text('delivery_address');
            $table->string('payment_method')->default('Cash on Delivery'); // 'Cash on Delivery', 'GCash', 'Bank Transfer'
            $table->enum('status', ['Pending', 'Processing', 'Shipped', 'Delivered'])->default('Pending');
            $table->decimal('total_amount', 10, 2);
            $table->decimal('delivery_fee', 8, 2)->default(0.00);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['branch_location', 'status']);
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->enum('size', ['Large', 'Medium', 'Small']);
            $table->decimal('unit_price', 8, 2);
            $table->unsignedInteger('quantity');
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('products');
        Schema::dropIfExists('vitality_logs');
        Schema::dropIfExists('sensor_readings');
        Schema::dropIfExists('chambers');
    }
};
