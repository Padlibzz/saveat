<?php

namespace Database\Seeders;

use App\Models\Profil;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProfilSeeder extends Seeder
{
    public function run(): void
    {
        $i = 0;
        foreach (User::all() as $user) {
            // Generate deterministic offsets within Sumedang to make distance calculations work realistically
            $latOffset = ($i % 5) * 0.01 - 0.02;
            $lngOffset = (($i * 3) % 5) * 0.01 - 0.02;

            Profil::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'tipe_profil' => $user->peran,
                    'nama_usaha' => $user->peran === 'merchant'
                        ? $user->name
                        : null,
                    'alamat' => 'Sumedang, Jawa Barat',
                    'latitude' => -6.8374 + $latOffset,
                    'longitude' => 107.9208 + $lngOffset,
                    'izin_lokasi' => true,
                    'deskripsi' => 'Data testing',
                    'status_verifikasi' => 'disetujui',
                ]
            );
            $i++;
        }
    }
}