<div x-show="sidebarOpen" @click="sidebarOpen = false" x-cloak class="fixed inset-0 bg-black/50 z-40 lg:hidden"></div>

<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed lg:fixed top-0 left-0 z-50 w-64 h-screen bg-white shadow-lg transition-transform duration-300 lg:translate-x-0">
    <div class="p-6">
        <div class="flex items-center gap-3">
            <img src="{{ asset('img/logo-saveat.png') }}" alt="SaveEat" class="w-10 h-10">
            <h1 class="text-3xl font-bold text-[#545523]">Saveat</h1>
        </div>
    </div>

    <nav class="p-4 space-y-2">
        <a href="/merchant/dashboard" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->is('merchant/dashboard') ? 'bg-[#F1F2CF] text-[#545523] font-semibold' : 'text-gray-600 hover:bg-[#F1F2CF] hover:text-[#545523]' }}">
            <i class="fa-solid fa-store w-5"></i>
            <span>Dashboard</span>
        </a>

        <a href="/merchant/upload-makanan" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->is('merchant/upload-makanan') ? 'bg-[#F1F2CF] text-[#545523] font-semibold' : 'text-gray-600 hover:bg-[#F1F2CF] hover:text-[#545523]' }}">
            <i class="fa-solid fa-cloud-arrow-up w-5"></i>
            <span>Upload Makanan</span>
        </a>

        <a href="/merchant/klaim-masuk" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->is('merchant/klaim-masuk') ? 'bg-[#F1F2CF] text-[#545523] font-semibold' : 'text-gray-600 hover:bg-[#F1F2CF] hover:text-[#545523]' }}">
            <i class="fa-solid fa-bell w-5"></i>
            <span>Klaim Masuk</span>
        </a>

        <a href="/merchant/produk-aktif" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->is('merchant/produk-aktif') ? 'bg-[#F1F2CF] text-[#545523] font-semibold' : 'text-gray-600 hover:bg-[#F1F2CF] hover:text-[#545523]' }}">
            <i class="fa-solid fa-boxes-stacked w-5"></i>
            <span>Produk Aktif</span>
        </a>

        <div class="pt-4 my-4 border-t border-gray-100">
            <a href="/dashboard" class="flex items-center gap-3 px-4 py-3 rounded-xl transition bg-amber-500 text-white font-semibold hover:bg-amber-600 shadow-sm">
                <i class="fa-solid fa-arrow-left-long w-5"></i>
                <span>Menu Konsumen</span>
            </a>
        </div>
    </nav>
</aside>