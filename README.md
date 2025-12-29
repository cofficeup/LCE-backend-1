🧺 LCE Backend (Laravel 12)

A modular, service-driven backend API for a laundry management platform supporting Pay-Per-Order (PPO), Subscriptions, Credits/Wallet, Billing, Invoices, and Pickup scheduling.

This project is designed with clean architecture, domain-driven services, and future-ready integrations (Stripe, scheduling, admin tools).

📌 Project Status

🚧 Backend in active development

Implemented

Core domain services

API endpoints (preview flows)

Pickup creation & billing preview

Invoice domain design (draft logic)

Laravel 12 API routing setup

Deferred (Planned)

Authentication (Sanctum / Breeze / JWT – TBD)

Stripe payment integration

Persistent invoice storage

Scheduling & recurring jobs

Admin dashboards

🧠 Architecture Overview

This backend follows a Service-First Architecture:

Controller (thin)
   ↓
Service Layer (business logic)
   ↓
Domain Models / DTOs

Key Principles

No business logic in controllers

Services are deterministic & testable

Stripe/payment logic is isolated

Auth is decoupled from core logic

Designed for legacy DB integration

🗂️ Project Structure (Relevant)
app/
├── Services/
│   ├── Subscription/
│   │   └── SubscriptionService.php
│   ├── Credit/
│   │   └── CreditService.php
│   ├── Pricing/
│   │   └── PricingService.php
│   ├── Billing/
│   │   └── BillingService.php
│   ├── Pickup/
│   │   └── PickupService.php
│   └── Invoice/
│       └── InvoiceService.php
│
├── Http/
│   └── Controllers/
│       └── Api/
│           └── V1/
│               ├── PickupController.php
│               ├── SubscriptionController.php
│               ├── BillingController.php
│               └── CreditController.php
│
routes/
├── api.php
├── web.php

🔁 Core Domain Flows
1️⃣ Pickup Flow
Pickup Request
 → PickupService
 → BillingService (preview)
 → PricingService + CreditService
 → JSON Preview (no DB writes)


Supports:

PPO pickups

Subscription pickups

Overage billing preview

2️⃣ Subscription Lifecycle

Managed via SubscriptionService:

create (pending)

activate

cancel

renew

calculateAvailableBags

3️⃣ Billing & Pricing

PricingService: pure calculations

BillingService: orchestration & decisions

CreditService: wallet & FIFO credit usage

No Stripe logic yet — fully testable offline.

4️⃣ Invoice System (Draft-Only for Now)

Invoices are generated from billing previews:

Canonical invoice types

Canonical invoice line types

Accounting-safe math (qty × unit_price = amount)

Draft lifecycle only (no DB writes yet)

🧾 Canonical Enums (Locked)
Invoice Types
ppo
subscription_overage
adjustment
refund

Invoice Status
draft
pending_payment
paid
refunded

Invoice Line Types
weight
minimum_adjustment
pickup_fee
service_fee
overage
credit
tax

🌐 API Endpoints (v1)

Base URL:

/api/v1

Pickups
POST /pickups

Subscriptions
POST /subscriptions
POST /subscriptions/{id}/activate
POST /subscriptions/{id}/cancel

Billing
POST /billing/ppo/preview

Credits
GET /credits


⚠️ Authentication middleware is intentionally disabled for now

⚙️ Laravel 12 Routing Note (Important)

Laravel 12 does not auto-load API routes.

Ensure bootstrap/app.php contains:

->withRouting(
    web: __DIR__.'/../routes/web.php',
    api: __DIR__.'/../routes/api.php',
    commands: __DIR__.'/../routes/console.php',
    health: '/up',
)


Without this, /api/* routes will not work.

🧪 Development Notes

Pickup & billing APIs return JSON previews

No DB persistence for pickups or invoices yet

Temporary fallback user may be used during development

All services are safe to unit test independently

🔐 Authentication (Deferred)

Auth strategy (Sanctum / Breeze / JWT) will be decided later.

Current design ensures:

No refactor required when auth is added

$request->user() can be plugged in later

Admin & CSR roles already modeled

🚀 Upcoming Milestones

Invoice persistence & migrations

InvoiceController & admin APIs

Stripe payments & webhooks

Pickup scheduling & cron jobs

Auth & role middleware

Admin dashboard support

👨‍💻 Developer Notes

This project is built with:

Laravel 12

PHP 8.2+

Service-driven architecture

API-first mindset

The codebase prioritizes clarity, auditability, and scalability over quick hacks.

📄 License

Private / Proprietary
All rights reserved.