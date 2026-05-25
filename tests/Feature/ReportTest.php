<?php

namespace Tests\Feature;

use App\Models\IncomingStock;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the report aggregation logic.
     */
    public function test_report_index_shows_correct_aggregated_data(): void
    {
        // 0. Setup: Create and login OWNER
        $user = User::factory()->create(['role' => 'owner']);
        $this->actingAs($user);

        // 1. Setup Data
        IncomingStock::create([
            'date' => '2026-05-01',
            'supplier_name' => 'Supplier A',
            'seafood_type' => 'Udang',
            'receipt_weight' => 100,
            'actual_weight' => 95,
            'shrinkage_weight' => 5,
            'status' => 'Normal',
        ]);

        Sale::create([
            'date' => '2026-05-05',
            'partner_restaurant_name' => 'Resto A',
            'seafood_type' => 'Udang',
            'quantity_sold_kg' => 40,
            'price_per_kg' => 100000,
            'total_price' => 4000000,
        ]);

        // 2. Call Report Page
        $response = $this->get(route('reports.index', [
            'start_date' => '2026-05-01',
            'end_date' => '2026-05-31'
        ]));

        // 3. Assertions
        $response->assertStatus(200);
        $response->assertSee('95.00 kg'); // Total Incoming
        $response->assertSee('5.00 kg');  // Total Shrinkage
        $response->assertSee('40.00 kg'); // Total Sales Kg
        $response->assertSee('Rp 4.000.000'); // Total Revenue
        $response->assertSee('55.00 kg'); // Current Stock (95 - 40)
    }

    /**
     * Test the PDF export route.
     */
    public function test_report_pdf_export_returns_download(): void
    {
        // Setup: Create and login OWNER
        $user = User::factory()->create(['role' => 'owner']);
        $this->actingAs($user);

        $response = $this->get(route('reports.export-pdf', [
            'start_date' => '2026-05-01',
            'end_date' => '2026-05-31'
        ]));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    /**
     * Test that Admin Gudang cannot access reports.
     */
    public function test_admin_gudang_cannot_access_reports(): void
    {
        $user = User::factory()->create(['role' => 'admin_gudang']);
        $this->actingAs($user);

        $response = $this->get(route('reports.index'));

        $response->assertStatus(403);
    }
}
