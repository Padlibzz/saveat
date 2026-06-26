<?php

namespace App\Http\Controllers;

use App\Enums\ListingStatus;
use App\Models\Category;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MerchantListingController extends Controller
{
    public function index(Request $request)
    {
        $merchant = $request->user()->merchant;

        if (! $merchant) {
            return redirect()->route('dashboard')->with('error', 'Profil merchant tidak ditemukan.');
        }

        // Mengambil semua dashboardk aktif milik merchant ini
        $listings = Listing::with('kategori')
            ->where('merchant_id', $merchant->id)
            ->whereIn('status', ['aktif', 'hampir_habis']) // Hanya tampilkan yang aktif
            ->orderBy('created_at', 'desc')
            ->get();

        // Mengembalikan ke halaman blade dashboardk-aktif
        return view('merchant.dashboardk-aktif', compact('listings'));
    }

    public function store(Request $request)
    {
        $merchant = $request->user()->merchant;

        if (! $merchant || $merchant->status_verifikasi !== 'disetujui') {
            return response()->json([
                'status' => 'error',
                'message' => 'Aksi ditolak. Akun Merchant Anda belum diverifikasi oleh Admin.',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'kategori_id' => 'required|exists:categories,id',
            'nama' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'harga_normal' => 'required|numeric|min:0',
            'harga_diskon' => 'required|numeric|min:0|lte:harga_normal',
            'stok_total' => 'required|integer|min:1',
            'batas_waktu' => 'required|date|after:now',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $input = $request->all();

            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $namaFoto = time().'_'.$file->getClientOriginalName();
                $path = $file->storeAs('listings', $namaFoto, 'public');
                $input['foto'] = $path;
            }

            $input['merchant_id'] = $merchant->id;
            $input['stok_sisa'] = $request->stok_total;
            $input['status'] = ListingStatus::AKTIF->value;

            $listing = Listing::create($input);

            return response()->json([
                'status' => 'success',
                'message' => 'Listing makanan berhasil dibuat.',
                'data' => $listing,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan pada server: '.$e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $merchant = $request->user()->merchant;

        $listing = Listing::where('id', $id)
            ->where('merchant_id', $merchant->id)
            ->first();

        if (! $listing) {
            return response()->json([
                'status' => 'error',
                'message' => 'Listing tidak ditemukan atau bukan milik Anda.',
            ], 404);
        }

        if (! in_array($listing->status, [ListingStatus::AKTIF, ListingStatus::HAMPIR_HABIS])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Listing yang sudah tutup/diarsipkan tidak dapat diedit.',
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'kategori_id' => 'sometimes|exists:categories,id',
            'nama' => 'sometimes|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'harga_normal' => 'sometimes|numeric|min:0',
            'harga_diskon' => [
                'sometimes',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) use ($request, $listing) {
                    $hargaNormal = $request->input('harga_normal', $listing->harga_normal);
                    if ($value > $hargaNormal) {
                        $fail('Harga diskon tidak boleh lebih besar dari harga normal.');
                    }
                },
            ],
            'stok_total' => 'sometimes|integer|min:1',
            'batas_waktu' => 'sometimes|date|after:now',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $input = $validator->validated();

        if ($request->filled('stok_total')) {
            $terjual = $listing->stok_total - $listing->stok_sisa;
            $stokBaru = $request->stok_total - $terjual;

            if ($stokBaru < 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Stok total baru tidak boleh kurang dari jumlah porsi yang sudah terklaim ('.$terjual.').',
                ], 400);
            }

            $input['stok_sisa'] = $stokBaru;
        }

        if ($request->hasFile('foto')) {
            if ($listing->foto && Storage::disk('public')->exists($listing->foto)) {
                Storage::disk('public')->delete($listing->foto);
            }

            $file = $request->file('foto');
            $namaFoto = time().'_'.$file->getClientOriginalName();
            $path = $file->storeAs('listings', $namaFoto, 'public');
            $input['foto'] = $path;
        }

        $listing->update($input);

        return response()->json([
            'status' => 'success',
            'message' => 'Listing berhasil diperbarui.',
            'data' => $listing,
        ], 200);
    }

    public function tutup(Request $request, $id)
    {
        $merchant = $request->user()->merchant;

        $listing = Listing::where('id', $id)
            ->where('merchant_id', $merchant->id)
            ->first();

        if (! $listing) {
            return redirect()->route('merchant.produk-aktif')->with('error', 'Listing tidak ditemukan atau bukan milik Anda.');
        }

        if ($listing->status === ListingStatus::TUTUP) {
            return redirect()->route('merchant.produk-aktif')->with('error', 'Listing ini sudah ditutup.');
        }

        $listing->update(['status' => ListingStatus::TUTUP->value]);

        return redirect()->route('merchant.produk-aktif')->with('success', 'Listing berhasil ditutup.');
    }

    /**
     * Soft-delete listing dari sisi merchant (modal "Anda yakin menghapus listing ini?")
     * Data tetap ada di DB agar riwayat klaim & ulasan konsumen tidak rusak,
     * tapi listing langsung hilang dari semua tampilan publik & dashboard merchant.
     */
    public function destroy(Request $request, $id)
    {
        $merchant = $request->user()->merchant;

        $listing = Listing::where('id', $id)
            ->where('merchant_id', $merchant->id)
            ->first();

        if (! $listing) {
            return redirect()->back()->with('error', 'Listing tidak ditemukan atau bukan milik Anda.');
        }

        $listing->update(['status' => 'dihapus']);

        return redirect()->route('merchant.produk-aktif')
            ->with('success', 'Listing berhasil dihapus.');
    }

    // ============================================================
    // ===================  WEB (BLADE) METHODS  ===================
    // ============================================================

    public function create(Request $request)
    {
        $merchant = $request->user()->merchant;
        $kategoris = Category::orderBy('nama')->get();

        return view('merchant.upload-makanan', compact('kategoris', 'merchant'));
    }

    public function storeWeb(Request $request)
    {
        $merchant = $request->user()->merchant;

        if (! $merchant || $merchant->status_verifikasi !== 'disetujui') {
            return redirect()->back()->with('error', 'Akun Merchant Anda belum diverifikasi oleh Admin.');
        }

        $validator = Validator::make($request->all(), [
            'kategori_id' => 'required|exists:categories,id',
            'nama' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'harga_normal' => 'required|numeric|min:0',
            'harga_diskon' => 'required|numeric|min:0|lte:harga_normal',
            'stok_total' => 'required|integer|min:1',
            'batas_waktu' => 'required|date|after:now',
            'deskripsi' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $input = $validator->validated();

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $namaFoto = time().'_'.$file->getClientOriginalName();
            $path = $file->storeAs('listings', $namaFoto, 'public');
            $input['foto'] = $path;
        }

        $input['merchant_id'] = $merchant->id;
        $input['stok_sisa'] = $request->stok_total;
        $input['status'] = ListingStatus::AKTIF->value;

        Listing::create($input);

        return redirect()->route('merchant.produk-aktif')->with('success', 'Listing berhasil diterbitkan.');
    }

    public function indexWeb(Request $request)
    {
        $merchant = $request->user()->merchant;

        $listings = Listing::with('kategori')
            ->where('merchant_id', $merchant->id)
            ->whereIn('status', ['aktif', 'hampir_habis'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('merchant.produk-aktif', compact('listings'));
    }

    public function edit(Request $request, $id)
    {
        $merchant = $request->user()->merchant;

        $listing = Listing::where('id', $id)
            ->where('merchant_id', $merchant->id)
            ->firstOrFail();

        $kategoris = Category::orderBy('nama')->get();

        return view('merchant.edit-listing', compact('listing', 'kategoris'));
    }

    /**
     * Proses submit form edit listing dari Blade
     */
    public function updateWeb(Request $request, $id)
    {
        $merchant = $request->user()->merchant;

        $listing = Listing::where('id', $id)
            ->where('merchant_id', $merchant->id)
            ->first();

        if (! $listing) {
            return redirect()->route('merchant.produk-aktif')->with('error', 'Listing tidak ditemukan atau bukan milik Anda.');
        }

        if (! in_array($listing->status, [ListingStatus::AKTIF, ListingStatus::HAMPIR_HABIS])) {
            return redirect()->route('merchant.produk-aktif')->with('error', 'Listing yang sudah tutup/diarsipkan tidak dapat diedit.');
        }

        $validator = Validator::make($request->all(), [
            'kategori_id' => 'sometimes|exists:categories,id',
            'nama' => 'sometimes|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'harga_normal' => 'sometimes|numeric|min:0',
            'harga_diskon' => [
                'sometimes',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) use ($request, $listing) {
                    $hargaNormal = $request->input('harga_normal', $listing->harga_normal);
                    if ($value > $hargaNormal) {
                        $fail('Harga diskon tidak boleh lebih besar dari harga normal.');
                    }
                },
            ],
            'stok_total' => 'sometimes|integer|min:1',
            'batas_waktu' => 'sometimes|date|after:now',
            'deskripsi' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $input = $validator->validated();

        if ($request->filled('stok_total')) {
            $terjual = $listing->stok_total - $listing->stok_sisa;
            $stokBaru = $request->stok_total - $terjual;

            if ($stokBaru < 0) {
                return redirect()->back()->withErrors([
                    'stok_total' => 'Stok total baru tidak boleh kurang dari jumlah porsi yang sudah terklaim ('.$terjual.').',
                ])->withInput();
            }

            $input['stok_sisa'] = $stokBaru;
        }

        if ($request->hasFile('foto')) {
            if ($listing->foto && Storage::disk('public')->exists($listing->foto)) {
                Storage::disk('public')->delete($listing->foto);
            }

            $file = $request->file('foto');
            $namaFoto = time().'_'.$file->getClientOriginalName();
            $path = $file->storeAs('listings', $namaFoto, 'public');
            $input['foto'] = $path;
        }

        $listing->update($input);

        return redirect()->route('merchant.dashboardk-aktif')->with('success', 'Listing berhasil diperbarui.');
    }
}
