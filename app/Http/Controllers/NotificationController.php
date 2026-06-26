<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // API
    public function index(Request $request)
    {
        $notifications = Notification::where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $notifications,
        ], 200);
    }

    public function read(Request $request, $id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (! $notification) {
            return response()->json([
                'status' => 'error',
                'message' => 'Notifikasi tidak ditemukan.',
            ], 404);
        }

        $notification->update(['is_read' => true]);

        return response()->json([
            'status' => 'success',
            'message' => 'Notifikasi ditandai sudah dibaca.',
        ], 200);
    }

    public function readAll(Request $request)
    {
        Notification::where('user_id', $request->user()->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'status' => 'success',
            'message' => 'Semua notifikasi ditandai sudah dibaca.',
        ], 200);
    }

    // BLADE
    public function merchantIndex(Request $request)
    {
        $userId = auth()->id();

        $aktivitas = Notification::where('user_id', $userId)
            ->whereIn('jenis', ['claims_masuk', 'pesanan_selesai', 'menunggu_pembayaran'])
            ->orderBy('created_at', 'desc')
            ->get();

        $sistem = Notification::where('user_id', $userId)
            ->whereIn('jenis', ['listing_expired', 'stok_menipis'])
            ->orderBy('created_at', 'desc')
            ->get();

        Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('merchant.notifikasi', compact('aktivitas', 'sistem'));
    }

    public function konsumenIndex(Request $request)
    {
        $userId = auth()->id();

        $aktivitas = Notification::where('user_id', $userId)
            ->whereIn('jenis', ['claims_berhasil', 'pesanan_selesai', 'menunggu_pembayaran'])
            ->orderBy('created_at', 'desc')
            ->get();

        $promo = Notification::where('user_id', $userId)
            ->whereIn('jenis', ['listing_baru', 'tonggak_dampak'])
            ->orderBy('created_at', 'desc')
            ->get();

        Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('customer.notifikasi', compact('aktivitas', 'promo'));
    }

    public function readWeb($id)
    {
        Notification::where('id', $id)
            ->where('user_id', auth()->id())
            ->update(['is_read' => true]);
        
        return redirect()->back();
    }

    public function readAllWeb()
    {
        Notification::forUser(auth()->id())
            ->unread()
            ->update(['is_read' => true]);
        
        return redirect()->back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }

    public function unreadCount()
    {
        $count = Notification::forUser(auth()->id())
            ->unread()
            ->count();
        
        return response()->json(['count' => $count]);
    }

}
