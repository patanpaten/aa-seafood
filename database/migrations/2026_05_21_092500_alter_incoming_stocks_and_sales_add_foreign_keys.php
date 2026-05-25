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
        // Alter Incoming Stocks
        Schema::table('incoming_stocks', function (Blueprint $table) {
            $table->foreignId('supplier_id')->after('id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('category_id')->after('supplier_id')->nullable()->constrained()->onDelete('set null');
            
            // Note: In real world, we would migrate data before dropping
            $table->dropColumn(['supplier_name', 'seafood_type']);
        });

        // Alter Sales
        Schema::table('sales', function (Blueprint $table) {
            $table->foreignId('partner_id')->after('id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('category_id')->after('partner_id')->nullable()->constrained()->onDelete('set null');
            
            $table->dropColumn(['partner_restaurant_name', 'seafood_type']);
        });
    }

    public function down(): void
    {
        Schema::table('incoming_stocks', function (Blueprint $table) {
            $table->dropForeign(['supplier_id']);
            $table->dropForeign(['category_id']);
            $table->dropColumn(['supplier_id', 'category_id']);
            $table->string('supplier_name')->after('date');
            $table->string('seafood_type')->after('supplier_name');
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign(['partner_id']);
            $table->dropForeign(['category_id']);
            $table->dropColumn(['partner_id', 'category_id']);
            $table->string('partner_restaurant_name')->after('date');
            $table->string('seafood_type')->after('partner_restaurant_name');
        });
    }
};
