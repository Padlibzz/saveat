<aside id="sidebar" class="fixed inset-y-0 left-0 z-40 w-64 bg-olive text-cream flex flex-col transition-all duration-300 shadow-2xl md:translate-x-0 -translate-x-full">
    <div class="flex items-center justify-left px-5 py-5 border-b border-[#5a7a2a]">
        <button onclick="toggleSidebar()" class="text-cream text-2xl">
            <i class="fa-solid fa-bars"></i>
        </button>
    </div>
    
    <nav class="flex-1 px-4 py-6 space-y-1 text-sm font-medium">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('dashboard') ? 'bg-cream/15 text-cream' : 'text-cream/70 hover:bg-cream/10 hover:text-cream' }}">
            <i class="fa-solid fa-chart-pie"></i> <span class="sidebar-text">Dashboard</span>
        </a>
        <a href="{{ route('listing-makanan') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('listing-makanan') ? 'bg-cream/15 text-cream' : 'text-cream/70 hover:bg-cream/10 hover:text-cream' }}">
            <i class="fa-solid fa-utensils"></i> <span class="sidebar-text">Makanan</span>
        </a>
        <a href="{{ route('pesanan.aktif') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('pesanan.aktif') ? 'bg-cream/15 text-cream' : 'text-cream/70 hover:bg-cream/10 hover:text-cream' }}">
            <i class="fa-solid fa-bag-shopping"></i> <span class="sidebar-text">Pesanan Aktif</span>
        </a>
        <a href="{{ route('riwayat.pesanan') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('riwayat.pesanan') ? 'bg-cream/15 text-cream' : 'text-cream/70 hover:bg-cream/10 hover:text-cream' }}">
            <i class="fa-solid fa-clock-rotate-left"></i> <span class="sidebar-text">Riwayat Pesanan</span>
        </a>
    </nav>

    <div class="p-4 border-t border-[#5a7a2a]">
        <a href="{{ route('profile') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('profile') ? 'bg-cream/15 text-cream' : 'text-cream/70 hover:bg-cream/10 hover:text-cream' }}">
            <i class="fa-solid fa-user"></i> <span class="sidebar-text">Profil</span>
        </a>
        <form action="{{ route('logout') }}" method="POST" class="mt-2">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-red-300 hover:bg-red-900/20 hover:text-red-200">
                <i class="fa-solid fa-right-from-bracket"></i> <span class="sidebar-text">Logout</span>
            </button>
        </form>
    </div>
</aside>
<div id="overlay" class="fixed inset-0 z-30 bg-black/40 hidden md:hidden" onclick="toggleSidebar()"></div>
