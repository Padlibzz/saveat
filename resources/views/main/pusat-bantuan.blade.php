@extends('layouts.pengaturan')

@section('title', 'Pusat Bantuan - SaveEat')
@section('page_title', 'Pusat Bantuan')

@section('content')
<div class="max-w-md mx-auto px-2">
    
    {{-- 1. BAGIAN HEADER (IKON & JUDUL) --}}
    <div class="flex flex-col items-center mt-4 mb-8">
        {{-- Ikon Shield dengan Sendok Garpu (Custom Stack FontAwesome) --}}
        <div class="text-[#545523] mb-3 relative flex items-center justify-center w-16 h-16">
            <span class="fa-stack fa-2x">
                <i class="fa-solid fa-shield fa-stack-2x"></i>
                <i class="fa-solid fa-utensils fa-stack-1x fa-inverse text-[20px] -mt-1"></i>
            </span>
        </div>
        
        <h2 class="text-lg md:text-xl font-extrabold text-[#545523]">
            Ada yang bisa kami bantu?
        </h2>
    </div>

    {{-- 2. BAGIAN PENCARIAN (SEARCH BAR) --}}
    <div class="relative mb-8">
        <input type="text" 
               placeholder="Cari Bantuan" 
               class="w-full border border-gray-300 rounded-full py-3.5 px-6 pr-12 text-sm focus:outline-none focus:border-[#545523] focus:ring-1 focus:ring-[#545523]/20 transition-all placeholder-gray-300 text-gray-700 bg-white/50 focus:bg-white shadow-sm"
               autocomplete="off">
        <button type="button" class="absolute right-5 top-1/2 -translate-y-1/2 text-gray-500 hover:text-[#545523] transition-colors cursor-pointer p-1">
            <i class="fa-solid fa-magnifying-glass text-lg"></i>
        </button>
    </div>

    {{-- 3. DAFTAR PERTANYAAN UMUM (FAQ LIST) --}}
    <div class="flex flex-col gap-3">
        
        {{-- List 1 --}}
        <a href="#" class="bg-white border border-gray-100 p-4.5 rounded-2xl flex items-center gap-4 shadow-sm hover:shadow-md hover:border-[#545523]/30 transition-all cursor-pointer group">
            <i class="fa-solid fa-message text-gray-700 text-lg group-hover:text-[#545523] transition-colors"></i>
            <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900">Bagaimana cara menjadi Merchant</span>
        </a>

        {{-- List 2 --}}
        <a href="#" class="bg-white border border-gray-100 p-4.5 rounded-2xl flex items-center gap-4 shadow-sm hover:shadow-md hover:border-[#545523]/30 transition-all cursor-pointer group">
            <i class="fa-solid fa-message text-gray-700 text-lg group-hover:text-[#545523] transition-colors"></i>
            <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900">Cara merubah Password</span>
        </a>

        {{-- List 3 --}}
        <a href="#" class="bg-white border border-gray-100 p-4.5 rounded-2xl flex items-center gap-4 shadow-sm hover:shadow-md hover:border-[#545523]/30 transition-all cursor-pointer group">
            <i class="fa-solid fa-message text-gray-700 text-lg group-hover:text-[#545523] transition-colors"></i>
            <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900">Cara laporkan merchant</span>
        </a>

        {{-- List 4 --}}
        <a href="#" class="bg-white border border-gray-100 p-4.5 rounded-2xl flex items-center gap-4 shadow-sm hover:shadow-md hover:border-[#545523]/30 transition-all cursor-pointer group">
            <i class="fa-solid fa-message text-gray-700 text-lg group-hover:text-[#545523] transition-colors"></i>
            <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900">Cara Hapus akun</span>
        </a>

    </div>

</div>
@endsection