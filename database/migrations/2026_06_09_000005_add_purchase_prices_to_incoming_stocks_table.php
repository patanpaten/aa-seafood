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
        if (! Schema::hasColumn('incoming_stocks', 'purchase_price_per_kg')) {
            Schema::table('incoming_stocks', function (Blueprint $table) {
                $table->decimal('purchase_price_per_kg', 15, 2)->default(0)->after('category_id');
                $table->decimal('total_purchase_price', 15, 2)->default(0)->after('purchase_price_per_kg');
            });
        }

        DB::table('incoming_stocks')
            ->whereNull('total_purchase_price')
            ->orWhere('total_purchase_price', 0)
            ->update([
                'total_purchase_price' => DB::raw('actual_weight * purchase_price_per_kg'),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('incoming_stocks', 'purchase_price_per_kg')) {
            Schema::table('incoming_stocks', function (Blueprint $table) {
                $table->dropColumn(['purchase_price_per_kg', 'total_purchase_price']);
            });
        }
    }
};
