<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Profil;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ---------------------------------------------------
        // 1. MENGISI MASTER DATA KATEGORI PANGAN
        // ---------------------------------------------------
        $categories = [
            ['nama' => 'Makanan Berat', 'deskripsi' => 'Nasi, lauk pauk, dan makanan utama.'],
            ['nama' => 'Makanan Ringan', 'deskripsi' => 'Roti, kue, pastri, dan camilan.'],
            ['nama' => 'Minuman', 'deskripsi' => 'Kopi, teh, jus, dan minuman kemasan.'],
            ['nama' => 'Bahan Segar', 'deskripsi' => 'Sayur, buah, atau bahan mentah layak olah.'],
        ];

        foreach ($categories as $kategori) {
            Category::firstOrCreate(['nama' => $kategori['nama']], $kategori);
        }

        // ---------------------------------------------------
        // 2. MEMBUAT AKUN ADMIN DEFAULT
        // ---------------------------------------------------
        $admin = User::firstOrCreate(
            ['email' => 'admin@saveat.com'],
            [
                'name' => 'Super Admin Saveat',
                'username' => 'admin_saveat',
                'password' => Hash::make('password123'),
                'peran' => 'admin',
                'status' => 'aktif',
                'no_telphone' => '081234567890',
            ]
        );

        // ---------------------------------------------------
        // 3. MEMBUAT AKUN MERCHANT (PELAKU USAHA) + PROFILNYA
        // ---------------------------------------------------
        $merchantUser = User::firstOrCreate(
            ['email' => 'merchant@saveat.com'],
            [
                'name' => 'Budi Penjual',
                'username' => 'merchant_budi',
                'password' => Hash::make('password123'),
                'peran' => 'merchant',
                'status' => 'aktif',
                'no_telphone' => '081299998888',
            ]
        );

        // Menyuntikkan profil Merchant dan langsung "disetujui" agar bisa langsung tes buat makanan
        Profil::firstOrCreate(
            ['user_id' => $merchantUser->id],
            [
                'tipe_profil' => 'merchant',
                'nama_usaha' => 'Toko Roti Budi',
                'alamat' => 'Jl. Merdeka No. 45, Kota Bandung',
                'deskripsi' => 'Menjual berbagai macam roti dan kue sisa produksi hari ini dengan harga miring.',
                'status_verifikasi' => 'disetujui', 
                'diverifikasi_oleh' => $admin->id, // Berelasi dengan admin yang dibuat di atas
            ]
        );

        // ---------------------------------------------------
        // 4. MEMBUAT AKUN KONSUMEN (PEMBELI)
        // ---------------------------------------------------
        User::firstOrCreate(
            ['email' => 'konsumen@saveat.com'],
            [
                'name' => 'Andi Pembeli',
                'username' => 'konsumen_andi',
                'password' => Hash::make('password123'),
                'peran' => 'konsumen',
                'status' => 'aktif',
                'no_telphone' => '081277776666',
            ]
        );
    }
}