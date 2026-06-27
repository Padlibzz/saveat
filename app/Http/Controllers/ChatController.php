<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Menampilkan halaman utama chat beserta daftar kontak
     */
    public function index()
    {
        $authId = Auth::id();
        $authUser = Auth::user();

        // Ambil daftar kontak yang pernah berkirim pesan
        $contactIds = Chat::where('sender_id', $authId)->pluck('receiver_id')
            ->merge(Chat::where('receiver_id', $authId)->pluck('sender_id'))
            ->unique()
            ->filter(fn($id) => $id != $authId);

        $contacts = User::whereIn('id', $contactIds)->get();

        // JIKA YANG LOGIN ADALAH MERCHANT, LEMPAR KE TAMPILAN DASHBOARD MERCHANT
        if ($authUser->role === 'merchant' || $authUser->is_merchant) { 
            return view('merchant.chat', compact('contacts'));
        }

        // JIKA KONSUMEN, LEMPAR KE TAMPILAN KONSUMEN BIASA
        return view('chat.index', compact('contacts'));
    }

    /**
     * Mengambil riwayat chat antara user aktif dengan lawan bicara tertentu (API / AJAX)
     */
    public function getMessages($receiverId)
    {
        $authId = Auth::id();

        $messages = Chat::where(function($query) use ($authId, $receiverId) {
            $query->where('sender_id', $authId)->where('receiver_id', $receiverId);
        })->orWhere(function($query) use ($authId, $receiverId) {
            $query->where('sender_id', $receiverId)->where('receiver_id', $authId);
        })
        ->orderBy('created_at', 'asc')
        ->get();

        // Tandai pesan dari lawan bicara sebagai "sudah dibaca"
        Chat::where('sender_id', $receiverId)->where('receiver_id', $authId)->update(['is_read' => true]);

        return response()->json($messages);
    }

    /**
     * Mengirim pesan baru ke database (API / AJAX)
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);

        $chat = Chat::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
            'is_read' => false
        ]);

        return response()->json(['status' => 'Success', 'data' => $chat]);
    }
}