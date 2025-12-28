<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('lce_user_credits', function (Blueprint $table) {
        $table->id();

        $table->unsignedBigInteger('user_id');

        $table->enum('type', [
            'welcome',
            'promo',
            'manual',
            'refund'
        ]);

        $table->string('description', 255);
        $table->decimal('amount', 10, 2);
        $table->decimal('balance', 10, 2);

        $table->timestamp('expires_at')->nullable();
        $table->boolean('used')->default(false);

        $table->timestamps();

        $table->index('user_id');
        $table->index('type');
    });
}

public function down()
{
    Schema::dropIfExists('lce_user_credits');
}

};
