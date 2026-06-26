@extends('layout.profile')

@section('title', 'User Profile - SaveEat')
@section('page_title', 'Profil') 

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    <div class="flex flex-col items-center bg-white p-6 rounded-2xl shadow-sm border border-gray-100 h-fit">
        <div class="relative w-32 h-32 mb-4">
            {{-- Logika Foto Profil otomatis inisial nama jika kosong --}}
            <img src="{{ Auth::user()->profil_image ? asset('storage/'.Auth::user()->profil_image) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=F1F2CF&color=545523&rounded=true&bold=true&size=128' }}" 
                 alt="Foto Profil {{ Auth::user()->name }}" 
                 class="w-full h-full object-cover rounded-full border-4 border-gray-50 shadow-sm">
            
            <a href="{{ route('profile.edit') }}" class="absolute bottom-1 right-1 bg-[#545523] text-white p-2 rounded-full hover:bg-[#43441c] shadow-md transition flex items-center justify-center w-9 h-9 cursor-pointer border-2 border-white">
                <i class="fa-solid fa-pencil text-xs"></i>
            </a>
        </div>

        <h3 class="text-xl font-bold text-gray-800 text-center flex items-center gap-2">
            {{ Auth::user()->name }}
            @if(Auth::user()->tipe_profil === 'merchant')
                <i class="fa-solid fa-circle-check text-emerald-500 text-sm" title="Verified Merchant"></i>
            @endif
        </h3>
        <p class="text-sm text-gray-500 text-center mt-1">
            {{ Auth::user()->email }}
        </p>

        {{-- Tombol Logout Desktop --}}
        <div class="hidden lg:block w-full mt-8">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 border border-red-200 text-red-500 py-3 rounded-xl hover:bg-red-50 transition font-bold cursor-pointer">
                    <i class="fa-solid fa-right-from-bracket"></i> Sign Out
                </button>
            </form>
        </div>
    </div>

    {{-- MENU PENGATURAN AKUN --}}
    <div class="lg:col-span-2">
        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3 px-2">
            Account Settings
        </h4>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden divide-y divide-gray-100">
            
            {{-- Edit Profil --}}
            <a href="{{ route('profile.edit') }}" class="flex items-center justify-between p-4 hover:bg-gray-50 transition group cursor-pointer block">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-gray-600 group-hover:bg-[#F1F2CF] group-hover:text-[#545523] transition">
                        <i class="fa-solid fa-user-pen"></i>
                    </div>
                    <span class="font-medium text-gray-700 text-sm md:text-base">Edit Profil</span>
                </div>
                <i class="fa-solid fa-chevron-right text-gray-400 text-xs"></i>
            </a>

            {{-- Metode Pembayaran --}}
            <a href="{{ route('profile.payment') }}" class="flex items-center justify-between p-4 hover:bg-gray-50 transition group cursor-pointer block">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-gray-600 group-hover:bg-[#F1F2CF] group-hover:text-[#545523] transition">
                        <i class="fa-solid fa-credit-card"></i>
                    </div>
                    <span class="font-medium text-gray-700 text-sm md:text-base">Metode Pembayaran</span>
                </div>
                <i class="fa-solid fa-chevron-right text-gray-400 text-xs"></i>
            </a>

            {{-- Notifikasi --}}
            <a href="{{ route('profile.notifications') }}" class="flex items-center justify-between p-4 hover:bg-gray-50 transition group cursor-pointer block">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-gray-600 group-hover:bg-[#F1F2CF] group-hover:text-[#545523] transition">
                        <i class="fa-solid fa-bell"></i>
                    </div>
                    <span class="font-medium text-gray-700 text-sm md:text-base">Notifikasi</span>
                </div>
                <i class="fa-solid fa-chevron-right text-gray-400 text-xs"></i>
            </a>

            {{-- Security & Privacy --}}
            <a href="{{ route('profile.security') }}" class="flex items-center justify-between p-4 hover:bg-gray-50 transition group cursor-pointer block">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-gray-600 group-hover:bg-[#F1F2CF] group-hover:text-[#545523] transition">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <span class="font-medium text-gray-700 text-sm md:text-base">Privacy & Security</span>
                </div>
                <i class="fa-solid fa-chevron-right text-gray-400 text-xs"></i>
            </a>

        </div>

        <div class="block lg:hidden w-full mt-8">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 border border-red-200 bg-white text-red-500 py-3.5 rounded-xl hover:bg-red-50 active:scale-95 transition font-bold cursor-pointer shadow-sm">
                    <i class="fa-solid fa-right-from-bracket"></i> Sign Out
                </button>
            </form>
        </div>

    </div>

</div>
@endsection