<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Checkout - {{ $listing->nama }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Poppins', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-[#E2E4B1]/40 min-h-screen flex items-center justify-center p-0 md:p-6"
      x-data="checkoutComponent()">

    <div class="w-full max-w-5xl bg-[#E2E4B1]/30 md:bg-white rounded-none md:rounded-3xl shadow-none md:shadow-lg overflow-hidden min-h-screen md:min-h-0">
        
        <div class="bg-white md:bg-gray-50/50 px-6 py-4 flex items-center gap-4 border-b border-gray-100">
            <a href="javascript:history.back()" class="text-[#545523] hover:opacity-75 transition">
                <i class="fa-solid fa-arrow-left text-xl"></i>
            </a>
            <h2 class="text-xl font-bold text-[#545523]">Checkout Pesanan</h2>
        </div>

        <div class="p-4 md:p-8 grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
            
            <div class="space-y-4">
                <div class="bg-white p-4 rounded-2xl shadow-xs border border-gray-100/80">
                    <div class="relative w-full h-52 md:h-56 rounded-xl overflow-hidden mb-4">
                        @if(!empty($listing->foto))
                            <img src="{{ asset('storage/' . $listing->foto) }}" alt="{{ $listing->nama }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-[#545523] to-[#7A7A33] flex flex-col items-center justify-center">
                                <i class="fa-solid fa-utensils text-white text-3xl mb-2"></i>
                                <p class="text-white font-medium">Surplus Food</p>
                            </div>
                        @endif
                        <span class="absolute top-3 right-3 bg-black/60 text-white text-xs px-3 py-1 rounded-full font-medium">
                            {{ $listing->kategori->nama ?? 'Surprise Bag' }}
                        </span>
                    </div>

                    <span class="text-xs text-gray-400 block mb-1">{{ $listing->nama }}</span>
                    <h3 class="text-xl font-bold text-gray-800">{{ $listing->merchant->nama_usaha ?? 'Mitra Saveat' }}</h3>
                    
                    <p class="text-xs text-gray-500 flex items-center gap-1 mt-1">
                        <i class="fa-solid fa-location-dot text-[#545523]"></i> 
                        {{ $listing->merchant->alamat ?? 'Alamat Merchant' }}
                    </p>

                    <div class="flex items-baseline gap-2 mt-3">
                        <span class="text-xl font-bold text-emerald-600">Rp {{ number_format($listing->harga_diskon, 0, ',', '.') }}</span>
                        @if($listing->harga_asli)
                            <span class="text-xs text-gray-400 line-through">Rp {{ number_format($listing->harga_asli, 0, ',', '.') }}</span>
                        @endif
                    </div>

                    <div class="flex items-center justify-between mt-5 pt-4 border-t border-gray-100">
                        <span class="text-xs font-medium text-gray-500">Jumlah Pesanan</span>
                        <div class="flex flex-col items-end gap-1">
                            <div class="flex items-center gap-3 bg-gray-50 px-3 py-1 rounded-full border border-gray-200">
                                <button @click="if(qty > 1) qty--" class="text-gray-500 hover:text-[#545523] transition text-sm font-bold cursor-pointer">&minus;</button>
                                <span class="font-bold text-gray-700 w-4 text-center text-sm" x-text="qty"></span>
                                <button @click="if(qty < maxStok) qty++" class="text-gray-500 hover:text-[#545523] transition text-sm font-bold cursor-pointer">&plus;</button>
                            </div>
                            <span class="text-[10px] text-amber-600 mt-1" x-show="qty >= maxStok" x-cloak>Sisa stok terakhir!</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-2xl shadow-xs border border-gray-100">
                    <span class="text-xs font-bold text-gray-400 block uppercase tracking-wider mb-2">Ambil Pesanan Sebelum</span>
                    <p class="text-sm font-medium text-gray-700 flex items-center gap-2">
                        <i class="fa-regular fa-clock text-amber-600"></i>
                        {{ \Carbon\Carbon::parse($listing->batas_waktu)->translatedFormat('d F Y, H:i') }} WIB
                    </p>
                </div>
            </div>

            <div class="space-y-4">
                <div class="bg-white p-4 rounded-2xl shadow-xs border border-gray-100">
                    <span class="text-xs font-bold text-gray-400 block uppercase tracking-wider mb-3">Pilih Metode Pembayaran</span>
                    
                    <div class="grid grid-cols-1 gap-2">
                        <label @click="metodePilihan = 'qris'" :class="metodePilihan === 'qris' ? 'border-[#545523] bg-[#F1F2CF]/20' : 'border-gray-200'" class="flex items-center justify-between p-3 border-2 rounded-xl cursor-pointer transition-all">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center text-[#545523]"><i class="fa-solid fa-qrcode text-lg"></i></div>
                                <span class="text-xs font-bold text-gray-700">QRIS (Gopay, OVO, Dana, ShopeePay)</span>
                            </div>
                            <div :class="metodePilihan === 'qris' ? 'bg-[#545523] border-[#545523]' : 'border-gray-300'" class="w-4 h-4 rounded-full border flex items-center justify-center"><div class="w-1.5 h-1.5 bg-white rounded-full"></div></div>
                        </label>

                        <label @click="metodePilihan = 'dana'" :class="metodePilihan === 'dana' ? 'border-[#545523] bg-[#F1F2CF]/20' : 'border-gray-200'" class="flex items-center justify-between p-3 border-2 rounded-xl cursor-pointer transition-all">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center font-bold text-xs">DANA</div>
                                <span class="text-xs font-bold text-gray-700">DANA Wallet</span>
                            </div>
                            <div :class="metodePilihan === 'dana' ? 'bg-[#545523] border-[#545523]' : 'border-gray-300'" class="w-4 h-4 rounded-full border flex items-center justify-center"><div class="w-1.5 h-1.5 bg-white rounded-full"></div></div>
                        </label>

                        <label @click="metodePilihan = 'transfer_bank'" :class="metodePilihan === 'transfer_bank' ? 'border-[#545523] bg-[#F1F2CF]/20' : 'border-gray-200'" class="flex items-center justify-between p-3 border-2 rounded-xl cursor-pointer transition-all">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center text-[#545523]"><i class="fa-solid fa-building-columns text-lg"></i></div>
                                <span class="text-xs font-bold text-gray-700">Transfer Bank (Virtual Account)</span>
                            </div>
                            <div :class="metodePilihan === 'transfer_bank' ? 'bg-[#545523] border-[#545523]' : 'border-gray-300'" class="w-4 h-4 rounded-full border flex items-center justify-center"><div class="w-1.5 h-1.5 bg-white rounded-full"></div></div>
                        </label>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-2xl shadow-xs border border-gray-100 space-y-3">
                    <div class="flex justify-between text-sm text-gray-500">
                        <span>Subtotal (<span x-text="qty"></span> barang)</span>
                        <span class="font-medium text-gray-700" x-text="'Rp ' + subtotal.toLocaleString('id-ID')"></span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-500">
                        <span>Pajak Aplikasi</span>
                        <span class="font-medium text-gray-700" x-text="'Rp ' + pajak.toLocaleString('id-ID')"></span>
                    </div>
                    <div class="flex justify-between items-center pt-2 border-t border-dashed border-gray-200">
                        <span class="font-bold text-gray-800">Total Pembayaran</span>
                        <span class="text-xl font-bold text-emerald-600" x-text="'Rp ' + total.toLocaleString('id-ID')"></span>
                    </div>

                    <div x-show="errorMessage" class="bg-red-50 text-red-600 p-3 rounded-xl text-xs font-medium border border-red-100" x-text="errorMessage" x-cloak></div>

                    <button @click="prosesPembayaran()" :disabled="loading" class="w-full bg-[#545523] text-white py-3.5 rounded-xl font-semibold mt-4 hover:bg-[#43441c] shadow-md transition cursor-pointer flex items-center justify-center gap-2">
                        <i class="fa-solid fa-circle-notch animate-spin" x-show="loading" x-cloak></i>
                        <span x-text="loading ? 'Menghubungkan ke Midtrans...' : 'Bayar Sekarang'"></span>
                    </button>
                </div>
            </div>

        </div>
    </div>

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
</body>
</html>