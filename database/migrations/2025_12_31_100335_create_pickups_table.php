<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pickups', function (Blueprint $table) {
            $table->id();

            // Foreign keys
            $table->foreignId('user_id')->constrained('lce_user_info')->onDelete('cascade');
            $table->foreignId('subscription_id')->nullable()->constrained('lce_user_subscriptions')->onDelete('set null');
            $table->foreignId('invoice_id')->nullable()->constrained('invoices')->onDelete('set null');

            // Pickup details
            $table->enum('order_type', ['ppo', 'subscription'])->default('ppo');
            $table->enum('status', [
                'pending_payment',
                'scheduled',
                'picked_up',
                'processing',
                'ready_for_delivery',
                'delivered',
                'cancelled'
            ])->default('pending_payment');

            $table->date('pickup_date')->nullable();
            $table->decimal('estimated_weight', 8, 2)->nullable();
            $table->decimal('actual_weight', 8, 2)->nullable();
            $table->integer('bags_used')->nullable();

            // Address info
            $table->text('pickup_address')->nullable();
            $table->text('delivery_address')->nullable();
            $table->text('notes')->nullable();

            // Timestamps
            $table->timestamp('picked_up_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();

            // Indexes for performance
            $table->index('pickup_date');
            $table->index('status');
            $table->index(['status', 'pickup_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pickups');
    }
};
