<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Makanan Berat',
            'Roti & Kue',
            'Minuman',
            'Buah & Sayur',
            'Snack',
        ];

        foreach ($categories as $category) {
            Category::create([
                'nama' => $category,
            ]);
        }
    }
}