<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\SubscriptionController;
use App\Http\Controllers\Api\V1\PickupController;
use App\Http\Controllers\Api\V1\CreditController;
use App\Http\Controllers\Api\V1\BillingController;
use App\Http\Controllers\Api\V1\InvoiceController;
use App\Http\Controllers\Api\V1\Admin\AdminInvoiceController;
use App\Http\Controllers\Api\V1\Admin\AdminDashboardController;


/*
|--------------------------------------------------------------------------
| Public Routes (No Auth)
|--------------------------------------------------------------------------
*/

// Pickups (temp: public for dev, add auth later)
Route::post('/v1/pickups', [PickupController::class, 'store']);
Route::put('/v1/pickups/{id}', [PickupController::class, 'update']);


/*
|--------------------------------------------------------------------------
| Protected Routes (Auth Required)
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {

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
});


/*
|--------------------------------------------------------------------------
| Admin Routes (Auth + Admin Role Required)
|--------------------------------------------------------------------------
*/

Route::prefix('v1/admin')
    ->middleware(['auth:sanctum', 'role:admin'])
    ->group(function () {

        // Admin Invoices
        Route::get('/invoices', [AdminInvoiceController::class, 'index']);
        Route::get('/invoices/export', [AdminInvoiceController::class, 'export']);
        Route::post('/invoices/{invoice}/refund', [AdminInvoiceController::class, 'refund']);

        // Admin Dashboard
        Route::get('/dashboard/summary', [AdminDashboardController::class, 'summary']);
        Route::get('/dashboard/revenue', [AdminDashboardController::class, 'revenue']);
    });
