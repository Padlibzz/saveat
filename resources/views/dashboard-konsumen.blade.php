@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-8">
    <!-- Welcome/Hero Section -->
    <div class="bg-white rounded-3xl p-6 shadow-sm">
        <h1 class="text-3xl font-display font-bold text-[#545523]">Halo, {{ Auth::user()->name }}!</h1>
        <p class="text-gray-500">Apa yang ingin Anda makan hari ini?</p>
        <a href="{{ route('listing-makanan') }}" class="inline-block mt-4 bg-[#545523] text-white px-6 py-2 rounded-xl font-bold hover:bg-[#3a4a22] transition">
            Lihat Semua Makanan
        </a>
    </div>

    <!-- Quick Links/Active Orders Alert -->
    <div class="bg-[#f8f9ee] rounded-3xl p-6 border border-[#e2e4c8]">
        <h2 class="text-xl font-display font-bold text-[#545523] mb-4">Pesanan Anda</h2>
        <a href="{{ route('pesanan.aktif') }}" class="block p-4 bg-white rounded-2xl shadow-sm hover:shadow-md transition text-center">
            <p class="text-gray-700">Cek status pesanan aktif Anda</p>
        </a>
    </div>

    <!-- Limited Recommendations -->
    <div>
        <h2 class="text-2xl font-display font-bold text-[#545523] mb-5">
            Rekomendasi Untukmu
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach($listings->take(3) as $listing)
                <x-food-card
                    data-url="/checkout/{{ $listing->id }}" :foto="$listing->foto"
                    :nama="$listing->nama"
                    :merchant="$listing->merchant?->nama_usaha ?? 'Merchant'"
                    :alamat="$listing->merchant?->alamat ?? '-'"
                    :jarak="$listing->jarak_km ?? '-'"
                    :harga_diskon="$listing->harga_diskon"
                    :harga_asli="$listing->harga_normal"
                    :tersisa="$listing->stok_sisa"
                />
            @endforeach
        </div>
    </div>

    <!-- Join Merchant -->
    @if(Auth::user()->peran !== 'merchant')
        <div class="bg-[#4a5c2f] rounded-3xl shadow-md p-8 text-center text-white">
            <h3 class="text-2xl font-display font-bold">Mulai Jual Makanan Anda</h3>
            <p class="text-[#c8d5b3] mt-2 mb-6">Lengkapi data usaha Anda dan ajukan verifikasi merchant.</p>
            <a href="{{ route('merchant.application') }}" class="inline-block bg-white text-[#4a5c2f] font-bold px-8 py-3 rounded-xl hover:bg-cream transition">
                Daftar Merchant
            </a>
        </div>
    @endif
</div>
@endsection