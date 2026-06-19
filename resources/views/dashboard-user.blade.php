<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>User Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
    </style>
</head>
<body
    x-data="{ sidebarOpen: false }"
    class="bg-gradient-to-r from-[#CFD086] to-[#F1F2CF] min-h-screen font-[Poppins]">

    <div class="flex min-h-screen">

        <!-- Overlay Mobile -->
        <div
            x-show="sidebarOpen"
            @click="sidebarOpen = false"
            x-cloak
            class="fixed inset-0 bg-black/50 z-40 lg:hidden">
        </div>

        <!-- Sidebar -->
        <aside
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed lg:static top-0 left-0 z-50 w-64 h-screen bg-white shadow-lg transition-transform duration-300 lg:translate-x-0">

            <div class="p-6">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('svg/logo.png') }}"
                        alt="SaveEat"
                        class="w-10 h-10">

                    <h1 class="text-3xl font-bold text-[#545523]">
                        SaveEat
                    </h1>
                </div>
            </div>

            <nav class="p-4 space-y-2">

                <a href="#"
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

        <!-- Main Content -->
        <div class="flex-1">

            <!-- Navbar -->
            <nav class="bg-white shadow-md p-4">

                <div class="flex items-center justify-between">

                    <div class="flex items-center gap-3">

                        <button
                            @click="sidebarOpen = true"
                            class="lg:hidden">

                            <i class="fa-solid fa-bars text-xl"></i>

                        </button>

                        <h2 class="text-xl font-semibold text-[#545523]">
                            Dashboard
                        </h2>

                    </div>

                    <div class="flex items-center gap-3">

                        <a href="{{ route('profile') }}" class="flex items-center gap-3 border border-gray-200 rounded-full px-4 py-2 bg-white hover:bg-gray-50 transition">
                            <img
                                src="{{ asset('svg/user-icon.png') }}"
                                alt="User"
                                class="w-10 h-10 rounded-full object-cover">

                            <div class="leading-tight">
                                <p class="text-xs text-gray-500">Hi,</p>
                                <p class="font-semibold text-gray-800">
                                    {{ Auth::user()->name }}
                                </p>
                            </div>
                        </a>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf

                            <button
                                type="submit"
                                class="flex items-center justify-center w-11 h-11 rounded-full border border-red-200 bg-red-50 text-red-600 hover:bg-red-100">

                                <i class="fa-solid fa-right-from-bracket"></i>

                            </button>

                        </form>

                    </div>

                </div>

            </nav>

            <!-- Content -->
            <section class="p-6">

                <!-- Search -->
                <input
                    type="text"
                    placeholder="Temukan Makananmu"
                    class="w-full p-4 rounded-full shadow-md bg-white outline-none focus:ring-2 focus:ring-[#6D6B2E]">

                <!-- Recommendation -->
                <div class="mt-8">

                    <h2 class="text-2xl font-semibold text-[#545523] mb-5">
                        Rekomendasi Untukmu
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

                        {{-- FOOD CARD --}}
                        {{-- Ganti dengan food-card milik kalian --}}

                        {{-- @foreach($recommendations as $food)
                            @include('components.food-card', ['food' => $food])
                        @endforeach --}}

                    </div>

                </div>

                <!-- Join Merchant -->
                @if(Auth::user()->peran !== 'merchant')

                <div class="mt-10 bg-white rounded-3xl shadow-md p-6">

                    <h3 class="text-xl font-semibold text-[#545523]">
                        Mulai Jual Makanan Anda
                    </h3>

                    <p class="text-gray-500 mt-2">
                        Lengkapi data usaha Anda dan ajukan verifikasi merchant.
                    </p>

                    <a
                        href="{{ route('merchant.application') }}"
                        class="inline-block mt-4 bg-[#6D6B2E] text-white px-6 py-3 rounded-xl hover:bg-[#545523]">

                        Daftar Merchant

                    </a>

                </div>

                @endif

            </section>

        </div>

    </div>

</body>
</html>