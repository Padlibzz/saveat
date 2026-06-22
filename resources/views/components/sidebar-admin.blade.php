<div x-show="sidebarOpen" @click="sidebarOpen = false" x-cloak class="fixed inset-0 bg-black/50 z-40 lg:hidden"></div>

<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed lg:fixed top-0 left-0 z-50 w-64 h-screen bg-white shadow-lg transition-transform duration-300 lg:translate-x-0">
    <div class="p-6">
        <div class="flex items-center gap-3">
            <img src="{{ asset('img/logo-saveat.png') }}" alt="SaveEat" class="w-10 h-10">
            <h1 class="text-3xl font-bold text-[#545523]">Saveat</h1>
        </div>
    </div>

    <nav class="p-4 space-y-2">
        <a href="/admin/dashboard" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->is('admin/dashboard') ? 'bg-[#F1F2CF] text-[#545523] font-semibold' : 'text-gray-600 hover:bg-[#F1F2CF] hover:text-[#545523]' }}">
            <i class="fa-solid fa-chart-pie w-5"></i>
            <span>Dashboard</span>
        </a>

        <a href="/admin/user-management" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->is('admin/user-management') ? 'bg-[#F1F2CF] text-[#545523] font-semibold' : 'text-gray-600 hover:bg-[#F1F2CF] hover:text-[#545523]' }}">
            <i class="fa-solid fa-users-gear w-5"></i>
            <span>User Management</span>
        </a>

        <a href="/admin/verifikasi-merchant" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->is('admin/verifikasi-merchant') ? 'bg-[#F1F2CF] text-[#545523] font-semibold' : 'text-gray-600 hover:bg-[#F1F2CF] hover:text-[#545523]' }}">
            <i class="fa-solid fa-building-circle-check w-5"></i>
            <span>Verifikasi Merchant</span>
        </a>

        <a href="/admin/analisis-penjualan" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->is('admin/analisis-penjualan') ? 'bg-[#F1F2CF] text-[#545523] font-semibold' : 'text-gray-600 hover:bg-[#F1F2CF] hover:text-[#545523]' }}">
            <i class="fa-solid fa-chart-line w-5"></i>
            <span>Analisis Penjualan</span>
        </a>
    </nav>
</aside>