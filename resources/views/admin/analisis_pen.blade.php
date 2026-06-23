@extends('layouts.admin.app')

@section('title', 'Analisis Penjualan')

@section('header', 'Analisis Penjualan')

@section('content')
<div class="space-y-6">
    <!-- Period Tabs (Simplified for now - can be made dynamic later) -->
    <div class="flex gap-2 bg-white rounded-2xl p-1.5 w-fit shadow-sm border border-gray-100">
        <a href="{{ route('admin.analisis', ['filter' => '7hari']) }}" class="{{ $filter === '7hari' ? 'bg-olive text-white' : 'bg-white text-olive hover:bg-sage/20' }} px-4 py-1.5 rounded-xl text-[13px] font-semibold">7 Hari terakhir</a>
        <a href="{{ route('admin.analisis', ['filter' => 'bulanini']) }}" class="{{ $filter === 'bulanini' ? 'bg-olive text-white' : 'bg-white text-olive hover:bg-sage/20' }} px-4 py-1.5 rounded-xl text-[13px] font-semibold hover:bg-sage/20">Bulan ini</a>
        <a href="{{ route('admin.analisis', ['filter' => 'tahunini']) }}" class="{{ $filter === 'tahunini' ? 'bg-olive text-white' : 'bg-white text-olive hover:bg-sage/20' }} px-4 py-1.5 rounded-xl text-[13px] font-semibold hover:bg-sage/20">Tahun Ini</a>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <p class="text-gray-500 text-[13px] font-semibold">Total Pendapatan</p>
            <p class="text-2xl font-bold text-gray-900 mt-2">Rp{{ number_format($totalPendapatan, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <p class="text-gray-500 text-[13px] font-semibold">Hemat Makanan</p>
            <p class="text-2xl font-bold text-gray-900 mt-2">{{ $totalMakananHemat }} kg</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <p class="text-gray-500 text-[13px] font-semibold">Listing Aktif</p>
            <p class="text-2xl font-bold text-gray-900 mt-2">{{ $totalListingAktif }}</p>
        </div>
    </div>

    <!-- Chart -->
    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
        <p class="text-gray-900 text-[14px] font-semibold mb-4">Trending Penjualan Mingguan</p>
        <div class="relative h-64">
            <canvas id="salesChart"></canvas>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('salesChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Penjualan',
                    data: @json($chartData),
                    borderColor: '#4a5c2f',
                    backgroundColor: 'rgba(74, 92, 47, 0.1)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    });
</script>
@endsection
