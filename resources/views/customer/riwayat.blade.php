@extends('layouts.dashboard')

@section('page_title', 'Riwayat Pesanan')

@section('content')
<div class="w-full max-w-4xl mx-auto" x-data="riwayatComponent()">
    
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

    {{-- SIMULASI DATA BACKEND DENGAN DETAIL INVOICE --}}
    @php
        $riwayatPesanan = [
            (object)[
                'id' => 'SV-8821',
                'status' => 'selesai',
                'tanggal' => '24 Jun 2026, 14:30 WIB',
                'mitra' => 'Dapur Roti Nusantara',
                'menu' => '2x Nasi Campur Bali',
                'foto' => 'https://images.unsplash.com/photo-1513104890138-7c749659a591?q=80&w=150&auto=format&fit=crop',
                'subtotal' => 43000,
                'biaya_aplikasi' => 2000,
                'total_harga' => 45000,
                'metode_pembayaran' => 'QRIS'
            ],
            (object)[
                'id' => 'SV-8819',
                'status' => 'selesai',
                'tanggal' => '21 Jun 2026, 18:15 WIB',
                'mitra' => 'Toko Kue Makmur',
                'menu' => '1x Brownies Box (Surprise Bag)',
                'foto' => null,
                'subtotal' => 23000,
                'biaya_aplikasi' => 2000,
                'total_harga' => 25000,
                'metode_pembayaran' => 'DANA'
            ],
            (object)[
                'id' => 'SV-8790',
                'status' => 'batal',
                'tanggal' => '18 Jun 2026, 19:00 WIB',
                'mitra' => 'Ayam Geprek Bu Sri',
                'menu' => '3x Paket Ayam Geprek Surplus',
                'foto' => 'https://images.unsplash.com/photo-1626082927389-6cd097cdc6ec?q=80&w=150&auto=format&fit=crop',
                'subtotal' => 28000,
                'biaya_aplikasi' => 2000,
                'total_harga' => 30000,
                'metode_pembayaran' => 'Transfer Bank'
            ],
        ];
    @endphp

    {{-- DAFTAR KARTU PESANAN --}}
    <div class="space-y-4">
        @foreach($riwayatPesanan as $pesanan)
            <div x-show="filterTab === 'semua' || filterTab === '{{ $pesanan->status }}'" 
                 x-transition.opacity
                 class="bg-[#FDFDF5] p-5 rounded-2xl border border-[#545523]/10 shadow-xs flex flex-col gap-4">
                
                {{-- Bagian Header Kartu --}}
                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-bag-shopping text-[#545523] text-sm"></i>
                        <span class="text-xs font-bold text-gray-700">Belanja • {{ $pesanan->tanggal }}</span>
                    </div>
                    
                    @if($pesanan->status === 'selesai')
                        <span class="px-3 py-1 bg-emerald-50 text-emerald-600 border border-emerald-200 rounded-lg text-[10px] font-bold uppercase tracking-wider">Selesai</span>
                    @else
                        <span class="px-3 py-1 bg-red-50 text-red-600 border border-red-200 rounded-lg text-[10px] font-bold uppercase tracking-wider">Dibatalkan</span>
                    @endif
                </div>

                {{-- Bagian Tengah --}}
                <div class="flex items-start gap-4">
                    <div class="w-16 h-16 md:w-20 md:h-20 rounded-xl overflow-hidden shrink-0 border border-gray-200 shadow-2xs">
                        @if($pesanan->foto)
                            <img src="{{ $pesanan->foto }}" alt="{{ $pesanan->menu }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-[#545523] to-[#7A7A33] flex items-center justify-center">
                                <i class="fa-solid fa-utensils text-white/50 text-2xl"></i>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-xs font-bold text-gray-500 mb-1">{{ $pesanan->mitra }}</h4>
                        <p class="text-sm md:text-base font-bold text-gray-800 leading-tight">{{ $pesanan->menu }}</p>
                        <p class="text-[10px] text-gray-400 mt-2 font-medium">Order ID: {{ $pesanan->id }}</p>
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
                        {{-- TOMBOL TRIGGER MODAL (Mengirim Data JSON ke Alpine) --}}
                        <button @click="bukaModal({{ json_encode($pesanan) }})" 
                                class="px-4 py-2 text-xs font-bold text-[#545523] bg-white border border-[#545523] rounded-lg hover:bg-[#545523]/5 transition cursor-pointer">
                            Lihat Detail
                        </button>
                        
                        @if($pesanan->status === 'selesai')
                            <button class="px-4 py-2 text-xs font-bold text-white bg-[#545523] rounded-lg hover:bg-[#43441c] shadow-xs transition cursor-pointer">
                                Beri Ulasan
                            </button>
                        @endif
                    </div>
                </div>
                
            </div>
        @endforeach
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

            {{-- Body Modal (Menggunakan template x-if agar tidak error saat data kosong) --}}
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
                            <span class="text-sm font-bold text-gray-800" x-text="'Rp ' + pesananAktif.subtotal.toLocaleString('id-ID')"></span>
                        </div>
                    </div>

                    <div class="border-t-2 border-dashed border-gray-200 my-4"></div>

                    {{-- Rincian Biaya --}}
                    <div class="space-y-2">
                        <div class="flex justify-between text-xs text-gray-500">
                            <span>Subtotal</span>
                            <span class="font-semibold text-gray-700" x-text="'Rp ' + pesananAktif.subtotal.toLocaleString('id-ID')"></span>
                        </div>
                        <div class="flex justify-between text-xs text-gray-500">
                            <span>Pajak & Biaya Layanan</span>
                            <span class="font-semibold text-gray-700" x-text="'Rp ' + pesananAktif.biaya_aplikasi.toLocaleString('id-ID')"></span>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 my-4"></div>

                    {{-- Total Akhir & Metode --}}
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-bold text-gray-800">Total Pembayaran</span>
                        <span class="text-xl font-extrabold text-[#545523]" x-text="'Rp ' + pesananAktif.total_harga.toLocaleString('id-ID')"></span>
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