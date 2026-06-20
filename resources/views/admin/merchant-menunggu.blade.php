@extends('layouts.admin.app')

@section('title', 'Verifikasi Merchant')

@section('header', 'Verifikasi Merchant')

@section('content')
<div class="space-y-6">
    <!-- Stat Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
        <div class="rounded-2xl p-5 text-white relative overflow-hidden" style="background: linear-gradient(135deg, #3a6b2a 0%, #4e8a3a 100%);">
            <p class="text-white/80 text-[13px] font-semibold mb-1">Verifikasi Ditunda</p>
            <p class="text-[42px] font-extrabold leading-none mb-2">{{ $stats['ditunda'] }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <p class="text-gray-500 text-[13px] font-semibold mb-1">Disetujui Minggu ini</p>
            <p class="text-[#2D4A1E] text-[42px] font-extrabold leading-none">{{ $stats['disetujui'] }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <p class="text-gray-500 text-[13px] font-semibold mb-1">Ditolak Minggu ini</p>
            <p class="text-[#2D4A1E] text-[42px] font-extrabold leading-none">{{ $stats['ditolak'] }}</p>
        </div>
    </div>

    <!-- Merchant List -->
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4">
        @foreach($merchants as $merchant)
            <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 flex flex-col">
                <div class="p-4 flex-1 flex flex-col gap-3">
                    <p class="text-[#1C2E12] text-[15px] font-bold leading-tight">{{ $merchant->nama_usaha }}</p>
                    <p class="text-[#6B8F52] text-[13px]">{{ $merchant->user->name }}</p>
                    
                    <div class="text-[12px] text-[#6B8F52] space-y-1">
                        <p>Status: {{ ucfirst($merchant->status_verifikasi) }}</p>
                        <p>Diajukan: {{ $merchant->created_at->format('d M Y') }}</p>
                    </div>

                    <a href="{{ route('admin.merchant-detail', $merchant->id) }}" class="mt-auto w-full flex items-center justify-center gap-2 py-2.5 rounded-xl font-semibold text-[14px] text-white bg-[#2D4A1E] hover:bg-[#3a5c28] transition-colors">
                        Lihat Detail
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
