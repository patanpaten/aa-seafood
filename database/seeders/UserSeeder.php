<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'owner@aaseafood.com'],
            [
                'name' => 'Owner AA Seafood',
                'password' => 'owner123',
                'role' => User::ROLE_OWNER,
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@aaseafood.com'],
            [
                'name' => 'Admin Gudang',
                'password' => 'admin123',
                'role' => User::ROLE_ADMIN_GUDANG,
            ]
        );
    }
}
