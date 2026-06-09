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
        if (! Schema::hasColumn('sales', 'buyer_name')) {
            Schema::table('sales', function (Blueprint $table) {
                $table->string('buyer_name')->nullable()->after('partner_id');
            });
        }

        if (Schema::hasTable('sales') && Schema::hasTable('partners')) {
            $partnerNames = DB::table('partners')->pluck('name', 'id');

            DB::table('sales')
                ->whereNull('buyer_name')
                ->orderBy('id')
                ->get(['id', 'partner_id'])
                ->each(function ($sale) use ($partnerNames) {
                    $buyerName = $partnerNames[$sale->partner_id] ?? 'Pembeli Umum';

                    DB::table('sales')
                        ->where('id', $sale->id)
                        ->update(['buyer_name' => $buyerName]);
                });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('sales', 'buyer_name')) {
            Schema::table('sales', function (Blueprint $table) {
                $table->dropColumn('buyer_name');
            });
        }
    }
};
