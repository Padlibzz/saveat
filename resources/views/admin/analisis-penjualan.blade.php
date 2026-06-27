@extends('layouts.dashboard')

@section('title', 'Analisis Penjualan - SaveEat')
@section('page_title', 'Analisis Penjualan')

@section('content')
<div class="space-y-6">
    {{-- Ringkasan Statistik --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h4 class="text-gray-500 text-sm font-medium">Total Pendapatan</h4>
            <p class="text-2xl font-bold text-[#545523] mt-2">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h4 class="text-gray-500 text-sm font-medium">Makanan Terselamatkan</h4>
            <p class="text-2xl font-bold text-[#545523] mt-2">{{ $totalMakananHemat }} Porsi</p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h4 class="text-gray-500 text-sm font-medium">Listing Aktif</h4>
            <p class="text-2xl font-bold text-[#545523] mt-2">{{ $totalListingAktif }}</p>
        </div>
    </div>

    {{-- Grafik Analisis --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <h4 class="text-gray-800 font-bold mb-4">Grafik Penjualan</h4>
        {{-- Tempat untuk grafik, bisa menggunakan Chart.js atau library lain --}}
        <div class="h-64 flex items-center justify-center bg-gray-50 rounded-xl border border-gray-100 text-gray-400">
            [Grafik Penjualan akan ditampilkan di sini]
        </div>
    </div>
</div>
@endsection
