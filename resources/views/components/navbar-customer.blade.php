<nav class="bg-white shadow-md p-4">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <button @click="sidebarOpen = true" class="lg:hidden">
                <i class="fa-solid fa-bars text-xl"></i>
            </button>
            <h2 class="text-xl font-semibold text-[#545523]">
                @yield('page_title', 'Dashboard') 
            </h2>
        </div>

        <div class="flex items-center gap-3">
            {{-- Notification --}}
            <a href="{{ route('customer.notifikasi') }}"
            class="relative flex items-center justify-center w-11 h-11 rounded-full border border-gray-200 bg-white hover:bg-gray-50">

                <i class="fa-solid fa-bell text-[#545523]"></i>

                @php
                    $jumlahNotif = \App\Models\Notification::where('user_id', auth()->id())
                        ->where('is_read', false)
                        ->count();
                @endphp

                @if($jumlahNotif > 0)
                    <span class="absolute -top-1 -right-1
                        bg-red-500 text-white text-[10px]
                        w-5 h-5 rounded-full flex items-center justify-center">
                        {{ $jumlahNotif }}
                    </span>
                @endif

            </a>
            <!-- Diarahkan ke route profile khusus User/Customer -->
            <a href="{{ route('profile') }}" class="flex items-center gap-3 border border-gray-200 rounded-full px-4 py-2 bg-white hover:bg-gray-50 transition">
                <img src="{{ asset('svg/user-icon.png') }}" alt="User" class="w-10 h-10 rounded-full object-cover">
                <div class="leading-tight">
                    <p class="text-xs text-gray-500">Hi,</p>
                    <p class="font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                </div>
            </a>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center justify-center w-11 h-11 rounded-full border border-red-200 bg-red-50 text-red-600 hover:bg-red-100">
                    <i class="fa-solid fa-right-from-bracket"></i>
                </button>
            </form>
        </div>
    </div>
</nav>