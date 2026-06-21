@section('page_title', 'Dashboard Merchant')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Dashboard Merchant - Saveat</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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

            <section class="p-6 lg:ml-64">

                @if (session('success'))
                    <div class="bg-green-100 text-green-800 px-4 py-3 rounded-lg mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <h2 class="text-2xl font-bold text-[#545523]">Selamat Datang,</h2>
                <p class="text-[#545523] mt-1">Pantau dampak dan penjualan makanan Anda hari ini.</p>

                <a href="{{ route('merchant.upload') }}"
                    class="block text-center bg-[#545523] text-white font-semibold py-3 rounded-xl mt-6 hover:opacity-90 transition">
                    Listing Makanan
                </a>

                <!-- Card: Penghasilan -->
                <div class="bg-[#F8FDD2] rounded-xl p-5 mt-6 shadow-sm">
                    <div class="bg-[#545523] w-10 h-10 rounded-lg flex items-center justify-center mb-3">
                        <i class="fa-solid fa-sack-dollar text-white"></i>
                    </div>
                    <p class="text-[#545523]">Penghasilan</p>
                    <p class="text-3xl font-bold text-[#545523] mt-1">
                        Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                    </p>
                </div>

                <!-- Card: Makanan Terjual -->
                <div class="bg-[#F8FDD2] rounded-xl p-5 mt-4 shadow-sm">
                    <div class="flex justify-between items-start">
                        <div class="bg-[#545523] w-10 h-10 rounded-lg flex items-center justify-center mb-3">
                            <i class="fa-solid fa-utensils text-white"></i>
                        </div>
                        <span class="text-sm text-gray-500">hari ini</span>
                    </div>
                    <p class="text-[#545523]">Makanan Terjual</p>
                    <p class="text-3xl font-bold text-[#545523] mt-1">{{ $makananTerjualHariIni }} Porsi</p>
                </div>

                <!-- Card: Total Pembeli -->
                <div class="bg-[#F8FDD2] rounded-xl p-5 mt-4 shadow-sm">
                    <div class="flex justify-between items-start">
                        <div class="bg-[#545523] w-10 h-10 rounded-lg flex items-center justify-center mb-3">
                            <i class="fa-solid fa-users text-white"></i>
                        </div>
                        @if ($pembeliBaruMingguIni > 0)
                            <span class="text-sm text-gray-500">+{{ $pembeliBaruMingguIni }} baru</span>
                        @endif
                    </div>
                    <p class="text-[#545523]">Total Pembeli</p>
                    <p class="text-3xl font-bold text-[#545523] mt-1">{{ $totalPembeliUnik }} Pelanggan</p>
                </div>

                <!-- Grafik Kurs Penjualan -->
                <div class="bg-[#F8FDD2] rounded-xl p-5 mt-4 shadow-sm">
                    <div class="flex justify-between items-center mb-4">
                        <p class="text-[#545523] font-semibold">Kurs Penjualan</p>
                        <span class="text-sm text-gray-500 bg-white px-3 py-1 rounded-lg">7 Hari Terakhir</span>
                    </div>

                    @php
                        $maxValue = max(array_column($grafikPenjualan, 'total')) ?: 1;
                    @endphp

                    <div class="flex items-end justify-between gap-2 h-40 mt-6">
                        @foreach ($grafikPenjualan as $hari)
                            <div class="flex flex-col items-center flex-1">
                                <div class="w-full bg-[#A6A84F] rounded-t-md transition-all"
                                     style="height: {{ max(($hari['total'] / $maxValue) * 100, 4) }}%"
                                     title="{{ $hari['total'] }} porsi"></div>
                                <span class="text-xs text-[#545523] mt-2">{{ $hari['label'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Aktivitas Terkini -->
                <div class="bg-[#F8FDD2] rounded-xl p-5 mt-4 shadow-sm mb-6">
                    <p class="text-[#545523] font-bold text-lg mb-4">Aktivitas Terkini</p>

                    @forelse ($aktivitasTerkini as $aktivitas)
                        <div class="flex items-start gap-3 py-3 {{ !$loop->last ? 'border-b border-gray-200' : '' }}">
                            <div class="bg-[#545523] w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid fa-{{ $aktivitas['icon'] }} text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="text-[#545523] font-semibold text-sm">{{ $aktivitas['judul'] }}</p>
                                <p class="text-gray-500 text-xs mt-0.5">
                                    {{ $aktivitas['keterangan'] }} • {{ $aktivitas['waktu']->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-sm">Belum ada aktivitas terbaru.</p>
                    @endforelse
                </div>

            </section>

            <x-footer/>
        </div>
    </div>

</body>
</html>