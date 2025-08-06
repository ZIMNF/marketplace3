<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@gmail.com',
                'address' => 'Admin Address',
                'phone_number' => '0000000000',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Seller User',
                'email' => 'seller@gmail.com',
                'address' => 'Seller Address',
                'phone_number' => '1111111111',
                'password' => Hash::make('password'),
                'role' => 'seller',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Buyer User',
                'email' => 'buyer@gmail.com',
                'address' => 'Buyer Address',
                'phone_number' => '2222222222',
                'password' => Hash::make('password'),
                'role' => 'buyer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('users')->insert($users);
    }
}
