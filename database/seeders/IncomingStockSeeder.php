<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\IncomingStock;
use App\Models\Supplier;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IncomingStockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Step 1: Create Suppliers
        $suppliers = [
            'pak memet' => ['name' => 'Pak Memet', 'address' => 'Rembang'],
            'agus' => ['name' => 'Agus', 'address' => 'Semarang'],
            'linda' => ['name' => 'Linda', 'address' => 'Cirebon'],
            'icang' => ['name' => 'Icang', 'address' => ''],
            'bu etik' => ['name' => 'Bu Etik', 'address' => 'Banyuwangi'],
            'adam malik' => ['name' => 'Adam Malik', 'address' => 'Surabaya'],
            'amin cirebon' => ['name' => 'Amin', 'address' => 'Cirebon'],
            'arif' => ['name' => 'Arif', 'address' => 'Rembang'],
            'pairan' => ['name' => 'Pairan', 'address' => 'Cilacap'],
            'ismun' => ['name' => 'Ismun', 'address' => 'Surabaya'],
            'amin madura' => ['name' => 'Amin', 'address' => 'Madura'],
            'makmur' => ['name' => 'Makmur', 'address' => ''],
            'dwi' => ['name' => 'Dwi', 'address' => ''],
            'timus' => ['name' => 'Timus', 'address' => ''],
            'kidir' => ['name' => 'Kidir', 'address' => ''],
            'bu eti' => ['name' => 'Bu Eti', 'address' => ''],
            'jakarta adam malik' => ['name' => 'Adam Malik', 'address' => 'Jakarta'],
            'husen' => ['name' => 'Husen', 'address' => 'Semarang'],
            'sutar' => ['name' => 'Sutar', 'address' => 'Purworejo'],
        ];

        $supplierMap = [];
        foreach ($suppliers as $key => $data) {
            $supplierMap[$key] = Supplier::updateOrCreate(
                ['name' => $data['name'], 'address' => $data['address']],
                ['contact' => '']
            );
        }

        // Step 2: Create Categories
        $categoriesData = [
            // Kerang ijo (8-10)
            'kerang ijo' => [
                'name' => 'Kerang Ijo',
                'group_name' => 'Kerang',
                'retail_price' => 10000,
                'wholesale_price' => 8000,
            ],

            // Kerang Dara B 18-20 & Kerang Dara K 9-13
            'kerang dara b 18-20' => [
                'name' => 'Kerang Dara B 18-20',
                'group_name' => 'Kerang',
                'retail_price' => 25000,
                'wholesale_price' => 20000,
            ],
            'kerang dara k 9-13' => [
                'name' => 'Kerang Dara K 9-13',
                'group_name' => 'Kerang',
                'retail_price' => 20000,
                'wholesale_price' => 15000,
            ],

            // Kerang bali (6k)
            'kerang bali' => [
                'name' => 'Kerang Bali',
                'group_name' => 'Kerang',
                'retail_price' => 7000,
                'wholesale_price' => 6000,
            ],

            // Kerang Batik (15k)
            'kerang batik' => [
                'name' => 'Kerang Batik',
                'group_name' => 'Kerang',
                'retail_price' => 18000,
                'wholesale_price' => 15000,
            ],

            // Tahu (5k)
            'tahu' => [
                'name' => 'Tahu',
                'group_name' => 'Lainnya',
                'retail_price' => 6000,
                'wholesale_price' => 5000,
            ],

            // Kerang Simping (35k)
            'kerang simping' => [
                'name' => 'Kerang Simping',
                'group_name' => 'Kerang',
                'retail_price' => 40000,
                'wholesale_price' => 35000,
            ],

            // Kerang Bambu (15k)
            'kerang bambu' => [
                'name' => 'Kerang Bambu',
                'group_name' => 'Kerang',
                'retail_price' => 18000,
                'wholesale_price' => 15000,
            ],

            // Kerang Tiram (11k)
            'kerang tiram' => [
                'name' => 'Kerang Tiram',
                'group_name' => 'Kerang',
                'retail_price' => 13000,
                'wholesale_price' => 11000,
            ],

            // Kepiting (Sp1=145, Sp2=110, Size 3-6=90, Wajar=90)
            'kepiting sp1' => [
                'name' => 'Kepiting SP1',
                'group_name' => 'Kepiting',
                'retail_price' => 150000,
                'wholesale_price' => 145000,
            ],
            'kepiting sp2' => [
                'name' => 'Kepiting SP2',
                'group_name' => 'Kepiting',
                'retail_price' => 120000,
                'wholesale_price' => 110000,
            ],
            'kepiting size 3-6' => [
                'name' => 'Kepiting Size 3-6',
                'group_name' => 'Kepiting',
                'retail_price' => 100000,
                'wholesale_price' => 90000,
            ],
            'kepiting wajar' => [
                'name' => 'Kepiting Wajar',
                'group_name' => 'Kepiting',
                'retail_price' => 100000,
                'wholesale_price' => 90000,
            ],

            // Lobster (265k)
            'lobster' => [
                'name' => 'Lobster',
                'group_name' => 'Lainnya',
                'retail_price' => 280000,
                'wholesale_price' => 265000,
            ],

            // Cumi (60k)
            'cumi' => [
                'name' => 'Cumi',
                'group_name' => 'Ikan & Seafood',
                'retail_price' => 70000,
                'wholesale_price' => 60000,
            ],

            // Udang Laut (55k)
            'udang laut' => [
                'name' => 'Udang Laut',
                'group_name' => 'Udang',
                'retail_price' => 65000,
                'wholesale_price' => 55000,
            ],

            // Udang Tambak/Vanami (85k)
            'udang tambak vanami' => [
                'name' => 'Udang Tambak/Vanami',
                'group_name' => 'Udang',
                'retail_price' => 95000,
                'wholesale_price' => 85000,
            ],

            // Ikan dari Agus Semarang
            'ikan kue' => [
                'name' => 'Ikan Kue',
                'group_name' => 'Ikan',
                'retail_price' => 65000,
                'wholesale_price' => 55000,
            ],
            'ikan cakalang' => [
                'name' => 'Ikan Cakalang',
                'group_name' => 'Ikan',
                'retail_price' => 30000,
                'wholesale_price' => 23000,
            ],
            'ikan kembung' => [
                'name' => 'Ikan Kembung',
                'group_name' => 'Ikan',
                'retail_price' => 55000,
                'wholesale_price' => 45000,
            ],
            'ikan kakap merah' => [
                'name' => 'Ikan Kakap Merah',
                'group_name' => 'Ikan',
                'retail_price' => 65000,
                'wholesale_price' => 55000,
            ],
            'ikan barakuda' => [
                'name' => 'Ikan Barakuda',
                'group_name' => 'Ikan',
                'retail_price' => 45000,
                'wholesale_price' => 38000,
            ],
            'ikan krapu' => [
                'name' => 'Ikan Krapu',
                'group_name' => 'Ikan',
                'retail_price' => 50000,
                'wholesale_price' => 40000,
            ],
        ];

        $categoryMap = [];
        foreach ($categoriesData as $key => $data) {
            $categoryMap[$key] = Category::updateOrCreate(
                ['name' => $data['name']],
                $data
            );
        }

        // Step 3: Create Incoming Stock Entries
        $incomingStocks = [
            // Kerang Ijo
            ['date' => '2026-06-10', 'supplier_key' => 'pak memet', 'category_key' => 'kerang ijo', 'price' => 8000, 'nota' => 100, 'aktual' => 96],
            ['date' => '2026-06-10', 'supplier_key' => 'agus', 'category_key' => 'kerang ijo', 'price' => 8000, 'nota' => 100, 'aktual' => 96],
            ['date' => '2026-06-10', 'supplier_key' => 'linda', 'category_key' => 'kerang ijo', 'price' => 8000, 'nota' => 100, 'aktual' => 96],
            ['date' => '2026-06-10', 'supplier_key' => 'icang', 'category_key' => 'kerang ijo', 'price' => 8000, 'nota' => 100, 'aktual' => 96],

            ['date' => '2026-06-11', 'supplier_key' => 'pak memet', 'category_key' => 'kerang ijo', 'price' => 8000, 'nota' => 180, 'aktual' => 174],
            ['date' => '2026-06-11', 'supplier_key' => 'agus', 'category_key' => 'kerang ijo', 'price' => 8000, 'nota' => 180, 'aktual' => 174],
            ['date' => '2026-06-11', 'supplier_key' => 'linda', 'category_key' => 'kerang ijo', 'price' => 8000, 'nota' => 180, 'aktual' => 174],
            ['date' => '2026-06-11', 'supplier_key' => 'icang', 'category_key' => 'kerang ijo', 'price' => 8000, 'nota' => 180, 'aktual' => 174],

            ['date' => '2026-06-13', 'supplier_key' => 'pak memet', 'category_key' => 'kerang ijo', 'price' => 8000, 'nota' => 90, 'aktual' => 84],
            ['date' => '2026-06-13', 'supplier_key' => 'agus', 'category_key' => 'kerang ijo', 'price' => 8000, 'nota' => 90, 'aktual' => 84],
            ['date' => '2026-06-13', 'supplier_key' => 'linda', 'category_key' => 'kerang ijo', 'price' => 8000, 'nota' => 90, 'aktual' => 84],
            ['date' => '2026-06-13', 'supplier_key' => 'icang', 'category_key' => 'kerang ijo', 'price' => 8000, 'nota' => 90, 'aktual' => 84],

            ['date' => '2026-06-15', 'supplier_key' => 'pak memet', 'category_key' => 'kerang ijo', 'price' => 8000, 'nota' => 150, 'aktual' => 144],
            ['date' => '2026-06-15', 'supplier_key' => 'agus', 'category_key' => 'kerang ijo', 'price' => 8000, 'nota' => 150, 'aktual' => 144],
            ['date' => '2026-06-15', 'supplier_key' => 'linda', 'category_key' => 'kerang ijo', 'price' => 8000, 'nota' => 150, 'aktual' => 144],
            ['date' => '2026-06-15', 'supplier_key' => 'icang', 'category_key' => 'kerang ijo', 'price' => 8000, 'nota' => 150, 'aktual' => 144],

            ['date' => '2026-06-16', 'supplier_key' => 'pak memet', 'category_key' => 'kerang ijo', 'price' => 8000, 'nota' => 90, 'aktual' => 84],
            ['date' => '2026-06-16', 'supplier_key' => 'agus', 'category_key' => 'kerang ijo', 'price' => 8000, 'nota' => 90, 'aktual' => 84],
            ['date' => '2026-06-16', 'supplier_key' => 'linda', 'category_key' => 'kerang ijo', 'price' => 8000, 'nota' => 90, 'aktual' => 84],
            ['date' => '2026-06-16', 'supplier_key' => 'icang', 'category_key' => 'kerang ijo', 'price' => 8000, 'nota' => 90, 'aktual' => 84],

            // Kerang Dara B & K
            ['date' => '2026-06-10', 'supplier_key' => 'bu etik', 'category_key' => 'kerang dara b 18-20', 'price' => 20000, 'nota' => 150, 'aktual' => 148],
            ['date' => '2026-06-10', 'supplier_key' => 'bu etik', 'category_key' => 'kerang dara k 9-13', 'price' => 15000, 'nota' => 150, 'aktual' => 148],
            ['date' => '2026-06-10', 'supplier_key' => 'adam malik', 'category_key' => 'kerang dara b 18-20', 'price' => 20000, 'nota' => 150, 'aktual' => 148],
            ['date' => '2026-06-10', 'supplier_key' => 'adam malik', 'category_key' => 'kerang dara k 9-13', 'price' => 15000, 'nota' => 150, 'aktual' => 148],
            ['date' => '2026-06-10', 'supplier_key' => 'amin cirebon', 'category_key' => 'kerang dara b 18-20', 'price' => 20000, 'nota' => 150, 'aktual' => 148],
            ['date' => '2026-06-10', 'supplier_key' => 'amin cirebon', 'category_key' => 'kerang dara k 9-13', 'price' => 15000, 'nota' => 150, 'aktual' => 148],
            ['date' => '2026-06-10', 'supplier_key' => 'arif', 'category_key' => 'kerang dara b 18-20', 'price' => 20000, 'nota' => 150, 'aktual' => 148],
            ['date' => '2026-06-10', 'supplier_key' => 'arif', 'category_key' => 'kerang dara k 9-13', 'price' => 15000, 'nota' => 150, 'aktual' => 148],

            ['date' => '2026-06-11', 'supplier_key' => 'bu etik', 'category_key' => 'kerang dara b 18-20', 'price' => 20000, 'nota' => 100, 'aktual' => 98.7],
            ['date' => '2026-06-11', 'supplier_key' => 'bu etik', 'category_key' => 'kerang dara k 9-13', 'price' => 15000, 'nota' => 100, 'aktual' => 98.7],
            ['date' => '2026-06-11', 'supplier_key' => 'adam malik', 'category_key' => 'kerang dara b 18-20', 'price' => 20000, 'nota' => 100, 'aktual' => 98.7],
            ['date' => '2026-06-11', 'supplier_key' => 'adam malik', 'category_key' => 'kerang dara k 9-13', 'price' => 15000, 'nota' => 100, 'aktual' => 98.7],
            ['date' => '2026-06-11', 'supplier_key' => 'amin cirebon', 'category_key' => 'kerang dara b 18-20', 'price' => 20000, 'nota' => 100, 'aktual' => 98.7],
            ['date' => '2026-06-11', 'supplier_key' => 'amin cirebon', 'category_key' => 'kerang dara k 9-13', 'price' => 15000, 'nota' => 100, 'aktual' => 98.7],
            ['date' => '2026-06-11', 'supplier_key' => 'arif', 'category_key' => 'kerang dara b 18-20', 'price' => 20000, 'nota' => 100, 'aktual' => 98.7],
            ['date' => '2026-06-11', 'supplier_key' => 'arif', 'category_key' => 'kerang dara k 9-13', 'price' => 15000, 'nota' => 100, 'aktual' => 98.7],

            ['date' => '2026-06-13', 'supplier_key' => 'bu etik', 'category_key' => 'kerang dara b 18-20', 'price' => 20000, 'nota' => 90, 'aktual' => 88.9],
            ['date' => '2026-06-13', 'supplier_key' => 'bu etik', 'category_key' => 'kerang dara k 9-13', 'price' => 15000, 'nota' => 90, 'aktual' => 88.9],
            ['date' => '2026-06-13', 'supplier_key' => 'adam malik', 'category_key' => 'kerang dara b 18-20', 'price' => 20000, 'nota' => 90, 'aktual' => 88.9],
            ['date' => '2026-06-13', 'supplier_key' => 'adam malik', 'category_key' => 'kerang dara k 9-13', 'price' => 15000, 'nota' => 90, 'aktual' => 88.9],
            ['date' => '2026-06-13', 'supplier_key' => 'amin cirebon', 'category_key' => 'kerang dara b 18-20', 'price' => 20000, 'nota' => 90, 'aktual' => 88.9],
            ['date' => '2026-06-13', 'supplier_key' => 'amin cirebon', 'category_key' => 'kerang dara k 9-13', 'price' => 15000, 'nota' => 90, 'aktual' => 88.9],
            ['date' => '2026-06-13', 'supplier_key' => 'arif', 'category_key' => 'kerang dara b 18-20', 'price' => 20000, 'nota' => 90, 'aktual' => 88.9],
            ['date' => '2026-06-13', 'supplier_key' => 'arif', 'category_key' => 'kerang dara k 9-13', 'price' => 15000, 'nota' => 90, 'aktual' => 88.9],

            ['date' => '2026-06-15', 'supplier_key' => 'bu etik', 'category_key' => 'kerang dara b 18-20', 'price' => 20000, 'nota' => 139, 'aktual' => 126.8],
            ['date' => '2026-06-15', 'supplier_key' => 'bu etik', 'category_key' => 'kerang dara k 9-13', 'price' => 15000, 'nota' => 139, 'aktual' => 126.8],
            ['date' => '2026-06-15', 'supplier_key' => 'adam malik', 'category_key' => 'kerang dara b 18-20', 'price' => 20000, 'nota' => 139, 'aktual' => 126.8],
            ['date' => '2026-06-15', 'supplier_key' => 'adam malik', 'category_key' => 'kerang dara k 9-13', 'price' => 15000, 'nota' => 139, 'aktual' => 126.8],
            ['date' => '2026-06-15', 'supplier_key' => 'amin cirebon', 'category_key' => 'kerang dara b 18-20', 'price' => 20000, 'nota' => 139, 'aktual' => 126.8],
            ['date' => '2026-06-15', 'supplier_key' => 'amin cirebon', 'category_key' => 'kerang dara k 9-13', 'price' => 15000, 'nota' => 139, 'aktual' => 126.8],
            ['date' => '2026-06-15', 'supplier_key' => 'arif', 'category_key' => 'kerang dara b 18-20', 'price' => 20000, 'nota' => 139, 'aktual' => 126.8],
            ['date' => '2026-06-15', 'supplier_key' => 'arif', 'category_key' => 'kerang dara k 9-13', 'price' => 15000, 'nota' => 139, 'aktual' => 126.8],

            ['date' => '2026-06-16', 'supplier_key' => 'bu etik', 'category_key' => 'kerang dara b 18-20', 'price' => 20000, 'nota' => 90, 'aktual' => 88],
            ['date' => '2026-06-16', 'supplier_key' => 'bu etik', 'category_key' => 'kerang dara k 9-13', 'price' => 15000, 'nota' => 90, 'aktual' => 88],
            ['date' => '2026-06-16', 'supplier_key' => 'adam malik', 'category_key' => 'kerang dara b 18-20', 'price' => 20000, 'nota' => 90, 'aktual' => 88],
            ['date' => '2026-06-16', 'supplier_key' => 'adam malik', 'category_key' => 'kerang dara k 9-13', 'price' => 15000, 'nota' => 90, 'aktual' => 88],
            ['date' => '2026-06-16', 'supplier_key' => 'amin cirebon', 'category_key' => 'kerang dara b 18-20', 'price' => 20000, 'nota' => 90, 'aktual' => 88],
            ['date' => '2026-06-16', 'supplier_key' => 'amin cirebon', 'category_key' => 'kerang dara k 9-13', 'price' => 15000, 'nota' => 90, 'aktual' => 88],
            ['date' => '2026-06-16', 'supplier_key' => 'arif', 'category_key' => 'kerang dara b 18-20', 'price' => 20000, 'nota' => 90, 'aktual' => 88],
            ['date' => '2026-06-16', 'supplier_key' => 'arif', 'category_key' => 'kerang dara k 9-13', 'price' => 15000, 'nota' => 90, 'aktual' => 88],

            // Kerang Bali (Pairan, tgl13, nota90, aktual85, 6k)
            ['date' => '2026-06-13', 'supplier_key' => 'pairan', 'category_key' => 'kerang bali', 'price' => 6000, 'nota' => 90, 'aktual' => 85],

            // Kerang Batik (Ismun, tgl13, nota90, aktual85, 15k)
            ['date' => '2026-06-13', 'supplier_key' => 'ismun', 'category_key' => 'kerang batik', 'price' => 15000, 'nota' => 90, 'aktual' => 85],

            // Tahu (Amin Madura, tgl12 & 15, 5k)
            ['date' => '2026-06-12', 'supplier_key' => 'amin madura', 'category_key' => 'tahu', 'price' => 5000, 'nota' => 90, 'aktual' => 87],
            ['date' => '2026-06-15', 'supplier_key' => 'amin madura', 'category_key' => 'tahu', 'price' => 5000, 'nota' => 100, 'aktual' => 98],

            // Kerang Simping (Agus, tgl13, nota50, aktual49.2, 35k)
            ['date' => '2026-06-13', 'supplier_key' => 'agus', 'category_key' => 'kerang simping', 'price' => 35000, 'nota' => 50, 'aktual' => 49.2],

            // Kerang Bambu (Amin Madura, tgl10, nota90, aktual85,15k)
            ['date' => '2026-06-10', 'supplier_key' => 'amin madura', 'category_key' => 'kerang bambu', 'price' => 15000, 'nota' => 90, 'aktual' => 85],

            // Kerang Tiram (Agus, tgl11, nota80, aktual76, 11k)
            ['date' => '2026-06-11', 'supplier_key' => 'agus', 'category_key' => 'kerang tiram', 'price' => 11000, 'nota' => 80, 'aktual' => 76],

            // Kepiting (Makmur, Dwi, Kidir, Bu Eti)
            ['date' => '2026-06-10', 'supplier_key' => 'makmur', 'category_key' => 'kepiting sp1', 'price' => 145000, 'nota' => 6.5, 'aktual' => 6.3],
            ['date' => '2026-06-10', 'supplier_key' => 'makmur', 'category_key' => 'kepiting sp2', 'price' => 110000, 'nota' => 8.8, 'aktual' => 8.8],
            ['date' => '2026-06-10', 'supplier_key' => 'makmur', 'category_key' => 'kepiting size 3-6', 'price' => 90000, 'nota' => 13, 'aktual' => 12.7],
            ['date' => '2026-06-10', 'supplier_key' => 'makmur', 'category_key' => 'kepiting wajar', 'price' => 90000, 'nota' => 8, 'aktual' => 8],
            ['date' => '2026-06-10', 'supplier_key' => 'dwi', 'category_key' => 'kepiting size 3-6', 'price' => 90000, 'nota' => 15, 'aktual' => 14.3],

            ['date' => '2026-06-11', 'supplier_key' => 'makmur', 'category_key' => 'kepiting sp1', 'price' => 145000, 'nota' => 3.5, 'aktual' => 3.5],
            ['date' => '2026-06-11', 'supplier_key' => 'makmur', 'category_key' => 'kepiting sp2', 'price' => 110000, 'nota' => 8, 'aktual' => 7.8],
            ['date' => '2026-06-11', 'supplier_key' => 'makmur', 'category_key' => 'kepiting size 3-6', 'price' => 90000, 'nota' => 10, 'aktual' => 9.8],
            ['date' => '2026-06-11', 'supplier_key' => 'makmur', 'category_key' => 'kepiting wajar', 'price' => 90000, 'nota' => 8, 'aktual' => 7.6],
            ['date' => '2026-06-11', 'supplier_key' => 'dwi', 'category_key' => 'kepiting size 3-6', 'price' => 90000, 'nota' => 21, 'aktual' => 20.3],

            ['date' => '2026-06-12', 'supplier_key' => 'makmur', 'category_key' => 'kepiting sp1', 'price' => 145000, 'nota' => 5, 'aktual' => 5],
            ['date' => '2026-06-12', 'supplier_key' => 'makmur', 'category_key' => 'kepiting sp2', 'price' => 110000, 'nota' => 7, 'aktual' => 7],
            ['date' => '2026-06-12', 'supplier_key' => 'makmur', 'category_key' => 'kepiting size 3-6', 'price' => 90000, 'nota' => 13, 'aktual' => 12.7],
            ['date' => '2026-06-12', 'supplier_key' => 'makmur', 'category_key' => 'kepiting wajar', 'price' => 90000, 'nota' => 10, 'aktual' => 10.2],
            ['date' => '2026-06-12', 'supplier_key' => 'dwi', 'category_key' => 'kepiting size 3-6', 'price' => 90000, 'nota' => 17, 'aktual' => 16.4],

            ['date' => '2026-06-16', 'supplier_key' => 'makmur', 'category_key' => 'kepiting sp1', 'price' => 145000, 'nota' => 3.5, 'aktual' => 3.5],
            ['date' => '2026-06-16', 'supplier_key' => 'makmur', 'category_key' => 'kepiting sp2', 'price' => 110000, 'nota' => 8, 'aktual' => 7.8],
            ['date' => '2026-06-16', 'supplier_key' => 'makmur', 'category_key' => 'kepiting size 3-6', 'price' => 90000, 'nota' => 10, 'aktual' => 9.8],
            ['date' => '2026-06-16', 'supplier_key' => 'makmur', 'category_key' => 'kepiting wajar', 'price' => 90000, 'nota' => 8, 'aktual' => 7.6],
            ['date' => '2026-06-16', 'supplier_key' => 'kidir', 'category_key' => 'kepiting sp1', 'price' => 145000, 'nota' => 10, 'aktual' => 9.8],
            ['date' => '2026-06-16', 'supplier_key' => 'kidir', 'category_key' => 'kepiting sp2', 'price' => 110000, 'nota' => 12, 'aktual' => 11.9],

            // Lobster (Jakarta Adam Malik, tgl11, nota30, 265k)
            ['date' => '2026-06-11', 'supplier_key' => 'jakarta adam malik', 'category_key' => 'lobster', 'price' => 265000, 'nota' => 30, 'aktual' => 30],

            // Cumi (Agus & Husen, tgl10, nota100, 60k)
            ['date' => '2026-06-10', 'supplier_key' => 'agus', 'category_key' => 'cumi', 'price' => 60000, 'nota' => 100, 'aktual' => 96],
            ['date' => '2026-06-10', 'supplier_key' => 'husen', 'category_key' => 'cumi', 'price' => 60000, 'nota' => 100, 'aktual' => 96],

            // Udang Laut (Agus, tgl10, nota20, 55k)
            ['date' => '2026-06-10', 'supplier_key' => 'agus', 'category_key' => 'udang laut', 'price' => 55000, 'nota' => 20, 'aktual' => 19],

            // Udang Tambak/Vanami (Sutar, tgl10, nota50, 85k)
            ['date' => '2026-06-10', 'supplier_key' => 'sutar', 'category_key' => 'udang tambak vanami', 'price' => 85000, 'nota' => 50, 'aktual' => 48],

            // Ikan dari Agus Semarang (tgl10)
            ['date' => '2026-06-10', 'supplier_key' => 'agus', 'category_key' => 'ikan kue', 'price' => 55000, 'nota' => 15, 'aktual' => 15],
            ['date' => '2026-06-10', 'supplier_key' => 'agus', 'category_key' => 'ikan cakalang', 'price' => 23000, 'nota' => 15, 'aktual' => 15],
            ['date' => '2026-06-10', 'supplier_key' => 'agus', 'category_key' => 'ikan kembung', 'price' => 45000, 'nota' => 15, 'aktual' => 15],
            ['date' => '2026-06-10', 'supplier_key' => 'agus', 'category_key' => 'ikan kakap merah', 'price' => 55000, 'nota' => 15, 'aktual' => 15],
            ['date' => '2026-06-10', 'supplier_key' => 'agus', 'category_key' => 'ikan barakuda', 'price' => 38000, 'nota' => 15, 'aktual' => 15],
            ['date' => '2026-06-10', 'supplier_key' => 'agus', 'category_key' => 'ikan krapu', 'price' => 40000, 'nota' => 15, 'aktual' => 15],
        ];

        foreach ($incomingStocks as $entry) {
            $supplier = $supplierMap[$entry['supplier_key']];
            $category = $categoryMap[$entry['category_key']];
            $total = $entry['price'] * $entry['aktual'];
            $shrinkage = $entry['nota'] - $entry['aktual'];

            IncomingStock::updateOrCreate(
                [
                    'date' => $entry['date'],
                    'supplier_id' => $supplier->id,
                    'category_id' => $category->id,
                    'receipt_weight' => $entry['nota'],
                    'actual_weight' => $entry['aktual'],
                ],
                [
                    'purchase_price_per_kg' => $entry['price'],
                    'total_purchase_price' => $total,
                    'shrinkage_weight' => $shrinkage,
                    'status' => 'completed',
                ]
            );
        }
    }
}
