<aside id="sidebar" class="fixed inset-y-0 left-0 z-40 w-64 bg-olive text-cream flex flex-col -translate-x-full md:translate-x-0 shadow-2xl md:open open">
    <div class="flex items-center gap-3 px-5 py-5 border-b border-[#5a7a2a]">
        <div class="w-9 h-9 bg-[#8faa3e] rounded-xl flex items-center justify-center">
            <img src="{{ asset('img/admin/Logo.png') }}" alt="SavEat Logo" class="w-9 h-9 object-contain rounded-xl" />
        </div>
        <span class="font-display text-xl font-bold">SavEat</span>
    </div>
    <nav class="flex-1 px-4 py-6 space-y-1 text-sm font-medium">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.dashboard') ? 'bg-cream/15 text-cream' : 'text-cream/70 hover:bg-cream/10 hover:text-cream' }}">
            <i class="fa-solid fa-gauge-high"></i> Dashboard
        </a>
        <a href="{{ route('admin.profile') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.profile') ? 'bg-cream/15 text-cream' : 'text-cream/70 hover:bg-cream/10 hover:text-cream' }}">
            <i class="fa-solid fa-user"></i> Profil Admin
        </a>
        <a href="{{ route('admin.analisis') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.analisis') ? 'bg-cream/15 text-cream' : 'text-cream/70 hover:bg-cream/10 hover:text-cream' }}">
            <i class="fa-solid fa-chart-line"></i> Analisis Penjualan
        </a>
        <a href="{{ route('admin.users') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.users') ? 'bg-cream/15 text-cream' : 'text-cream/70 hover:bg-cream/10 hover:text-cream' }}">
            <i class="fa-solid fa-users"></i> User Management
        </a>
        <a href="{{ route('admin.merchant-menunggu') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.merchant-menunggu') ? 'bg-cream/15 text-cream' : 'text-cream/70 hover:bg-cream/10 hover:text-cream' }}">
            <i class="fa-solid fa-check-to-slot"></i> Verifikasi Merchant
        </a>
    </nav>
</aside>
<div id="overlay" class="fixed inset-0 z-30 bg-black/40 hidden md:hidden" onclick="toggleSidebar()"></div>
