<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Check and add composite index on invoices (status, created_at)
        if (!$this->indexExists('invoices', 'invoices_status_created_at_index')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->index(['status', 'created_at'], 'invoices_status_created_at_index');
            });
        }

        // user_id index likely already exists from foreign key
        // Only add if not present
        if (
            !$this->indexExists('invoices', 'invoices_user_id_index') &&
            !$this->indexExists('invoices', 'invoices_user_id_foreign')
        ) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->index('user_id', 'invoices_user_id_index');
            });
        }
    }

    public function down(): void
    {
        if ($this->indexExists('invoices', 'invoices_status_created_at_index')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->dropIndex('invoices_status_created_at_index');
            });
        }
    }

    /**
     * Check if an index exists on a table.
     */
    private function indexExists(string $table, string $indexName): bool
    {
        $indexes = DB::select("SHOW INDEX FROM `$table` WHERE Key_name = ?", [$indexName]);
        return count($indexes) > 0;
    }
};
