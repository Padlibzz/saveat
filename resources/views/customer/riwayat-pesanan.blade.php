@extends('layouts.dashboard')

@section('page_title', 'Riwayat Pesanan')

@section('content')
<div class="w-full max-w-4xl mx-auto" x-data="riwayatComponent()">
    
    {{-- ALERT MESSAGES --}}
    @if(session('success'))
        <x-alert type="success" :message="session('success')" />
    @endif

    @if(session('error'))
        <x-alert type="error" :message="session('error')" />
    @endif

    {{-- JUDUL HALAMAN & NAVIGASI TAB --}}
    <div class="mb-6 border-b border-[#545523]/10 pb-4">
        <h2 class="text-xl font-bold text-[#545523]">Riwayat Pesanan 🛍️</h2>
        <p class="text-xs text-gray-400 font-medium mt-1">Lihat kembali daftar makanan penyelamat yang pernah kamu beli.</p>
        
        {{-- Tabs Filter --}}
        <div class="flex items-center gap-4 mt-5">
            <button @click="filterTab = 'semua'" 
                    :class="filterTab === 'semua' ? 'bg-[#545523] text-white' : 'bg-white text-gray-500 border-gray-200 hover:bg-gray-50'" 
                    class="px-5 py-2 rounded-full text-xs font-bold border transition-all shadow-2xs cursor-pointer">
                Semua
            </button>
            <button @click="filterTab = 'selesai'" 
                    :class="filterTab === 'selesai' ? 'bg-[#545523] text-white' : 'bg-white text-gray-500 border-gray-200 hover:bg-gray-50'" 
                    class="px-5 py-2 rounded-full text-xs font-bold border transition-all shadow-2xs cursor-pointer">
                Selesai
            </button>
            <button @click="filterTab = 'batal'" 
                    :class="filterTab === 'batal' ? 'bg-[#545523] text-white' : 'bg-white text-gray-500 border-gray-200 hover:bg-gray-50'" 
                    class="px-5 py-2 rounded-full text-xs font-bold border transition-all shadow-2xs cursor-pointer">
                Dibatalkan
            </button>
        </div>
    </div>

    {{-- DAFTAR KARTU PESANAN DARI DATABASE --}}
    <div class="space-y-4">
        @forelse($claims as $pesanan)
            @php
                // Mapping status database ke nama tab & UI
                $statusTab = $pesanan->status === 'diambil' ? 'selesai' : 'batal';
                
                // Menyiapkan array khusus agar data bisa dibaca oleh Modal Alpine.js dengan rapi
                $modalData = [
                    'id' => $pesanan->kode_klaim,
                    'status' => $statusTab,
                    'tanggal' => $pesanan->updated_at->translatedFormat('d M Y, H:i WIB'),
                    'mitra' => optional(optional($pesanan->listing)->merchant)->nama_usaha ?? 'Mitra Tidak Ditemukan',
                    'menu' => $pesanan->jumlah . 'x ' . (optional($pesanan->listing)->nama ?? 'Produk Tidak Ditemukan'),
                    'subtotal' => $pesanan->total_harga, // Bisa diganti jika ada field harga dasar
                    'biaya_aplikasi' => 0, // Ganti dengan field biaya layanan jika ada di database
                    'total_harga' => $pesanan->total_harga,
                    'metode_pembayaran' => 'Sistem Online' // Ganti dengan field payment_method jika ada
                ];
            @endphp

            <div x-show="filterTab === 'semua' || filterTab === '{{ $statusTab }}'" 
                 x-transition.opacity
                 class="bg-[#FDFDF5] p-5 rounded-2xl border border-[#545523]/10 shadow-xs flex flex-col gap-4">
                
                {{-- Bagian Header Kartu --}}
                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-bag-shopping text-[#545523] text-sm"></i>
                        <span class="text-xs font-bold text-gray-700">Belanja • {{ $pesanan->updated_at->translatedFormat('d M Y, H:i WIB') }}</span>
                    </div>
                    
                    @if($statusTab === 'selesai')
                        <span class="px-3 py-1 bg-emerald-50 text-emerald-600 border border-emerald-200 rounded-lg text-[10px] font-bold uppercase tracking-wider">Selesai</span>
                    @else
                        <span class="px-3 py-1 bg-red-50 text-red-600 border border-red-200 rounded-lg text-[10px] font-bold uppercase tracking-wider">Dibatalkan</span>
                    @endif
                </div>

                {{-- Bagian Tengah --}}
                <div class="flex items-start gap-4">
                    <div class="w-16 h-16 md:w-20 md:h-20 rounded-xl overflow-hidden shrink-0 border border-gray-200 shadow-2xs">
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
                    <div class="text-right border-l border-gray-100 pl-4 hidden md:block">
                        <span class="text-xs text-gray-500 block mb-1">Total Belanja</span>
                        <span class="text-base font-extrabold text-[#545523]">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                    </div>
                </div>

                {{-- Bagian Footer Kartu --}}
                <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                    <div class="md:hidden">
                        <span class="text-[10px] text-gray-500 block">Total Belanja</span>
                        <span class="text-sm font-extrabold text-[#545523]">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                    </div>
                    
                    <div class="flex items-center gap-2 w-full justify-end">
                        {{-- TOMBOL TRIGGER MODAL --}}
                        {{-- Menggunakan @json agar parsing data string ke modal aman dari error tanda kutip --}}
                        <button @click='bukaModal(@json($modalData))' 
                                class="px-4 py-2 text-xs font-bold text-[#545523] bg-white border border-[#545523] rounded-lg hover:bg-[#545523]/5 transition cursor-pointer">
                            Lihat Detail
                        </button>
                        
                        @if($statusTab === 'selesai')
                            <button class="px-4 py-2 text-xs font-bold text-white bg-[#545523] rounded-lg hover:bg-[#43441c] shadow-xs transition cursor-pointer">
                                Beri Ulasan
                            </button>
                        @endif
                    </div>
                </div>
                
            </div>
        @empty
            <div class="py-12 text-center flex flex-col items-center justify-center bg-white rounded-2xl border border-gray-100 shadow-sm">
                <i class="fa-solid fa-receipt text-gray-300 text-4xl mb-3"></i>
                <h3 class="text-sm font-bold text-gray-500">Belum Ada Riwayat</h3>
                <p class="text-xs text-gray-400 mt-1">Data pesanan yang selesai atau dibatalkan akan muncul di sini.</p>
            </div>
        @endforelse
    </div>

    {{-- ========================================== --}}
    {{-- MODAL POPUP RINCIAN INVOICE (ALPINE.JS)    --}}
    {{-- ========================================== --}}
    <div x-show="modalOpen" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 backdrop-blur-sm bg-black/60"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         x-cloak>
         
        {{-- Box Modal --}}
        <div @click.away="tutupModal()" 
             class="bg-[#FDFDF5] w-full max-w-md rounded-3xl overflow-hidden shadow-2xl relative"
             x-show="modalOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-8 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-8 scale-95">
            
            {{-- Header Modal --}}
            <div class="bg-white px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-sm font-bold text-[#545523]">Detail Pesanan</h3>
                <button @click="tutupModal()" class="text-gray-400 hover:text-red-500 transition cursor-pointer">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            {{-- Body Modal --}}
            <template x-if="pesananAktif">
                <div class="p-6">
                    {{-- Info Toko & Status --}}
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h2 class="text-lg font-bold text-gray-800" x-text="pesananAktif.mitra"></h2>
                            <p class="text-xs font-medium text-gray-500 mt-1" x-text="pesananAktif.tanggal"></p>
                            <p class="text-[10px] text-gray-400 mt-1 font-mono" x-text="'ID: ' + pesananAktif.id"></p>
                        </div>
                        <span :class="pesananAktif.status === 'selesai' ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-red-50 text-red-600 border-red-200'"
                              class="px-3 py-1 border rounded-lg text-[10px] font-bold uppercase tracking-wider"
                              x-text="pesananAktif.status === 'selesai' ? 'Selesai' : 'Batal'">
                        </span>
                    </div>

                    {{-- Garis Putus-putus ala Struk Kasir --}}
                    <div class="border-t-2 border-dashed border-gray-200 my-4"></div>

                    {{-- Rincian Item --}}
                    <div class="space-y-3">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Item Pesanan</h4>
                        <div class="flex justify-between items-start">
                            <span class="text-sm font-semibold text-gray-700 w-2/3 leading-tight" x-text="pesananAktif.menu"></span>
                            <span class="text-sm font-bold text-gray-800" x-text="'Rp ' + (pesananAktif.subtotal || 0).toLocaleString('id-ID')"></span>
                        </div>
                    </div>

                    <div class="border-t-2 border-dashed border-gray-200 my-4"></div>

                    {{-- Rincian Biaya --}}
                    <div class="space-y-2">
                        <div class="flex justify-between text-xs text-gray-500">
                            <span>Subtotal</span>
                            <span class="font-semibold text-gray-700" x-text="'Rp ' + (pesananAktif.subtotal || 0).toLocaleString('id-ID')"></span>
                        </div>
                        <div class="flex justify-between text-xs text-gray-500">
                            <span>Pajak & Biaya Layanan</span>
                            <span class="font-semibold text-gray-700" x-text="'Rp ' + (pesananAktif.biaya_aplikasi || 0).toLocaleString('id-ID')"></span>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 my-4"></div>

                    {{-- Total Akhir & Metode --}}
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-bold text-gray-800">Total Pembayaran</span>
                        <span class="text-xl font-extrabold text-[#545523]" x-text="'Rp ' + (pesananAktif.total_harga || 0).toLocaleString('id-ID')"></span>
                    </div>
                    <div class="flex justify-between items-center text-xs text-gray-500">
                        <span>Metode Pembayaran</span>
                        <span class="font-bold text-gray-700" x-text="pesananAktif.metode_pembayaran"></span>
                    </div>
                </div>
            </template>

            {{-- Footer Modal --}}
            <div class="bg-gray-50 px-6 py-4 flex gap-3 border-t border-gray-100">
                <button @click="tutupModal()" class="flex-1 px-4 py-2.5 text-xs font-bold text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-100 transition cursor-pointer text-center">
                    Tutup
                </button>
                <button class="flex-1 px-4 py-2.5 text-xs font-bold text-[#F1F2CF] bg-[#545523] rounded-xl hover:bg-[#43441c] shadow-xs transition cursor-pointer text-center flex justify-center items-center gap-2">
                    <i class="fa-solid fa-download"></i> Unduh Struk
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('riwayatComponent', () => ({
                filterTab: 'semua',
                modalOpen: false,
                pesananAktif: null,

                bukaModal(dataPesanan) {
                    this.pesananAktif = dataPesanan;
                    this.modalOpen = true;
                    document.body.style.overflow = 'hidden';
                },

                tutupModal() {
                    this.modalOpen = false;
                    setTimeout(() => { 
                        this.pesananAktif = null; 
                        document.body.style.overflow = 'auto';
                    }, 300);
                }
            }));
        });
    </script>
@endpush