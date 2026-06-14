<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Profil;
use App\Models\User;
use App\Models\Listing;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
                'name' => 'admin',
                'username' => 'admin',
                'password' => Hash::make('admin'),
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
                'name' => 'merchant',
                'username' => 'merchant',
                'password' => Hash::make('merchant'),
                'peran' => 'merchant',
                'status' => 'aktif',
                'no_telphone' => '081299998888',
            ]
        );

        // Menyuntikkan profil Merchant dan langsung "disetujui" agar bisa langsung tes buat makanan
       $merchantProfile = Profil::firstOrCreate(
            ['user_id' => $merchantUser->id],
            [
                'tipe_profil' => 'merchant',
                'nama_usaha' => 'Toko Roti',
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
                'name' => 'konsumen',
                'username' => 'konsumen',
                'password' => Hash::make('konsumen'),
                'peran' => 'konsumen',
                'status' => 'aktif',
                'no_telphone' => '081277776666',
            ]
        );

        // ---------------------------------------------------
        // 5. MEMBUAT DATA LISTING MAKANAN DARI MERCHANT
        // ---------------------------------------------------
        // Ambil ID Kategori untuk dimasukkan ke listing
        $catMakananRingan = Category::where('nama', 'Makanan Ringan')->first()->id;
        $catMakananBerat = Category::where('nama', 'Makanan Berat')->first()->id;
        $catMinuman = Category::where('nama', 'Minuman')->first()->id;
        $catBahanSegar = Category::where('nama', 'Bahan Segar')->first()->id;

        $listings = [
            [
                'merchant_id' => $merchantProfile->id, // <--- UBAH DI SINI
                'kategori_id' => $catMakananRingan,
                'nama' => 'Roti Coklat Lumer (Sisa Produksi Hari Ini)',
                'harga_normal' => 15000,
                'harga_diskon' => 5000,
                'stok_total' => 10,
                'stok_sisa' => 10,
                'batas_waktu' => Carbon::now()->addHours(5), 
                'status' => 'aktif',
            ],
            [
                'merchant_id' => $merchantProfile->id, // <--- UBAH DI SINI
                'kategori_id' => $catMakananBerat,
                'nama' => 'Nasi Bakar Ayam Kemangi',
                'harga_normal' => 25000,
                'harga_diskon' => 12000,
                'stok_total' => 5,
                'stok_sisa' => 5,
                'batas_waktu' => Carbon::now()->addHours(3),
                'status' => 'aktif',
            ],
            [
                'merchant_id' => $merchantProfile->id, // <--- UBAH DI SINI
                'kategori_id' => $catMakananRingan,
                'nama' => 'Donat Kentang Gula Halus (Isi 6)',
                'harga_normal' => 30000,
                'harga_diskon' => 15000,
                'stok_total' => 8,
                'stok_sisa' => 8,
                'batas_waktu' => Carbon::now()->addHours(8),
                'status' => 'aktif',
            ],
            [
                'merchant_id' => $merchantProfile->id, // <--- UBAH DI SINI
                'kategori_id' => $catMakananBerat,
                'nama' => 'Bento Box Ayam Teriyaki',
                'harga_normal' => 35000,
                'harga_diskon' => 17500,
                'stok_total' => 15,
                'stok_sisa' => 15,
                'batas_waktu' => Carbon::now()->addDays(1), 
                'status' => 'aktif',
            ],
            [
                'merchant_id' => $merchantProfile->id, // <--- UBAH DI SINI
                'kategori_id' => $catMinuman,
                'nama' => 'Kopi Susu Gula Aren (Sisa Event)',
                'harga_normal' => 20000,
                'harga_diskon' => 10000,
                'stok_total' => 20,
                'stok_sisa' => 20,
                'batas_waktu' => Carbon::now()->addHours(2), 
                'status' => 'aktif',
            ],
            [
                'merchant_id' => $merchantProfile->id, // <--- UBAH DI SINI
                'kategori_id' => $catBahanSegar,
                'nama' => 'Paket Sayur Sop (Bentuk masih bagus)',
                'harga_normal' => 15000,
                'harga_diskon' => 5000,
                'stok_total' => 4,
                'stok_sisa' => 4,
                'batas_waktu' => Carbon::now()->addHours(10),
                'status' => 'aktif',
            ],
        ];

        foreach ($listings as $listing) {
            Listing::create($listing);
        }
    }
}