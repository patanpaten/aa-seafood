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
        Schema::create('incoming_stocks', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('supplier_name');
            $table->string('seafood_type');
            $table->decimal('receipt_weight', 10, 2);
            $table->decimal('actual_weight', 10, 2);
            $table->decimal('shrinkage_weight', 10, 2);
            $table->string('status'); // 'Normal' or 'Warning/Loss'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incoming_stocks');
    }
};
