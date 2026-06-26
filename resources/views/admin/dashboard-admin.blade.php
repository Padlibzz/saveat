@extends('layouts.dashboard')

@section('page_title', 'Dashboard Admin')

@section('content')
<div class="space-y-6">

    {{-- BANNER NOTIFIKASI FLASH SESSION --}}
    @if(session('success'))
        <div class="mb-4">
            <x-alert type="success" :message="session('success')" />
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4">
            <x-alert type="error" :message="session('error')" />
        </div>
    @endif

    {{-- JUDUL SEKSI --}}
    <div>
        <h2 class="text-xl md:text-2xl font-extrabold text-[#545523] tracking-wide">
            Ringkasan Platform 📊
        </h2>
    </div>

    {{-- GRID KARTU STATISTIK (STAT CARDS) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        
        <div class="bg-white rounded-2xl p-5 shadow-xs border-l-4 border-emerald-500 flex justify-between items-center transition-all hover:shadow-md">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Pengguna</p>
                <h3 class="text-2xl font-black text-gray-800 mt-1">
                    {{ $totalPengguna >= 1000 ? number_format($totalPengguna / 1000, 1) . ' K' : $totalPengguna }}
                </h3>
            </div>
            <div class="text-emerald-500 bg-emerald-50/60 w-10 h-10 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-users text-base"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-xs border-l-4 border-emerald-500 flex justify-between items-center transition-all hover:shadow-md">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">User Aktif</p>
                <h3 class="text-2xl font-black text-gray-800 mt-1">{{ $aktifHariIni }}</h3>
            </div>
            <div class="text-emerald-500 bg-emerald-50/60 w-10 h-10 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-user-check text-base"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-xs border-l-4 border-amber-400 flex justify-between items-center transition-all hover:shadow-md">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Baru Daftar (Hari Ini)</p>
                <h3 class="text-2xl font-black text-gray-800 mt-1">{{ $baruDaftar }}</h3>
            </div>
            <div class="text-amber-500 bg-amber-50/60 w-10 h-10 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-user-plus text-base"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-xs border-l-4 border-emerald-500 flex justify-between items-center transition-all hover:shadow-md">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Makanan Terselamatkan</p>
                <h3 class="text-xl font-black text-gray-800 mt-1">{{ number_format($makananTerselamatkan) }} Porsi</h3>
            </div>
            <div class="text-emerald-500 bg-emerald-50/60 w-10 h-10 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-bowl-food text-base"></i>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-xs border-l-4 border-red-500 flex justify-between items-center transition-all hover:shadow-md">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Laporan Pelanggaran</p>
                <h3 class="text-2xl font-black text-gray-800 mt-1">{{ $totalDilaporkan }}</h3>
            </div>
            <div class="text-red-500 bg-red-50/60 w-10 h-10 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-triangle-exclamation text-base"></i>
            </div>
        </div>
    </div>

    {{-- KARTU GRAFIK PLATFORM --}}
    <div class="bg-white rounded-2xl p-5 shadow-xs border border-gray-100 w-full overflow-hidden">
        <div class="h-[320px] w-full">
            <canvas id="platformChart"></canvas>
        </div>
    </div>
</div>

{{-- SCRIPT KHUSUS GRAFIK BAR CHART --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('platformChart').getContext('2d');
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Pengguna', 'Aktif', 'Baru Daftar', 'Terselamatkan', 'Dilaporkan'],
                datasets: [{
                    data: [
                        {{ $totalPengguna }}, 
                        {{ $aktifHariIni }}, 
                        {{ $baruDaftar }}, 
                        {{ $makananTerselamatkan }}, 
                        {{ $totalDilaporkan }}
                    ], 
                    backgroundColor: [
                        '#696B43', 
                        '#9EA072', 
                        '#ECEEBA', 
                        '#828459', 
                        '#C2C496'  
                    ],
                    borderWidth: 0,
                    maxBarThickness: 45
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            font: { family: 'Poppins', size: 11 }
                        },
                        grid: { color: '#E5E7EB', drawTicks: false },
                        border: { dash: [4, 4] }
                    },
                    x: {
                        ticks: {
                            font: { family: 'Poppins', size: 11, weight: '500' },
                            color: '#4B5563',
                            maxRotation: 45,
                            minRotation: 45
                        },
                        grid: { display: false }
                    }
                }
            }
        });
    });
</script>
@endsection