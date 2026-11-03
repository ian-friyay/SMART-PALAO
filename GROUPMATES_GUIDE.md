# 🥚 Smart Palao — Quick Start Guide for Groupmates

Hello team! Here is how to run the **Smart Palao** incubation & e-commerce project locally on your computer without needing any cloud hosting.

---

## ⚡ Option A: 1-Click Setup (Windows Users)

If you are on Windows and have **PHP**, **Composer**, and **MySQL (Laragon or XAMPP)** installed:

1. **Extract the ZIP** file into your web server folder (e.g. `C:\laragon\www\SMART-PALAO` or any folder).
2. Start **MySQL** in Laragon or XAMPP.
3. Simply **double-click** the file:
   ```cmd
   setup.bat
   ```
4. That's it! It will automatically configure `.env`, install composer libraries, create and seed the database, and open `http://localhost:8000` in your browser.

---

## 🛠️ Option B: Manual Setup (Windows, macOS, or Linux)

If you prefer to run commands manually in your terminal/PowerShell:

### 1. Copy Environment Config
```bash
cp .env.example .env
```

### 2. Install PHP Dependencies
```bash
composer install
```
*(Note: You do **not** need `npm install` because production CSS and JS are already compiled inside `public/build`!)*

### 3. Generate Security Key
```bash
php artisan key:generate
```

### 4. Setup MySQL Database
Make sure your MySQL is running, then either:

- **Method 1 (Artisan Automatic)**:
  ```bash
  php artisan migrate:fresh --seed
  ```
- **Method 2 (SQL Import)**:
  Create database `smart_palao` in phpMyAdmin or HeidiSQL, then import:
  `database/smart_palao.sql`

### 5. Launch the Web Application
```bash
php artisan serve
```
Open your browser and navigate to:
👉 **[http://localhost:8000](http://localhost:8000)**

---

## 🔑 Login Accounts (Pre-configured)

You can use the 1-click **"👑 Switch to Owner"** or **"🛒 Switch to Buyer"** pill in the top navigation bar, or log in with these credentials:

| Role | Email | Password | What You Can Access |
|---|---|---|---|
| **Owner / Admin** | `owner@smartpalao.com` | `password123` | IoT Dashboard, 8x8 Thermal Heatmap, PID Controls, Inventory Management, Order Kanban Board |
| **Buyer / Customer** | `buyer@gmail.com` | `password123` | Egg Storefront, Branch Selector, Cart & Checkout, Personal Order History (Telemetry is hidden) |

---

## 🎮 Features to Test for Presentation

### 1. IoT Incubation Dashboard (Owner Only)
- Visit `/admin/dashboard`
- **8x8 Thermal Heatmap**: Hover over any of the 64 sensel cells to view coordinate and exact egg temperature.
- **Thermal Runaway Alert System**: Click the red **"🔥 Inject Thermal Spike"** button to simulate temperature exceeding 38.5°C. Notice the flashing alert banner, audio alarm, and automated safety interlock (cuts heater and forces fan to 100% PWM).
- **Control Panel**: Adjust setpoint temperature (37.5°C) and PID fan speed, then click Deploy.
- **Vitality Log**: Click **"+ Log Egg Candling"** to record an embryo candling classification.

### 2. E-Commerce Storefront (Buyer & Public)
- Visit `/`
- **Branch Selection**: Switch fulfillment hub between **Gloria (Main)** and **Boracay**. Notice stock and prices update.
- **Shopping Cart**: Add Large, Medium, or Small eggs to cart.
- **Checkout**: Complete an order using Cash on Delivery, GCash, or Bank Transfer.

### 3. Order Kanban Board (Owner Only)
- Visit `/admin/orders`
- Move orders between **Pending &rarr; Processing &rarr; Shipped &rarr; Delivered** with 1 click.

### 4. Dark Mode
- Click the **🌙 / ☀️ icon** in the top navbar to switch between Minimalist Eggshell White and Charcoal Membrane Dark Mode!

### 5. Terminal Telemetry Simulator
To simulate real-time ESP32 sensor transmissions from the terminal:
```bash
php artisan palao:simulate --count=10 --delay=2
```
