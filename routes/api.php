<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\SubscriptionController;
use App\Http\Controllers\Api\V1\PickupController;
use App\Http\Controllers\Api\V1\CreditController;
use App\Http\Controllers\Api\V1\BillingController;


Route::prefix('v1')->middleware('auth:sanctum')->group(function () {

    Route::post('/subscriptions', [SubscriptionController::class, 'store']);
    Route::post('/subscriptions/{id}/activate', [SubscriptionController::class, 'activate']);
    Route::post('/subscriptions/{id}/cancel', [SubscriptionController::class, 'cancel']);

});


Route::post('/v1/billing/ppo/preview', [BillingController::class, 'ppoPreview'])
    ->middleware('auth:sanctum');

Route::get('/v1/credits', [CreditController::class, 'index'])
    ->middleware('auth:sanctum');


Route::post('/v1/pickups', [PickupController::class, 'store']);
