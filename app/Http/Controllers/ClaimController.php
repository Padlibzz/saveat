<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Notification;
use Illuminate\Http\Request; // Pastikan ini di-import

class ClaimController extends Controller
{
    public function selesai(Request $request, $id)
    {
        $claims = Claim::findOrFail($id);

        if ($claims->id_pengguna !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Anda tidak berhak menyelesaikan pesanan ini.',
            ], 403);
        }

        $claims->update([
            'status' => 'sudah_diambil',
            'diambil_pada' => now(),
        ]);

        Notification::create([
            'id_pengguna' => $claims->id_pengguna,
            'id_claims' => $claims->id,
            'jenis' => 'pesanan_selesai',
            'judul' => 'Pesanan Selesai',
            'pesan' => 'Pesanan Anda telah selesai dan siap diambil.',
            'is_read' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil diselesaikan',
        ]);
    }
}
