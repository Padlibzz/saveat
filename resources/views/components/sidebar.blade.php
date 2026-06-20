        <div
            x-show="sidebarOpen"
            @click="sidebarOpen = false"
            x-cloak
            class="fixed inset-0 bg-black/50 z-40 lg:hidden">
        </div>

<aside
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed lg:fixed top-0 left-0 z-50 w-64 h-screen bg-white shadow-lg transition-transform duration-300 lg:translate-x-0">

            <div class="p-6">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('img/logo-saveat.png') }}"
                        alt="SaveEat"
                        class="w-10 h-10">

                    <h1 class="text-3xl font-bold text-[#545523]">
                        SaveEat
                    </h1>
                </div>
            </div>

            <nav class="p-4 space-y-2">

                <a href="/listing-makanan"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-[#F1F2CF] transition">

                    <i class="fa-solid fa-utensils w-5"></i>
                    <span>Makanan</span>

                </a>

                <a href="#"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-[#F1F2CF] transition">

                    <i class="fa-solid fa-bag-shopping w-5"></i>
                    <span>Pesanan</span>

                </a>

                <a href="#"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-[#F1F2CF] transition">

                    <i class="fa-solid fa-clock-rotate-left w-5"></i>
                    <span>Riwayat Pesanan</span>

                </a>

                @if(Auth::user()->peran === 'merchant')

                <a href="/merchant/dashboard"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-[#F1F2CF] transition">

                    <i class="fa-solid fa-store w-5"></i>
                    <span>Merchant</span>

                </a>

                @endif

            </nav>

        </aside>