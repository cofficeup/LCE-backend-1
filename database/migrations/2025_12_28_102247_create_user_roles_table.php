<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('user_roles', function (Blueprint $table) {
        $table->id();

        $table->unsignedBigInteger('user_id');
        $table->unsignedBigInteger('role_id');

        $table->timestamps();

        $table->index('user_id');
        $table->index('role_id');
        $table->unique(['user_id', 'role_id']);
    });
}

public function down()
{
    Schema::dropIfExists('user_roles');
}

};
