@extends('layouts.pengaturan')

@section('title', 'Laporan - SaveEat')
@section('page_title', 'Laporan')

@section('content')
{{-- x-data="{ adaLaporan: false }" mengatur state awal halaman ini --}}
<div class="max-w-md mx-auto px-4 min-h-[65vh] flex flex-col justify-center relative" x-data="{ adaLaporan: false }">
    
    {{-- TOMBOL PREVIEW (Hanya untuk keperluan testing UI, bisa Anda hapus nanti) --}}
    <button @click="adaLaporan = !adaLaporan" 
            class="absolute -top-10 right-0 md:-right-4 text-[10px] font-bold bg-gray-200 text-gray-600 px-3 py-1.5 rounded-full hover:bg-gray-300 transition z-10">
        <i class="fa-solid fa-rotate mr-1"></i> Ganti Tampilan
    </button>

    {{-- ========================================== --}}
    {{-- STATE 1: BELUM ADA LAPORAN (KIRI MOCKUP) --}}
    {{-- ========================================== --}}
    <div x-show="!adaLaporan" 
         x-transition.opacity.duration.300ms 
         class="text-center flex flex-col items-center justify-center">
        
        <h3 class="text-[17px] font-extrabold text-black mb-2">
            Belum Ada Laporan
        </h3>
        <p class="text-[13px] text-gray-400 font-medium leading-relaxed max-w-[260px]">
            Jika kamu melaporkan akun, maka dapat Kamu lihat di halaman ini
        </p>

    </div>

    {{-- ========================================== --}}
    {{-- STATE 2: ADA LAPORAN (KANAN MOCKUP)      --}}
    {{-- ========================================== --}}
    <div x-show="adaLaporan" 
         style="display: none;" 
         x-transition.opacity.duration.300ms>
        
        <div class="bg-[#FAE9E8] rounded-[20px] p-5 shadow-sm border border-[#F2D7D6]">
            <div class="flex items-start gap-3">
                {{-- Ikon Gembok Merah --}}
                <i class="fa-solid fa-lock text-[#A33434] mt-0.5 text-sm shrink-0"></i>
                
                {{-- Teks Laporan --}}
                <div class="space-y-1.5">
                    <h4 class="font-extrabold text-[#A33434] text-sm">
                        Akun anda telah dilaporkan
                    </h4>
                    <p class="text-[11px] text-[#B04C4C] leading-[1.6] font-medium pr-2">
                        Terima kasih atas laporan Anda. Tim Saveat telah menerima laporan tersebut dan sedang melakukan proses verifikasi. Kami akan meninjau informasi yang diberikan dan mengambil tindakan yang diperlukan sesuai kebijakan platform.
                    </p>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection