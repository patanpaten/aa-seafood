<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Owner account
        User::create([
            'name' => 'Pemilik AA Seafood',
            'email' => 'owner@aaseafood.com',
            'password' => Hash::make('password123'),
            'role' => 'owner',
        ]);

        // Create Admin Gudang account
        User::create([
            'name' => 'Admin Gudang',
            'email' => 'admin@aaseafood.com',
            'password' => Hash::make('password123'),
            'role' => 'admin_gudang',
        ]);
    }
}
