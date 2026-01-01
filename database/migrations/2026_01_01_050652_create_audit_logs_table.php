<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->nullable(); // admin or system
            $table->string('action');                 // e.g. invoice_refund
            $table->string('entity_type');            // e.g. invoice
            $table->unsignedBigInteger('entity_id');  // invoice_id
            $table->json('metadata')->nullable();     // reason, old/new values

            $table->timestamps();

            $table->index(['entity_type', 'entity_id']);
            $table->index('action');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
