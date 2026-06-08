<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Notification;
use Illuminate\Http\Request;

class ClaimController extends Controller
{
    public function selesai ($id)
    {
        $claims = Claim::findOrFail($id);
        $claims->update([
            'status' => 'sudah_diambil',
            'diambil_pada' => now()
        ]);

        Notification::create([
            'id_pengguna' => $claims->id_pengguna,
            'id_claims' => $claims->id,
            'jenis' => 'pesanan_selesai',
            'judul' => 'Pesanan Selesai',
            'pesan' => 'Pesanan Anda telah selesai dan siap diambil.',
            'is_read' => false
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil diselesaikan'
        ]);
    }
}
