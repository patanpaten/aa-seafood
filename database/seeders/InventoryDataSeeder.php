<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;
use App\Models\Category;
use App\Models\IncomingStock;
use Carbon\Carbon;

class InventoryDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. DATA SUPPLIER (TEMPAT BELI) - SESUAI DATA DATABASE ANDA
        $suppliersData = [
            ['name' => 'Pak Memet', 'address' => 'Rembang'],
            ['name' => 'Agus', 'address' => 'Semarang'],
            ['name' => 'Linda', 'address' => 'Cirebon'],
            ['name' => 'Icang', 'address' => 'Cirebon'], // Sesuai data DB Anda
            ['name' => 'Bu Etik', 'address' => 'Banyuwangi'],
            ['name' => 'Adam Malik', 'address' => 'Surabaya'],
            ['name' => 'Amin', 'address' => 'Cirebon'],
            ['name' => 'Arif', 'address' => 'Rembang'],
            ['name' => 'Pairan', 'address' => 'Cilacap'],
            ['name' => 'Ismun', 'address' => 'Surabaya'],
            ['name' => 'Amin', 'address' => 'Madura'],
            ['name' => 'Makmur', 'address' => 'Cilacap'],   // Sesuai data DB Anda
            ['name' => 'Dwi', 'address' => 'Cilacap'],      // Sesuai data DB Anda
            ['name' => 'Timus', 'address' => 'Pemalang'],    // Sesuai data DB Anda
            ['name' => 'Kidir', 'address' => 'Pemalang'],    // Sesuai data DB Anda
            ['name' => 'Bu Eti', 'address' => 'Banyuwangi'], // Sesuai data DB Anda
            ['name' => 'Husen', 'address' => 'Semarang'],
            ['name' => 'Sutar', 'address' => 'Purworejo']
        ];

        $suppliers = [];
        foreach ($suppliersData as $data) {
            $supplierInstance = Supplier::firstOrCreate(
                ['name' => $data['name'], 'address' => $data['address']],
                ['contact' => null]
            );
            
            // Key mapper untuk mencocokkan string asal nota (contoh: 'Makmur' atau 'Icang' tanpa kota di array $stocks)
            // Agar tetap mengarah ke ID supplier yang tepat, mapping kita buat fleksibel
            $suppliers[$data['name']] = $supplierInstance; 
            
            // Back-up key jika di data transaksi tertulis nama lengkap beserta kota asal
            $keyWithCity = $data['name'] . ' ' . $data['address'];
            $suppliers[$keyWithCity] = $supplierInstance;
        }

        // 2. DATA BARANG (CATEGORIES)
        $categoriesData = [
            'Kerang Ijo' => ['Kerang', 9000], 
            'Kerang Dara B' => ['Kerang', 19000], 
            'Kerang Dara K' => ['Kerang', 11000], 
            'Kerang Bali' => ['Kerang', 6000],
            'Kerang Batik' => ['Kerang', 15000],
            'Tahu' => ['Lainnya', 5000],
            'Kerang Simping' => ['Kerang', 35000],
            'Kerang Bambu' => ['Kerang', 15000],
            'Kerang Tiram' => ['Kerang', 11000],
            
            // Variasi Kepiting
            'Kepiting Sp1' => ['Kepiting', 145000],
            'Kepiting Sp2' => ['Kepiting', 110000],
            'Kepiting Size 3-6' => ['Kepiting', 90000],
            'Kepiting Wajar' => ['Kepiting', 90000],
            
            'Lobster' => ['Seafood', 265000],
            'Cumi' => ['Seafood', 60000],
            'Udang Laut' => ['Seafood', 55000],
            'Udang Tambak / Vanami' => ['Seafood', 85000],
            
            // Jenis Ikan
            'Ikan Kuwek' => ['Ikan', 55000],
            'Ikan Cakalang' => ['Ikan', 23000],
            'Ikan Kembung' => ['Ikan', 45000],
            'Ikan Kakap Merah' => ['Ikan', 55000],
            'Ikan Barakuda' => ['Ikan', 38000],
            'Ikan Krapu' => ['Ikan', 40000],
        ];

        $categories = [];
        foreach ($categoriesData as $name => $info) {
            $categories[$name] = Category::firstOrCreate(
                ['name' => $name],
                [
                    'group_name' => $info[0],
                    'retail_price' => $info[1] * 1.2,   
                    'wholesale_price' => $info[1] * 1.1, 
                ]
            );
        }

        // 3. DATA TRANSAKSI STOK MASUK (INCOMING STOCKS)
        $currentYearMonth = '2026-06-';

        $stocks = [
            // --- Kerang Ijo ---
            ['date' => '10', 'item' => 'Kerang Ijo', 'supplier' => 'Pak Memet Rembang', 'nota' => 100, 'aktual' => 96, 'harga' => 9000],
            ['date' => '11', 'item' => 'Kerang Ijo', 'supplier' => 'Pak Memet Rembang', 'nota' => 180, 'aktual' => 174, 'harga' => 9000],
            ['date' => '13', 'item' => 'Kerang Ijo', 'supplier' => 'Pak Memet Rembang', 'nota' => 90, 'aktual' => 84, 'harga' => 9000],
            ['date' => '15', 'item' => 'Kerang Ijo', 'supplier' => 'Pak Memet Rembang', 'nota' => 150, 'aktual' => 144, 'harga' => 9000],
            ['date' => '16', 'item' => 'Kerang Ijo', 'supplier' => 'Pak Memet Rembang', 'nota' => 90, 'aktual' => 84, 'harga' => 9000],

            // --- Kerang Dara B ---
            ['date' => '10', 'item' => 'Kerang Dara B', 'supplier' => 'Bu Etik Banyuwangi', 'nota' => 150, 'aktual' => 148, 'harga' => 19000],
            ['date' => '11', 'item' => 'Kerang Dara B', 'supplier' => 'Bu Etik Banyuwangi', 'nota' => 100, 'aktual' => 98.7, 'harga' => 19000],
            ['date' => '13', 'item' => 'Kerang Dara B', 'supplier' => 'Bu Etik Banyuwangi', 'nota' => 90, 'aktual' => 88.9, 'harga' => 19000],
            ['date' => '15', 'item' => 'Kerang Dara B', 'supplier' => 'Bu Etik Banyuwangi', 'nota' => 139, 'aktual' => 126.8, 'harga' => 19000],
            ['date' => '16', 'item' => 'Kerang Dara B', 'supplier' => 'Bu Etik Banyuwangi', 'nota' => 90, 'aktual' => 88, 'harga' => 19000],

            // --- Kerang Lainnya & Tahu ---
            ['date' => '13', 'item' => 'Kerang Bali', 'supplier' => 'Pairan Cilacap', 'nota' => 90, 'aktual' => 85, 'harga' => 6000],
            ['date' => '13', 'item' => 'Kerang Batik', 'supplier' => 'Ismun Surabaya', 'nota' => 90, 'aktual' => 85, 'harga' => 15000],
            ['date' => '12', 'item' => 'Tahu', 'supplier' => 'Amin Madura', 'nota' => 90, 'aktual' => 87, 'harga' => 5000],
            ['date' => '15', 'item' => 'Tahu', 'supplier' => 'Amin Madura', 'nota' => 100, 'aktual' => 98, 'harga' => 5000],
            ['date' => '13', 'item' => 'Kerang Simping', 'supplier' => 'Agus Semarang', 'nota' => 50, 'aktual' => 49.2, 'harga' => 35000],
            ['date' => '10', 'item' => 'Kerang Bambu', 'supplier' => 'Amin Madura', 'nota' => 90, 'aktual' => 85, 'harga' => 15000],
            ['date' => '11', 'item' => 'Kerang Tiram', 'supplier' => 'Agus Semarang', 'nota' => 80, 'aktual' => 76, 'harga' => 11000],

            // --- Kepiting Tgl 10 ---
            ['date' => '10', 'item' => 'Kepiting Sp1', 'supplier' => 'Makmur', 'nota' => 6.5, 'aktual' => 6.3, 'harga' => 145000],
            ['date' => '10', 'item' => 'Kepiting Sp2', 'supplier' => 'Makmur', 'nota' => 8.8, 'aktual' => 8.8, 'harga' => 110000],
            ['date' => '10', 'item' => 'Kepiting Size 3-6', 'supplier' => 'Makmur', 'nota' => 13, 'aktual' => 12.7, 'harga' => 90000],
            ['date' => '10', 'item' => 'Kepiting Wajar', 'supplier' => 'Makmur', 'nota' => 8, 'aktual' => 8, 'harga' => 90000],
            ['date' => '10', 'item' => 'Kepiting Size 3-6', 'supplier' => 'Dwi', 'nota' => 15, 'aktual' => 14.3, 'harga' => 90000],

            // --- Kepiting Tgl 11 ---
            ['date' => '11', 'item' => 'Kepiting Sp1', 'supplier' => 'Makmur', 'nota' => 3.5, 'aktual' => 3.5, 'harga' => 145000],
            ['date' => '11', 'item' => 'Kepiting Sp2', 'supplier' => 'Makmur', 'nota' => 8, 'aktual' => 7.8, 'harga' => 110000],
            ['date' => '11', 'item' => 'Kepiting Size 3-6', 'supplier' => 'Makmur', 'nota' => 10, 'aktual' => 9.8, 'harga' => 90000],
            ['date' => '11', 'item' => 'Kepiting Wajar', 'supplier' => 'Makmur', 'nota' => 8, 'aktual' => 7.6, 'harga' => 90000],
            ['date' => '11', 'item' => 'Kepiting Size 3-6', 'supplier' => 'Dwi', 'nota' => 21, 'aktual' => 20.3, 'harga' => 90000],

            // --- Kepiting Tgl 12 ---
            ['date' => '12', 'item' => 'Kepiting Sp1', 'supplier' => 'Makmur', 'nota' => 5, 'aktual' => 5, 'harga' => 145000],
            ['date' => '12', 'item' => 'Kepiting Sp2', 'supplier' => 'Makmur', 'nota' => 7, 'aktual' => 7, 'harga' => 110000],
            ['date' => '12', 'item' => 'Kepiting Size 3-6', 'supplier' => 'Makmur', 'nota' => 13, 'aktual' => 12.7, 'harga' => 90000],
            ['date' => '12', 'item' => 'Kepiting Wajar', 'supplier' => 'Makmur', 'nota' => 10, 'aktual' => 10.2, 'harga' => 90000],
            ['date' => '12', 'item' => 'Kepiting Size 3-6', 'supplier' => 'Dwi', 'nota' => 17, 'aktual' => 16.4, 'harga' => 90000],

            // --- Kepiting Tgl 16 ---
            ['date' => '16', 'item' => 'Kepiting Sp1', 'supplier' => 'Makmur', 'nota' => 3.5, 'aktual' => 3.5, 'harga' => 145000],
            ['date' => '16', 'item' => 'Kepiting Sp2', 'supplier' => 'Makmur', 'nota' => 8, 'aktual' => 7.8, 'harga' => 110000],
            ['date' => '16', 'item' => 'Kepiting Size 3-6', 'supplier' => 'Makmur', 'nota' => 10, 'aktual' => 9.8, 'harga' => 90000],
            ['date' => '16', 'item' => 'Kepiting Wajar', 'supplier' => 'Makmur', 'nota' => 8, 'aktual' => 7.6, 'harga' => 90000],
            ['date' => '16', 'item' => 'Kepiting Sp1', 'supplier' => 'Kidir', 'nota' => 10, 'aktual' => 9.8, 'harga' => 145000],
            ['date' => '16', 'item' => 'Kepiting Sp2', 'supplier' => 'Kidir', 'nota' => 12, 'aktual' => 11.9, 'harga' => 110000],

            // --- Seafood ---
            ['date' => '11', 'item' => 'Lobster', 'supplier' => 'Adam Malik Surabaya', 'nota' => 30, 'aktual' => 30, 'harga' => 265000],
            ['date' => '10', 'item' => 'Cumi', 'supplier' => 'Agus Semarang', 'nota' => 100, 'aktual' => 96, 'harga' => 60000],
            ['date' => '10', 'item' => 'Udang Laut', 'supplier' => 'Agus Semarang', 'nota' => 20, 'aktual' => 19, 'harga' => 55000],
            ['date' => '10', 'item' => 'Udang Tambak / Vanami', 'supplier' => 'Sutar Purworejo', 'nota' => 50, 'aktual' => 48, 'harga' => 85000],

            // --- Bermacam Ikan dari Agus Semarang Tgl 10 ---
            ['date' => '10', 'item' => 'Ikan Kuwek', 'supplier' => 'Agus Semarang', 'nota' => 15, 'aktual' => 15, 'harga' => 55000],
            ['date' => '10', 'item' => 'Ikan Cakalang', 'supplier' => 'Agus Semarang', 'nota' => 15, 'aktual' => 15, 'harga' => 23000],
            ['date' => '10', 'item' => 'Ikan Kembung', 'supplier' => 'Agus Semarang', 'nota' => 15, 'aktual' => 15, 'harga' => 45000],
            ['date' => '10', 'item' => 'Ikan Kakap Merah', 'supplier' => 'Agus Semarang', 'nota' => 15, 'aktual' => 15, 'harga' => 55000],
            ['date' => '10', 'item' => 'Ikan Barakuda', 'supplier' => 'Agus Semarang', 'nota' => 15, 'aktual' => 15, 'harga' => 38000],
            ['date' => '10', 'item' => 'Ikan Krapu', 'supplier' => 'Agus Semarang', 'nota' => 15, 'aktual' => 15, 'harga' => 40000],
        ];

        foreach ($stocks as $stock) {
            $shrinkage = $stock['nota'] - $stock['aktual'];
            $itemTotal = $stock['aktual'] * $stock['harga'];
            
            $threshold = $stock['nota'] * 0.05;
            $status = ($shrinkage > $threshold) ? 'Warning/Loss' : 'Normal';

            IncomingStock::create([
                'date' => Carbon::parse($currentYearMonth . $stock['date'])->format('Y-m-d'),
                'supplier_id' => $suppliers[$stock['supplier']]->id,
                'category_id' => $categories[$stock['item']]->id,
                'purchase_price_per_kg' => $stock['harga'],
                'total_purchase_price' => $itemTotal,
                'receipt_weight' => $stock['nota'],
                'actual_weight' => $stock['aktual'],
                'shrinkage_weight' => $shrinkage,
                'status' => $status,
            ]);
        }
    }
}