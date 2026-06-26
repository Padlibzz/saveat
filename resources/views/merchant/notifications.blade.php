@extends('layout.profile')

@section('title', 'Notifikasi Merchant - SaveEat')
@section('page_title', 'Notifikasi')

{{-- Ikon Gear di sudut kanan atas --}}
@section('navbar_action')
    <a href="{{ route('merchant.profile.settings') ?? '#' }}" class="text-[#545523] hover:opacity-75 transition text-lg">
        <i class="fa-solid fa-gear"></i>
    </a>
@endsection

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
        
        {{-- Tab Sistem --}}
        <button @click="activeTab = 'sistem'" 
                :class="activeTab === 'sistem' ? 'border-[#545523] text-[#545523] font-bold' : 'border-transparent text-gray-400 font-medium hover:text-gray-600'"
                class="flex-1 pb-3 text-sm text-center border-b-2 transition duration-300 outline-none">
            Sistem
        </button>
    </div>

    {{-- KONTEN TAB: AKTIVITAS --}}
    <div x-show="activeTab === 'aktivitas'" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-x-2"
         x-transition:enter-end="opacity-100 translate-x-0"
         class="p-4 space-y-4">
        
        {{-- Notifikasi Klaim Baru (Dengan Tombol Aksi) --}}
        <div class="bg-white border-l-4 border-[#545523] rounded-2xl border border-gray-100 p-4 shadow-sm">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 shrink-0 bg-[#545523] rounded-full flex items-center justify-center text-white">
                    <i class="fa-solid fa-bag-shopping text-sm"></i>
                </div>
                <div class="flex-1">
                    <div class="flex justify-between items-center mb-1">
                        <h4 class="text-xs font-bold text-gray-800">Klaim Baru</h4>
                        <span class="text-[10px] text-gray-400">Baru saja</span>
                    </div>
                    <p class="text-xs text-gray-600 leading-relaxed mb-3">
                        Pesanan #4521 dari Budi S. sedang menunggu konfirmasi.
                    </p>
                    {{-- Tombol Aksi --}}
                    <div class="flex gap-2">
                        <button class="bg-[#545523] text-white px-4 py-1.5 rounded-lg text-[10px] font-bold hover:bg-[#43441c] transition">
                            Konfirmasi
                        </button>
                        <button class="bg-white border border-gray-200 text-gray-600 px-4 py-1.5 rounded-lg text-[10px] font-bold hover:bg-gray-50 transition">
                            Detail
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Notifikasi Stok Menipis (Warning Style) --}}
        <div class="bg-amber-50/50 border border-amber-200 rounded-2xl p-4 shadow-sm">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 shrink-0 bg-amber-500 rounded-full flex items-center justify-center text-white">
                    <i class="fa-solid fa-triangle-exclamation text-sm"></i>
                </div>
                <div class="flex-1">
                    <div class="flex justify-between items-center mb-1">
                        <h4 class="text-xs font-bold text-amber-700">Stok Menipis</h4>
                        <span class="text-[10px] text-amber-600/70">15 mnt lalu</span>
                    </div>
                    <p class="text-xs text-amber-800 leading-relaxed">
                        Hanya tersisa 2 'Surprise Bag' di listing Anda. Segera update stok!
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- KONTEN TAB: SISTEM --}}
    <div x-show="activeTab === 'sistem'" style="display: none;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-x-2"
         x-transition:enter-end="opacity-100 translate-x-0"
         class="p-4 space-y-4">
        
        {{-- Contoh Notifikasi Sistem --}}
        <div class="bg-gray-50 border border-gray-100 rounded-2xl p-4 flex items-start gap-3">
            <div class="w-10 h-10 shrink-0 bg-blue-500 rounded-full flex items-center justify-center text-white">
                <i class="fa-solid fa-circle-info text-sm"></i>
            </div>
            <div class="flex-1">
                <div class="flex justify-between items-center mb-1">
                    <h4 class="text-xs font-bold text-gray-800">Pembaruan Sistem</h4>
                    <span class="text-[10px] text-gray-400">1 hari lalu</span>
                </div>
                <p class="text-xs text-gray-600 leading-relaxed">
                    Fitur laporan bulanan sekarang sudah tersedia di dashboard Anda.
                </p>
            </div>
        </div>

        {{-- Item Stok Menipis juga muncul di Sistem sesuai mockup --}}
        <div class="bg-amber-50/50 border border-amber-200 rounded-2xl p-4 shadow-sm">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 shrink-0 bg-amber-500 rounded-full flex items-center justify-center text-white">
                    <i class="fa-solid fa-triangle-exclamation text-sm"></i>
                </div>
                <div class="flex-1">
                    <div class="flex justify-between items-center mb-1">
                        <h4 class="text-xs font-bold text-amber-700">Stok Menipis</h4>
                        <span class="text-[10px] text-amber-600/70">15 mnt lalu</span>
                    </div>
                    <p class="text-xs text-amber-800 leading-relaxed">
                        Hanya tersisa 2 'Surprise Bag' di listing Anda. Segera update stok!
                    </p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection