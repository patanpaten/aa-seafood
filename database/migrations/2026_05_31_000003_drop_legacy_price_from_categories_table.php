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
        if (! Schema::hasColumn('categories', 'price')) {
            return;
        }

        DB::statement('
            UPDATE categories
            SET retail_price = CASE
                    WHEN retail_price IS NULL OR retail_price = 0 THEN price
                    ELSE retail_price
                END,
                wholesale_price = CASE
                    WHEN wholesale_price IS NULL OR wholesale_price = 0 THEN price
                    ELSE wholesale_price
                END
        ');

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('categories', 'price')) {
            return;
        }

        Schema::table('categories', function (Blueprint $table) {
            $table->decimal('price', 15, 2)->default(0)->after('group_name');
        });

        DB::statement('UPDATE categories SET price = retail_price');
    }
};
