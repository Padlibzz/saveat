@extends('layouts.app')

@section('title', 'Profil Pengguna')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="bg-white rounded-3xl shadow-sm p-6">
        <h2 class="font-display text-2xl font-bold text-[#555524] mb-6">Profil Saya</h2>
        
        <div class="space-y-4">
            <div>
                <label class="text-xs font-semibold uppercase text-gray-500">Nama Lengkap</label>
                <p class="text-gray-900">{{ Auth::user()->name }}</p>
            </div>
            <div>
                <label class="text-xs font-semibold uppercase text-gray-500">Email</label>
                <p class="text-gray-900">{{ Auth::user()->email }}</p>
            </div>
            <div>
                <label class="text-xs font-semibold uppercase text-gray-500">Peran</label>
                <p class="text-gray-900 capitalize">{{ Auth::user()->peran }}</p>
            </div>
        </div>
    </div>

    @if($profil)
        <div class="bg-white rounded-3xl shadow-sm p-6">
            <h3 class="font-display text-xl font-bold text-[#555524] mb-4">Status Merchant</h3>
            <div class="p-4 rounded-xl {{ $profil->status_verifikasi === 'disetujui' ? 'bg-green-50 text-green-700' : 'bg-yellow-50 text-yellow-700' }}">
                <p class="font-semibold">Status: {{ ucfirst($profil->status_verifikasi) }}</p>
                @if($profil->status_verifikasi === 'menunggu')
                    <p class="text-sm">Pengajuan merchant Anda sedang dalam proses verifikasi.</p>
                @elseif($profil->status_verifikasi === 'ditolak')
                    <p class="text-sm">Pengajuan Anda ditolak: {{ $profil->alasan_penolakan }}</p>
                @endif
            </div>
        </div>
    @else
        <div class="bg-olive text-white rounded-3xl p-8 text-center shadow-lg">
            <h3 class="font-display text-2xl font-bold mb-2">Ingin Jual Makanan Anda?</h3>
            <p class="text-sage mb-6">Lengkapi data usaha Anda dan ajukan verifikasi merchant sekarang.</p>
            <a href="{{ route('merchant.application') }}" class="inline-block bg-white text-olive font-bold px-8 py-3 rounded-xl hover:bg-cream transition-colors">
                Daftar Merchant
            </a>
        </div>
    @endif
</div>
@endsection
