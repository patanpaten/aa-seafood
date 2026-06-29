<?php

namespace Database\Seeders;

use App\Models\Partner;
use Illuminate\Database\Seeder;

class PartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $partnerNames = [
            'Zona Seafood',
            'Sentosa',
            'Kepiting Bang Jai',
            'King Kerang GD',
            'King Kerang KC',
            'Kerang AB',
            'Kakoi Seturan',
            'Kakoi Jamal',
            'Hezel',
            'Katombo',
            'Mbak Diyah Joglo',
        ];

        foreach ($partnerNames as $name) {
            Partner::updateOrCreate(
                ['name' => $name],
                ['contact' => '', 'address' => '']
            );
        }
    }
}
