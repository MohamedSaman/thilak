<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('product_details', function (Blueprint $table) {
            if (!Schema::hasColumn('product_details', 'image_url')) {
                $table->string('image_url')->nullable()->after('product_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_details', function (Blueprint $table) {
            if (Schema::hasColumn('product_details', 'image_url')) {
                $table->dropColumn('image_url');
            }
        });
    }
};
