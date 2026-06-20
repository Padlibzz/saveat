@extends('layouts.admin.app')

@section('title', 'Dashboard Admin')

@section('header', 'Dashboard Admin')

@section('content')
<style>
    /* Card fade-in */
    @keyframes fadeUp {
      from { opacity:0; transform:translateY(20px); }
      to   { opacity:1; transform:translateY(0); }
    }
    .stat-card { animation: fadeUp .5s ease both; }
    .stat-card:nth-child(1){ animation-delay:.05s }
    .stat-card:nth-child(2){ animation-delay:.1s }
    .stat-card:nth-child(3){ animation-delay:.15s }
    .stat-card:nth-child(4){ animation-delay:.2s }
    .stat-card:nth-child(5){ animation-delay:.25s }
</style>

<div class="space-y-6">
    <div>
        <h2 class="font-display text-3xl font-extrabold leading-tight text-[#555524]">Ringkasan Platform</h2>
        <p class="text-xs text-charcoal/50 mt-1">{{ now()->format('l, j F Y') }}</p>
    </div>

    <!-- ── STAT CARDS ── -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        
        <!-- 1. Merchant Aktif -->
        <div class="stat-card border-l-4 border-olive bg-white rounded-2xl px-5 py-4 shadow-sm hover:shadow-md transition-shadow">
            <p class="text-xs font-medium uppercase tracking-wide text-[#555524]">Merchant Aktif</p>
            <p class="font-display text-3xl font-extrabold mt-1 text-[#555524]">{{ $totalMerchantAktif }}</p>
        </div>

        <!-- 2. Konsumen Aktif -->
        <div class="stat-card border-l-4 border-olive bg-white rounded-2xl px-5 py-4 shadow-sm hover:shadow-md transition-shadow">
            <p class="text-xs font-medium uppercase tracking-wide text-[#555524]">Konsumen Aktif</p>
            <p class="font-display text-3xl font-extrabold mt-1 text-[#555524]">{{ $totalKonsumenAktif }}</p>
        </div>

        <!-- 3. Total Listing -->
        <div class="stat-card border-l-4 border-olive bg-white rounded-2xl px-5 py-4 shadow-sm hover:shadow-md transition-shadow">
            <p class="text-xs font-medium uppercase tracking-wide text-[#555524]">Total Listing</p>
            <p class="font-display text-3xl font-extrabold mt-1 text-[#555524]">{{ $totalListing }}</p>
        </div>

        <!-- 4. Total Klaim -->
        <div class="stat-card border-l-4 border-olive bg-white rounded-2xl px-5 py-4 shadow-sm hover:shadow-md transition-shadow">
            <p class="text-xs font-medium uppercase tracking-wide text-[#555524]">Total Klaim</p>
            <p class="font-display text-3xl font-extrabold mt-1 text-[#555524]">{{ $totalKlaim }}</p>
        </div>

        <!-- 5. Makanan Terselamatkan -->
        <div class="stat-card border-l-4 border-olive bg-white rounded-2xl px-5 py-4 shadow-sm hover:shadow-md transition-shadow">
            <p class="text-xs font-medium uppercase tracking-wide text-[#555524]">Makanan Terselamatkan</p>
            <p class="font-display text-3xl font-extrabold mt-1 text-[#555524]">{{ $totalMakananTerselamatkan }}</p>
        </div>
    </div>

    <!-- ── BAR CHART ── -->
    <div class="bg-white rounded-2xl p-5 shadow-sm">
        <div class="relative h-56">
            <canvas id="summaryChart"></canvas>
        </div>
    </div>
</div>

<script>
    const ctx = document.getElementById('summaryChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Merchant', 'Konsumen', 'Listing', 'Klaim', 'Terselamatkan'],
            datasets: [{
                data: [{{ $totalMerchantAktif }}, {{ $totalKonsumenAktif }}, {{ $totalListing }}, {{ $totalKlaim }}, {{ $totalMakananTerselamatkan }}],
                backgroundColor: ['#4a5c2f', '#6b7c45', '#c8d5b3', '#4a5c2f', '#6b7c45'],
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endsection
