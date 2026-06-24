@extends('layout.landing')

@section('page_title', 'Pesanan Saya')

@section('content')
<div class="bg-[#FDFDF5] min-h-screen py-8">
    <div class="container mx-auto px-4 max-w-4xl">
        <h1 class="text-2xl font-bold text-[#545523] mb-6">Pesanan Saya</h1>
        
        @forelse($claims as $claim)
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-[#545523]/10 mb-4 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-lg text-gray-800">{{ $claim->listing->nama }}</h3>
                    <p class="text-sm text-gray-500">Jumlah: {{ $claim->jumlah }}</p>
                    <p class="text-xs text-gray-400 mt-1">Kode Klaim: <span class="font-mono bg-gray-100 px-2 py-0.5 rounded">{{ $claim->kode_klaim }}</span></p>
                </div>
                <div class="text-right">
                    <p class="text-lg font-bold text-emerald-600">Rp {{ number_format($claim->total_harga, 0, ',', '.') }}</p>
                    <span class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-semibold 
                        {{ $claim->status === 'diambil' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                        {{ ucfirst($claim->status) }}
                    </span>
                </div>
            </div>
        @empty
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-[#545523]/10 text-center">
                <p class="text-gray-500">Belum ada pesanan aktif.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
