<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\IncomingStock;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IncomingStockTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test calculation logic for normal shrinkage (<= 5%).
     */
    public function test_it_calculates_normal_shrinkage_correctly(): void
    {
        $user = User::factory()->create(['role' => 'admin_gudang']);
        $this->actingAs($user);

        $supplier = Supplier::create(['name' => 'Supplier A']);
        $category = Category::create([
            'name' => 'Udang Vaname',
            'group_name' => 'Udang',
            'retail_price' => 50000,
            'wholesale_price' => 45000,
        ]);

        $data = [
            'date' => '2026-05-11',
            'supplier_id' => $supplier->id,
            'category_id' => $category->id,
            'purchase_price_per_kg' => 40000,
            'receipt_weight' => 100,
            'actual_weight' => 96, // 4kg shrinkage = 4% (<= 5%)
        ];

        $response = $this->post(route('incoming-stocks.store'), $data);

        $response->assertRedirect();
        $this->assertDatabaseHas('incoming_stocks', [
            'supplier_id' => $supplier->id,
            'category_id' => $category->id,
            'purchase_price_per_kg' => 40000,
            'total_purchase_price' => 3840000,
            'receipt_weight' => 100,
            'actual_weight' => 96,
            'shrinkage_weight' => 4,
            'status' => 'Normal',
        ]);
    }

    /**
     * Test calculation logic for high shrinkage (> 5%).
     */
    public function test_it_calculates_warning_shrinkage_correctly(): void
    {
        $user = User::factory()->create(['role' => 'admin_gudang']);
        $this->actingAs($user);

        $supplier = Supplier::create(['name' => 'Supplier B']);
        $category = Category::create([
            'name' => 'Ikan Kakap',
            'group_name' => 'Ikan',
            'retail_price' => 70000,
            'wholesale_price' => 65000,
        ]);

        $data = [
            'date' => '2026-05-11',
            'supplier_id' => $supplier->id,
            'category_id' => $category->id,
            'purchase_price_per_kg' => 55000,
            'receipt_weight' => 100,
            'actual_weight' => 94, // 6kg shrinkage = 6% (> 5%)
        ];

        $response = $this->post(route('incoming-stocks.store'), $data);

        $response->assertRedirect();
        $this->assertDatabaseHas('incoming_stocks', [
            'supplier_id' => $supplier->id,
            'category_id' => $category->id,
            'purchase_price_per_kg' => 55000,
            'total_purchase_price' => 5170000,
            'receipt_weight' => 100,
            'actual_weight' => 94,
            'shrinkage_weight' => 6,
            'status' => 'Warning/Loss',
        ]);
    }
}
