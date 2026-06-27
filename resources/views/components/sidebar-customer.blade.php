<div x-show="sidebarOpen" @click="sidebarOpen = false" x-cloak class="fixed inset-0 bg-black/50 z-40 lg:hidden"></div>

<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed lg:fixed top-0 left-0 z-50 w-64 h-screen bg-white shadow-lg transition-transform duration-300 lg:translate-x-0">
    <div class="p-6">
        <div class="flex items-center gap-3">
            <img src="{{ asset('img/logo-saveat.png') }}" alt="SaveEat" class="w-10 h-10">
            <h1 class="text-3xl font-bold text-[#545523]">Saveat</h1>
        </div>
    </div>

    <nav class="p-4 space-y-2">
        <a href="/dashboard" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->is('dashboard') ? 'bg-[#F1F2CF] text-[#545523] font-semibold' : 'text-gray-600 hover:bg-[#F1F2CF] hover:text-[#545523]' }}">
            <i class="fa-solid fa-chart-pie w-5"></i>
            <span>Dashboard</span>
        </a>

        <a href="/listing-makanan" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->is('listing-makanan') ? 'bg-[#F1F2CF] text-[#545523] font-semibold' : 'text-gray-600 hover:bg-[#F1F2CF] hover:text-[#545523]' }}">
            <i class="fa-solid fa-utensils w-5"></i>
            <span>Makanan</span>
        </a>

        <a href="/pesanan" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->is('pesanan') ? 'bg-[#F1F2CF] text-[#545523] font-semibold' : 'text-gray-600 hover:bg-[#F1F2CF] hover:text-[#545523]' }}">
            <i class="fa-solid fa-bag-shopping w-5"></i>
            <span>Pesanan</span>
        </a>

        <a href="/riwayat-pesanan" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->is('riwayat-pesanan') ? 'bg-[#F1F2CF] text-[#545523] font-semibold' : 'text-gray-600 hover:bg-[#F1F2CF] hover:text-[#545523]' }}">
            <i class="fa-solid fa-clock-rotate-left w-5"></i>
            <span>Riwayat Pesanan</span>
        </a>

        @if(Auth::check() && Auth::user()->peran === 'merchant')
            <div class="pt-4 my-4 border-t border-gray-100">
                <p class="text-[10px] font-bold tracking-wider uppercase text-gray-400 px-4 mb-2">Akses Toko</p>
                <a href="{{ route('merchant.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition bg-[#545523] text-[#F1F2CF] font-semibold hover:bg-opacity-90 shadow-sm">
                    <i class="fa-solid fa-store w-5"></i>
                    <span>Dashboard Merchant</span>
                </a>
            </div>
        @endif
    </nav>
</aside>