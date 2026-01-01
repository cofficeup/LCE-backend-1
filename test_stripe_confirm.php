<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Payment;

echo "=== Stripe Payment Confirmation Test ===\n\n";

// Get the pending payment
$payment = Payment::where('status', 'pending')->first();

if (!$payment) {
    die("No pending payment found. Run test_stripe.php first.\n");
}

echo "Payment ID: {$payment->id}\n";
echo "Payment Intent: {$payment->stripe_payment_intent_id}\n";
echo "Amount: \${$payment->amount}\n\n";

// Initialize Stripe
$stripe = new \Stripe\StripeClient(config('stripe.secret'));

try {
    echo "Confirming payment with test card (4242424242424242)...\n\n";

    // Confirm the payment intent with a test payment method
    $paymentIntent = $stripe->paymentIntents->confirm(
        $payment->stripe_payment_intent_id,
        [
            'payment_method' => 'pm_card_visa', // Stripe's built-in test card
        ]
    );

    echo "✅ Payment Confirmed!\n";
    echo "Status: {$paymentIntent->status}\n";
    echo "Payment Intent ID: {$paymentIntent->id}\n";

    if ($paymentIntent->status === 'succeeded') {
        echo "\n🎉 Payment succeeded! Check your Stripe dashboard now.\n";
        echo "Dashboard URL: https://dashboard.stripe.com/test/payments/{$paymentIntent->id}\n";

        // Manually update our records (normally webhook does this)
        $payment->update([
            'status' => 'succeeded',
            'paid_at' => now(),
        ]);

        $payment->invoice->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        echo "\n✅ Local records updated:\n";
        echo "Payment status: succeeded\n";
        echo "Invoice status: paid\n";
    }
} catch (\Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
}
