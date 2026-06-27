<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('page_title', 'SaveEat - Selamatkan Makanan')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gradient-to-r from-[#CFD086] to-[#F1F2CF] min-h-screen font-poppins">

    {{-- NAVBAR RESPONSIF --}}
    <nav class="container mx-auto px-4 py-6 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <img src="{{ asset('img/logo-saveat.png') }}" alt="SaveEat Logo" class="w-10 h-10">
            <h1 class="text-2xl md:text-3xl font-bold text-[#545523]">SaveEat</h1>
        </div>

        <div class="flex items-center gap-2 md:gap-4">
            <a href="/login" class="py-2 px-4 md:px-6 text-[#545523] text-sm md:text-base font-medium">
                Masuk
            </a>
            <a href="/register" class="bg-[#545523] py-2 px-4 md:px-6 text-white text-sm md:text-base rounded-xl shadow-sm hover:opacity-90 transition">
                Daftar
            </a>
        </div>
    </nav>

    {{-- KONTEN UTAMA --}}
    <main>
        @yield('content')
    </main>

</body>
</html>