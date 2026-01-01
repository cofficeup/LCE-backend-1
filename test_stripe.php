<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Services\Stripe\StripeService;
use App\Services\Payment\PaymentService;
use App\Models\Invoice;
use App\Models\User;

echo "=== Stripe Integration Test ===\n\n";

// Get invoice and user
$invoice = Invoice::first();
if (!$invoice) {
    die("No invoice found. Create a pickup first.\n");
}

$user = User::find($invoice->user_id);
echo "Invoice ID: {$invoice->id}\n";
echo "Invoice Total: \${$invoice->total}\n";
echo "Invoice Status: {$invoice->status}\n";
echo "User: {$user->email}\n\n";

// Test StripeService
$stripeService = app(StripeService::class);
$paymentService = app(PaymentService::class);

try {
    echo "Creating Payment Intent...\n";
    $result = $paymentService->createPaymentIntent($invoice, $user);

    echo "\n✅ SUCCESS!\n";
    echo "Payment Intent ID: {$result['payment_intent_id']}\n";
    echo "Client Secret: " . substr($result['client_secret'], 0, 30) . "...\n";
    echo "Amount: \${$result['amount']}\n";
    echo "Currency: {$result['currency']}\n";

    // Check invoice status
    $invoice->refresh();
    echo "\nInvoice Status (after): {$invoice->status}\n";
} catch (\Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
