<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HealthController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\SubscriptionController;
use App\Http\Controllers\Api\V1\PickupController;
use App\Http\Controllers\Api\V1\CreditController;
use App\Http\Controllers\Api\V1\BillingController;
use App\Http\Controllers\Api\V1\InvoiceController;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\Api\V1\StripeWebhookController;
use App\Http\Controllers\Api\V1\Admin\AdminInvoiceController;
use App\Http\Controllers\Api\V1\Admin\AdminDashboardController;


/*
|--------------------------------------------------------------------------
| Health Check (Public)
|--------------------------------------------------------------------------
*/

Route::get('/health', HealthController::class);

/*
|--------------------------------------------------------------------------
| Public Auth Routes (Strict Rate Limit)
|--------------------------------------------------------------------------
*/

Route::prefix('v1/auth')
    ->middleware('throttle:10,1') // 10 requests per minute for auth
    ->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
    });


/*
|--------------------------------------------------------------------------
| Protected Routes (Auth + API Rate Limit)
|--------------------------------------------------------------------------
*/

Route::prefix('v1')
    ->middleware(['auth:sanctum', 'throttle:api'])
    ->group(function () {

        // Auth (protected)
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/me', [AuthController::class, 'me']);

        // Pickups
        Route::post('/pickups/preview', [PickupController::class, 'preview']);
        Route::post('/pickups/confirm', [PickupController::class, 'confirm']);
        Route::put('/pickups/{id}', [PickupController::class, 'update']);

        // Subscriptions
        Route::post('/subscriptions', [SubscriptionController::class, 'store']);
        Route::post('/subscriptions/{id}/activate', [SubscriptionController::class, 'activate']);
        Route::post('/subscriptions/{id}/cancel', [SubscriptionController::class, 'cancel']);

        // Billing
        Route::post('/billing/ppo/preview', [BillingController::class, 'ppoPreview']);

        // Credits
        Route::get('/credits', [CreditController::class, 'index']);

        // Invoices (customer)
        Route::get('/invoices', [InvoiceController::class, 'index']);
        Route::get('/invoices/{invoice}', [InvoiceController::class, 'show']);
        Route::post('/invoices/{invoice}/pay', [InvoiceController::class, 'pay']);

        // Payments
        Route::post('/payments/intent', [PaymentController::class, 'createIntent']);
        Route::get('/payments/status/{invoice}', [PaymentController::class, 'status']);
    });


/*
|--------------------------------------------------------------------------
| Stripe Webhook (No Auth - Uses Signature Verification)
|--------------------------------------------------------------------------
*/

Route::post('/v1/webhooks/stripe', [StripeWebhookController::class, 'handle'])
    ->withoutMiddleware(['throttle:api']);


/*
|--------------------------------------------------------------------------
| Admin Routes (Auth + Admin Role + Stricter Rate Limit)
|--------------------------------------------------------------------------
*/

Route::prefix('v1/admin')
    ->middleware(['auth:sanctum', 'role:admin', 'throttle:30,1']) // 30 requests per minute
    ->group(function () {

        // Admin Invoices
        Route::get('/invoices', [AdminInvoiceController::class, 'index']);
        Route::get('/invoices/export', [AdminInvoiceController::class, 'export']);
        Route::post('/invoices/{invoice}/refund', [AdminInvoiceController::class, 'refund']);

        // Admin Dashboard
        Route::get('/dashboard/summary', [AdminDashboardController::class, 'summary']);
        Route::get('/dashboard/revenue', [AdminDashboardController::class, 'revenue']);
    });
