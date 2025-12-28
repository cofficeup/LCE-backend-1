<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
public function up()
{
    Schema::create('lce_user_subscriptions', function (Blueprint $table) {
        $table->id();

        $table->unsignedBigInteger('user_id');
        $table->unsignedBigInteger('plan_id');

        $table->enum('status', [
            'pending',
            'active',
            'paused',
            'cancelled',
            'upgraded'
        ])->default('active');

        $table->enum('billing_cycle', ['monthly', 'annual']);

        $table->date('start_date');
        $table->date('end_date');
        $table->date('next_renewal_date');

        $table->integer('bags_plan_period');
        $table->integer('bags_plan_total');
        $table->integer('bags_plan_balance')->default(0);
        $table->integer('bags_plan_used')->default(0);
        $table->integer('bags_available')->default(1);

        $table->decimal('payment_last', 10, 2)->default(0);
        $table->decimal('payment_discount', 10, 2)->default(0);
        $table->decimal('payment_balance', 10, 2)->default(0);

        $table->text('notes')->nullable();

        $table->timestamps();

        $table->index('user_id');
        $table->index('plan_id');
        $table->index('status');
    });
}

public function down()
{
    Schema::dropIfExists('lce_user_subscriptions');
}


};
