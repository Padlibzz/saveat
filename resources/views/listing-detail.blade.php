<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>{{ $listing->nama }} - Saveat</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap');
    </style>
</head>
<body class="bg-gradient-to-r from-[#CFD086] to-[#F1F2CF] min-h-screen font-[Poppins]">

    {{-- Navbar Publik --}}
    <nav class="container mx-auto px-4 py-6 flex items-center justify-between">
        <a href="/" class="flex items-center gap-3">
            <img src="{{ asset('img/logo-saveat.png') }}" alt="Saveat" class="w-10 h-10">
            <h1 class="text-2xl font-bold text-[#545523]">SaveEat</h1>
        </a>
        <div class="flex items-center gap-3">
            @auth
                <a href="{{ route('dashboard') }}" class="py-2 px-5 text-[#545523] font-semibold">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="py-2 px-5 text-[#545523]">Masuk</a>
                <a href="/auth/register" class="bg-[#545523] py-2 px-5 text-white rounded-lg">Daftar</a>
            @endauth
        </div>
    </nav>

    <div class="container mx-auto px-4 pb-12 max-w-2xl">

        {{-- Tombol kembali --}}
        <a href="{{ route('listing-makanan') }}" class="inline-flex items-center gap-2 text-[#545523] mb-6 hover:opacity-70">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke daftar makanan
        </a>

        {{-- Card Detail --}}
        <div class="bg-white rounded-2xl overflow-hidden shadow-sm">

            {{-- Foto --}}
            <img src="{{ $listing->foto ? asset('storage/'.$listing->foto) : asset('img/sample-food.png') }}"
                 alt="{{ $listing->nama }}"
                 class="w-full h-56 object-cover">

            <div class="p-6">

                {{-- Badge status --}}
                @if ($listing->status === 'hampir_habis')
                    <span class="bg-red-100 text-red-600 text-xs font-bold px-3 py-1 rounded-full">
                        🔥 Hampir Habis
                    </span>
                @endif

                {{-- Nama & Merchant --}}
                <h1 class="text-2xl font-bold text-[#545523] mt-3">{{ $listing->nama }}</h1>
                <p class="text-gray-500 mt-1">
                    <i class="fa-solid fa-store mr-1"></i>
                    {{ $listing->merchant->nama_usaha ?? '-' }}
                </p>

                {{-- Alamat --}}
                @if ($listing->merchant?->alamat)
                    <p class="text-gray-400 text-sm mt-1">
                        <i class="fa-solid fa-location-dot mr-1"></i>
                        {{ $listing->merchant->alamat }}
                    </p>
                @endif

                {{-- Harga --}}
                <div class="flex items-center gap-3 mt-4">
                    <span class="text-3xl font-extrabold text-[#545523]">
                        Rp{{ number_format($listing->harga_diskon, 0, ',', '.') }}
                    </span>
                    <span class="text-gray-400 line-through text-lg">
                        Rp{{ number_format($listing->harga_normal, 0, ',', '.') }}
                    </span>
                    <span class="bg-green-100 text-green-700 text-xs font-bold px-2 py-1 rounded-full">
                        Hemat {{ round((1 - $listing->harga_diskon / $listing->harga_normal) * 100) }}%
                    </span>
                </div>

                {{-- Stok & Batas Waktu --}}
                <div class="flex gap-4 mt-4">
                    <div class="bg-[#F8FDD2] rounded-xl px-4 py-3 flex-1 text-center">
                        <p class="text-xs text-gray-500">Sisa Stok</p>
                        <p class="text-xl font-bold text-[#545523]">{{ $listing->stok_sisa }} porsi</p>
                    </div>
                    <div class="bg-[#F8FDD2] rounded-xl px-4 py-3 flex-1 text-center">
                        <p class="text-xs text-gray-500">Ambil Sebelum</p>
                        <p class="text-xl font-bold text-[#545523]">
                            {{ \Carbon\Carbon::parse($listing->batas_waktu)->format('H:i') }}
                        </p>
                        <p class="text-xs text-gray-400">
                            {{ \Carbon\Carbon::parse($listing->batas_waktu)->format('d M Y') }}
                        </p>
                    </div>
                </div>

                {{-- Deskripsi --}}
                @if ($listing->deskripsi)
                    <div class="mt-4">
                        <p class="text-sm font-semibold text-[#545523]">Deskripsi</p>
                        <p class="text-gray-600 text-sm mt-1">{{ $listing->deskripsi }}</p>
                    </div>
                @endif

                {{-- Countdown --}}
                <div class="mt-4 bg-[#545523] text-white rounded-xl px-4 py-3 text-center" id="countdown-box">
                    <p class="text-xs mb-1">Waktu tersisa</p>
                    <p class="text-2xl font-bold" id="countdown">--:--:--</p>
                </div>

                {{-- Tombol Beli --}}
                <div class="mt-6">
                    @auth
                        <a href="{{ route('checkout', $listing->id) }}"
                           class="block w-full text-center bg-[#545523] text-white py-4 rounded-xl font-bold text-lg hover:opacity-90 transition">
                            Beli Sekarang
                        </a>
                    @else
                        <a href="{{ route('login') }}?redirect={{ urlencode(route('listing.show', $listing->id)) }}"
                           class="block w-full text-center bg-[#545523] text-white py-4 rounded-xl font-bold text-lg hover:opacity-90 transition">
                            Login untuk Beli
                        </a>
                        <p class="text-center text-xs text-gray-400 mt-2">
                            Anda perlu login untuk melakukan pembelian
                        </p>
                    @endauth
                </div>

            </div>
        </div>
    </div>

    {{-- Countdown Script --}}
    <script>
        const batasWaktu = new Date("{{ \Carbon\Carbon::parse($listing->batas_waktu)->toIso8601String() }}");

        function updateCountdown() {
            const now = new Date();
            const diff = batasWaktu - now;

            if (diff <= 0) {
                document.getElementById('countdown').textContent = 'Sudah Berakhir';
                document.getElementById('countdown-box').classList.replace('bg-[#545523]', 'bg-gray-400');
                return;
            }

            const jam    = Math.floor(diff / 3600000);
            const menit  = Math.floor((diff % 3600000) / 60000);
            const detik  = Math.floor((diff % 60000) / 1000);

            document.getElementById('countdown').textContent =
                String(jam).padStart(2,'0') + ':' +
                String(menit).padStart(2,'0') + ':' +
                String(detik).padStart(2,'0');
        }

        updateCountdown();
        setInterval(updateCountdown, 1000);
    </script>
</body>
</html>