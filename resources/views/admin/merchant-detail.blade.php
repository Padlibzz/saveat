@extends('layouts.admin.app')

@section('title', 'Detail Merchant')

@section('header', 'Detail Merchant')

@section('content')
<div class="max-w-2xl bg-white rounded-3xl shadow-sm p-6">
    <h3 class="text-xl font-bold text-gray-900 mb-4">{{ $merchant->nama_usaha }}</h3>
    
    <div class="space-y-4">
        <div>
            <label class="text-xs font-semibold uppercase text-gray-500">Nama Pemilik</label>
            <p class="text-gray-900">{{ $merchant->user->name }}</p>
        </div>
        <div>
            <label class="text-xs font-semibold uppercase text-gray-500">Email</label>
            <p class="text-gray-900">{{ $merchant->user->email }}</p>
        </div>
        <div>
            <label class="text-xs font-semibold uppercase text-gray-500">Alamat</label>
            <p class="text-gray-900">{{ $merchant->alamat ?? '-' }}</p>
        </div>
        <div>
            <label class="text-xs font-semibold uppercase text-gray-500">Deskripsi</label>
            <p class="text-gray-900">{{ $merchant->deskripsi ?? '-' }}</p>
        </div>
    </div>

    <form action="{{ route('admin.merchant-verifikasi', $merchant->id) }}" method="POST" class="mt-8 flex gap-4">
        @csrf
        @method('PATCH')
        <input type="hidden" name="diverifikasi_oleh" value="{{ auth()->id() }}">
        
        <button type="submit" name="status_verifikasi" value="disetujui" class="flex-1 py-3 rounded-xl font-semibold text-white bg-green-600 hover:bg-green-700">
            Setujui
        </button>
        <button type="submit" name="status_verifikasi" value="ditolak" class="flex-1 py-3 rounded-xl font-semibold text-white bg-red-600 hover:bg-red-700">
            Tolak
        </button>
    </form>
</div>
@endsection
