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
        if (! Schema::hasColumn('categories', 'group_name')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->string('group_name')->nullable()->after('name');
            });
        }

        if (! Schema::hasColumn('categories', 'retail_price')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->decimal('retail_price', 15, 2)->default(0)->after('group_name');
            });
        }

        if (! Schema::hasColumn('categories', 'wholesale_price')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->decimal('wholesale_price', 15, 2)->default(0)->after('retail_price');
            });
        }

        if (! Schema::hasColumn('categories', 'image_path')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->string('image_path')->nullable()->after('wholesale_price');
            });
        }

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
            $table->dropColumn(['group_name', 'retail_price', 'wholesale_price', 'image_path']);
        });
    }
};
