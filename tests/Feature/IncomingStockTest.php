<?php

namespace Tests\Feature;

use App\Models\IncomingStock;
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

        $data = [
            'date' => '2026-05-11',
            'supplier_name' => 'Supplier A',
            'seafood_type' => 'Udang',
            'receipt_weight' => 100,
            'actual_weight' => 96, // 4kg shrinkage = 4% (<= 5%)
        ];

        $response = $this->post(route('incoming-stocks.store'), $data);

        $response->assertRedirect();
        $this->assertDatabaseHas('incoming_stocks', [
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

        $data = [
            'date' => '2026-05-11',
            'supplier_name' => 'Supplier B',
            'seafood_type' => 'Ikan',
            'receipt_weight' => 100,
            'actual_weight' => 94, // 6kg shrinkage = 6% (> 5%)
        ];

        $response = $this->post(route('incoming-stocks.store'), $data);

        $response->assertRedirect();
        $this->assertDatabaseHas('incoming_stocks', [
            'receipt_weight' => 100,
            'actual_weight' => 94,
            'shrinkage_weight' => 6,
            'status' => 'Warning/Loss',
        ]);
    }
}
