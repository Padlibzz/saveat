<?php

namespace Database\Seeders;

use App\Models\Profil;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProfilSeeder extends Seeder
{
    public function run(): void
    {
        foreach (User::all() as $user) {

            Profil::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'tipe_profil' => $user->peran,
                    'nama_usaha' => $user->peran === 'merchant'
                        ? "Usaha {$user->name}"
                        : null,
                    'alamat' => 'Sumedang, Jawa Barat',
                    'deskripsi' => 'Data testing',
                    'status_verifikasi' => 'disetujui',
                ]
            );
        }
    }
}