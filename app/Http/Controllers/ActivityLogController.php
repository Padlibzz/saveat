<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    // Hanya sisakan metode index ini saja! Hapus metode lain (create, store, dll)
    public function index(Request $request)
    {
        $query = ActivityLog::with('user:id,name,email,peran');

        if ($request->user()->peran !== 'admin') {
            $query->where('user_id', $request->user()->id);
        }

        $logs = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'status' => 'success',
            'data' => $logs,
        ], 200);
    }
}
