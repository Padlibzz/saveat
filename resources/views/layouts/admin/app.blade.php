<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>@yield('title') – SavEat Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        #sidebar { 
            transition: transform .3s ease; 
            transform: translateX(-100%);
        }
        #sidebar.open { transform: translateX(0); }
        
        /* Ensure overlay visibility */
        #overlay.hidden { display: none; }
        #overlay:not(.hidden) { display: block; }
        
        .accent-green { border-left: 4px solid #4a5c2f; }
        .accent-red { border-left: 4px solid #dc3545; }
    </style>
</head>
<body class="min-h-screen bg-cream">

    @include('admin.partials.sidebar')

    <!-- Wrapper that manages sidebar state -->
    <div id="main-wrapper" class="min-h-screen flex flex-col transition-all duration-300 md:ml-64">
        <header class="sticky top-0 z-20 bg-white border-b border-gray-100 shadow-sm px-5 py-4 flex items-center gap-4">
            <!-- Fixed button to be more visible -->
            <button onclick="toggleSidebar()" class="w-10 h-10 flex justify-center items-center border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors">
                <i class="fa-solid fa-bars text-[#6c6d2d] text-xl"></i>
            </button>
            <h1 class="font-display text-lg font-bold text-[#6c6d2d]">@yield('header')</h1>
        </header>

        <main class="flex-1 px-5 py-6">
            @yield('content')
        </main>

        <footer class="text-center text-xs text-charcoal/40 py-6 border-t border-sage/30 text-[#555524]">
            © 2026 Saveat. All rights reserved.
        </footer>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            const mainWrapper = document.getElementById('main-wrapper');
            
            sidebar.classList.toggle('open');
            
            // Toggle overlay (only on mobile)
            if (sidebar.classList.contains('open')) {
                if (window.innerWidth < 768) overlay.classList.remove('hidden');
                mainWrapper.classList.add('md:ml-64');
            } else {
                overlay.classList.add('hidden');
                mainWrapper.classList.remove('md:ml-64');
            }
        }
    </script>
</body>
</html>
