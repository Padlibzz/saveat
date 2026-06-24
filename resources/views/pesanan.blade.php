@extends('layout.landing') 

@section('page_title', 'Pesanan Saya')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-[#545523] mb-6">Pesanan Saya</h1>
    
    @forelse( as )
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-4">
            <h3 class="font-bold">{{ ->listing->nama }}</h3>
            <p class="text-sm text-gray-500">Jumlah: {{ ->jumlah }}</p>
            <p class="text-sm font-semibold text-emerald-600">Rp {{ number_format(->total_harga, 0, ',', '.') }}</p>
        </div>
    @empty
        <p class="text-gray-500">Tidak ada pesanan aktif.</p>
    @endforelse
</div>
@endsection
