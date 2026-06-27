<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SaveEat')</title>
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50/50">

    <div class="p-4 md:p-8 max-w-6xl mx-auto pb-24">
        
        {{-- HEADER NAVIGATION --}}
        <div class="flex items-center justify-between mb-8">
            
            {{-- Tombol Kembali (Kiri) --}}
            <a href="javascript:history.back()" class="text-[#545523] hover:opacity-75 transition p-2 -ml-2">
                <i class="fa-solid fa-arrow-left text-xl"></i>
            </a>
            
            {{-- Judul Halaman (Tengah) --}}
            <h2 class="text-xl font-bold text-[#545523]">@yield('page_title')</h2>
            
            {{-- Tombol Pengaturan (Satu untuk Semua Role) --}}
            <a href="{{ url('main/pengaturan') }}" class="text-[#545523] hover:opacity-75 transition p-2 -mr-2">
                <i class="fa-solid fa-gear text-xl"></i>
            </a>

        </div>

        {{-- KONTEN HALAMAN --}}
        <main>
            @yield('content')
        </main>

    </div>

</body>
</html>