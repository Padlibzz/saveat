<?php

namespace App\Http\Controllers;

use App\Models\Notification;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notifications = Notification::where('id_pengguna', auth()->id())->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $notifications,
        ]);
    }

    public function read($id)
    {
        $notifications = Notification::where(
            'id',
            $id
        )
            ->where(
                'id_pengguna',
                auth()->id()
            )
            ->firstOrFail();

        $notifications->update([
            'is_read' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi dibaca',
        ]);
    }
}
