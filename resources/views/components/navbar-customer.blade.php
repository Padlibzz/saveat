<nav class="bg-white shadow-sm p-4 mb-6 md:mb-8 border-b border-gray-100">
    <div class="flex items-center justify-between w-full">
        
        {{-- Bagian Kiri: Tombol Menu Mobile & Judul Halaman --}}
        <div class="flex items-center gap-3">
            <button @click="sidebarOpen = true" class="lg:hidden text-[#545523] hover:text-[#7A7A33] transition cursor-pointer">
                <i class="fa-solid fa-bars text-xl"></i>
            </button>
            <h2 class="text-xl md:text-2xl font-extrabold text-[#545523]">
                @yield('page_title', 'Dashboard') 
            </h2>
        </div>

        {{-- Bagian Kanan: Notifikasi, Profil, & Logout --}}
        <div class="flex items-center gap-3 md:gap-4">
            
            {{-- Tombol Notifikasi (Diambil dari branch main) --}}
            <a href="{{ route('customer.notifikasi') }}"
               class="relative flex items-center justify-center w-10 h-10 md:w-11 md:h-11 rounded-full border border-gray-200 bg-white hover:bg-gray-50 transition-all shadow-sm cursor-pointer">
                
                <i class="fa-solid fa-bell text-[#545523] text-sm md:text-base"></i>

                @php
                    $jumlahNotif = \App\Models\Notification::where('user_id', auth()->id())
                        ->where('is_read', false)
                        ->count();
                @endphp

                @if($jumlahNotif > 0)
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] w-5 h-5 rounded-full flex items-center justify-center font-bold shadow-sm">
                        {{ $jumlahNotif > 99 ? '99+' : $jumlahNotif }}
                    </span>
                @endif
            </a>

            {{-- Tombol Profil --}}
            <a href="{{ route('profile') }}" class="flex items-center gap-2.5 bg-white border border-[#545523]/10 rounded-full pl-2 pr-4 py-1.5 shadow-sm hover:shadow-md transition-all cursor-pointer">
                <img src="{{ asset('svg/user-icon.png') }}" alt="User" class="w-8 h-8 md:w-9 md:h-9 rounded-full object-cover border border-gray-100">
                
                {{-- Nama User (Disembunyikan di layar sangat kecil) --}}
                <div class="leading-tight hidden sm:block">
                    <p class="text-[10px] text-gray-500 font-medium">Hi,</p>
                    <p class="text-xs font-bold text-[#545523]">{{ Auth::user()->name }}</p>
                </div>
            </a>

            {{-- Form & Tombol Logout --}}
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="flex items-center justify-center w-10 h-10 md:w-11 md:h-11 rounded-full border border-red-200 bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-all shadow-sm cursor-pointer" title="Keluar">
                    <i class="fa-solid fa-right-from-bracket text-sm md:text-base"></i>
                </button>
            </form>
            
        </div>
    </div>
</nav>