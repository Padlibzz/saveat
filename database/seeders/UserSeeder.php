<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'no_telphone' => '081234567890',
            'peran' => 'admin',
            'status' => 'aktif',
        ]);

        // Merchant
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'name' => "Merchant {$i}",
                'username' => "merchant{$i}",
                'email' => "merchant{$i}@test.com",
                'password' => Hash::make('password'),
                'no_telphone' => '08123456789' . $i,
                'peran' => 'merchant',
                'status' => 'aktif',
            ]);
        }

        // Konsumen
        for ($i = 1; $i <= 10; $i++) {
            User::create([
                'name' => "Konsumen {$i}",
                'username' => "konsumen{$i}",
                'email' => "konsumen{$i}@test.com",
                'password' => Hash::make('password'),
                'no_telphone' => '08234567890' . $i,
                'peran' => 'konsumen',
                'status' => 'aktif',
            ]);
        }
    }
}