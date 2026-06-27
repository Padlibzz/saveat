@extends('layouts.profile')

@section('title', 'Privacy & Security - SaveEat')
@section('page_title', 'Privasi & Keamanan')

@section('navbar_action')
    <a href="{{ route('merchant.profile.settings') ?? '#' }}" class="text-[#545523] hover:opacity-75 transition text-lg">
        <i class="fa-solid fa-gear"></i>
    </a>
@endsection

@section('content')
<div class="max-w-md mx-auto space-y-6 pb-8" x-data="{ lokasi: true, notif: false }">
    
    {{-- 1. KARTU PENGATURAN PRIVASI DASAR --}}
    <div class="bg-white rounded-3xl border border-gray-100 shadow-xs p-6 space-y-6">
        
        {{-- Keamanan Akun --}}
        <div>
            <div class="flex items-center gap-2.5 text-gray-400 mb-4">
                <i class="fa-regular fa-shield text-base text-[#545523]"></i>
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400">Keamanan Akun</h3>
            </div>

            <a href="#" class="flex items-center justify-between py-2 group cursor-pointer block">
                <div class="flex-1 pr-4">
                    <h4 class="text-sm font-bold text-gray-800 mb-0.5 group-hover:text-[#545523] transition">Ubah Password</h4>
                    <p class="text-xs text-gray-500 leading-relaxed">Perbarui kata sandi akun Anda secara berkala.</p>
                </div>
                <i class="fa-solid fa-chevron-right text-gray-400 text-xs group-hover:translate-x-0.5 transition"></i>
            </a>
        </div>

        <hr class="border-gray-100">

        {{-- Status Izin --}}
        <div>
            <div class="flex items-center gap-2.5 text-gray-400 mb-4">
                <i class="fa-regular fa-circle-check text-base text-[#545523]"></i>
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400">Status Izin</h3>
            </div>

            <div class="space-y-5">
                <div class="flex items-start justify-between py-1">
                    <div class="flex-1 pr-6">
                        <h4 class="text-sm font-bold text-gray-800 mb-0.5">Layanan Lokasi</h4>
                        <p class="text-xs text-gray-500 leading-relaxed">Izinkan Saveat untuk menemukan peluang penyelamatan makanan di dekat Anda</p>
                    </div>
                    <button @click="lokasi = !lokasi" :class="lokasi ? 'bg-[#545523]' : 'bg-gray-200'" class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none mt-1">
                        <span :class="lokasi ? 'translate-x-5' : 'translate-x-0'" class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow-sm ring-0 transition duration-200 ease-in-out"></span>
                    </button>
                </div>

                <div class="flex items-start justify-between py-1">
                    <div class="flex-1 pr-6">
                        <h4 class="text-sm font-bold text-gray-800 mb-0.5">Notifikasi</h4>
                        <p class="text-xs text-gray-500 leading-relaxed">Pemberitahuan untuk penawaran "Hampir Habis" dan pengingat pengambilan Makanan</p>
                    </div>
                    <button @click="notif = !notif" :class="notif ? 'bg-[#545523]' : 'bg-gray-200'" class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none mt-1">
                        <span :class="notif ? 'translate-x-5' : 'translate-x-0'" class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow-sm ring-0 transition duration-200 ease-in-out"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. BANNER INFORMASI DATA --}}
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

    {{-- 3. RIWAYAT AKTIVITAS PERANGKAT (SESSION MANAGEMENT) --}}
    <div class="bg-white rounded-3xl border border-gray-100 shadow-xs overflow-hidden">
        
        {{-- Header Activity --}}
        <div class="px-5 py-4 border-b border-gray-50 flex items-center justify-between bg-gray-50/50">
            <div class="flex items-center gap-2 text-gray-700">
                <i class="fa-solid fa-desktop text-gray-400"></i>
                <h3 class="text-sm font-bold text-gray-800">Recent Merchant Activity</h3>
            </div>
            <a href="#" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 transition">View All Logs</a>
        </div>

        {{-- List Perangkat --}}
        <div class="divide-y divide-gray-100">
            
            {{-- Device 1: Current Session --}}
            <div class="p-5 flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-gray-500 shrink-0 border border-gray-100">
                    <i class="fa-solid fa-laptop text-lg"></i>
                </div>
                <div class="flex-1">
                    <h4 class="text-xs font-bold text-gray-800">MacOS • Chrome (Staff: manager_1)</h4>
                    <p class="text-[10px] text-gray-500 mt-1">London, UK • IP: 192.168.1.45</p>
                    <div class="flex items-center gap-1.5 mt-2 text-emerald-600 text-[10px] font-bold">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Current Session
                    </div>
                </div>
                <div class="text-[10px] text-gray-400 font-medium shrink-0 pt-0.5">
                    Just now
                </div>
            </div>

            {{-- Device 2: Mobile App --}}
            <div class="p-5 flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-gray-500 shrink-0 border border-gray-100">
                    <i class="fa-solid fa-mobile-screen text-lg"></i>
                </div>
                <div class="flex-1">
                    <h4 class="text-xs font-bold text-gray-800">iPhone 14 • Saveat Merchant App</h4>
                    <p class="text-[10px] text-gray-500 mt-1">Manchester, UK • IP: 104.22.1.9</p>
                </div>
                <div class="flex flex-col items-end gap-2 shrink-0 pt-0.5">
                    <span class="text-[10px] text-gray-400 font-medium">2 hours ago</span>
                    <button class="border border-red-200 text-red-500 hover:bg-red-50 px-3 py-1 rounded-lg text-[10px] font-bold transition">
                        Revoke
                    </button>
                </div>
            </div>

            {{-- Device 3: Tablet/POS --}}
            <div class="p-5 flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-gray-500 shrink-0 border border-gray-100">
                    <i class="fa-solid fa-tablet-screen-button text-lg"></i>
                </div>
                <div class="flex-1">
                    <h4 class="text-xs font-bold text-gray-800">iPad Pro • POS Browser Terminal</h4>
                    <p class="text-[10px] text-gray-500 mt-1">London, UK • IP: 192.168.1.12</p>
                </div>
                <div class="text-[10px] text-gray-400 font-medium shrink-0 pt-0.5 text-right">
                    Yesterday,<br>18:45
                </div>
            </div>

        </div>
    </div>

    {{-- 4. KARTU ADVANCED GLOBAL LOGOUT --}}
    <div class="bg-red-50/50 border border-red-100 rounded-3xl p-6">
        <h3 class="text-sm font-bold text-red-600 mb-2">Advanced: Global Logout</h3>
        <p class="text-xs text-red-800/80 leading-relaxed font-medium mb-5">
            Instantly terminate all active sessions across all devices for your business account.
        </p>
        <form action="#" method="POST">
            @csrf
            <button type="submit" class="w-full bg-[#B91C1C] text-white py-3 rounded-xl text-xs font-bold hover:bg-red-800 transition shadow-sm">
                Logout All Sessions
            </button>
        </form>
    </div>

</div>
@endsection