<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->decimal('retail_price', 15, 2)->default(0)->after('price');
            $table->decimal('wholesale_price', 15, 2)->default(0)->after('retail_price');
        });

        DB::table('categories')->update([
            'retail_price' => DB::raw('price'),
            'wholesale_price' => DB::raw('price'),
        ]);

        Schema::table('sales', function (Blueprint $table) {
            $table->enum('price_type', ['eceran', 'grosir'])->default('eceran')->after('category_id');
        });
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
