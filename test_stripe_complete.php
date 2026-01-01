<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\User;

echo "=== Complete Stripe Payment Test ===\n\n";

// Get or create a fresh invoice
$invoice = Invoice::where('status', 'draft')->first();

if (!$invoice) {
    echo "Creating new invoice...\n";
    $invoice = Invoice::create([
        'user_id' => 2,
        'type' => 'ppo',
        'status' => 'draft',
        'currency' => 'USD',
        'subtotal' => 38.00,
        'tax' => 0,
        'total' => 38.00,
    ]);
}

$user = User::find($invoice->user_id);
echo "Invoice ID: {$invoice->id}\n";
echo "Invoice Total: \${$invoice->total}\n";
echo "User: {$user->email}\n\n";

// Initialize Stripe
$stripe = new \Stripe\StripeClient(config('stripe.secret'));

try {
    // Step 1: Create Stripe Customer
    echo "Step 1: Creating/Getting Stripe Customer...\n";
    $stripeCustomer = \App\Models\StripeCustomer::where('user_id', $user->id)->first();

    if (!$stripeCustomer) {
        $customer = $stripe->customers->create([
            'email' => $user->email,
            'name' => $user->first_name . ' ' . $user->last_name,
        ]);
        $stripeCustomer = \App\Models\StripeCustomer::create([
            'user_id' => $user->id,
            'stripe_customer_id' => $customer->id,
        ]);
        echo "Created customer: {$customer->id}\n";
    } else {
        echo "Using existing customer: {$stripeCustomer->stripe_customer_id}\n";
    }

    // Step 2: Create Payment Intent with card-only (no redirects)
    echo "\nStep 2: Creating Payment Intent...\n";
    $amountCents = (int) round($invoice->total * 100);

    $paymentIntent = $stripe->paymentIntents->create([
        'amount' => $amountCents,
        'currency' => 'usd',
        'customer' => $stripeCustomer->stripe_customer_id,
        'payment_method_types' => ['card'], // Card only, no redirects
        'metadata' => [
            'invoice_id' => $invoice->id,
            'user_id' => $user->id,
        ],
    ]);

    echo "Payment Intent: {$paymentIntent->id}\n";
    echo "Client Secret: " . substr($paymentIntent->client_secret, 0, 30) . "...\n";

    // Step 3: Confirm with test card
    echo "\nStep 3: Confirming with test card (4242...)...\n";
    $confirmedIntent = $stripe->paymentIntents->confirm(
        $paymentIntent->id,
        ['payment_method' => 'pm_card_visa']
    );

    echo "Status: {$confirmedIntent->status}\n";

    if ($confirmedIntent->status === 'succeeded') {
        echo "\n🎉 PAYMENT SUCCEEDED!\n";

        // Update invoice
        $invoice->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        // Create payment record
        Payment::create([
            'invoice_id' => $invoice->id,
            'user_id' => $user->id,
            'amount' => $invoice->total,
            'currency' => 'USD',
            'status' => 'succeeded',
            'stripe_payment_intent_id' => $paymentIntent->id,
            'paid_at' => now(),
        ]);

        echo "\n✅ Records Updated:\n";
        echo "Invoice Status: paid\n";
        echo "Payment recorded in database\n";
        echo "\n📊 Check Stripe Dashboard:\n";
        echo "https://dashboard.stripe.com/test/payments/{$paymentIntent->id}\n";
    }
} catch (\Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
}
