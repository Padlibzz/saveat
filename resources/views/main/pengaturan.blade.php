@extends('layout.pengaturan')

@section('title', 'Pengaturan - SaveEat')
@section('page_title', 'Pengaturan')

@section('content')
<div class="max-w-md mx-auto">
    
    {{-- Container Menu Pengaturan --}}
    <div class="bg-white rounded-3xl p-2 shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 flex flex-col">
        
        {{-- Menu 1: Pusat Bantuan (FAQ) --}}
        <a href="main/pusat-bantuan" class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-2xl transition group cursor-pointer">
            <div class="flex items-center gap-4 text-[#545523]">
                <i class="fa-regular fa-square-question text-lg w-6 text-center"></i>
                <span class="text-sm font-bold text-[#545523]">Pusat Bantuan (FAQ)</span>
            </div>
            <i class="fa-solid fa-chevron-right text-xs text-gray-400 group-hover:translate-x-1 transition-transform"></i>
        </a>

        {{-- Menu 2: Kotak Masuk Dukungan --}}
        <a href="main\dukungan" class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-2xl transition group cursor-pointer">
            <div class="flex items-center gap-4 text-[#545523]">
                <i class="fa-solid fa-headset text-lg w-6 text-center"></i>
                <span class="text-sm font-bold text-[#545523]">Kotak Masuk Dukungan</span>
            </div>
            <i class="fa-solid fa-chevron-right text-xs text-gray-400 group-hover:translate-x-1 transition-transform"></i>
        </a>

        {{-- Menu 3: Bahasa --}}
        <a href="main\bahasa" class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-2xl transition group cursor-pointer">
            <div class="flex items-center gap-4 text-[#545523]">
                <i class="fa-solid fa-globe text-lg w-6 text-center"></i>
                <span class="text-sm font-bold text-[#545523]">Bahasa</span>
            </div>
            <i class="fa-solid fa-chevron-right text-xs text-gray-400 group-hover:translate-x-1 transition-transform"></i>
        </a>

        {{-- Menu 4: Laporkan --}}
        <a href="main\laporkan" class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-2xl transition group cursor-pointer">
            <div class="flex items-center gap-4 text-[#545523]">
                <i class="fa-regular fa-circle-exclamation text-lg w-6 text-center"></i>
                <span class="text-sm font-bold text-[#545523]">Laporkan</span>
            </div>
            <i class="fa-solid fa-chevron-right text-xs text-gray-400 group-hover:translate-x-1 transition-transform"></i>
        </a>

    </div>

</div>
@endsection