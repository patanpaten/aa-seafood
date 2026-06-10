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
    Schema::table('sales', function (Blueprint $table) {
        // Default 'sedang diproses' saat penjualan baru dibuat
        $table->enum('status', ['sedang diproses', 'dalam perjalanan', 'selesai'])->default('sedang diproses')->after('total_price');
        $table->string('delivery_proof')->nullable()->after('status');
        $table->string('driver_name')->nullable()->after('delivery_proof');
        $table->string('driver_phone')->nullable()->after('driver_name');
    });
}

public function down(): void
{
    Schema::table('sales', function (Blueprint $table) {
        $table->dropColumn(['status', 'delivery_proof']);
    });
}


};
