@extends('layouts.dashboard')

@section('page_title', 'Dashboard Merchant')

@section('content')

    {{-- 1. ALERT BANNER SYSTEM --}}
    @if(session('success'))
        <x-alert type="success" :message="session('success')" />
    @endif

    @if(session('error'))
        <x-alert type="error" :message="session('error')" />
    @endif

    {{-- 2. GREETING HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white/40 backdrop-blur-xs p-4 rounded-2xl border border-white/40">
        <div>
            <h1 class="text-2xl font-bold text-[#545523]">Selamat Datang, Merchant! 👋</h1>
            <p class="text-sm text-[#545523]/80 font-medium">Pantau dampak dan penjualan makanan Anda hari ini.</p>
        </div>
        {{-- Menggunakan route dinamis Laravel --}}
        <a href="{{ route('merchant.upload') }}" class="inline-flex items-center justify-center bg-[#545523] text-[#F1F2CF] font-semibold px-6 py-3 rounded-xl shadow-sm hover:bg-[#43441c] transition-all gap-2 text-sm">
            <i class="fa-solid fa-plus text-xs"></i> Listing Makanan Baru
        </a>
    </div>

    {{-- 3. STATS CARDS (Responsif Grid: 1 Kolom HP, 3 Kolom Desktop) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
        
        <div class="bg-[#FDFDF5] p-6 rounded-2xl shadow-xs flex flex-col justify-between border border-[#545523]/10 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="bg-[#545523]/10 p-2.5 rounded-xl text-[#545523]">
                        <i class="fa-solid fa-wallet text-lg"></i>
                    </div>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Penghasilan</span>
                </div>
            </div>
            <h3 class="text-2xl font-black text-[#545523] mt-6 tracking-tight">
                Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}
            </h3>
        </div>

        <div class="bg-[#FDFDF5] p-6 rounded-2xl shadow-xs flex flex-col justify-between border border-[#545523]/10 relative hover:shadow-md transition">
            <span class="absolute top-6 right-6 text-[10px] font-bold bg-orange-100 text-orange-700 px-2 py-0.5 rounded-md uppercase">hari ini</span>
            <div class="flex items-center gap-3">
                <div class="bg-[#545523]/10 p-2.5 rounded-xl text-[#545523]">
                    <i class="fa-solid fa-utensils text-lg"></i>
                </div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Makanan Terjual</span>
            </div>
            <h3 class="text-2xl font-black text-[#545523] mt-6 tracking-tight">
                {{ $totalPorsiTerjual ?? 0 }} <span class="text-sm font-medium text-gray-500">Porsi</span>
            </h3>
        </div>

        <div class="bg-[#FDFDF5] p-6 rounded-2xl shadow-xs flex flex-col justify-between border border-[#545523]/10 hover:shadow-md transition sm:col-span-2 lg:col-span-1">
            <div class="flex items-center gap-3">
                <div class="bg-[#545523]/10 p-2.5 rounded-xl text-[#545523]">
                    <i class="fa-solid fa-users text-lg"></i>
                </div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Pembeli</span>
            </div>
            <h3 class="text-2xl font-black text-[#545523] mt-6 tracking-tight">
                {{ $totalPembeliUnik ?? 0 }} <span class="text-sm font-medium text-gray-500">Pelanggan</span>
            </h3>
        </div>

    </div>

    {{-- 4. ANALYTICS & ACTIVITY SPLIT GRID (Responsif Stack di HP, Berdampingan di Desktop) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="bg-[#FDFDF5] p-6 rounded-2xl shadow-xs border border-[#545523]/10 lg:col-span-2 flex flex-col justify-between">
            <div class="flex justify-between items-center mb-6">
                <h4 class="text-sm font-bold text-[#545523] uppercase tracking-wider">Kurs Penjualan</h4>
                <select class="text-xs font-semibold text-gray-600 bg-gray-100 border-none rounded-lg px-3 py-2 focus:ring-1 focus:ring-[#545523]/30">
                    <option>7 Hari Terakhir</option>
                </select>
            </div>
            
            <div class="flex items-end justify-between gap-1 sm:gap-2 pt-4 h-48 border-b border-gray-100 px-2">
                {{-- Logika Grafik Dinamis --}}
                @php
                    $maxTotal = collect($grafikPenjualan ?? [])->max('total') ?: 1;
                @endphp
                @forelse($grafikPenjualan ?? [] as $item)
                    @php
                        $heightPercent = ($item['total'] / $maxTotal) * 100;
                        $heightPercent = max(5, $heightPercent); 
                    @endphp
                    <div class="flex flex-col items-center flex-1 gap-2 group cursor-pointer">
                        <div class="w-full max-w-[36px] bg-[#A3A463] rounded-t-md transition-all group-hover:bg-[#545523] shadow-xs duration-300" style="height: {{ $heightPercent }}%;"></div>
                        <span class="text-[11px] font-medium text-gray-400 group-hover:text-gray-700">{{ $item['label'] }}</span>
                    </div>
                @empty
                    <p class="text-xs text-gray-400 text-center w-full py-12">Belum ada data grafik penjualan.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-[#FDFDF5] p-6 rounded-2xl shadow-xs border border-[#545523]/10 flex flex-col justify-between">
            <div>
                <h4 class="text-sm font-bold text-[#545523] uppercase tracking-wider mb-4">Aktivitas Terkini</h4>
                
                <div class="space-y-4">
                    {{-- Logika Aktivitas Dinamis --}}
                    @forelse($aktivitasTerkini ?? [] as $act)
                        <div class="flex items-start gap-4 p-2 rounded-xl hover:bg-gray-50/50 transition">
                            {{-- Jika Anda mengirim warna background dari Controller (misal 'bg_class' => 'bg-amber-100 text-amber-700'), akan dipakai. Jika tidak, pakai default --}}
                            <div class="{{ $act['bg_class'] ?? 'bg-[#545523] text-white' }} p-2.5 rounded-full flex items-center justify-center w-9 h-9 shrink-0">
                                <i class="fa-solid fa-{{ $act['icon'] ?? 'bag-shopping' }} text-xs"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h5 class="text-xs font-bold text-gray-800 truncate">{{ $act['judul'] }}</h5>
                                <p class="text-[11px] text-gray-400 mt-0.5">{{ $act['keterangan'] }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-gray-500 text-center py-4">Belum ada aktivitas saat ini.</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

@endsection