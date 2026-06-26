@extends('layout.profile')

@section('title', 'Notifikasi - SaveEat')
@section('page_title', 'Notifikasi') {{-- Otomatis mengisi judul di navbar --}}

@section('content')
<div class="max-w-md mx-auto bg-white rounded-3xl border border-gray-100 shadow-xs overflow-hidden min-h-[600px]" 
     x-data="{ activeTab: 'aktivitas' }">
    
    {{-- BAGIAN TABS HEADER --}}
    <div class="flex px-4 pt-2 border-b border-gray-100">
        {{-- Tab Aktivitas --}}
        <button @click="activeTab = 'aktivitas'" 
                :class="activeTab === 'aktivitas' ? 'border-[#545523] text-[#545523] font-bold' : 'border-transparent text-gray-400 font-medium hover:text-gray-600'"
                class="flex-1 pb-3 text-sm text-center border-b-2 transition duration-300 outline-none">
            Aktivitas
        </button>
        
        {{-- Tab Promo --}}
        <button @click="activeTab = 'promo'" 
                :class="activeTab === 'promo' ? 'border-[#545523] text-[#545523] font-bold' : 'border-transparent text-gray-400 font-medium hover:text-gray-600'"
                class="flex-1 pb-3 text-sm text-center border-b-2 transition duration-300 outline-none">
            Promo
        </button>
    </div>

    {{-- KONTEN TAB: AKTIVITAS --}}
    <div x-show="activeTab === 'aktivitas'" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-x-2"
         x-transition:enter-end="opacity-100 translate-x-0"
         class="flex flex-col divide-y divide-gray-50 p-2">
        
        {{-- Item Notifikasi 1 (Unread) --}}
        <div class="flex items-start gap-3.5 p-3 hover:bg-gray-50/70 transition rounded-xl cursor-pointer">
            <div class="w-11 h-11 shrink-0 bg-[#545523] rounded-full flex items-center justify-center text-white shadow-sm">
                <i class="fa-solid fa-bag-shopping text-sm"></i>
            </div>
            <div class="flex-1 pt-0.5">
                <h4 class="text-sm font-bold text-gray-800 leading-tight mb-1">Pesanan Diterima</h4>
                <p class="text-xs text-gray-600 leading-relaxed">Pesanan yang kamu klaim sudah diterima oleh "Dapur Roti Nusantara". Ambil di jam 18:30 WIB.</p>
            </div>
            <div class="flex flex-col items-end gap-1.5 shrink-0 pt-0.5">
                <span class="text-[10px] font-medium text-gray-400">2m</span>
                <div class="w-2 h-2 rounded-full bg-emerald-500"></div> {{-- Indikator unread --}}
            </div>
        </div>

        {{-- Item Notifikasi 2 (Read) --}}
        <div class="flex items-start gap-3.5 p-3 hover:bg-gray-50/70 transition rounded-xl cursor-pointer">
            <div class="w-11 h-11 shrink-0 rounded-full overflow-hidden border border-gray-100 shadow-sm">
                <img src="https://images.unsplash.com/photo-1513104890138-7c749659a591?q=80&w=200&auto=format&fit=crop" alt="Pizza" class="w-full h-full object-cover">
            </div>
            <div class="flex-1 pt-0.5">
                <h4 class="text-sm font-bold text-gray-800 leading-tight mb-1">Listing Baru</h4>
                <p class="text-xs text-gray-600 leading-relaxed">"Cassa Pizza" Stok Terbatas, Segera Klaim Pesanan Sebelum kehabisan!</p>
            </div>
            <div class="flex flex-col items-end gap-1.5 shrink-0 pt-0.5">
                <span class="text-[10px] font-medium text-gray-400">1j</span>
            </div>
        </div>

        {{-- Item Notifikasi 3 (Read) --}}
        <div class="flex items-start gap-3.5 p-3 hover:bg-gray-50/70 transition rounded-xl cursor-pointer">
            <div class="w-11 h-11 shrink-0 bg-[#545523] rounded-full flex items-center justify-center text-white shadow-sm">
                <i class="fa-solid fa-leaf text-sm"></i>
            </div>
            <div class="flex-1 pt-0.5">
                <h4 class="text-sm font-bold text-gray-800 leading-tight mb-1">Tonggak Dampak</h4>
                <p class="text-xs text-gray-600 leading-relaxed">Anda telah menghemat 5 kg CO2 minggu ini. Anda termasuk dalam 5% penyelamat teratas di wilayah Anda!</p>
            </div>
            <div class="flex flex-col items-end gap-1.5 shrink-0 pt-0.5">
                <span class="text-[10px] font-medium text-gray-400">4j</span>
            </div>
        </div>

        {{-- Item Notifikasi 4 (Unread) --}}
        <div class="flex items-start gap-3.5 p-3 hover:bg-gray-50/70 transition rounded-xl cursor-pointer">
            <div class="w-11 h-11 shrink-0 bg-[#545523] rounded-full flex items-center justify-center text-white shadow-sm">
                <i class="fa-solid fa-bag-shopping text-sm"></i>
            </div>
            <div class="flex-1 pt-0.5">
                <h4 class="text-sm font-bold text-gray-800 leading-tight mb-1">Pesanan siap diambil</h4>
                <p class="text-xs text-gray-600 leading-relaxed">Pesanan yang sudah kamu pesan di "Dapur Roti Nusantara" Sudah siap untuk diambil.</p>
            </div>
            <div class="flex flex-col items-end gap-1.5 shrink-0 pt-0.5">
                <span class="text-[10px] font-medium text-gray-400">2m</span>
                <div class="w-2 h-2 rounded-full bg-emerald-500"></div> {{-- Indikator unread --}}
            </div>
        </div>

    </div>

    {{-- KONTEN TAB: PROMO --}}
    <div x-show="activeTab === 'promo'" style="display: none;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-x-2"
         x-transition:enter-end="opacity-100 translate-x-0"
         class="flex flex-col p-8 items-center justify-center text-center h-64">
        
        <div class="w-16 h-16 bg-amber-50 text-amber-500 rounded-full flex items-center justify-center mb-4">
            <i class="fa-solid fa-ticket text-2xl"></i>
        </div>
        <h4 class="font-bold text-gray-800 mb-2">Belum Ada Promo</h4>
        <p class="text-xs text-gray-500">Voucher dan promo spesial untukmu akan muncul di sini nanti.</p>
        
    </div>

</div>
@endsection