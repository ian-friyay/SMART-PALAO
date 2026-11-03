# 🥚 Smart Palao — IoT Duck Egg Incubation Dashboard & E-Commerce Platform

**Smart Palao** is a production-grade, full-stack IoT duck egg incubation monitoring dashboard and e-commerce platform built with **Laravel 12 (PHP 8.2+)**, **MySQL 8.4**, and **Tailwind CSS v4**.

It bridges agricultural heritage in **Gloria, Oriental Mindoro** and island distribution in **Boracay** with modern 64-sensel thermal telemetry (**Panasonic AMG8833**), precision humidity telematics (**Sensirion SHT31**), and digital candling classification.

---

## 🎨 Design System: Minimalist Egg Vibe

The entire application uses a cohesive, high-contrast palette calibrated for incubation interfaces:

| UI Token | Hex Code | Visual Application |
|---|---|---|
| **Primary Background** | `#F4F1EA` | Eggshell White canvas across all screens |
| **Primary Accents & Buttons** | `#FFC107` | Yolk Yellow action buttons, active tabs, alerts |
| **Cards & Structural Borders** | `#D9CBB8` | Warm Taupe cards, table borders, dividers |
| **Typography & Icons** | `#3A3A3A` | Charcoal Membrane high-readability text |
| **Status / Cool Zones** | `#89B4C4` | Incubator Blue cool zones, humidity tags, IoT badges |
| **Thermal Runaway Alert** | `#E63946` | Critical hot zone cutoff, flashing alarm banner |

---

## 🔐 Role-Based Access Control (RBAC)

Two distinct roles are strictly enforced via middleware (`App\Http\Middleware\CheckRole`) and Laravel Sanctum tokens:

| Feature / Page | 👑 Farm Owner / Admin (`owner`) | 🛒 Customer / Buyer (`buyer`) |
|---|:---:|:---:|
| **E-Commerce Storefront** (`/`) | ✅ Full Access | ✅ Full Access |
| **About Us & Tech Guide** (`/about`) | ✅ Full Access | ✅ Full Access |
| **Shopping Cart & Checkout** (`/cart`) | ✅ Full Access | ✅ Full Access |
| **Personal Order History** (`/my-orders`) | ✅ Full Access | ✅ Full Access |
| **IoT Incubation Dashboard** (`/admin/dashboard`) | ✅ **FULL ACCESS** | ❌ **FORBIDDEN (403 / Redirected)** |
| **Real-time AMG8833 Thermal Heatmap** | ✅ **LIVE TELEMETRY** | ❌ **HIDDEN** |
| **Hardware PID & Setpoint Controls** | ✅ **WRITABLE** | ❌ **HIDDEN** |
| **Vitality Candling Logs** | ✅ **VIEW / LOG / DELETE** | ❌ **HIDDEN** |
| **Inventory Admin** (`/admin/inventory`) | ✅ **PRICING & STOCK EDIT** | ❌ **HIDDEN** |
| **Order Kanban Board** (`/admin/orders`) | ✅ **KANBAN DISPATCH** | ❌ **HIDDEN** |

> ⚡ **Live Demo Role Switcher**: A top notification bar allows 1-click toggling between `Owner` and `Buyer` modes to immediately test and verify RBAC enforcement without logging out!

---

## 🐣 System Modules

### Module 1: IoT Incubation Dashboard (Owner Only)
- **8x8 AMG8833 Thermal Heatmap**: 64 color-interpolated cells mapping surface egg temperature (Incubator Blue &rarr; Warm Taupe &rarr; Yolk Yellow &rarr; Critical Red). Includes interactive sensel inspection, coordinate readouts (`Row X, Col Y`), temperature offsets, and summary stats (Max, Min, Avg, Uniformity).
- **Ambient Metrics (SHT31)**: Real-time cards displaying chamber temperature (°C), target setpoint delta (`±0.00°C`), and relative humidity (% RH) with membrane moisture equilibrium badges.
- **Hardware Control Panel**: Input fields for incubation setpoint (37.5°C), critical threshold (38.5°C), closed-loop PID mode (`AUTO` vs `MANUAL`), heating relay toggle, and PWM fan duty cycle slider (0-100%). Also manages batch identifier, incubation day (Day 1-28), breed, and egg count.
- **Vitality Classification Log**: Table logging embryo candling audits (`viable` with vascular network vs `non-viable` infertile/blood ring) with live quick-log modal, grid sensel mapping, and automatic viability rate computation.
- **Thermal Runaway Alert System**: Triggered when any temperature breaches the critical threshold (`critical_temp`). Features a pulsing banner, push alert, Web Audio synthesized warning alarm, and an **Emergency Cooldown** interlock (instantly disables heater, forces exhaust fan to 100% PWM).
- **Built-in ESP32 Telemetry Simulator**: Dashboard buttons to simulate normal incoming ESP32 packets or inject critical thermal spikes to test live UI in real-time.

### Module 2: E-Commerce Storefront (Buyer & Public)
- **Branch Selection**: Fast modal/dropdown prompt to choose fulfillment branch: **Gloria (Main Farm)** or **Boracay Island Depot**.
- **Product Catalog (3 Standard Items)**:
  1. **Large Duck Eggs** (65g - 75g jumbo, premium yolk density)
  2. **Medium Duck Eggs** (55g - 64g standard table & incubation eggs)
  3. **Small Duck Eggs** (45g - 54g pullet value pack)
- **Cart & Checkout**: Real-time quantity adjustment, branch delivery calculation, customer shipping input, payment method selection (`Cash on Delivery`, `GCash`, `Bank Transfer`), and unique order numbering (`SP-GLO-YYYYMMDD-XXXX`).
- **About Us**: Narrative highlighting 30+ years of duck farming in Gloria, Mindoro, and why IoT incubation guarantees >99% eggshell viability and freshness.
- **Buyer Order History**: View order items, branch assignment, payment method, and live status badge.

### Module 3: E-Commerce Admin (Owner Only)
- **Inventory Management**: Fast inputs to update stock levels (dozens) and pricing (₱) for Large, Medium, and Small duck eggs independently per branch (**Gloria** vs **Boracay**).
- **Order Fulfillment Board**: 4-column Kanban board (**Pending &rarr; Processing &rarr; Shipped &rarr; Delivered**) with branch filters and 1-click status advancement.

---

## 🗄️ Database Architecture

- **`users`**: `id`, `name`, `email`, `password`, `role` (`'owner'` | `'buyer'`), `phone`, `address`, `timestamps`
- **`chambers`**: `id`, `name`, `location`, `setpoint`, `critical_temp`, `heater_state`, `fan_pwm`, `mode`, `batch_name`, `batch_day`, `duck_breed`, `egg_count`, `timestamps`
- **`sensor_readings`**: `id`, `chamber_id`, `thermal_grid_json` (8x8 array), `ambient_temp`, `humidity`, `heater_active`, `fan_pwm`, `is_thermal_runaway`, `created_at`, `updated_at` (Broadcasts `NewSensorReadingEvent`)
- **`vitality_logs`**: `id`, `chamber_id`, `batch_id`, `grid_position`, `result` (`'viable'` | `'non-viable'`), `candling_day`, `notes`, `timestamps`
- **`products`**: `id`, `branch_location` (`'Gloria'` | `'Boracay'`), `size` (`'Large'` | `'Medium'` | `'Small'`), `price`, `stock`, `weight_range`, `description`, `timestamps`
- **`orders`**: `id`, `order_number`, `user_id`, `branch_location`, `customer_name`, `customer_email`, `customer_phone`, `delivery_address`, `payment_method`, `status`, `total_amount`, `delivery_fee`, `timestamps`
- **`order_items`**: `id`, `order_id`, `product_id`, `size`, `unit_price`, `quantity`, `subtotal`, `timestamps`

---

## 📡 ESP32 Telemetry API & Closed-Loop Directives

### Ingestion Endpoint
`POST /api/telemetry/push`

**Request Payload (Lightweight JSON for microcontrollers):**
```json
{
  "chamber_id": 1,
  "ambient_temp": 37.52,
  "humidity": 65.4,
  "thermal_grid": [37.2, 37.4, 37.5, ... 64 float values ...],
  "heater_active": 1,
  "fan_pwm": 45
}
```

**Response Payload (Returns closed-loop actuator instructions to ESP32):**
```json
{
  "status": "ok",
  "reading_id": 105,
  "alert": false,
  "critical_temp": 38.5,
  "actuators": {
    "heater_state": true,
    "fan_pwm": 45,
    "setpoint": 37.5,
    "mode": "AUTO"
  },
  "timestamp": 1730648400
}
```

An Arduino/C++ firmware ready to flash is provided at:
[`esp32_firmware/smart_palao_amg8833_sht31.ino`](esp32_firmware/smart_palao_amg8833_sht31.ino).

---

## 🚀 Running the Project

### 1. Requirements
- PHP 8.2+
- Composer 2.x
- MySQL 8.x
- Node.js 18+ & npm

### 2. Environment Setup
```bash
# Clone and enter directory
cd C:\laragon\www\SMART-PALAO

# Run Database Migrations & Seeds
php artisan migrate:fresh --seed

# Build Frontend Assets
npm run build
```

### 3. Start Development Server
```bash
php artisan serve
```
Visit: `http://localhost:8000`

### 4. Running the Test Suite
```bash
php artisan test --filter=SmartPalaoSystemTest
```

### 5. CLI Telemetry Simulator
```bash
# Send 10 packets with 2-second delay
php artisan palao:simulate --count=10 --delay=2

# Inject thermal runaway spike
php artisan palao:simulate --spike
```

---

## 👥 Default Evaluator Credentials

| Role | Email | Password | Access Level |
|---|---|---|---|
| **Owner / Admin** | `owner@smartpalao.com` | `password123` | Full IoT Telemetry, Controls, Inventory, Orders |
| **Buyer / User** | `buyer@gmail.com` | `password123` | Storefront, Shopping Cart, Personal Order History |
