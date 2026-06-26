@section('page_title', 'Dashboard Merchant')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Merchant's Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
    </style>
</head>
<body
    x-data="{ sidebarOpen: false }"
    class="bg-gradient-to-r from-[#CFD086] to-[#F1F2CF] min-h-screen font-[Poppins]">

    <div class="flex min-h-screen">
        <x-sidebar />

        <div class="flex-1 flex flex-col justify-between min-h-screen">

            <div>
                <x-navbar />

                <section class="p-6 lg:ml-64 justify-center flex">
                    <div class="w-full max-w-5xl space-y-6">
                        
                        @if(session('success'))
                            <x-alert type="success" :message="session('success')" />
                        @endif

                        @if(session('error'))
                            <x-alert type="error" :message="session('error')" />
                        @endif

                        <div class="mt-2">
                            <h1 class="text-2xl font-bold text-[#545523]">Selamat Datang,</h1>
                            <p class="text-sm text-[#545523]/80 font-medium">Pantau dampak dan penjualan makanan Anda hari ini.</p>
                        </div>

                        <div class="w-full">
                            <a href="/merchant/upload-makanan" class="block w-full text-center bg-[#545523] text-[#F1F2CF] font-medium py-3 rounded-xl shadow-md hover:bg-[#43441c] transition-all">
                                listing makanan
                            </a>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            
                            <div class="bg-[#FDFDF5] p-6 min-h-[140px] rounded-2xl shadow-sm flex flex-col justify-between border border-[#545523]/10">
                                <div class="flex items-center gap-3">
                                    <div class="bg-[#545523]/10 p-2.5 rounded-xl text-[#545523]">
                                        <i class="fa-solid fa-wallet text-lg"></i>
                                    </div>
                                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Penghasilan</span>
                                </div>
                                <h3 class="text-2xl font-bold text-[#545523] mt-4">
                                    Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}
                                </h3>
                            </div>

                            <div class="bg-[#FDFDF5] p-6 min-h-[140px] rounded-2xl shadow-sm flex flex-col justify-between border border-[#545523]/10 relative">
                                <span class="absolute top-6 right-6 text-[11px] font-medium text-gray-400">hari ini</span>
                                <div class="flex items-center gap-3">
                                    <div class="bg-[#545523]/10 p-2.5 rounded-xl text-[#545523]">
                                        <i class="fa-solid fa-utensils text-lg"></i>
                                    </div>
                                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Makanan Terjual</span>
                                </div>
                                <h3 class="text-2xl font-bold text-[#545523] mt-4">
                                    {{ $totalPorsiTerjual ?? 0 }} Porsi
                                </h3>
                            </div>

                            <div class="bg-[#FDFDF5] p-6 min-h-[140px] rounded-2xl shadow-sm flex flex-col justify-between border border-[#545523]/10 relative">
                                <div class="flex items-center gap-3">
                                    <div class="bg-[#545523]/10 p-2.5 rounded-xl text-[#545523]">
                                        <i class="fa-solid fa-users text-lg"></i>
                                    </div>
                                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Pembeli</span>
                                </div>
                                <h3 class="text-2xl font-bold text-[#545523] mt-4">
                                    {{ $totalPembeliUnik ?? 0 }} Pelanggan
                                </h3>
                            </div>

                        </div>

                        <!-- Grafik Penjualan Dinamis -->
                        <div class="bg-[#FDFDF5] p-6 rounded-2xl shadow-sm border border-[#545523]/10">
                            <div class="flex justify-between items-center mb-6">
                                <h4 class="text-sm font-bold text-[#545523]">Kurs Penjualan</h4>
                            </div>
                            
                            <div class="flex items-end justify-between gap-2 pt-4 px-2 h-48 border-b border-gray-100">
                                @php
                                    $maxTotal = collect($grafikPenjualan ?? [])->max('total') ?: 1;
                                @endphp
                                @foreach($grafikPenjualan ?? [] as $item)
                                    @php
                                        $heightPercent = ($item['total'] / $maxTotal) * 100;
                                        $heightPercent = max(5, $heightPercent); 
                                    @endphp
                                    <div class="flex flex-col items-center flex-1 gap-2 group">
                                        <span class="text-[10px] font-bold text-[#545523] opacity-0 group-hover:opacity-100">
                                            {{ $item['total'] }}
                                        </span>
                                        <div class="w-8 md:w-10 bg-[#A3A463] rounded-t-lg group-hover:bg-[#545523]" style="height: {{ $heightPercent }}%;"></div>
                                        <span class="text-[11px] font-medium text-gray-500">{{ $item['label'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Aktivitas Terkini Dinamis -->
                        <div class="bg-[#FDFDF5] p-6 rounded-2xl shadow-sm border border-[#545523]/10 space-y-4">
                            <h4 class="text-sm font-bold text-[#545523] mb-2">Aktivitas Terkini</h4>
                            
                            <div class="space-y-3">
                                @forelse($aktivitasTerkini ?? [] as $act)
                                    <div class="flex items-center gap-4 p-2 rounded-xl hover:bg-gray-50 transition">
                                        <div class="bg-[#545523]/10 text-[#545523] p-2.5 rounded-full flex items-center justify-center w-10 h-10">
                                            <i class="fa-solid fa-{{ $act['icon'] }} text-sm"></i>
                                        </div>
                                        <div class="flex-1">
                                            <h5 class="text-xs font-bold text-gray-800">{{ $act['judul'] }}</h5>
                                            <p class="text-[11px] text-gray-400 mt-0.5">{{ $act['keterangan'] }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-xs text-gray-500 text-center py-4">Belum ada aktivitas saat ini.</p>
                                @endforelse
                            </div>
                        </div>

                    </div>
                </section>
            </div>

            <x-footer />
        </div>
    </div>

</body>
</html>