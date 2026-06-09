<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\IncomingStock;
use App\Models\Partner;
use App\Models\Supplier;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SaleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test creating a sale when stock is sufficient.
     */
    public function test_it_creates_sale_when_stock_is_sufficient(): void
    {
        $user = User::factory()->create(['role' => 'admin_gudang']);
        $this->actingAs($user);

        $supplier = Supplier::create(['name' => 'Supplier A']);
        $partner = Partner::create(['name' => 'Resto A']);
        $category = Category::create([
            'name' => 'Udang Vaname',
            'group_name' => 'Udang',
            'retail_price' => 50000,
            'wholesale_price' => 45000,
        ]);

        IncomingStock::create([
            'date' => '2026-05-11',
            'supplier_id' => $supplier->id,
            'category_id' => $category->id,
            'receipt_weight' => 100,
            'actual_weight' => 100,
            'shrinkage_weight' => 0,
            'status' => 'Normal',
        ]);

        $data = [
            'date' => '2026-05-11',
            'partner_id' => $partner->id,
            'category_id' => $category->id,
            'quantity_sold_kg' => 40,
            'price_per_kg' => 50000,
        ];

        $response = $this->post(route('sales.store'), $data);

        $response->assertRedirect();
        $this->assertDatabaseHas('sales', [
            'partner_id' => $partner->id,
            'buyer_name' => $partner->name,
            'category_id' => $category->id,
            'price_type' => 'eceran',
            'quantity_sold_kg' => 40,
            'price_per_kg' => 50000,
            'total_price' => 2000000,
        ]);
    }

    /**
     * Test creating a sale fails when stock is insufficient.
     */
    public function test_it_fails_to_create_sale_when_stock_is_insufficient(): void
    {
        $user = User::factory()->create(['role' => 'admin_gudang']);
        $this->actingAs($user);

        $supplier = Supplier::create(['name' => 'Supplier A']);
        $partner = Partner::create(['name' => 'Resto B']);
        $category = Category::create([
            'name' => 'Udang Windu',
            'group_name' => 'Udang',
            'retail_price' => 50000,
            'wholesale_price' => 45000,
        ]);

        IncomingStock::create([
            'date' => '2026-05-11',
            'supplier_id' => $supplier->id,
            'category_id' => $category->id,
            'receipt_weight' => 100,
            'actual_weight' => 50, // Only 50kg available
            'shrinkage_weight' => 50,
            'status' => 'Warning/Loss',
        ]);

        $data = [
            'date' => '2026-05-11',
            'partner_id' => $partner->id,
            'category_id' => $category->id,
            'quantity_sold_kg' => 60, // Requesting more than available
            'price_per_kg' => 43000,
        ];

        $response = $this->post(route('sales.store'), $data);

        $response->assertSessionHasErrors(['quantity_sold_kg']);
        $this->assertDatabaseCount('sales', 0);
    }

    /**
     * Test creating a general retail sale with manual buyer and price.
     */
    public function test_it_creates_general_sale_with_manual_buyer_name_and_price(): void
    {
        $user = User::factory()->create(['role' => 'admin_gudang']);
        $this->actingAs($user);

        $supplier = Supplier::create(['name' => 'Supplier A']);
        $category = Category::create([
            'name' => 'Kerang Dara',
            'group_name' => 'Kerang',
            'retail_price' => 35000,
            'wholesale_price' => 30000,
        ]);

        IncomingStock::create([
            'date' => '2026-05-11',
            'supplier_id' => $supplier->id,
            'category_id' => $category->id,
            'receipt_weight' => 100,
            'actual_weight' => 100,
            'shrinkage_weight' => 0,
            'status' => 'Normal',
        ]);

        $response = $this->post(route('sales.store'), [
            'date' => '2026-05-11',
            'buyer_name' => 'Ibu Sari',
            'category_id' => $category->id,
            'quantity_sold_kg' => 5,
            'price_per_kg' => 37000,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('sales', [
            'partner_id' => null,
            'buyer_name' => 'Ibu Sari',
            'category_id' => $category->id,
            'price_type' => 'eceran',
            'quantity_sold_kg' => 5,
            'price_per_kg' => 37000,
            'total_price' => 185000,
        ]);
    }
}
