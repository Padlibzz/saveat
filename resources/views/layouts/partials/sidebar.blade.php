<aside id="sidebar" class="fixed inset-y-0 left-0 z-40 w-64 bg-olive text-cream flex flex-col -translate-x-full md:translate-x-0 shadow-2xl">
    <div class="flex items-center gap-3 px-5 py-5 border-b border-[#5a7a2a]">
        <span class="font-display text-xl font-bold">SavEat</span>
    </div>
    <nav class="flex-1 px-4 py-6 space-y-1 text-sm font-medium">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('dashboard') ? 'bg-cream/15 text-cream' : 'text-cream/70 hover:bg-cream/10 hover:text-cream' }}">
            <i class="fa-solid fa-utensils"></i> Dashboard
        </a>
        <a href="{{ route('profile') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('profile') ? 'bg-cream/15 text-cream' : 'text-cream/70 hover:bg-cream/10 hover:text-cream' }}">
            <i class="fa-solid fa-user"></i> Profil
        </a>
    </nav>
</aside>
<div id="overlay" class="fixed inset-0 z-30 bg-black/40 hidden md:hidden" onclick="toggleSidebar()"></div>
