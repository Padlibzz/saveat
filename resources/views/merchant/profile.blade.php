@extends('layout.profile')

@section('title', 'Profil Merchant - SaveEat')
@section('page_title', '') {{-- Dikosongkan sesuai mockup, atau bisa diisi 'Profil Merchant' --}}

@section('content')
<div class="max-w-md mx-auto pb-8">
    
    {{-- 1. HEADER PROFIL MERCHANT --}}
    <div class="flex flex-col items-center mb-8">
        <div class="relative w-24 h-24 mb-3">
            {{-- Foto Profil Merchant --}}
            <img src="{{ Auth::user()->profil_image ? asset('storage/'.Auth::user()->profil_image) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name ?? 'Merchant').'&background=F1F2CF&color=545523&rounded=true&bold=true&size=128' }}" 
                 alt="Foto Profil Toko" 
                 class="w-full h-full object-cover rounded-full border border-gray-200 shadow-sm bg-white p-1">
            
            {{-- Tombol Edit Foto (Kecil di sudut kanan bawah) --}}
            <a href="{{ route('merchant.profile.edit') ?? '#' }}" class="absolute bottom-0 right-0 bg-[#545523] text-white p-1.5 rounded-full hover:bg-[#43441c] shadow-md transition flex items-center justify-center border-2 border-white">
                <i class="fa-solid fa-pencil text-[10px]"></i>
            </a>
        </div>

        <h2 class="text-base md:text-lg font-bold text-gray-800 text-center leading-tight">
            {{ Auth::user()->nama_usaha ?? Auth::user()->name ?? 'Dapur Roti Nusantara' }}
        </h2>
        <p class="text-xs md:text-sm text-gray-500 text-center mt-0.5">
            {{ Auth::user()->email ?? 'dapuroti.n@gmail.com' }}
        </p>

        {{-- Badge Rating & Reviews --}}
        <div class="mt-3 bg-[#dcd484] text-[#545523] px-4 py-1.5 rounded-full text-xs font-bold flex items-center gap-1.5 shadow-sm">
            <i class="fa-solid fa-star text-[#545523]"></i> 
            <span>4.9</span> 
            <span class="text-[#545523]/50 font-normal mx-1">|</span> 
            <span class="font-medium">128 Reviews</span>
        </div>
    </div>

    {{-- 2. KELOMPOK MENU 1: PENGATURAN TOKO (Informasi & Pembayaran) --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-xs overflow-hidden mb-4">
        
        {{-- Menu: Informasi Merchant --}}
        <a href="{{ route('merchant.profile.info') ?? '#' }}" class="flex items-center justify-between p-4 hover:bg-gray-50 transition group border-b border-gray-50">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-[#545523] text-white flex items-center justify-center shadow-sm group-hover:scale-105 transition-transform">
                    <i class="fa-solid fa-store"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-gray-800 mb-0.5">Informasi Merchant</h4>
                    <p class="text-[10px] text-gray-500">Edit Profil, Alamat, Deskripsi</p>
                </div>
            </div>
            <i class="fa-solid fa-chevron-right text-gray-400 text-xs group-hover:translate-x-0.5 transition-transform"></i>
        </a>

        {{-- Menu: Pengaturan Pembayaran --}}
        <a href="{{ route('merchant.profile.payment') ?? '#' }}" class="flex items-center justify-between p-4 hover:bg-gray-50 transition group">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-[#545523] text-white flex items-center justify-center shadow-sm group-hover:scale-105 transition-transform">
                    <i class="fa-solid fa-money-bill-wave"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-gray-800 mb-0.5">Pengaturan Pembayaran</h4>
                    <p class="text-[10px] text-gray-500">Bank, Cashdraw</p>
                </div>
            </div>
            <i class="fa-solid fa-chevron-right text-gray-400 text-xs group-hover:translate-x-0.5 transition-transform"></i>
        </a>

    </div>

    {{-- 3. KELOMPOK MENU 2: PENGATURAN UMUM (Notifikasi & Privasi) --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-xs overflow-hidden mb-8">
        
        {{-- Menu: Notifikasi --}}
        <a href="{{ route('merchant.profile.notifications') ?? '#' }}" class="flex items-center justify-between p-4.5 hover:bg-gray-50 transition group border-b border-gray-50">
            <div class="flex items-center gap-4">
                <div class="w-8 flex justify-center text-gray-500 group-hover:text-[#545523] transition-colors">
                    <i class="fa-regular fa-bell text-lg"></i>
                </div>
                <h4 class="text-sm font-semibold text-gray-700">Notifikasi</h4>
            </div>
            <i class="fa-solid fa-chevron-right text-gray-400 text-xs group-hover:translate-x-0.5 transition-transform"></i>
        </a>

        {{-- Menu: Privacy & Security --}}
        <a href="{{ route('merchant.profile.security') ?? '#' }}" class="flex items-center justify-between p-4.5 hover:bg-gray-50 transition group">
            <div class="flex items-center gap-4">
                <div class="w-8 flex justify-center text-gray-500 group-hover:text-[#545523] transition-colors">
                    <i class="fa-regular fa-shield-halved text-lg"></i>
                </div>
                <h4 class="text-sm font-semibold text-gray-700">Privacy & Security</h4>
            </div>
            <i class="fa-solid fa-chevron-right text-gray-400 text-xs group-hover:translate-x-0.5 transition-transform"></i>
        </a>

    </div>

    {{-- 4. TOMBOL LOGOUT --}}
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="w-full flex items-center justify-center gap-2 border border-red-200 bg-white text-red-500 py-3.5 rounded-xl hover:bg-red-50 active:scale-95 transition font-bold cursor-pointer shadow-sm">
            <i class="fa-solid fa-right-from-bracket"></i> Keluar
        </button>
    </form>

</div>
@endsection