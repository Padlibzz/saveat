@extends('layouts.dashboard')

@section('page_title', 'Aktivitas & Pesanan')

@section('content')
<div class="w-full max-w-4xl mx-auto" x-data="{ filterTab: 'aktif' }">

    {{-- ALERT MESSAGES --}}
    @if(session('success'))
        <x-alert type="success" :message="session('success')" />
    @endif

    @if(session('error'))
        <x-alert type="error" :message="session('error')" />
    @endif
    
    {{-- JUDUL HALAMAN & TABS --}}
    <div class="mb-6 border-b border-[#545523]/10 pb-4">
        <h2 class="text-xl font-bold text-[#545523]">Aktivitas & Riwayat Pesanan 📋</h2>
        <p class="text-xs text-gray-500 font-medium mt-1">Pantau terus status klaim makanan penyelamatmu dan tumpukan kontribusi lingkunganmu.</p>
        
        {{-- Tabs Filter Navigasi --}}
        <div class="flex items-center gap-3 mt-5">
            <button @click="filterTab = 'aktif'" 
                    :class="filterTab === 'aktif' ? 'bg-[#545523] text-white' : 'bg-white text-gray-500 border-gray-200 hover:bg-gray-50'" 
                    class="px-5 py-2 rounded-full text-xs font-bold border transition-all shadow-sm cursor-pointer">
                Pesanan Aktif
            </button>
            <button @click="filterTab = 'selesai'" 
                    :class="filterTab === 'selesai' ? 'bg-[#545523] text-white' : 'bg-white text-gray-500 border-gray-200 hover:bg-gray-50'" 
                    class="px-5 py-2 rounded-full text-xs font-bold border transition-all shadow-sm cursor-pointer">
                Selesai
            </button>
            <button @click="filterTab = 'batal'" 
                    :class="filterTab === 'batal' ? 'bg-[#545523] text-white' : 'bg-white text-gray-500 border-gray-200 hover:bg-gray-50'" 
                    class="px-5 py-2 rounded-full text-xs font-bold border transition-all shadow-sm cursor-pointer">
                Dibatalkan
            </button>
        </div>
    </div>

    {{-- KONTEN FEED UTAMA (UI BENTUK LIST TERBARU) --}}
    <div class="bg-white rounded-3xl shadow-xs border border-gray-100 overflow-hidden divide-y divide-gray-50">
        
        {{-- ========================================== --}}
        {{-- 1. BLOK PESANAN AKTIF ($claims)            --}}
        {{-- ========================================== --}}
        @forelse($claims as $pesanan)
            <div x-show="filterTab === 'aktif'" 
                 x-transition.opacity
                 class="p-5 flex items-start gap-4 hover:bg-gray-50/50 transition-all relative">
                
                {{-- Foto / Ikon Mitra --}}
                <div class="w-12 h-12 md:w-14 md:h-14 rounded-full overflow-hidden shrink-0 border border-gray-100 shadow-2xs">
                    @if(optional($pesanan->listing)->foto)
                        <img src="{{ asset('storage/' . $pesanan->listing->foto) }}" alt="{{ optional($pesanan->listing)->nama }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-[#545523] flex items-center justify-center text-white text-lg">
                            <i class="fa-solid fa-store"></i>
                        </div>
                    @endif
                </div>
                
                {{-- Informasi Pesanan --}}
                <div class="flex-1 min-w-0 pr-16 md:pr-24">
                    <div class="flex flex-wrap items-center gap-2 mb-1">
                        <h4 class="text-sm font-bold text-gray-800 tracking-wide">{{ optional(optional($pesanan->listing)->merchant)->nama_usaha ?? 'Mitra Tidak Ditemukan' }}</h4>
                        <span class="px-2 py-0.5 bg-amber-50 text-amber-600 border border-amber-200 rounded text-[9px] font-bold uppercase tracking-wider">
                            {{ str_replace('_', ' ', $pesanan->status) }}
                        </span>
                    </div>
                    
                    <p class="text-xs text-gray-600 mt-1 leading-relaxed">
                        Kamu memesan <span class="font-semibold text-[#545523]">{{ $pesanan->jumlah }}x {{ optional($pesanan->listing)->nama ?? 'Produk Tidak Ditemukan' }}</span>.<br>
                        Total Tagihan: <span class="font-bold">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                    </p>
                    <p class="text-[10px] text-gray-400 mt-0.5">Order ID: {{ $pesanan->kode_klaim }}</p>

                    {{-- Tombol Action --}}
                    <div class="mt-3">
                        <a href="{{ route('claim.success', $pesanan->id) }}" class="px-4 py-1.5 text-xs font-bold text-[#545523] bg-white border border-[#545523] rounded-lg hover:bg-gray-50 transition cursor-pointer inline-block text-center shadow-sm">
                            <i class="fa-solid fa-qrcode mr-1"></i> Lihat QR Code
                        </a>
                    </div>
                </div>

                {{-- Waktu & Indikator --}}
                <div class="absolute right-5 top-5 flex flex-col items-end gap-2">
                    <span class="text-[10px] md:text-[11px] font-medium text-gray-400 text-right">{{ $pesanan->created_at->diffForHumans() }}</span>
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse block shadow-[0_0_5px_rgba(16,185,129,0.5)]"></span>
                </div>
            </div>
        @empty
            <div x-show="filterTab === 'aktif'" class="py-12 text-center flex flex-col items-center justify-center bg-white">
                <i class="fa-solid fa-basket-shopping text-gray-200 text-4xl mb-3"></i>
                <h3 class="text-sm font-bold text-gray-500">Belum Ada Pesanan Aktif</h3>
                <p class="text-xs text-gray-400 mt-1">Cari makanan penyelamat dan mulai berkontribusi hari ini.</p>
            </div>
        @endforelse

        {{-- ========================================== --}}
        {{-- 2. BLOK RIWAYAT (Selesai & Batal)          --}}
        {{-- ========================================== --}}
        @forelse($riwayatPesanan as $pesanan)
            @php
                $statusTab = $pesanan->status === 'diambil' ? 'selesai' : 'batal';
            @endphp

            <div x-show="filterTab === '{{ $statusTab }}'" 
                 x-transition.opacity
                 class="p-5 flex items-start gap-4 hover:bg-gray-50/50 transition-all relative">
                
                {{-- Foto / Ikon Mitra --}}
                <div class="w-12 h-12 md:w-14 md:h-14 rounded-full overflow-hidden shrink-0 border border-gray-100 shadow-2xs opacity-80">
                    @if(optional($pesanan->listing)->foto)
                        <img src="{{ asset('storage/' . $pesanan->listing->foto) }}" alt="{{ optional($pesanan->listing)->nama }}" class="w-full h-full object-cover grayscale-[30%]">
                    @else
                        <div class="w-full h-full bg-gray-400 flex items-center justify-center text-white text-lg">
                            <i class="fa-solid fa-store"></i>
                        </div>
                    @endif
                </div>
                
                {{-- Informasi Pesanan --}}
                <div class="flex-1 min-w-0 pr-16 md:pr-24">
                    <div class="flex flex-wrap items-center gap-2 mb-1">
                        <h4 class="text-sm font-bold text-gray-800 tracking-wide">{{ optional(optional($pesanan->listing)->merchant)->nama_usaha ?? 'Mitra Tidak Ditemukan' }}</h4>
                        
                        @if($pesanan->status === 'diambil')
                            <span class="px-2 py-0.5 bg-emerald-50 text-emerald-600 border border-emerald-200 rounded text-[9px] font-bold uppercase tracking-wider">
                                Selesai
                            </span>
                        @else
                            <span class="px-2 py-0.5 bg-red-50 text-red-600 border border-red-200 rounded text-[9px] font-bold uppercase tracking-wider">
                                Dibatalkan
                            </span>
                        @endif
                    </div>
                    
                    <p class="text-xs text-gray-600 mt-1 leading-relaxed">
                        Pesanan <span class="font-semibold text-gray-700">{{ $pesanan->jumlah }}x {{ optional($pesanan->listing)->nama ?? 'Produk Tidak Ditemukan' }}</span> 
                        sebesar Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}.
                    </p>
                    <p class="text-[10px] text-gray-400 mt-0.5">Order ID: {{ $pesanan->kode_klaim }}</p>

                    {{-- Tombol Action --}}
                    <div class="mt-3">
                        <a href="{{ route('pesanan.detail', $pesanan->id) }}" class="px-4 py-1.5 text-xs font-bold text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 transition cursor-pointer inline-block text-center shadow-sm">
                            Lihat Detail
                        </a>
                    </div>
                </div>

                {{-- Waktu --}}
                <div class="absolute right-5 top-5 flex flex-col items-end gap-2">
                    <span class="text-[10px] md:text-[11px] font-medium text-gray-400 text-right">{{ $pesanan->updated_at->translatedFormat('d M Y') }}</span>
                    {{-- Tidak ada dot hijau berkedip untuk riwayat --}}
                </div>
            </div>
        @empty
            <div x-show="filterTab === 'selesai' || filterTab === 'batal'" class="py-12 text-center flex flex-col items-center justify-center bg-white">
                <i class="fa-solid fa-receipt text-gray-200 text-4xl mb-3"></i>
                <h3 class="text-sm font-bold text-gray-500">Belum Ada Riwayat</h3>
                <p class="text-xs text-gray-400 mt-1">Data pesanan yang selesai atau dibatalkan akan muncul di sini.</p>
            </div>
        @endforelse

    </div>
</div>
@endsection