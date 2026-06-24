@extends('layouts.dashboard')

@section('page_title', 'Riwayat Pesanan')

@section('content')
<div class="w-full max-w-4xl mx-auto" x-data="{ filterTab: 'semua' }">
    
    {{-- JUDUL HALAMAN & NAVIGASI TAB --}}
    <div class="mb-6 border-b border-[#545523]/10 pb-4">
        <h2 class="text-xl font-bold text-[#545523]">Riwayat Pesanan</h2>
        <p class="text-xs text-gray-400 font-medium mt-1">Lihat kembali daftar makanan penyelamat yang pernah kamu beli.</p>
        
        {{-- Tabs Filter --}}
        <div class="flex items-center gap-4 mt-5">
            <button @click="filterTab = 'semua'" 
                    :class="filterTab === 'semua' ? 'bg-[#545523] text-white' : 'bg-white text-gray-500 border-gray-200 hover:bg-gray-50'" 
                    class="px-5 py-2 rounded-full text-xs font-bold border transition-all shadow-2xs cursor-pointer">
                Semua
            </button>
            <button @click="filterTab = 'selesai'" 
                    :class="filterTab === 'selesai' ? 'bg-[#545523] text-white' : 'bg-white text-gray-500 border-gray-200 hover:bg-gray-50'" 
                    class="px-5 py-2 rounded-full text-xs font-bold border transition-all shadow-2xs cursor-pointer">
                Selesai
            </button>
            <button @click="filterTab = 'batal'" 
                    :class="filterTab === 'batal' ? 'bg-[#545523] text-white' : 'bg-white text-gray-500 border-gray-200 hover:bg-gray-50'" 
                    class="px-5 py-2 rounded-full text-xs font-bold border transition-all shadow-2xs cursor-pointer">
                Dibatalkan
            </button>
        </div>
    </div>

    {{-- SIMULASI DATA BACKEND --}}
    @php
        $riwayatPesanan = [
            (object)[
                'id' => 'SV-8821',
                'status' => 'selesai',
                'tanggal' => '24 Jun 2026, 14:30 WIB',
                'mitra' => 'Dapur Roti Nusantara',
                'menu' => '2x Nasi Campur Bali',
                'foto' => 'https://images.unsplash.com/photo-1513104890138-7c749659a591?q=80&w=150&auto=format&fit=crop',
                'total_harga' => 45000,
            ],
            (object)[
                'id' => 'SV-8819',
                'status' => 'selesai',
                'tanggal' => '21 Jun 2026, 18:15 WIB',
                'mitra' => 'Toko Kue Makmur',
                'menu' => '1x Brownies Box (Surprise Bag)',
                'foto' => null, // Simulasi jika tidak ada foto
                'total_harga' => 25000,
            ],
            (object)[
                'id' => 'SV-8790',
                'status' => 'batal',
                'tanggal' => '18 Jun 2026, 19:00 WIB',
                'mitra' => 'Ayam Geprek Bu Sri',
                'menu' => '3x Paket Ayam Geprek Surplus',
                'foto' => 'https://images.unsplash.com/photo-1626082927389-6cd097cdc6ec?q=80&w=150&auto=format&fit=crop',
                'total_harga' => 30000,
            ],
        ];
    @endphp

    {{-- DAFTAR KARTU PESANAN --}}
    <div class="space-y-4">
        @foreach($riwayatPesanan as $pesanan)
            <div x-show="filterTab === 'semua' || filterTab === '{{ $pesanan->status }}'" 
                 x-transition.opacity
                 class="bg-[#FDFDF5] p-5 rounded-2xl border border-[#545523]/10 shadow-xs flex flex-col gap-4">
                
                {{-- Bagian Header Kartu: Tanggal & Status --}}
                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-bag-shopping text-[#545523] text-sm"></i>
                        <span class="text-xs font-bold text-gray-700">Belanja • {{ $pesanan->tanggal }}</span>
                    </div>
                    
                    @if($pesanan->status === 'selesai')
                        <span class="px-3 py-1 bg-emerald-50 text-emerald-600 border border-emerald-200 rounded-lg text-[10px] font-bold uppercase tracking-wider">
                            Selesai
                        </span>
                    @else
                        <span class="px-3 py-1 bg-red-50 text-red-600 border border-red-200 rounded-lg text-[10px] font-bold uppercase tracking-wider">
                            Dibatalkan
                        </span>
                    @endif
                </div>

                {{-- Bagian Tengah: Detail Produk --}}
                <div class="flex items-start gap-4">
                    {{-- Foto Produk --}}
                    <div class="w-16 h-16 md:w-20 md:h-20 rounded-xl overflow-hidden shrink-0 border border-gray-200 shadow-2xs">
                        @if($pesanan->foto)
                            <img src="{{ $pesanan->foto }}" alt="{{ $pesanan->menu }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-[#545523] to-[#7A7A33] flex items-center justify-center">
                                <i class="fa-solid fa-utensils text-white/50 text-2xl"></i>
                            </div>
                        @endif
                    </div>
                    
                    {{-- Info Produk --}}
                    <div class="flex-1 min-w-0">
                        <h4 class="text-xs font-bold text-gray-500 mb-1">{{ $pesanan->mitra }}</h4>
                        <p class="text-sm md:text-base font-bold text-gray-800 leading-tight">{{ $pesanan->menu }}</p>
                        <p class="text-[10px] text-gray-400 mt-2 font-medium">Order ID: {{ $pesanan->id }}</p>
                    </div>

                    {{-- Harga & Total (Sisi Kanan) --}}
                    <div class="text-right border-l border-gray-100 pl-4 hidden md:block">
                        <span class="text-xs text-gray-500 block mb-1">Total Belanja</span>
                        <span class="text-base font-extrabold text-[#545523]">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                    </div>
                </div>

                {{-- Bagian Footer Kartu: Aksi & Harga Mobile --}}
                <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                    <div class="md:hidden">
                        <span class="text-[10px] text-gray-500 block">Total Belanja</span>
                        <span class="text-sm font-extrabold text-[#545523]">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                    </div>
                    
                    <div class="flex items-center gap-2 w-full justify-end">
                        <button class="px-4 py-2 text-xs font-bold text-[#545523] bg-white border border-[#545523] rounded-lg hover:bg-gray-50 transition cursor-pointer">
                            Lihat Detail
                        </button>
                        @if($pesanan->status === 'selesai')
                            <button class="px-4 py-2 text-xs font-bold text-white bg-[#545523] rounded-lg hover:bg-[#43441c] shadow-xs transition cursor-pointer">
                                Beri Ulasan
                            </button>
                        @endif
                    </div>
                </div>
                
            </div>
        @endforeach
    </div>

</div>
@endsection