@extends('layouts.dashboard')

@section('page_title', 'User Management')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div>
        <h2 class="text-xl md:text-2xl font-extrabold text-[#545523] tracking-wide">
            User Management
        </h2>
        <p class="text-gray-500 text-xs md:text-sm mt-1 font-medium">
            Kelola akun, peran, dan akses platform.
        </p>
    </div>

    {{-- STATISTIK (2x2 Grid di HP, 4x1 di Desktop) --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4">
        <div class="bg-white p-4 md:p-5 rounded-2xl shadow-xs border border-gray-100 flex flex-col justify-center">
            <p class="text-[9px] md:text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Pengguna</p>
            <h3 class="text-xl md:text-2xl font-black text-gray-800 mt-1">12.4k</h3>
        </div>
        <div class="bg-white p-4 md:p-5 rounded-2xl shadow-xs border border-gray-100 flex flex-col justify-center">
            <p class="text-[9px] md:text-[10px] font-bold text-gray-400 uppercase tracking-wider">Active Hari Ini</p>
            <h3 class="text-xl md:text-2xl font-black text-gray-800 mt-1">892</h3>
        </div>
        <div class="bg-white p-4 md:p-5 rounded-2xl shadow-xs border border-gray-100 flex flex-col justify-center">
            <p class="text-[9px] md:text-[10px] font-bold text-gray-400 uppercase tracking-wider">Baru Daftar</p>
            <h3 class="text-xl md:text-2xl font-black text-amber-600 mt-1">45</h3>
        </div>
        <div class="bg-white p-4 md:p-5 rounded-2xl shadow-xs border border-gray-100 flex flex-col justify-center">
            <p class="text-[9px] md:text-[10px] font-bold text-gray-400 uppercase tracking-wider">Dilaporkan</p>
            <h3 class="text-xl md:text-2xl font-black text-red-500 mt-1">12</h3>
        </div>
    </div>

    {{-- SEARCH & FILTER --}}
    <div class="space-y-3">
        @php
            $currentRole = request('role', 'semua');
        @endphp

        <form action="{{ url()->current() }}" method="GET" class="relative w-full">
            <input type="hidden" name="role" value="{{ $currentRole }}">
            
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="fa-solid fa-magnifying-glass text-gray-400 text-sm"></i>
            </div>
            <input type="text" name="search" value="{{ request('search') }}"
                class="w-full bg-white border border-gray-200 text-gray-700 rounded-2xl pl-10 pr-4 py-3 focus:ring-1 focus:ring-[#545523] focus:border-[#545523] text-sm shadow-xs transition-all" 
                placeholder="Cari dengan nama atau email...">
        </form>

        <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-hide -mx-2 px-2 md:mx-0 md:px-0">
            <a href="{{ request()->fullUrlWithQuery(['role' => 'semua']) }}" 
               class="px-4 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap transition-colors shadow-sm
               {{ $currentRole === 'semua' ? 'bg-[#545523] text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}">
                Semua
            </a>
            
            <a href="{{ request()->fullUrlWithQuery(['role' => 'pelanggan']) }}" 
               class="px-4 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap transition-colors shadow-sm
               {{ $currentRole === 'pelanggan' ? 'bg-[#545523] text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}">
                Pelanggan
            </a>

            <a href="{{ request()->fullUrlWithQuery(['role' => 'merchant']) }}" 
               class="px-4 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap transition-colors shadow-sm
               {{ $currentRole === 'merchant' ? 'bg-[#545523] text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}">
                Merchant
            </a>

            <a href="{{ request()->fullUrlWithQuery(['role' => 'admin']) }}" 
               class="px-4 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap transition-colors shadow-sm
               {{ $currentRole === 'admin' ? 'bg-[#545523] text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}">
                Admin
            </a>
        </div>
    </div>

    {{-- LIST PENGGUNA --}}
    <div class="space-y-3">
        
        @empty($users)
            <div class="bg-white p-8 rounded-2xl border border-gray-100 text-center">
                <p class="text-gray-400 text-sm font-medium">Tidak ada pengguna ditemukan.</p>
            </div>
        @else
            @foreach($users as $user)
                <div class="bg-white p-4 rounded-2xl shadow-xs border border-gray-100 flex items-center justify-between hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-3 md:gap-4">
                        
                        {{-- KONDISI AVATAR --}}
                        <div class="relative">
                            @if($user->avatar_type === 'image')
                                <img src="{{ $user->avatar_value }}" alt="Avatar" 
                                     class="w-11 h-11 md:w-12 md:h-12 rounded-full object-cover border-2 border-gray-50 {{ $user->status === 'ditunda' ? 'grayscale' : '' }}">
                            @elseif($user->avatar_type === 'icon')
                                <div class="w-11 h-11 md:w-12 md:h-12 rounded-full flex items-center justify-center border-2 border-gray-50
                                     {{ $user->status === 'verifikasi_ditunda' ? 'bg-amber-100 text-amber-600' : 'bg-[#545523]/10 text-[#545523]' }}">
                                    <i class="fa-solid {{ $user->avatar_value }} text-lg"></i>
                                </div>
                            @elseif($user->avatar_type === 'initial')
                                <div class="w-11 h-11 md:w-12 md:h-12 rounded-full bg-[#545523] text-white flex items-center justify-center border-2 border-gray-50 font-bold text-lg">
                                    {{ $user->avatar_value }}
                                </div>
                            @endif

                            {{-- Badge Centang Biru Terverifikasi --}}
                            @if($user->is_verified)
                                <div class="absolute bottom-0 right-0 bg-white rounded-full p-0.5 shadow-sm border border-gray-100">
                                    <i class="fa-solid fa-circle-check text-blue-500 text-[10px]"></i>
                                </div>
                            @endif
                        </div>

                        {{-- INFORMASI PENGGUNA --}}
                        <div>
                            <h4 class="font-bold text-gray-800 text-sm md:text-base leading-none">{{ $user->name }}</h4>
                            <p class="text-gray-400 text-[11px] md:text-xs mt-1">{{ $user->email }}</p>
                            
                            <div class="flex items-center gap-2 mt-1.5">
                                {{-- KONDISI WARNA BADGE ROLE --}}
                                @if($user->role === 'ADMIN' || $user->status === 'verifikasi_ditunda')
                                    <span class="bg-[#545523] text-white text-[9px] font-bold px-1.5 py-0.5 rounded tracking-wide">
                                        {{ $user->role }}
                                    </span>
                                @else
                                    <span class="bg-gray-100 text-gray-600 text-[9px] font-bold px-1.5 py-0.5 rounded tracking-wide border border-gray-200">
                                        {{ $user->role }}
                                    </span>
                                @endif

                                {{-- KONDISI STATUS --}}
                                @if($user->status === 'active')
                                    <span class="text-emerald-500 text-[10px] font-semibold flex items-center gap-1">
                                        <i class="fa-solid fa-circle text-[6px]"></i> Active
                                    </span>
                                @elseif($user->status === 'ditunda')
                                    <span class="text-red-500 text-[10px] font-semibold flex items-center gap-1">
                                        <i class="fa-regular fa-circle-xmark text-[10px]"></i> ditunda
                                    </span>
                                @elseif($user->status === 'verifikasi_ditunda')
                                    <span class="text-amber-600 text-[10px] font-semibold flex items-center gap-1">
                                        <i class="fa-regular fa-clock text-[10px]"></i> Verifikasi ditunda
                                    </span>
                                @elseif($user->status === 'baru')
                                    <span class="text-amber-500 text-[10px] font-semibold flex items-center gap-1">
                                        {{ $user->meta_status }}
                                    </span>
                                @endif
                            </div>
                        </div>

                    </div>

                    {{-- ACTION BUTTON (AKSI DROPDOWN) --}}
                    <button class="text-gray-400 hover:text-gray-700 p-2 transition-colors" 
                            onclick="alert('Aksi untuk user ID: {{ $user->id }}')">
                        <i class="fa-solid fa-ellipsis-vertical"></i>
                    </button>
                </div>
            @endforeach
        @endempty

    </div>

    {{-- TOMBOL LOAD MORE --}}
    <div class="flex justify-center mt-6">
        <button class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold py-2.5 px-5 rounded-full transition-colors flex items-center gap-2 shadow-xs border border-gray-200">
            Tampilkan lebih banyak <i class="fa-solid fa-chevron-down text-[10px]"></i>
        </button>
    </div>

</div>

<style>
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
@endsection