<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@test.com',
            'password' => 'admin1234', // Teks biasa
            'no_telphone' => '081234567890',
            'peran' => 'admin',
            'status' => 'aktif',
        ]);

        // 5 Data Merchant Spesifik (Tanpa Hash)
        $merchants = [
            ['name' => 'Toko Roti Bu Sari', 'username' => 'rotibusari', 'email' => 'busari@test.com', 'telp' => '081211112222'],
            ['name' => 'Warung Ayam Geprek Jontor', 'username' => 'geprekjontor', 'email' => 'geprekjontor@test.com', 'telp' => '081233334444'],
            ['name' => 'Kopi Kenangan Senja', 'username' => 'kopisenja', 'email' => 'kopisenja@test.com', 'telp' => '081255556666'],
            ['name' => 'Martabak Manis Legit', 'username' => 'martabaklegit', 'email' => 'martabaklegit@test.com', 'telp' => '081277778888'],
            ['name' => 'Sate Kambing Pak Kumis', 'username' => 'satepakkumis', 'email' => 'pakkumis@test.com', 'telp' => '081299990000'],
        ];

        foreach ($merchants as $merchant) {
            User::create([
                'name' => $merchant['name'],
                'username' => $merchant['username'],
                'email' => $merchant['email'],
                'password' => 'password', // Teks biasa untuk login merchant
                'no_telphone' => $merchant['telp'],
                'peran' => 'merchant',
                'status' => 'aktif',
            ]);
        }

        // 10 Data Konsumen Spesifik (Tanpa Hash)
        $konsumen = [
            ['name' => 'Budi Santoso', 'username' => 'budis', 'email' => 'budi@test.com', 'telp' => '082311110001'],
            ['name' => 'Siti Aminah', 'username' => 'sitiaminah', 'email' => 'siti@test.com', 'telp' => '082322220002'],
            ['name' => 'Ahmad Hidayat', 'username' => 'ahmadh', 'email' => 'ahmad@test.com', 'telp' => '082333330003'],
            ['name' => 'Dewi Lestari', 'username' => 'dewil', 'email' => 'dewi@test.com', 'telp' => '082344440004'],
            ['name' => 'Rian Hidayatullah', 'username' => 'rianh', 'email' => 'rian@test.com', 'telp' => '082355550005'],
            ['name' => 'Rina Permata', 'username' => 'rinap', 'email' => 'rina@test.com', 'telp' => '082366660006'],
            ['name' => 'Eko Prasetyo', 'username' => 'ekop', 'email' => 'eko@test.com', 'telp' => '082377770007'],
            ['name' => 'Indah Cahyani', 'username' => 'indahc', 'email' => 'indah@test.com', 'telp' => '082388880008'],
            ['name' => 'Fajar Nugroho', 'username' => 'fajarn', 'email' => 'fajar@test.com', 'telp' => '082399990009'],
            ['name' => 'Andi Wijaya', 'username' => 'andiw', 'email' => 'andi@test.com', 'telp' => '082300000010'],
        ];

        foreach ($konsumen as $k) {
            User::create([
                'name' => $k['name'],
                'username' => $k['username'],
                'email' => $k['email'],
                'password' => 'password', // Teks biasa untuk login konsumen
                'no_telphone' => $k['telp'],
                'peran' => 'konsumen',
                'status' => 'aktif',
            ]);
        }
    }
}