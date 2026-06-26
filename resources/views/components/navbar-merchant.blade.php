<nav class="flex items-center justify-between w-full mb-6 md:mb-8">
    <div class="flex items-center gap-3">
        <button @click="sidebarOpen = true" class="lg:hidden text-[#545523] hover:text-[#7A7A33] transition cursor-pointer">
            <i class="fa-solid fa-bars text-xl"></i>
        </button>
        <h2 class="text-xl md:text-2xl font-extrabold text-[#545523]">
            @yield('page_title', 'Merchant Dashboard') 
        </h2>
    </div>

    <div class="flex items-center gap-3 md:gap-4">
        <a href="{{ route('merchant.profile') }}" class="flex items-center gap-2.5 bg-white border border-[#545523]/10 rounded-full pl-2 pr-4 py-1.5 shadow-sm hover:shadow-md transition-all">
            <img src="{{ asset('svg/user-icon.png') }}" alt="Merchant" class="w-8 h-8 md:w-9 md:h-9 rounded-full object-cover border border-gray-100">
            <div class="leading-tight hidden sm:block">
                <p class="text-[10px] text-emerald-600 font-bold uppercase tracking-wider">Mitra Toko</p>
                <p class="text-xs font-bold text-gray-800">{{ Auth::user()->name }}</p>
            </div>
        </a>

        <form action="{{ route('logout') }}" method="POST" class="m-0">
            @csrf
            <button type="submit" class="flex items-center justify-center w-10 h-10 md:w-11 md:h-11 rounded-full border border-red-200 bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-all shadow-sm cursor-pointer" title="Keluar">
                <i class="fa-solid fa-right-from-bracket text-sm md:text-base"></i>
            </button>
        </form>
    </div>
</nav>