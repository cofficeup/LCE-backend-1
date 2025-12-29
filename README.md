# 🧺 LCE Backend (Laundry Care Express)

A modular, service-driven Laravel 12 backend API for a laundry management platform supporting **Pay-Per-Order (PPO)**, **Subscriptions**, **Credits/Wallet**, **Billing**, **Invoices**, and **Pickup scheduling**.

---

## 📌 Project Status

| Feature                           | Status      |
| --------------------------------- | ----------- |
| Core Domain Services              | ✅ Complete |
| API Endpoints (Preview Flows)     | ✅ Complete |
| Pickup Creation & Billing Preview | ✅ Complete |
| Invoice System (Draft Logic)      | ✅ Complete |
| Database Migrations               | ✅ Complete |
| Database Seeders                  | ✅ Complete |
| Authentication                    | 🔜 Planned  |
| Stripe Integration                | 🔜 Planned  |
| Admin Dashboard                   | 🔜 Planned  |

---

## 🏗️ Architecture Overview

This backend follows a **Service-First Architecture**:

```
Controller (thin)
     ↓
Service Layer (business logic)
     ↓
Domain Models / DTOs
```

### Key Principles

-   ✅ No business logic in controllers
-   ✅ Services are deterministic & testable
-   ✅ Payment logic is isolated
-   ✅ Auth is decoupled from core logic
-   ✅ Designed for legacy DB integration

---

## 📂 Project Structure

```
app/
├── Http/Controllers/Api/V1/
│   ├── BillingController.php
│   ├── CreditController.php
│   ├── PickupController.php
│   └── SubscriptionController.php
│
├── Models/
│   ├── User.php
│   ├── SubscriptionPlan.php
│   ├── UserSubscription.php
│   ├── Credit.php
│   ├── Invoice.php
│   ├── InvoiceLine.php
│   ├── Pickup.php
│   └── Role.php
│
├── Services/
│   ├── Billing/BillingService.php
│   ├── Credit/CreditService.php
│   ├── Invoice/InvoiceService.php
│   ├── Pickup/PickupService.php
│   ├── Pricing/PricingService.php
│   └── Subscription/SubscriptionService.php
│
database/
├── migrations/
└── seeders/
    ├── DatabaseSeeder.php
    ├── SubscriptionPlanSeeder.php
    ├── UserSeeder.php
    ├── RoleSeeder.php
    ├── UserSubscriptionSeeder.php
    └── CreditSeeder.php
```

---

## 🗄️ Database Schema

### Tables

| Table                        | Description                         |
| ---------------------------- | ----------------------------------- |
| `lce_user_info`              | User accounts and profiles          |
| `lce_subscription_plans`     | Available subscription tiers        |
| `lce_user_subscriptions`     | User subscription instances         |
| `lce_subscription_bag_usage` | Bag usage tracking per subscription |
| `lce_user_credits`           | User wallet/credits                 |
| `invoices`                   | Invoice headers                     |
| `invoice_lines`              | Invoice line items                  |
| `roles`                      | System roles (admin, customer, csr) |
| `user_roles`                 | User-role pivot table               |

---

## 🌐 API Endpoints

**Base URL:** `/api/v1`

### Pickups

| Method | Endpoint   | Description                           |
| ------ | ---------- | ------------------------------------- |
| POST   | `/pickups` | Create a pickup (PPO or subscription) |

### Subscriptions

| Method | Endpoint                       | Description                   |
| ------ | ------------------------------ | ----------------------------- |
| POST   | `/subscriptions`               | Create new subscription       |
| POST   | `/subscriptions/{id}/activate` | Activate pending subscription |
| POST   | `/subscriptions/{id}/cancel`   | Cancel subscription           |

### Billing

| Method | Endpoint               | Description             |
| ------ | ---------------------- | ----------------------- |
| POST   | `/billing/ppo/preview` | Get PPO billing preview |

### Credits

| Method | Endpoint   | Description             |
| ------ | ---------- | ----------------------- |
| GET    | `/credits` | Get user credit balance |

> ⚠️ **Note:** Authentication middleware is disabled for development. Add `auth:sanctum` in production.

---

## 🧾 Invoice System

### Invoice Types

-   `ppo` - Pay-Per-Order
-   `subscription_overage` - Subscription overweight charges
-   `adjustment` - Manual adjustments
-   `refund` - Refunds

### Invoice Statuses

-   `draft` - Not yet finalized
-   `pending_payment` - Awaiting payment
-   `paid` - Payment complete
-   `refunded` - Refunded

### Invoice Line Types

-   `weight` - Laundry by weight
-   `minimum_adjustment` - Minimum charge adjustment
-   `pickup_fee` - Pickup service fee
-   `service_fee` - Service fee
-   `overage` - Overweight charges
-   `credit` - Credit applied (negative)
-   `tax` - Tax charges

---

## 🚀 Getting Started

### Prerequisites

-   PHP 8.2+
-   Composer
-   MySQL 8.0+
-   Node.js (for frontend assets)

### Installation

```bash
# Clone the repository
git clone <repository-url>
cd LCE-backend

# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure database in .env
DB_DATABASE=lce_backend
DB_USERNAME=root
DB_PASSWORD=

# Run migrations with seeders
php artisan migrate:fresh --seed

# Start development server
php artisan serve
```

### Demo Credentials

| Email                       | Password      | Role                    |
| --------------------------- | ------------- | ----------------------- |
| `admin@lce.com`             | `password123` | Admin                   |
| `john.doe@example.com`      | `password123` | Customer (Subscription) |
| `jane.smith@example.com`    | `password123` | Customer (PPO)          |
| `bob.wilson@example.com`    | `password123` | Customer (Subscription) |
| `alice.johnson@example.com` | `password123` | Customer (PPO)          |

---

## 🔧 Configuration

### Laravel 12 API Routing

Ensure `bootstrap/app.php` contains:

```php
->withRouting(
    web: __DIR__.'/../routes/web.php',
    api: __DIR__.'/../routes/api.php',
    commands: __DIR__.'/../routes/console.php',
    health: '/up',
)
```

---

## 🧪 Development

### Useful Commands

```bash
# Fresh migration with seed
php artisan migrate:fresh --seed

# Run specific seeder
php artisan db:seed --class=UserSeeder

# Clear all caches
php artisan optimize:clear

# Check routes
php artisan route:list --path=api
```

---

## 📋 Subscription Plans

| Plan     | Bags/Month | Monthly | Annual    |
| -------- | ---------- | ------- | --------- |
| Basic    | 2          | $29.99  | $299.99   |
| Standard | 4          | $49.99  | $499.99   |
| Premium  | 8          | $89.99  | $899.99   |
| Business | 16         | $159.99 | $1,599.99 |

---

## 🔮 Roadmap

-   [ ] Sanctum/JWT Authentication
-   [ ] Stripe Payment Integration
-   [ ] Persistent Invoice Storage
-   [ ] Pickup Scheduling & Cron Jobs
-   [ ] Admin Dashboard API
-   [ ] Email Notifications
-   [ ] SMS Notifications

---

## 📄 License

Private / Proprietary - All rights reserved.
