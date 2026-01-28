<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create indexes only if they don't already exist to avoid duplicate key errors
        $this->createIndexIfNotExists('product_details', 'idx_product_name', 'product_name');
        $this->createIndexIfNotExists('product_details', 'idx_product_code', 'product_code');
        $this->createIndexIfNotExists('product_details', 'idx_stock_quantity', 'stock_quantity');
        $this->createIndexIfNotExists('product_details', 'idx_status', 'status');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop indexes only if they exist
        $this->dropIndexIfExists('product_details', 'idx_product_name');
        $this->dropIndexIfExists('product_details', 'idx_product_code');
        $this->dropIndexIfExists('product_details', 'idx_stock_quantity');
        $this->dropIndexIfExists('product_details', 'idx_status');
    }

    /**
     * Create an index if it does not already exist.
     */
    protected function createIndexIfNotExists(string $table, string $indexName, string $column)
    {
        $exists = DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$indexName]);
        if (empty($exists)) {
            Schema::table($table, function (Blueprint $t) use ($column, $indexName) {
                $t->index($column, $indexName);
            });
        }
    }

    /**
     * Drop an index if it exists.
     */
    protected function dropIndexIfExists(string $table, string $indexName)
    {
        $exists = DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$indexName]);
        if (!empty($exists)) {
            Schema::table($table, function (Blueprint $t) use ($indexName) {
                $t->dropIndex($indexName);
            });
        }
    }
};
