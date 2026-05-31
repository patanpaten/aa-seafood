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
            $table->string('group_name')->nullable()->after('name');
            $table->decimal('price', 15, 2)->default(0)->after('group_name');
            $table->string('image_path')->nullable()->after('price');
        });

        DB::table('categories')
            ->whereNull('group_name')
            ->update(['group_name' => DB::raw('name')]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['group_name', 'price', 'image_path']);
        });
    }
};
