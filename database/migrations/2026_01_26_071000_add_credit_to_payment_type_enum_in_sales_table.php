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
        // For MySQL, we need to modify the enum by changing the column definition
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE sales MODIFY payment_type ENUM('full', 'partial', 'credit') NOT NULL DEFAULT 'full'");
        } else {
            // For other databases, use the schema builder
            Schema::table('sales', function (Blueprint $table) {
                $table->enum('payment_type', ['full', 'partial', 'credit'])->default('full')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to the original enum values
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE sales MODIFY payment_type ENUM('full', 'partial') NOT NULL DEFAULT 'full'");
        } else {
            Schema::table('sales', function (Blueprint $table) {
                $table->enum('payment_type', ['full', 'partial'])->default('full')->change();
            });
        }
    }
};
