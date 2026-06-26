<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SaveEat')</title>
    
    {{-- Memanggil kompilasi Tailwind & Poppins dari app.css lokaslmu --}}
    @vite(['resources/css/app.css'])

    {{-- Font Awesome untuk ikon --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    
    {{-- Alpine JS --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50/50">

    <div class="p-4 md:p-8 max-w-6xl mx-auto pb-24">
        <div class="flex items-center justify-between mb-8">
            <a href="javascript:history.back()" class="text-[#545523] hover:opacity-75 transition p-2 -ml-2">
                <i class="fa-solid fa-arrow-left text-xl"></i>
            </a>
            <h2 class="text-xl font-bold text-[#545523]">@yield('page_title')</h2>
            <div class="w-9"></div> 
        </div>

        <main>
            @yield('content')
        </main>

    </div>

</body>
</html>