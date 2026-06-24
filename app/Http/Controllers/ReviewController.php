<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'claim_id' => 'required|exists:claims,id',
            'rating'   => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:500',
        ]);

        $claim = Claim::find($request->claim_id);

        if ($claim->user_id !== $request->user()->id) {
            return response()->json(['status' => 'error', 'message' => 'Klaim ini bukan milik Anda.'], 403);
        }

        if ($claim->status !== 'diambil') {
            return response()->json(['status' => 'error', 'message' => 'Hanya pesanan yang sudah diambil yang bisa diberi ulasan.'], 400);
        }

        if (Review::where('claim_id', $claim->id)->exists()) {
            return response()->json(['status' => 'error', 'message' => 'Pesanan ini sudah pernah diberi ulasan.'], 400);
        }

        $review = Review::create([
            'claim_id'   => $claim->id,
            'user_id'    => $request->user()->id,
            'listing_id' => $claim->listing_id,
            'rating'     => $request->rating,
            'komentar'   => $request->komentar,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Terima kasih atas ulasannya!',
            'data'    => $review,
        ], 201);
    }
}