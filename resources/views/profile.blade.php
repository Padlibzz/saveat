@extends('layouts.app')

@section('title', 'Profil Pengguna')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="bg-white rounded-3xl shadow-sm p-6 flex items-center gap-6">
        <!-- Foto Profil -->
        <div class="w-24 h-24 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden border-2 border-[#555524]">
            @if(Auth::user()->profil_image)
                <img src="{{ asset('storage/' . Auth::user()->profil_image) }}" alt="Profil" class="w-full h-full object-cover">
            @else
                <i class="fa-solid fa-user text-4xl text-gray-400"></i>
            @endif
        </div>
        <div>
            <h2 class="font-display text-2xl font-bold text-[#555524]">{{ Auth::user()->name }}</h2>
            <p class="text-gray-600">{{ Auth::user()->email }}</p>
        </div>
    </div>

    @if($profil && ($profil->tipe_profil === 'merchant' || $profil->status_verifikasi === 'menunggu'))
        <div class="bg-white rounded-3xl shadow-sm p-6">
            <h3 class="font-display text-xl font-bold text-[#555524] mb-4">Status Merchant</h3>
            <div class="p-4 rounded-xl {{ $profil->status_verifikasi === 'disetujui' ? 'bg-green-50 text-green-700' : ($profil->status_verifikasi === 'menunggu' ? 'bg-yellow-50 text-yellow-700' : 'bg-red-50 text-red-700') }}">
                <p class="font-semibold">Status: {{ ucfirst($profil->status_verifikasi) }}</p>
                @if($profil->status_verifikasi === 'menunggu')
                    <p class="text-sm">Pengajuan merchant Anda sedang dalam proses verifikasi.</p>
                @elseif($profil->status_verifikasi === 'ditolak')
                    <p class="text-sm">Pengajuan Anda ditolak: {{ $profil->alasan_penolakan }}</p>
                @endif
            </div>
        </div>
    @elseif(Auth::user()->peran !== 'merchant')
        <div class="bg-[#4a5c2f] text-white rounded-3xl p-8 text-center shadow-lg">
            <h3 class="font-display text-2xl font-bold mb-2">Ingin Jual Makanan Anda?</h3>
            <p class="text-[#c8d5b3] mb-6">Lengkapi data usaha Anda dan ajukan verifikasi merchant sekarang.</p>
            <a href="{{ route('merchant.application') }}" class="inline-block bg-white text-[#4a5c2f] font-bold px-8 py-3 rounded-xl hover:bg-[#f0f2dc] transition-colors">
                Daftar Merchant
            </a>
        </div>
    @endif
</div>
@endsection
