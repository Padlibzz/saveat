<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>@yield('title') – SavEat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        olive: { DEFAULT: '#4a5c2f', dark: '#3a4a22', light: '#6b7c45' },
                        cream: { DEFAULT: '#f0f2dc', light: '#f8f9ee', dark: '#e2e4c8' },
                        sage: { DEFAULT: '#c8d5b3', light: '#dce8cc' },
                        charcoal: '#2c2c2c',
                    },
                    fontFamily: {
                        display: ['"Playfair Display"', 'serif'],
                        body: ['"DM Sans"', 'sans-serif'],
                    },
                },
            },
        }
    </script>
    <style>
        body { font-family: 'DM Sans', sans-serif; background: #f0f2dc; }
        #overlay.hidden { display: none; }
        #overlay:not(.hidden) { display: block; }
    </style>
</head>
<body class="min-h-screen bg-cream">

    @include('layouts.partials.sidebar')

    <div id="main-wrapper" class="min-h-screen flex flex-col transition-all duration-300 md:ml-64">
        <header class="bg-white border-b border-gray-100 shadow-sm px-5 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ asset('img/logo-saveat.png') }}" alt="Logo" class="w-8 h-8 object-contain">
                <h1 class="font-display text-xl font-bold text-olive">SavEat</h1>
            </div>
        </header>

        <main class="flex-1 px-5 py-6">
            @yield('content')
        </main>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            const mainWrapper = document.getElementById('main-wrapper');
            const sidebarText = document.querySelectorAll('.sidebar-text');
            
            sidebar.classList.toggle('w-64');
            sidebar.classList.toggle('w-20');
            
            if (window.innerWidth >= 768) {
                mainWrapper.classList.toggle('md:ml-64');
                mainWrapper.classList.toggle('md:ml-20');
                sidebarText.forEach(el => el.classList.toggle('hidden'));
            } else {
                sidebar.classList.toggle('-translate-x-full');
                overlay.classList.toggle('hidden');
            }
        }
    </script>
</body>
</html>
