@extends('layouts.app')

@section('title', 'Dashboard Konsumen')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <h2 class="font-display text-3xl font-extrabold text-[#555524]">Halo, {{ Auth::user()->name }}!</h2>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-sage/30">
            <p class="text-xs font-medium uppercase tracking-wide text-[#555524]">Total Makanan Diselamatkan</p>
            <p class="font-display text-3xl font-extrabold mt-1 text-olive">{{ Auth::user()->claims()->where('status', 'selesai')->sum('jumlah') }} porsi</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-sage/30">
            <p class="text-xs font-medium uppercase tracking-wide text-[#555524]">Total Pesanan</p>
            <p class="font-display text-3xl font-extrabold mt-1 text-olive">{{ Auth::user()->claims()->count() }} pesanan</p>
        </div>
    </div>

    <!-- Recommendations -->
    <div>
        <h3 class="font-display text-2xl font-bold text-[#555524] mb-4">Rekomendasi Untukmu</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach($listings as $listing)
                <div class="bg-white rounded-2xl p-4 shadow-sm hover:shadow-md transition-shadow">
                    <img src="{{ $listing->foto ? asset('storage/'.$listing->foto) : asset('img/default-food.jpg') }}" alt="{{ $listing->nama }}" class="w-full h-40 object-cover rounded-xl mb-3">
                    <h4 class="font-bold text-gray-900">{{ $listing->nama }}</h4>
                    <p class="text-sm text-gray-500">{{ $listing->merchant?->nama_usaha ?? 'Merchant' }}</p>
                    <div class="flex items-center justify-between mt-3">
                        <span class="font-bold text-olive">Rp{{ number_format($listing->harga_diskon, 0, ',', '.') }}</span>
                        <span class="text-xs text-red-500 font-semibold">Sisa {{ $listing->stok_sisa }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
