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
        if (! Schema::hasColumn('sales', 'price_type')) {
            Schema::table('sales', function (Blueprint $table) {
                $table->enum('price_type', ['eceran', 'grosir'])->default('eceran')->after('category_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('price_type');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['retail_price', 'wholesale_price']);
        });
    }
};
