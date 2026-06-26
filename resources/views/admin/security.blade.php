@extends('layout.profile')

@section('title', 'Privasi & Keamanan Admin - SaveEat')
@section('page_title', 'Privasi & Keamanan')

@section('content')
<div class="max-w-md mx-auto space-y-6 pb-8" x-data="{ lokasi: true, notif: false }">
    
    {{-- KARTU UTAMA PENGATURAN PRIVASI & KEAMANAN --}}
    <div class="bg-white rounded-3xl border border-gray-100 shadow-xs p-6 space-y-6">
        
        {{-- SEKSI 1: KEAMANAN AKUN --}}
        <div>
            <div class="flex items-center gap-2.5 text-gray-400 mb-4">
                <i class="fa-regular fa-shield text-base text-[#545523]"></i>
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400">Keamanan Akun</h3>
            </div>

            {{-- Menu Ubah Password --}}
            <a href="#" class="flex items-center justify-between py-2 group cursor-pointer block">
                <div class="flex-1 pr-4">
                    <h4 class="text-sm font-bold text-gray-800 mb-0.5 group-hover:text-[#545523] transition">Ubah Password</h4>
                    <p class="text-xs text-gray-500 leading-relaxed">Perbarui kata sandi akun Anda secara berkala.</p>
                </div>
                <i class="fa-solid fa-chevron-right text-gray-400 text-xs group-hover:translate-x-0.5 transition"></i>
            </a>
        </div>

        <hr class="border-gray-100">

        {{-- SEKSI 2: STATUS IZIN --}}
        <div>
            <div class="flex items-center gap-2.5 text-gray-400 mb-4">
                <i class="fa-regular fa-circle-check text-base text-[#545523]"></i>
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400">Status Izin</h3>
            </div>

            <div class="space-y-5">
                {{-- Toggle Layanan Lokasi --}}
                <div class="flex items-start justify-between py-1">
                    <div class="flex-1 pr-6">
                        <h4 class="text-sm font-bold text-gray-800 mb-0.5">Layanan Lokasi</h4>
                        <p class="text-xs text-gray-500 leading-relaxed">Izinkan Saveat untuk menemukan peluang penyelamatan makanan di dekat Anda</p>
                    </div>
                    {{-- Tombol Toggle Alpine.js --}}
                    <button @click="lokasi = !lokasi" 
                            :class="lokasi ? 'bg-[#545523]' : 'bg-gray-200'" 
                            class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none mt-1">
                        <span :class="lokasi ? 'translate-x-5' : 'translate-x-0'" 
                              class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow-sm ring-0 transition duration-200 ease-in-out"></span>
                    </button>
                </div>

                {{-- Toggle Notifikasi --}}
                <div class="flex items-start justify-between py-1">
                    <div class="flex-1 pr-6">
                        <h4 class="text-sm font-bold text-gray-800 mb-0.5">Notifikasi</h4>
                        <p class="text-xs text-gray-500 leading-relaxed">Pemberitahuan untuk penawaran "Hampir Habis" dan pengingat pengambilan Makanan</p>
                    </div>
                    {{-- Tombol Toggle Alpine.js --}}
                    <button @click="notif = !notif" 
                            :class="notif ? 'bg-[#545523]' : 'bg-gray-200'" 
                            class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none mt-1">
                        <span :class="notif ? 'translate-x-5' : 'translate-x-0'" 
                              class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow-sm ring-0 transition duration-200 ease-in-out"></span>
                    </button>
                </div>
            </div>
        </div>

    </div>

    {{-- BANNER INFORMASI PRIVASI AMAN (HIJAU MINT) --}}
    <div class="bg-emerald-50/60 border border-emerald-100 rounded-3xl p-5 flex items-start gap-3.5">
        <div class="w-8 h-8 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-600 shrink-0 mt-0.5">
            <i class="fa-solid fa-lock text-sm"></i>
        </div>
        <div class="space-y-1.5">
            <h4 class="text-sm font-bold text-emerald-900">Data Anda aman bersama Kami</h4>
            <p class="text-xs text-emerald-800/80 leading-relaxed font-medium">
                Data Anda aman bersama kami Saveat menggunakan enkripsi AES-256 standar industri untuk melindungi informasi pribadi Anda. Kami tidak pernah menjual data Anda kepada pihak ketiga. Misi kami adalah menyelamatkan makanan, bukan mengorbankan privasi Anda.
            </p>
        </div>
    </div>

</div>
@endsection