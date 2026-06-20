<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Listing;
use App\Models\Profil;
use Illuminate\Database\Seeder;

class ListingSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            'Nasi Goreng',
            'Ayam Geprek',
            'Roti Coklat',
            'Croissant',
            'Es Teh',
            'Kopi Susu',
            'Donat',
            'Brownies',
            'Burger',
            'Kentang Goreng',
        ];

        $merchants = Profil::where('tipe_profil', 'merchant')->get();
        $categories = Category::all();

        foreach ($merchants as $merchant) {

            for ($i = 1; $i <= rand(3, 5); $i++) {

                $hargaNormal = rand(15000, 50000);
                $hargaDiskon = $hargaNormal - rand(3000, 10000);
                $stok = rand(5, 30);

                Listing::create([
                    'merchant_id' => $merchant->id,
                    'kategori_id' => $categories->random()->id,
                    'nama' => $products[array_rand($products)],
                    'foto' => null,
                    'harga_normal' => $hargaNormal,
                    'harga_diskon' => $hargaDiskon,
                    'stok_total' => $stok,
                    'stok_sisa' => rand(1, $stok),
                    'batas_waktu' => now()->addDays(rand(1, 7)),
                    'status' => 'aktif',
                ]);
            }
        }
    }
}