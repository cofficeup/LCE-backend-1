<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
{
    Schema::create('lce_subscription_bag_usage', function (Blueprint $table) {
        $table->id();

        $table->unsignedBigInteger('user_subscription_id');
        $table->unsignedBigInteger('pickup_id')->nullable();
        $table->unsignedBigInteger('invoice_id')->nullable();

        $table->integer('bags_used')->default(1);

        $table->timestamps();

        $table->index('user_subscription_id');
        $table->index('pickup_id');
        $table->index('invoice_id');
    });
}

public function down()
{
    Schema::dropIfExists('lce_subscription_bag_usage');
}

};
