@extends('layouts.profile')

@section('title', 'Admin Profile - SaveEat')
@section('page_title', 'Profile')

@section('content')
<div class="max-w-md mx-auto pb-8">
    
    {{-- 1. HEADER PROFIL ADMIN --}}
    <div class="flex flex-col items-center mb-6">
        <div class="relative w-24 h-24 mb-3">
            {{-- Foto Profil Admin --}}
            <img src="{{ Auth::user()->profil_image ? asset('storage/'.Auth::user()->profil_image) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name ?? 'Admin').'&background=545523&color=F1F2CF&rounded=true&bold=true&size=128' }}" 
                 alt="Admin Photo" 
                 class="w-full h-full object-cover rounded-full border-4 border-white shadow-sm">
            
            {{-- Badge Verified Admin (Biru) --}}
            <div class="absolute bottom-1 right-1 bg-white rounded-full p-0.5 shadow-sm">
                <i class="fa-solid fa-circle-check text-blue-500 text-lg"></i>
            </div>
        </div>

        <h2 class="text-base font-bold text-gray-800 text-center leading-tight">
            {{ Auth::user()->name ?? 'Jerome' }}
        </h2>
        <p class="text-xs text-gray-500 text-center mt-0.5">
            {{ Auth::user()->email ?? 'jeromeadmin@saveat.com' }}
        </p>
    </div>

    {{-- 2. RINGKASAN STATISTIK DAMPAK (GRID) --}}
    <div class="space-y-3 mb-8">
        {{-- Card Full Width: CO2 --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                <i class="fa-solid fa-globe text-xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wide">Total CO2 Terselamatkan</p>
                <h4 class="text-sm font-extrabold text-emerald-600">12,480 kg</h4>
            </div>
        </div>

        {{-- Card Grid 1x2: Merchant & Makanan --}}
        <div class="grid grid-cols-2 gap-3">
            <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-xs">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wide">Merchant Aktif</p>
                <h4 class="text-xl font-black text-gray-800 mt-1">842</h4>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-xs">
                <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-wide">Makanan Terselamatkan</p>
                <h4 class="text-xl font-black text-emerald-600 mt-1">45.2k</h4>
            </div>
        </div>
    </div>

    {{-- 3. MANAGEMENT LIST --}}
    <div class="space-y-3 mb-10">
        <h4 class="text-[11px] font-bold text-[#545523] uppercase tracking-[0.1em] px-1">Management</h4>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-xs overflow-hidden divide-y divide-gray-50">

            {{-- Privacy & Security --}}
            <a href="{{ route('admin.security') ?? '#' }}" class="flex items-center justify-between p-4 hover:bg-gray-50 transition group">
                <div class="flex items-center gap-3.5">
                    <i class="fa-solid fa-shield-halved text-gray-400 group-hover:text-[#545523] transition-colors w-5 text-center"></i>
                    <span class="font-semibold text-gray-700 text-sm">Privacy & Security</span>
                </div>
                <i class="fa-solid fa-chevron-right text-gray-300 text-[10px]"></i>
            </a>
        </div>
    </div>

    {{-- 4. SIGN OUT --}}
    <form action="{{ route('logout') }}" method="POST" class="flex justify-center">
        @csrf
        <button type="submit" class="flex items-center gap-2.5 text-red-500 font-bold text-sm hover:opacity-75 active:scale-95 transition-all">
            <i class="fa-solid fa-right-from-bracket rotate-180"></i>
            <span>Sign Out</span>
        </button>
    </form>

</div>
@endsection