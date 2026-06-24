@section('page_title', 'Dashboard Admin')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Admin's Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
    </style>
</head>
<body
    x-data="{ sidebarOpen: false }"
    class="bg-gradient-to-r from-[#CFD086] to-[#F1F2CF] min-h-screen font-[Poppins]">

    <div class="flex min-h-screen">
        <x-sidebar />

        <div class="flex-1">

            <x-navbar />

            <section class="p-6 mb-2 lg:ml-64">
            @if(session('success'))
                <x-alert type="success" :message="session('success')" />
            @endif

            @if(session('error'))
                <x-alert type="error" :message="session('error')" />
            @endif
                <div class="mb-6">
                    <h2 class="text-3xl font-bold text-[#545523] tracking-wide">Ringkasan Platform</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 mb-6">
                    
                    <div class="bg-white rounded-2xl p-5 shadow-sm border-l-5 border-emerald-500 flex justify-between items-center">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 tracking-wide">Total Pengguna</p>
                            <h3 class="text-3xl font-extrabold text-gray-800 mt-1">
                                {{ $totalPengguna >= 1000 ? number_format($totalPengguna / 1000, 1) . ' K' : $totalPengguna }}
                            </h3>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-5 shadow-sm border-l-5 border-emerald-500 flex justify-between items-center">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 tracking-wide">User Aktif</p>
                            <h3 class="text-3xl font-extrabold text-gray-800 mt-1">{{ $aktifHariIni }}</h3>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-5 shadow-sm border-l-5 border-amber-400 flex justify-between items-center">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 tracking-wide">Baru Daftar (Hari Ini)</p>
                            <h3 class="text-3xl font-extrabold text-gray-800 mt-1">{{ $baruDaftar }}</h3>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-5 shadow-sm border-l-5 border-emerald-500 flex justify-between items-center">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 tracking-wide">Makanan Terselamatkan</p>
                            <h3 class="text-2xl font-extrabold text-gray-800 mt-1">{{ number_format($makananTerselamatkan) }} Porsi</h3>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-5 shadow-sm border-l-5 border-red-500 flex justify-between items-center">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 tracking-wide">Laporan Pelanggaran</p>
                            <h3 class="text-3xl font-extrabold text-gray-800 mt-1">{{ $totalDilaporkan }}</h3>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-5 shadow-sm w-full overflow-hidden">
                    <div class="h-[320px] w-full">
                        <canvas id="platformChart"></canvas>
                    </div>
                </div>
            </section>

            <x-footer/>
        </div>

    </div>

</body>
</html>

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