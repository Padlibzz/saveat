<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>User Profile Pengaturan - SaveEat</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Poppins', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-[#E2E4E1] min-h-screen">

    <div class="p-4 md:p-8 max-w-6xl mx-auto" x-data="{ currentSubMenu: 'main_menu' }">
        
        <div class="flex items-center justify-between mb-6 md:mb-8">
            <button @click="currentSubMenu === 'main_menu' ? window.history.back() : currentSubMenu = 'main_menu'" class="text-[#545523] hover:opacity-75 transition cursor-pointer focus:outline-none">
                <i class="fa-solid fa-arrow-left text-xl"></i>
            </button>
            <h2 class="text-xl font-bold text-[#545523]">Profil Pengaturan</h2>
            <div class="w-6"></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">
            
            <div class="flex flex-col items-center bg-white p-6 rounded-2xl shadow-xs border border-gray-100 h-fit"
                 :class="{ 'hidden lg:flex': currentSubMenu !== 'main_menu' }">
                <div class="relative w-28 h-28 mb-4">
                    <img src="{{ Auth::user()->profil_image ? asset('storage/' . Auth::user()->profil_image) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=F1F2CF&color=545523' }}"
                         alt="Foto Profil"
                         class="w-full h-full object-cover rounded-full border-2 border-gray-200">
                </div>
                <h3 class="text-lg font-bold text-gray-800 text-center">Halo, {{ Auth::user()->name }}!</h3>
                <p class="text-xs text-gray-400 text-center mt-0.5">{{ Auth::user()->email }}</p>

                <span class="mt-3 px-3 py-1 text-[10px] font-bold uppercase tracking-wider rounded-full 
                    {{ Auth::user()->peran === 'admin' ? 'bg-red-100 text-red-700' : (Auth::user()->peran === 'merchant' ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700') }}">
                    Peran: {{ Auth::user()->peran }}
                </span>

                <div class="hidden lg:block w-full mt-8">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center gap-2 border border-red-200 text-red-500 py-2.5 rounded-xl hover:bg-red-50 transition text-sm font-medium cursor-pointer">
                            <i class="fa-solid fa-right-from-bracket text-xs"></i>
                            Keluar Akun
                        </button>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-2">
                
                @if(Route::currentRouteName() === 'keamanan.index')
                    @yield('profile_content')
                @else

                    <div x-show="currentSubMenu === 'main_menu'" class="space-y-6">
                        
                        <div class="space-y-3">
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider px-1">Pengaturan Akun</h4>
                            <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden divide-y divide-gray-100">
                                
                                @if(Auth::user()->peran === 'konsumen')
                                    <a href="{{ route('profile.edit') }}" class="flex items-center justify-between p-4 hover:bg-gray-50/80 transition group">
                                        <div class="flex items-center gap-4"><div class="w-9 h-9 rounded-xl bg-gray-50 flex items-center justify-center text-gray-500 group-hover:bg-[#F1F2CF] group-hover:text-[#545523] transition"><i class="fa-solid fa-user-pen text-sm"></i></div><span class="text-sm font-medium text-gray-700">Edit Profil</span></div>
                                        <i class="fa-solid fa-chevron-right text-gray-400 text-xs"></i>
                                    </a>
                                    <button @click="currentSubMenu = 'payment_customer'" class="w-full flex items-center justify-between p-4 hover:bg-gray-50/80 transition group text-left cursor-pointer focus:outline-none">
                                        <div class="flex items-center gap-4"><div class="w-9 h-9 rounded-xl bg-gray-50 flex items-center justify-center text-gray-500 group-hover:bg-[#F1F2CF] group-hover:text-[#545523] transition"><i class="fa-solid fa-credit-card text-sm"></i></div><span class="text-sm font-medium text-gray-700">Metode Pembayaran</span></div>
                                        <i class="fa-solid fa-chevron-right text-gray-400 text-xs"></i>
                                    </button>
                                    <button @click="currentSubMenu = 'notification_settings'" class="w-full flex items-center justify-between p-4 hover:bg-gray-50/80 transition group text-left cursor-pointer focus:outline-none">
                                        <div class="flex items-center gap-4"><div class="w-9 h-9 rounded-xl bg-gray-50 flex items-center justify-center text-gray-500 group-hover:bg-[#F1F2CF] group-hover:text-[#545523] transition"><i class="fa-solid fa-bell text-sm"></i></div><span class="text-sm font-medium text-gray-700">Notifikasi</span></div>
                                        <i class="fa-solid fa-chevron-right text-gray-400 text-xs"></i>
                                    </button>
                                @endif

                                @if(Auth::user()->peran === 'merchant')
                                    <button @click="currentSubMenu = 'info_merchant'" class="w-full flex items-center justify-between p-4 hover:bg-gray-50/80 transition group text-left cursor-pointer focus:outline-none">
                                        <div class="flex items-center gap-4"><div class="w-9 h-9 rounded-xl bg-gray-50 flex items-center justify-center text-gray-500 group-hover:bg-[#F1F2CF] group-hover:text-[#545523] transition"><i class="fa-solid fa-store text-sm"></i></div><span class="text-sm font-medium text-gray-700">Informasi Merchant (Profil, Alamat, Deskripsi)</span></div>
                                        <i class="fa-solid fa-chevron-right text-gray-400 text-xs"></i>
                                    </button>
                                    <button @click="currentSubMenu = 'payment_merchant'" class="w-full flex items-center justify-between p-4 hover:bg-gray-50/80 transition group text-left cursor-pointer focus:outline-none">
                                        <div class="flex items-center gap-4"><div class="w-9 h-9 rounded-xl bg-gray-50 flex items-center justify-center text-gray-500 group-hover:bg-[#F1F2CF] group-hover:text-[#545523] transition"><i class="fa-solid fa-wallet text-sm"></i></div><span class="text-sm font-medium text-gray-700">Pengaturan Pembayaran</span></div>
                                        <i class="fa-solid fa-chevron-right text-gray-400 text-xs"></i>
                                    </button>
                                @endif

                                @if(Auth::user()->peran === 'admin')
                                    <a href="{{ route('profile.edit') }}" class="flex items-center justify-between p-4 hover:bg-gray-50/80 transition group">
                                        <div class="flex items-center gap-4"><div class="w-9 h-9 rounded-xl bg-gray-50 flex items-center justify-center text-gray-500 group-hover:bg-[#F1F2CF] group-hover:text-[#545523] transition"><i class="fa-solid fa-user-shield text-sm"></i></div><span class="text-sm font-medium text-gray-700">Edit Profil Admin</span></div>
                                        <i class="fa-solid fa-chevron-right text-gray-400 text-xs"></i>
                                    </a>
                                    <button @click="currentSubMenu = 'system_logs'" class="w-full flex items-center justify-between p-4 hover:bg-gray-50/80 transition group text-left cursor-pointer focus:outline-none">
                                        <div class="flex items-center gap-4"><div class="w-9 h-9 rounded-xl bg-gray-50 flex items-center justify-center text-gray-500 group-hover:bg-[#F1F2CF] group-hover:text-[#545523] transition"><i class="fa-solid fa-terminal text-sm"></i></div><span class="text-sm font-medium text-gray-700">Log Aktivitas Sistem</span></div>
                                        <i class="fa-solid fa-chevron-right text-gray-400 text-xs"></i>
                                    </button>
                                @endif

                                <a href="{{ route('keamanan.index') }}" class="flex items-center justify-between p-4 hover:bg-gray-50/80 transition group">
                                    <div class="flex items-center gap-4"><div class="w-9 h-9 rounded-xl bg-gray-50 flex items-center justify-center text-gray-500 group-hover:bg-[#F1F2CF] group-hover:text-[#545523] transition"><i class="fa-solid fa-shield-halved text-sm"></i></div><span class="text-sm font-medium text-gray-700">Privacy & Security</span></div>
                                    <i class="fa-solid fa-chevron-right text-gray-400 text-xs"></i>
                                </a>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider px-1">Bantuan & Dukungan</h4>
                            <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden divide-y divide-gray-100">
                                <button @click="currentSubMenu = 'faq_center'" class="w-full flex items-center justify-between p-4 hover:bg-gray-50/80 transition group text-left cursor-pointer focus:outline-none">
                                    <div class="flex items-center gap-4"><div class="w-9 h-9 rounded-xl bg-gray-50 flex items-center justify-center text-gray-500 group-hover:bg-[#F1F2CF] group-hover:text-[#545523] transition"><i class="fa-solid fa-circle-question text-sm"></i></div><span class="text-sm font-medium text-gray-700">Pusat Bantuan (FAQ)</span></div>
                                    <i class="fa-solid fa-chevron-right text-gray-400 text-xs"></i>
                                </button>
                                <button @click="currentSubMenu = 'support_inbox'" class="w-full flex items-center justify-between p-4 hover:bg-gray-50/80 transition group text-left cursor-pointer focus:outline-none">
                                    <div class="flex items-center gap-4"><div class="w-9 h-9 rounded-xl bg-gray-50 flex items-center justify-center text-gray-500 group-hover:bg-[#F1F2CF] group-hover:text-[#545523] transition"><i class="fa-solid fa-inbox text-sm"></i></div><span class="text-sm font-medium text-gray-700">Kotak Masuk Dukungan</span></div>
                                    <i class="fa-solid fa-chevron-right text-gray-400 text-xs"></i>
                                </button>
                                <button @click="currentSubMenu = 'report_problem'" class="w-full flex items-center justify-between p-4 hover:bg-gray-50/80 transition group text-left cursor-pointer focus:outline-none">
                                    <div class="flex items-center gap-4"><div class="w-9 h-9 rounded-xl bg-gray-50 flex items-center justify-center text-gray-500 group-hover:bg-[#F1F2CF] group-hover:text-[#545523] transition"><i class="fa-solid fa-triangle-exclamation text-sm"></i></div><span class="text-sm font-medium text-gray-700 text-red-600 font-semibold">Laporkan Masalah</span></div>
                                    <i class="fa-solid fa-chevron-right text-gray-400 text-xs"></i>
                                </button>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider px-1">Preferensi</h4>
                            <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden divide-y divide-gray-100">
                                <button @click="currentSubMenu = 'language_settings'" class="w-full flex items-center justify-between p-4 hover:bg-gray-50/80 transition group text-left cursor-pointer focus:outline-none">
                                    <div class="flex items-center gap-4"><div class="w-9 h-9 rounded-xl bg-gray-50 flex items-center justify-center text-gray-500 group-hover:bg-[#F1F2CF] group-hover:text-[#545523] transition"><i class="fa-solid fa-globe text-sm"></i></div><span class="text-sm font-medium text-gray-700">Bahasa / Language</span></div>
                                    <div class="flex items-center gap-2"><span class="text-xs text-gray-400">Indonesia (ID)</span><i class="fa-solid fa-chevron-right text-gray-400 text-xs"></i></div>
                                </button>
                            </div>
                        </div>

                        <div class="block lg:hidden pt-2">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center justify-center gap-2 bg-white border border-red-100 text-red-500 py-3 rounded-2xl font-semibold text-sm cursor-pointer shadow-2xs">
                                    Keluar Akun
                                </button>
                            </form>
                        </div>
                    </div>

                    <div x-show="currentSubMenu === 'faq_center'" x-cloak class="bg-white rounded-2xl p-5 md:p-6 shadow-xs border border-gray-100 space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                            <h3 class="font-bold text-[#545523] text-sm md:text-base">Pusat Bantuan (FAQ)</h3>
                            <button @click="currentSubMenu = 'main_menu'" class="text-xs text-[#545523] hover:underline font-medium cursor-pointer">Kembali</button>
                        </div>
                        <div class="space-y-2 text-xs text-gray-600" x-data="{ openFaq: null }">
                            <div class="border border-gray-100 rounded-xl p-3 cursor-pointer" @click="openFaq = openFaq === 1 ? null : 1">
                                <div class="flex justify-between font-semibold text-gray-800"><span>Bagaimana cara menyelamatkan makanan?</span><i class="fa-solid text-[10px]" :class="openFaq === 1 ? 'fa-chevron-up' : 'fa-chevron-down'"></i></div>
                                <p x-show="openFaq === 1" class="mt-2 text-gray-500 leading-relaxed">Cari listing makanan terdekat dari dashboard, lakukan checkout, dan ambil sebelum batas waktu berakhir.</p>
                            </div>
                        </div>
                    </div>

                    <div x-show="currentSubMenu === 'support_inbox'" x-cloak class="bg-white rounded-2xl p-5 md:p-6 shadow-xs border border-gray-100 space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                            <h3 class="font-bold text-[#545523] text-sm md:text-base">Kotak Masuk Tiket</h3>
                            <button @click="currentSubMenu = 'main_menu'" class="text-xs text-[#545523] hover:underline font-medium cursor-pointer">Kembali</button>
                        </div>
                        <p class="text-xs text-gray-400">Pesan dan respon bantuan teknis dari tim support SaveEat.</p>
                        <div class="p-8 text-center text-xs text-gray-400 border border-dashed border-gray-200 rounded-xl">Tidak ada tiket aktif saat ini.</div>
                    </div>

                    <div x-show="currentSubMenu === 'report_problem'" x-cloak class="bg-white rounded-2xl p-5 md:p-6 shadow-xs border border-gray-100 space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                            <h3 class="font-bold text-red-600 text-sm md:text-base">Laporkan Masalah / Bug</h3>
                            <button @click="currentSubMenu = 'main_menu'" class="text-xs text-[#545523] hover:underline font-medium cursor-pointer">Kembali</button>
                        </div>
                        <form class="space-y-3">
                            <div><label class="block text-[11px] font-semibold text-gray-500 mb-1">Judul Kendala</label><input type="text" class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl p-2.5 focus:outline-red-500" placeholder="Contoh: Gagal checkout e-wallet"></div>
                            <div><label class="block text-[11px] font-semibold text-gray-500 mb-1">Detail Masalah</label><textarea rows="3" class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl p-2.5 focus:outline-red-500" placeholder="Deskripsikan kendala yang dihadapi..."></textarea></div>
                            <button type="button" class="w-full bg-red-600 text-white text-xs py-2.5 rounded-xl font-bold cursor-pointer hover:bg-red-700 transition">Kirim Laporan</button>
                        </form>
                    </div>

                    <div x-show="currentSubMenu === 'language_settings'" x-cloak class="bg-white rounded-2xl p-5 md:p-6 shadow-xs border border-gray-100 space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                            <h3 class="font-bold text-[#545523] text-sm md:text-base">Pilih Bahasa (Language)</h3>
                            <button @click="currentSubMenu = 'main_menu'" class="text-xs text-[#545523] hover:underline font-medium cursor-pointer">Kembali</button>
                        </div>
                        <div class="space-y-2">
                            <label class="flex items-center justify-between p-3 bg-gray-50 rounded-xl cursor-pointer"><span class="text-xs font-semibold text-gray-800">Bahasa Indonesia</span><input type="radio" name="lang" checked class="accent-[#545523]"></label>
                            <label class="flex items-center justify-between p-3 rounded-xl cursor-pointer hover:bg-gray-50"><span class="text-xs font-semibold text-gray-800">English (US)</span><input type="radio" name="lang" class="accent-[#545523]"></label>
                        </div>
                    </div>

                    {{-- SUB-MENU BAWAAN SEBELUMNYA (Payment, Notif, Info Merchant) TETAP BERJALAN SEMPURNA DI SINI --}}
                    <div x-show="currentSubMenu === 'payment_customer'" x-cloak class="bg-white rounded-2xl p-5 shadow-xs border border-gray-100 space-y-3"><div class="flex justify-between border-b pb-2"><h3 class="font-bold text-sm text-[#545523]">Metode Pembayaran</h3><button @click="currentSubMenu = 'main_menu'" class="text-xs text-gray-400">Kembali</button></div><p class="text-xs text-gray-400">Belum ada kartu atau e-wallet tertaut.</p></div>
                    <div x-show="currentSubMenu === 'notification_settings'" x-cloak class="bg-white rounded-2xl p-5 shadow-xs border border-gray-100 space-y-3"><div class="flex justify-between border-b pb-2"><h3 class="font-bold text-sm text-[#545523]">Notifikasi Flash Sale</h3><button @click="currentSubMenu = 'main_menu'" class="text-xs text-gray-400">Kembali</button></div><p class="text-xs text-gray-500">Push Notification aktif.</p></div>
                    <div x-show="currentSubMenu === 'info_merchant'" x-cloak class="bg-white rounded-2xl p-5 shadow-xs border border-gray-100 space-y-3"><div class="flex justify-between border-b pb-2"><h3 class="font-bold text-sm text-[#545523]">Info Toko</h3><button @click="currentSubMenu = 'main_menu'" class="text-xs text-gray-400">Kembali</button></div><p class="text-xs text-gray-500">Kelola detail toko operasional.</p></div>

                @endif
            </div>

        </div>
    </div>

</body>
</html>