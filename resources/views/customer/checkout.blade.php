@extends('layouts.dashboard')

@section('page_title', 'Checkout - ' . $listing->nama)

@section('content')
    <div class="w-full max-w-5xl mx-auto" x-data="checkoutComponent()">
        
        {{-- TOMBOL KEMBALI & JUDUL HALAMAN --}}
        <div class="flex items-center gap-4 mb-6 border-b border-[#545523]/10 pb-4">
            <a href="javascript:history.back()" class="text-[#545523] hover:opacity-75 transition-all">
                <i class="fa-solid fa-arrow-left text-lg"></i>
            </a>
            <div>
                <h2 class="text-xl font-bold text-[#545523]">Checkout Pesanan</h2>
                <p class="text-xs text-gray-400 font-medium">Tinjau kembali rincian makanan penyelamatmu sebelum menyelesaikan pembayaran.</p>
            </div>
        </div>

        {{-- GRID UTAMA CHECKOUT --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
            
            {{-- KOLOM KIRI: KARTU DETAIL MAKANAN & BATAS WAKTU --}}
            <div class="space-y-4">
                {{-- Detail Produk --}}
                <div class="bg-[#FDFDF5] p-5 rounded-2xl border border-[#545523]/10 shadow-xs">
                    <div class="relative w-full h-52 rounded-xl overflow-hidden mb-4 shadow-2xs">
                        @if(!empty($listing->foto))
                            <img src="{{ asset('storage/' . $listing->foto) }}" alt="{{ $listing->nama }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-[#545523] to-[#7A7A33] flex flex-col items-center justify-center">
                                <i class="fa-solid fa-utensils text-white text-3xl mb-2"></i>
                                <p class="text-white font-medium text-xs">Surplus Food</p>
                            </div>
                        @endif
                        <span class="absolute top-3 right-3 bg-black/60 text-white text-[10px] px-2.5 py-1 rounded-full font-semibold tracking-wider uppercase">
                            {{ $listing->kategori->nama ?? 'Surprise Bag' }}
                        </span>
                    </div>

                    <span class="text-xs text-gray-400 font-medium block mb-0.5">{{ $listing->nama }}</span>
                    <h3 class="text-lg font-bold text-[#545523]">{{ $listing->merchant->nama_usaha ?? 'Mitra Saveat' }}</h3>
                    
                    <p class="text-xs text-gray-500 flex items-center gap-1.5 mt-1">
                        <i class="fa-solid fa-location-dot text-[#6D6B2E]"></i> 
                        {{ $listing->merchant->alamat ?? 'Alamat Merchant' }}
                    </p>

                    <div class="flex items-baseline gap-2 mt-3">
                        <span class="text-xl font-extrabold text-emerald-600">Rp {{ number_format($listing->harga_diskon, 0, ',', '.') }}</span>
                        @if($listing->harga_asli)
                            <span class="text-xs text-gray-400 line-through">Rp {{ number_format($listing->harga_asli, 0, ',', '.') }}</span>
                        @endif
                    </div>

                    {{-- KUANTITAS (QUANTITY SELECTOR) --}}
                    <div class="flex items-center justify-between mt-5 pt-4 border-t border-[#545523]/10">
                        <span class="text-xs font-bold text-[#545523]">Jumlah Pesanan</span>
                        <div class="flex flex-col items-end gap-1">
                            <div class="flex items-center gap-3 bg-white px-3 py-1 rounded-full border border-gray-200 shadow-2xs">
                                <button @click="if(qty > 1) qty--" class="text-gray-400 hover:text-[#545523] transition-all text-sm font-bold cursor-pointer select-none">&minus;</button>
                                <span class="font-bold text-gray-700 w-5 text-center text-xs" x-text="qty"></span>
                                <button @click="if(qty < maxStok) qty++" class="text-gray-400 hover:text-[#545523] transition-all text-sm font-bold cursor-pointer select-none">&plus;</button>
                            </div>
                            <span class="text-[10px] font-semibold text-amber-600 mt-1" x-show="qty >= maxStok" x-cloak>Sisa stok terakhir!</span>
                        </div>
                    </div>
                </div>

                {{-- Batas Waktu Pengambilan --}}
                <div class="bg-[#FDFDF5] p-4 rounded-2xl border border-[#545523]/10 shadow-xs flex items-center gap-3">
                    <div class="w-10 h-10 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center text-base shrink-0">
                        <i class="fa-regular fa-clock"></i>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-gray-400 block uppercase tracking-wider">Ambil Pesanan Sebelum</span>
                        <p class="text-xs font-semibold text-gray-700 mt-0.5">
                            {{ \Carbon\Carbon::parse($listing->batas_waktu)->translatedFormat('d F Y, H:i') }} WIB
                        </p>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: PILIHAN METODE & RINCIAN BIAYA --}}
            <div class="space-y-4">
                {{-- Pilihan Metode Pembayaran --}}
                <div class="bg-[#FDFDF5] p-5 rounded-2xl border border-[#545523]/10 shadow-xs">
                    <span class="text-xs font-bold text-[#545523] block uppercase tracking-wider mb-3">Pilih Metode Pembayaran</span>
                    
                    <div class="grid grid-cols-1 gap-2">
                        <label @click="metodePilihan = 'qris'" :class="metodePilihan === 'qris' ? 'border-[#545523] bg-[#545523]/5' : 'border-gray-200 bg-white'" class="flex items-center justify-between p-3 border-2 rounded-xl cursor-pointer transition-all shadow-2xs">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-gray-50 rounded-lg flex items-center justify-center text-[#545523] text-sm"><i class="fa-solid fa-qrcode"></i></div>
                                <span class="text-xs font-bold text-gray-700">QRIS (Gopay, OVO, Dana, ShopeePay)</span>
                            </div>
                            <div :class="metodePilihan === 'qris' ? 'bg-[#545523] border-[#545523]' : 'border-gray-300'" class="w-4 h-4 rounded-full border flex items-center justify-center transition-all"><div class="w-1.5 h-1.5 bg-white rounded-full"></div></div>
                        </label>

                        <label @click="metodePilihan = 'dana'" :class="metodePilihan === 'dana' ? 'border-[#545523] bg-[#545523]/5' : 'border-gray-200 bg-white'" class="flex items-center justify-between p-3 border-2 rounded-xl cursor-pointer transition-all shadow-2xs">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center font-extrabold text-[10px]">DANA</div>
                                <span class="text-xs font-bold text-gray-700">DANA Wallet</span>
                            </div>
                            <div :class="metodePilihan === 'dana' ? 'bg-[#545523] border-[#545523]' : 'border-gray-300'" class="w-4 h-4 rounded-full border flex items-center justify-center transition-all"><div class="w-1.5 h-1.5 bg-white rounded-full"></div></div>
                        </label>

                        <label @click="metodePilihan = 'transfer_bank'" :class="metodePilihan === 'transfer_bank' ? 'border-[#545523] bg-[#545523]/5' : 'border-gray-200 bg-white'" class="flex items-center justify-between p-3 border-2 rounded-xl cursor-pointer transition-all shadow-2xs">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-gray-50 rounded-lg flex items-center justify-center text-[#545523] text-sm"><i class="fa-solid fa-building-columns"></i></div>
                                <span class="text-xs font-bold text-gray-700">Transfer Bank (Virtual Account)</span>
                            </div>
                            <div :class="metodePilihan === 'transfer_bank' ? 'bg-[#545523] border-[#545523]' : 'border-gray-300'" class="w-4 h-4 rounded-full border flex items-center justify-center transition-all"><div class="w-1.5 h-1.5 bg-white rounded-full"></div></div>
                        </label>
                    </div>
                </div>

                {{-- Rincian Biaya Akhir --}}
                <div class="bg-[#FDFDF5] p-5 rounded-2xl border border-[#545523]/10 shadow-xs space-y-3">
                    <span class="text-xs font-bold text-[#545523] block uppercase tracking-wider mb-1">Rincian Pembayaran</span>
                    <div class="flex justify-between text-xs text-gray-500">
                        <span>Subtotal (<span class="font-semibold" x-text="qty"></span> barang)</span>
                        <span class="font-semibold text-gray-700" x-text="'Rp ' + subtotal.toLocaleString('id-ID')"></span>
                    </div>
                    <div class="flex justify-between text-xs text-gray-500">
                        <span>Pajak Aplikasi</span>
                        <span class="font-semibold text-gray-700" x-text="'Rp ' + pajak.toLocaleString('id-ID')"></span>
                    </div>
                    <div class="flex justify-between items-center pt-3 border-t border-dashed border-gray-200">
                        <span class="text-xs font-bold text-gray-800">Total Pembayaran</span>
                        <span class="text-lg font-extrabold text-emerald-600" x-text="'Rp ' + total.toLocaleString('id-ID')"></span>
                    </div>

                    {{-- Balon Error Komponen --}}
                    <div x-show="errorMessage" class="bg-red-50 text-red-600 p-3 rounded-xl text-xs font-medium border border-red-100" x-text="errorMessage" x-cloak></div>

                    {{-- Tombol Aksi Pembayaran --}}
                    <button @click="prosesPembayaran()" :disabled="loading" class="w-full bg-[#545523] text-[#F1F2CF] py-3.5 rounded-xl font-bold mt-4 hover:bg-[#43441c] shadow-xs hover:shadow-md transition-all cursor-pointer flex items-center justify-center gap-2 text-xs uppercase tracking-wider disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fa-solid fa-circle-notch animate-spin text-sm" x-show="loading" x-cloak></i>
                        <span x-text="loading ? 'Menghubungkan ke Midtrans...' : 'Bayar Sekarang'"></span>
                    </button>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    {{-- INJEKSI DRIVER SCRIPT MIDTRANS SNAP --}}
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    
    {{-- LOGIKAL LIVE KALKULASI & CLAIM ENGINE --}}
    <script>
        function checkoutComponent() {
            return {
                qty: 1,
                maxStok: {{ $listing->stok_sisa ?? $listing->stok_total }},
                hargaSatuan: {{ $listing->harga_diskon }},
                pajak: 2000,
                metodePilihan: 'qris',
                loading: false,
                errorMessage: '',

                get subtotal() { return this.qty * this.hargaSatuan },
                get total() { return this.subtotal + this.pajak },

                async prosesPembayaran() {
                    this.loading = true;
                    this.errorMessage = '';

                    try {
                        // LANGKAH 1: Daftarkan klaim data transaksi internal ke database
                        let responseClaim = await fetch('/api/claim/store', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                listing_id: {{ $listing->id }},
                                jumlah: this.qty,
                                total_harga: this.total
                            })
                        });

                        let dataClaim = await responseClaim.json();

                        if (!responseClaim.ok || dataClaim.status !== 'success') {
                            throw new Error(dataClaim.message || 'Gagal membuat invoice pesanan.');
                        }

                        const claimId = dataClaim.claim_id;

                        // LANGKAH 2: Eksekusi bypass status pelunasan instan di level backend simulator
                        let responseSimulasi = await fetch(`/api/payment/create/${claimId}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                metode_pembayaran: this.metodePilihan
                            })
                        });

                        let dataSimulasi = await responseSimulasi.json();

                        if (!responseSimulasi.ok || dataSimulasi.status !== 'success') {
                            throw new Error(dataSimulasi.message || 'Gagal memproses simulasi pembayaran.');
                        }

                        // LANGKAH 3: Lempar user langsung ke sirkuit halaman sukses klaim barcode
                        window.location.href = `/claim/success/${claimId}`;

                    } catch (error) {
                        this.errorMessage = error.message;
                    } finally {
                        this.loading = false;
                    }
                }
            }
        }
    </script>
@endpush