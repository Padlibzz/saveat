@section('page_title', 'Aktivitas & Pesanan')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Aktivitas & Pesanan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
    </style>
</head>
<body
    x-data="{ sidebarOpen: false }"
    class="bg-gradient-to-r from-[#CFD086] to-[#F1F2CF] min-h-screen font-[Poppins] flex">

    <x-sidebar />

    <div class="flex-1 flex flex-col min-h-screen">

        <x-navbar />

        <section class="p-6 mb-10 lg:ml-64 flex-1">
            @if(session('success'))
                <x-alert type="success" :message="session('success')" />
            @endif

            @if(session('error'))
                <x-alert type="error" :message="session('error')" />
            @endif

            {{-- BUNGKUSAN UTAMA PESANAN --}}
            <div class="w-full max-w-4xl" x-data="{ filterTab: 'aktif' }">
                
                {{-- JUDUL HALAMAN & NAVIGASI TAB --}}
                <div class="mb-6 border-b border-[#545523]/10 pb-4">
                    <h2 class="text-2xl font-semibold text-[#545523]">Aktivitas & Riwayat Pesanan</h2>
                    <p class="text-sm text-gray-600 mt-1">Pantau terus status klaim makanan penyelamatmu dan tumpukan kontribusi lingkunganmu.</p>
                    
                    {{-- Tabs Filter --}}
                    <div class="flex items-center gap-4 mt-5">
                        <button @click="filterTab = 'aktif'" 
                                :class="filterTab === 'aktif' ? 'bg-[#545523] text-white' : 'bg-white text-gray-500 border-gray-200 hover:bg-gray-50'" 
                                class="px-5 py-2 rounded-full text-xs font-bold border transition-all shadow-sm cursor-pointer">
                            Pesanan Aktif
                        </button>
                        <button @click="filterTab = 'selesai'" 
                                :class="filterTab === 'selesai' ? 'bg-[#545523] text-white' : 'bg-white text-gray-500 border-gray-200 hover:bg-gray-50'" 
                                class="px-5 py-2 rounded-full text-xs font-bold border transition-all shadow-sm cursor-pointer">
                            Selesai
                        </button>
                        <button @click="filterTab = 'batal'" 
                                :class="filterTab === 'batal' ? 'bg-[#545523] text-white' : 'bg-white text-gray-500 border-gray-200 hover:bg-gray-50'" 
                                class="px-5 py-2 rounded-full text-xs font-bold border transition-all shadow-sm cursor-pointer">
                            Dibatalkan
                        </button>
                    </div>
                </div>

                {{-- KONTENER DAFTAR PESANAN --}}
                <div class="space-y-4">
                    
                    {{-- ========================================== --}}
                    {{-- 1. BLOK PESANAN AKTIF (Dari DB: $claims)   --}}
                    {{-- ========================================== --}}
                    @forelse($claims as $pesanan)
                        <div x-show="filterTab === 'aktif'" 
                             x-transition.opacity
                             class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col gap-4 relative overflow-hidden">
                            
                            {{-- Indikator Garis Samping --}}
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-amber-400"></div>

                            <div class="flex items-center justify-between pb-3 border-b border-gray-50 pl-2">
                                <div class="flex items-center gap-3">
                                    <i class="fa-solid fa-clock text-amber-500 text-sm"></i>
                                    <span class="text-xs font-bold text-gray-700">Berlangsung • {{ $pesanan->created_at->diffForHumans() }}</span>
                                </div>
                                <span class="px-3 py-1 bg-amber-50 text-amber-600 border border-amber-200 rounded-lg text-[10px] font-bold uppercase tracking-wider">
                                    {{ str_replace('_', ' ', $pesanan->status) }}
                                </span>
                            </div>

                            <div class="flex items-start gap-4 pl-2">
                                <div class="w-16 h-16 md:w-20 md:h-20 rounded-xl overflow-hidden shrink-0 border border-gray-100 shadow-sm">
                                    @if(optional($pesanan->listing)->foto)
                                        <img src="{{ asset('storage/' . $pesanan->listing->foto) }}" alt="{{ optional($pesanan->listing)->nama }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-[#545523] flex items-center justify-center">
                                            <i class="fa-solid fa-utensils text-white/50 text-2xl"></i>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-xs font-bold text-gray-500 mb-1">{{ optional(optional($pesanan->listing)->merchant)->nama_usaha ?? 'Mitra Tidak Ditemukan' }}</h4>
                                    <p class="text-sm md:text-base font-bold text-[#545523] leading-tight">{{ $pesanan->jumlah }}x {{ optional($pesanan->listing)->nama ?? 'Produk Tidak Ditemukan' }}</p>
                                    <p class="text-[10px] text-gray-400 mt-2 font-medium">Order ID: {{ $pesanan->kode_klaim }}</p>
                                </div>

                                <div class="text-right border-l border-gray-50 pl-4 hidden md:block">
                                    <span class="text-xs text-gray-500 block mb-1">Total Tagihan</span>
                                    <span class="text-base font-extrabold text-[#545523]">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-3 border-t border-gray-50 pl-2">
                                <div class="md:hidden">
                                    <span class="text-[10px] text-gray-500 block">Total Tagihan</span>
                                    <span class="text-sm font-extrabold text-[#545523]">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex items-center gap-2 w-full justify-end">
                                    {{-- PERUBAHAN DI SINI: Tombol untuk pesanan aktif mengarah ke halaman QR --}}
                                    <a href="{{ route('claim.success', $pesanan->id) }}" class="px-4 py-2 text-xs font-bold text-[#545523] bg-white border border-[#545523] rounded-lg hover:bg-gray-50 transition cursor-pointer inline-block text-center">
                                        <i class="fa-solid fa-qrcode mr-1"></i> Lihat QR Code
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div x-show="filterTab === 'aktif'" class="py-12 text-center flex flex-col items-center justify-center bg-white rounded-2xl border border-gray-100 shadow-sm">
                            <i class="fa-solid fa-box-open text-gray-300 text-4xl mb-3"></i>
                            <h3 class="text-sm font-bold text-gray-500">Belum Ada Pesanan Aktif</h3>
                            <p class="text-xs text-gray-400 mt-1">Cari makanan penyelamat dan mulai berkontribusi hari ini.</p>
                        </div>
                    @endforelse

                    {{-- ========================================== --}}
                    {{-- 2. BLOK RIWAYAT (Dari DB: $riwayatPesanan) --}}
                    {{-- ========================================== --}}
                    @forelse($riwayatPesanan as $pesanan)
                        @php
                            $statusTab = $pesanan->status === 'diambil' ? 'selesai' : 'batal';
                        @endphp

                        <div x-show="filterTab === '{{ $statusTab }}'" 
                             x-transition.opacity
                             class="bg-white/80 backdrop-blur-sm p-5 rounded-2xl border border-white/50 shadow-sm flex flex-col gap-4">
                            
                            <div class="flex items-center justify-between pb-3 border-b border-gray-100/50">
                                <div class="flex items-center gap-3">
                                    <i class="fa-solid fa-bag-shopping text-[#545523] text-sm"></i>
                                    <span class="text-xs font-bold text-gray-700">Belanja • {{ $pesanan->updated_at->translatedFormat('d M Y, H:i WIB') }}</span>
                                </div>
                                
                                @if($pesanan->status === 'diambil')
                                    <span class="px-3 py-1 bg-emerald-50 text-emerald-600 border border-emerald-200 rounded-lg text-[10px] font-bold uppercase tracking-wider">
                                        Selesai
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-red-50 text-red-600 border border-red-200 rounded-lg text-[10px] font-bold uppercase tracking-wider">
                                        Dibatalkan
                                    </span>
                                @endif
                            </div>

                            <div class="flex items-start gap-4">
                                <div class="w-16 h-16 md:w-20 md:h-20 rounded-xl overflow-hidden shrink-0 border border-gray-200 shadow-sm">
                                    @if(optional($pesanan->listing)->foto)
                                        <img src="{{ asset('storage/' . $pesanan->listing->foto) }}" alt="{{ optional($pesanan->listing)->nama }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-[#545523] to-[#7A7A33] flex items-center justify-center">
                                            <i class="fa-solid fa-utensils text-white/50 text-2xl"></i>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-xs font-bold text-gray-500 mb-1">{{ optional(optional($pesanan->listing)->merchant)->nama_usaha ?? 'Mitra Tidak Ditemukan' }}</h4>
                                    <p class="text-sm md:text-base font-bold text-gray-800 leading-tight">{{ $pesanan->jumlah }}x {{ optional($pesanan->listing)->nama ?? 'Produk Tidak Ditemukan' }}</p>
                                    <p class="text-[10px] text-gray-400 mt-2 font-medium">Order ID: {{ $pesanan->kode_klaim }}</p>
                                </div>

                                <div class="text-right border-l border-gray-100/50 pl-4 hidden md:block">
                                    <span class="text-xs text-gray-500 block mb-1">Total Belanja</span>
                                    <span class="text-base font-extrabold text-[#545523]">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-3 border-t border-gray-100/50">
                                <div class="md:hidden">
                                    <span class="text-[10px] text-gray-500 block">Total Belanja</span>
                                    <span class="text-sm font-extrabold text-[#545523]">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                                </div>
                                
                                <div class="flex items-center gap-2 w-full justify-end">
                                    {{-- PERUBAHAN DI SINI: Tombol untuk riwayat bisa diarahkan ke detail atau QR jika masih diperlukan --}}
                                    <a href="{{ route('pesanan.detail', $pesanan->id) }}" class="px-4 py-2 text-xs font-bold text-[#545523] bg-white border border-[#545523] rounded-lg hover:bg-gray-50 transition cursor-pointer inline-block text-center">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                            
                        </div>
                    @empty
                        <div x-show="filterTab === 'selesai' || filterTab === 'batal'" class="py-12 text-center flex flex-col items-center justify-center bg-white rounded-2xl border border-gray-100 shadow-sm">
                            <i class="fa-solid fa-receipt text-gray-300 text-4xl mb-3"></i>
                            <h3 class="text-sm font-bold text-gray-500">Belum Ada Riwayat</h3>
                            <p class="text-xs text-gray-400 mt-1">Data pesanan yang selesai atau dibatalkan akan muncul di sini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>
    </div>
</body>
</html>