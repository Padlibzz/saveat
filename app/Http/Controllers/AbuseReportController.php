<?php

namespace App\Http\Controllers;

use App\Models\AbuseReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AbuseReportController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'listing_id' => 'required|exists:listings,id',
            'alasan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        $report = AbuseReport::create([
            'user_id' => $request->user()->id,
            'listing_id' => $request->listing_id,
            'alasan' => $request->alasan,
            'deskripsi' => $request->deskripsi,
            'status' => 'menunggu',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Laporan penyalahgunaan berhasil dikirim dan akan segera ditinjau oleh Admin.',
            'data' => $report,
        ], 201);
    }

    public function index(Request $request)
    {
        if ($request->user()->peran !== 'admin') {
            return response()->json(['status' => 'error', 'message' => 'Anda tidak memiliki akses.'], 403);
        }

        $reports = AbuseReport::with(['user:id,name', 'listing:id,nama,merchant_id', 'listing.merchant:id,nama_usaha'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $reports,
        ], 200);
    }

    public function updateStatus(Request $request, $id)
    {
        if ($request->user()->peran !== 'admin') {
            return response()->json(['status' => 'error', 'message' => 'Anda tidak memiliki akses.'], 403);
        }

        $request->validate(['status' => 'required|in:menunggu,diproses,selesai,ditolak']);

        $report = AbuseReport::find($id);
        if (! $report) {
            return response()->json(['status' => 'error', 'message' => 'Laporan tidak ditemukan'], 404);
        }

        $report->update(['status' => $request->status]);

        return response()->json([
            'status' => 'success',
            'message' => 'Status laporan penyalahgunaan berhasil diperbarui',
            'data' => $report,
        ], 200);
    }
}
