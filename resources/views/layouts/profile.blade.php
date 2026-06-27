<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>User Profile - SaveEat</title>
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
        
        <div class="flex items-center justify-between mb-8">
            <button @click="currentSubMenu === 'main_menu' ? window.history.back() : currentSubMenu = 'main_menu'" class="text-[#545523] hover:opacity-75 transition cursor-pointer">
                <i class="fa-solid fa-arrow-left text-xl"></i>
            </button>
            <h2 class="text-xl font-bold text-[#545523]">Profil Pengaturan</h2>
            <div class="w-6"></div> {{-- Spacer penyeimbang layout --}}
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
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

                    <div x-show="currentSubMenu === 'main_menu'" class="space-y-4">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider px-1">Pengaturan Akun</h4>
                        
                        <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden divide-y divide-gray-100">
                            
                            @if(Auth::user()->peran === 'konsumen')
                                <a href="{{ route('profile.edit') }}" class="flex items-center justify-between p-4 hover:bg-gray-50/80 transition group">
                                    <div class="flex items-center gap-4"><div class="w-9 h-9 rounded-xl bg-gray-50 flex items-center justify-center text-gray-500 group-hover:bg-[#F1F2CF] group-hover:text-[#545523] transition"><i class="fa-solid fa-user-pen text-sm"></i></div><span class="text-sm font-medium text-gray-700">Edit Profil</span></div>
                                    <i class="fa-solid fa-chevron-right text-gray-400 text-xs"></i>
                                </a>
                                <button @click="currentSubMenu = 'payment_customer'" class="w-full flex items-center justify-between p-4 hover:bg-gray-50/80 transition group text-left cursor-pointer">
                                    <div class="flex items-center gap-4"><div class="w-9 h-9 rounded-xl bg-gray-50 flex items-center justify-center text-gray-500 group-hover:bg-[#F1F2CF] group-hover:text-[#545523] transition"><i class="fa-solid fa-credit-card text-sm"></i></div><span class="text-sm font-medium text-gray-700">Metode Pembayaran</span></div>
                                    <i class="fa-solid fa-chevron-right text-gray-400 text-xs"></i>
                                </button>
                                <button @click="currentSubMenu = 'notification_settings'" class="w-full flex items-center justify-between p-4 hover:bg-gray-50/80 transition group text-left cursor-pointer">
                                    <div class="flex items-center gap-4"><div class="w-9 h-9 rounded-xl bg-gray-50 flex items-center justify-center text-gray-500 group-hover:bg-[#F1F2CF] group-hover:text-[#545523] transition"><i class="fa-solid fa-bell text-sm"></i></div><span class="text-sm font-medium text-gray-700">Notifikasi</span></div>
                                    <i class="fa-solid fa-chevron-right text-gray-400 text-xs"></i>
                                </button>
                            @endif

                            @if(Auth::user()->peran === 'merchant')
                                <button @click="currentSubMenu = 'info_merchant'" class="w-full flex items-center justify-between p-4 hover:bg-gray-50/80 transition group text-left cursor-pointer">
                                    <div class="flex items-center gap-4"><div class="w-9 h-9 rounded-xl bg-gray-50 flex items-center justify-center text-gray-500 group-hover:bg-[#F1F2CF] group-hover:text-[#545523] transition"><i class="fa-solid fa-store text-sm"></i></div><span class="text-sm font-medium text-gray-700">Informasi Merchant (Profil, Alamat, Deskripsi)</span></div>
                                    <i class="fa-solid fa-chevron-right text-gray-400 text-xs"></i>
                                </button>
                                <button @click="currentSubMenu = 'payment_merchant'" class="w-full flex items-center justify-between p-4 hover:bg-gray-50/80 transition group text-left cursor-pointer">
                                    <div class="flex items-center gap-4"><div class="w-9 h-9 rounded-xl bg-gray-50 flex items-center justify-center text-gray-500 group-hover:bg-[#F1F2CF] group-hover:text-[#545523] transition"><i class="fa-solid fa-wallet text-sm"></i></div><span class="text-sm font-medium text-gray-700">Pengaturan Pembayaran (Bank, Cashdraw)</span></div>
                                    <i class="fa-solid fa-chevron-right text-gray-400 text-xs"></i>
                                </button>
                                <button @click="currentSubMenu = 'notification_settings'" class="w-full flex items-center justify-between p-4 hover:bg-gray-50/80 transition group text-left cursor-pointer">
                                    <div class="flex items-center gap-4"><div class="w-9 h-9 rounded-xl bg-gray-50 flex items-center justify-center text-gray-500 group-hover:bg-[#F1F2CF] group-hover:text-[#545523] transition"><i class="fa-solid fa-bell text-sm"></i></div><span class="text-sm font-medium text-gray-700">Notifikasi</span></div>
                                    <i class="fa-solid fa-chevron-right text-gray-400 text-xs"></i>
                                </button>
                            @endif

                            @if(Auth::user()->peran === 'admin')
                                <a href="{{ route('profile.edit') }}" class="flex items-center justify-between p-4 hover:bg-gray-50/80 transition group">
                                    <div class="flex items-center gap-4"><div class="w-9 h-9 rounded-xl bg-gray-50 flex items-center justify-center text-gray-500 group-hover:bg-[#F1F2CF] group-hover:text-[#545523] transition"><i class="fa-solid fa-user-shield text-sm"></i></div><span class="text-sm font-medium text-gray-700">Edit Profil Admin</span></div>
                                    <i class="fa-solid fa-chevron-right text-gray-400 text-xs"></i>
                                </a>
                                <button @click="currentSubMenu = 'system_logs'" class="w-full flex items-center justify-between p-4 hover:bg-gray-50/80 transition group text-left cursor-pointer">
                                    <div class="flex items-center gap-4"><div class="w-9 h-9 rounded-xl bg-gray-50 flex items-center justify-center text-gray-500 group-hover:bg-[#F1F2CF] group-hover:text-[#545523] transition"><i class="fa-solid fa-terminal text-sm"></i></div><span class="text-sm font-medium text-gray-700">Log Aktivitas Sistem</span></div>
                                    <i class="fa-solid fa-chevron-right text-gray-400 text-xs"></i>
                                </button>
                                <button @click="currentSubMenu = 'notification_settings'" class="w-full flex items-center justify-between p-4 hover:bg-gray-50/80 transition group text-left cursor-pointer">
                                    <div class="flex items-center gap-4"><div class="w-9 h-9 rounded-xl bg-gray-50 flex items-center justify-center text-gray-500 group-hover:bg-[#F1F2CF] group-hover:text-[#545523] transition"><i class="fa-solid fa-bell text-sm"></i></div><span class="text-sm font-medium text-gray-700">Notifikasi Sistem</span></div>
                                    <i class="fa-solid fa-chevron-right text-gray-400 text-xs"></i>
                                </button>
                            @endif

                            <a href="{{ route('keamanan.index') }}" class="flex items-center justify-between p-4 hover:bg-gray-50/80 transition group">
                                <div class="flex items-center gap-4"><div class="w-9 h-9 rounded-xl bg-gray-50 flex items-center justify-center text-gray-500 group-hover:bg-[#F1F2CF] group-hover:text-[#545523] transition"><i class="fa-solid fa-shield-halved text-sm"></i></div><span class="text-sm font-medium text-gray-700">Privacy & Security</span></div>
                                <i class="fa-solid fa-chevron-right text-gray-400 text-xs"></i>
                            </a>

                        </div>

                        <div class="block lg:hidden pt-4">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center justify-center gap-2 bg-white border border-red-100 text-red-500 py-3 rounded-2xl font-semibold text-sm cursor-pointer shadow-2xs">
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>

                    <div x-show="currentSubMenu === 'payment_customer'" x-cloak class="bg-white rounded-2xl p-6 shadow-xs border border-gray-100 space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                            <h3 class="font-bold text-[#545523] text-sm md:text-base">Metode Pembayaran Saya</h3>
                            <button @click="currentSubMenu = 'main_menu'" class="text-xs text-[#545523] hover:underline font-medium cursor-pointer">Kembali</button>
                        </div>
                        <p class="text-xs text-gray-400 leading-relaxed">Kelola kartu kredit, debit, atau dompet digital E-Wallet Anda untuk bertransaksi di SaveEat.</p>
                        <div class="p-4 bg-gray-50 rounded-xl border border-dashed border-gray-200 flex items-center justify-center text-xs text-gray-500 font-medium">Belum ada metode pembayaran yang tertaut.</div>
                    </div>

                    <div x-show="currentSubMenu === 'notification_settings'" x-cloak class="bg-white rounded-2xl p-6 shadow-xs border border-gray-100 space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                            <h3 class="font-bold text-[#545523] text-sm md:text-base">Pengaturan Notifikasi</h3>
                            <button @click="currentSubMenu = 'main_menu'" class="text-xs text-[#545523] hover:underline font-medium cursor-pointer">Kembali</button>
                        </div>
                        <div class="space-y-3 divide-y divide-gray-50">
                            <div class="flex items-center justify-between pt-2">
                                <div><h5 class="text-xs font-bold text-gray-800">Email Marketing</h5><p class="text-[11px] text-gray-400">Terima pembaruan diskon mingguan.</p></div>
                                <input type="checkbox" class="accent-[#545523]" checked>
                            </div>
                            <div class="flex items-center justify-between pt-3">
                                <div><h5 class="text-xs font-bold text-gray-800">Push Notification Flash Sale</h5><p class="text-[11px] text-gray-400">Dapatkan alert makanan hampir habis terdekat.</p></div>
                                <input type="checkbox" class="accent-[#545523]" checked>
                            </div>
                        </div>
                    </div>

                    <div x-show="currentSubMenu === 'info_merchant'" x-cloak class="bg-white rounded-2xl p-6 shadow-xs border border-gray-100 space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                            <h3 class="font-bold text-[#545523] text-sm md:text-base">Informasi Toko / Merchant</h3>
                            <button @click="currentSubMenu = 'main_menu'" class="text-xs text-[#545523] hover:underline font-medium cursor-pointer">Kembali</button>
                        </div>
                        <form action="#" method="POST" class="space-y-3">
                            <div><label class="block text-[11px] font-semibold text-gray-500 mb-1">Nama Toko Mitra</label><input type="text" class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl p-2.5 focus:outline-[#545523]" value="{{ Auth::user()->profil->nama_usaha ?? '' }}"></div>
                            <div><label class="block text-[11px] font-semibold text-gray-500 mb-1">Alamat Operasional</label><textarea rows="2" class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl p-2.5 focus:outline-[#545523]">{{ Auth::user()->profil->alamat ?? '' }}</textarea></div>
                            <div><label class="block text-[11px] font-semibold text-gray-500 mb-1">Deskripsi Toko</label><textarea rows="2" class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl p-2.5 focus:outline-[#545523]">{{ Auth::user()->profil->deskripsi ?? '' }}</textarea></div>
                            <button type="button" class="w-full bg-[#545523] text-white text-xs py-2.5 rounded-xl font-semibold cursor-pointer">Simpan Info Toko</button>
                        </form>
                    </div>

                    <div x-show="currentSubMenu === 'payment_merchant'" x-cloak class="bg-white rounded-2xl p-6 shadow-xs border border-gray-100 space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                            <h3 class="font-bold text-[#545523] text-sm md:text-base">Metode Pencairan Dana Merchant</h3>
                            <button @click="currentSubMenu = 'main_menu'" class="text-xs text-[#545523] hover:underline font-medium cursor-pointer">Kembali</button>
                        </div>
                        <div class="space-y-2">
                            <div class="p-3 border border-gray-200 rounded-xl flex items-center justify-between">
                                <div class="flex items-center gap-3"><i class="fa-solid fa-bank text-gray-500 text-sm"></i><div><h5 class="text-xs font-bold text-gray-800">Rekening Bank Utama</h5><p class="text-[10px] text-gray-400">Pencairan otomatis setiap hari Jumat.</p></div></div>
                                <button class="text-[11px] text-[#545523] font-bold">Atur</button>
                            </div>
                            <div class="p-3 border border-gray-200 rounded-xl flex items-center justify-between">
                                <div class="flex items-center gap-3"><i class="fa-solid fa-cash-register text-gray-500 text-sm"></i><div><h5 class="text-xs font-bold text-gray-800">Cashdraw (Kas Fisik)</h5><p class="text-[10px] text-gray-400">Penerimaan pembayaran manual di toko.</p></div></div>
                                <input type="checkbox" class="accent-[#545523]" checked>
                            </div>
                        </div>
                    </div>

                    <div x-show="currentSubMenu === 'system_logs'" x-cloak class="bg-white rounded-2xl p-6 shadow-xs border border-gray-100 space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                            <h3 class="font-bold text-[#545523] text-sm md:text-base">Log Audit Aktivitas Sistem</h3>
                            <button @click="currentSubMenu = 'main_menu'" class="text-xs text-[#545523] hover:underline font-medium cursor-pointer">Kembali</button>
                        </div>
                        <div class="space-y-2 max-h-48 overflow-y-auto text-[11px] text-gray-600 font-mono divide-y divide-gray-50">
                            <div class="py-1.5"><span class="text-green-600">[INFO]</span> User ID 12 mengubah password - Jun 2026</div>
                            <div class="py-1.5"><span class="text-amber-600">[WARN]</span> Merchant ID 2 gagal verifikasi berkas - Jun 2026</div>
                        </div>
                    </div>

                @endif
            </div>

        </div>
    </div>

</body>
</html>