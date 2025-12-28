<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('lce_user_pickup', function (Blueprint $table) {

        $table->enum('order_type', [
            'PPO',
            'subscription',
            'business'
        ])->default('PPO')->after('id');

        $table->unsignedBigInteger('subscription_id')->nullable()->after('order_type');

        $table->integer('subscription_bags')->nullable()->after('subscription_id');

        $table->decimal('subscription_overweight_lbs', 10, 2)->nullable()->after('subscription_bags');

        $table->decimal('subscription_overweight_charge', 10, 2)->nullable()->after('subscription_overweight_lbs');

    });
}

public function down()
{
    Schema::table('lce_user_pickup', function (Blueprint $table) {

        $table->dropColumn([
            'order_type',
            'subscription_id',
            'subscription_bags',
            'subscription_overweight_lbs',
            'subscription_overweight_charge',
        ]);

    });
}


};
