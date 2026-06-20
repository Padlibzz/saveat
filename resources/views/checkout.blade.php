<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Checkout - {{ $listing->nama }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-[#E2E4B1]/40 min-h-screen flex items-center justify-center p-0 md:p-6">

    <div class="w-full max-w-5xl bg-[#E2E4B1]/30 md:bg-white rounded-none md:rounded-3xl shadow-none md:shadow-lg overflow-hidden min-h-screen md:min-h-0"
         x-data="{ 
            qty: 1, 
            maxStok: {{ $listing->stok_sisa }},
            hargaSatuan: {{ $listing->harga_diskon }}, 
            pajak: 2000,
            metodePilihan: 'cash',
            get subtotal() { return this.qty * this.hargaSatuan },
            get total() { return this.subtotal + this.pajak }
         }">
        
        <div class="bg-white md:bg-gray-50/50 px-6 py-4 flex items-center gap-4 border-b border-gray-100">
            <a href="javascript:history.back()" class="text-[#545523] hover:opacity-75 transition">
                <i class="fa-solid fa-arrow-left text-xl"></i>
            </a>
            <h2 class="text-xl font-bold text-[#545523]">Checkout</h2>
        </div>

        <div class="p-4 md:p-8 grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
            
            <div class="space-y-4">
                
                <div class="bg-white p-4 rounded-2xl shadow-xs border border-gray-100/80">
                    
                    <div class="relative w-full h-52 md:h-56 rounded-xl overflow-hidden mb-4">
                        @if(!empty($listing->foto))
                            <img
                                src="{{ asset('storage/' . $listing->foto) }}"
                                alt="{{ $listing->nama }}"
                                class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-[#545523] to-[#7A7A33] flex flex-col items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                    class="w-12 h-12 text-white mb-2">
                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M12 3v18m-4.5-15v12m9-12v12M5.25 7.5h13.5" />
                                </svg>

                                <p class="text-white font-medium">
                                    Surplus Food
                                </p>
                            </div>
                        @endif
                        
                        <span class="absolute top-3 right-3 bg-black/60 text-white text-xs px-3 py-1 rounded-full font-medium tracking-wide">
                            {{ $listing->kategori->nama ?? 'Surprise Bag' }}
                        </span>
                    </div>

                    <span class="text-xs text-gray-400 block mb-1">{{ $listing->nama }}</span>
                    <h3 class="text-xl font-bold text-gray-800">{{ $listing->merchant->nama_usaha ?? 'Dapur Roti Nusantara' }}</h3>
                    
                    <p class="text-xs text-gray-500 flex items-center gap-1 mt-1">
                        <i class="fa-solid fa-location-dot text-[#545523]"></i> 
                        {{ $listing->merchant->alamat ?? 'Alamat Merchant' }} 
                        @if($listing->jarak_km)
                            &bull; {{ round($listing->jarak_km, 1) }} km
                        @endif
                    </p>

                    <div class="flex items-baseline gap-2 mt-3">
                        <span class="text-xl font-bold text-emerald-600">Rp. {{ number_format($listing->harga_diskon, 0, ',', '.') }}</span>
                        @if($listing->harga_asli)
                            <span class="text-xs text-gray-400 line-through">Rp. {{ number_format($listing->harga_asli, 0, ',', '.') }}</span>
                        @endif
                    </div>

                    <div class="flex items-center justify-between mt-5 pt-4 border-t border-gray-100">
                        <span class="text-xs font-medium text-gray-500">Jumlah Pesanan</span>
                        
                        <div class="flex flex-col items-end gap-1">
                            <div class="flex items-center gap-3 bg-gray-50 px-3 py-1 rounded-full border border-gray-200">
                                <button @click="if(qty > 1) qty--" class="text-gray-500 hover:text-[#545523] transition w-5 h-5 flex items-center justify-center text-sm font-bold cursor-pointer">&minus;</button>
                                <span class="font-bold text-gray-700 w-4 text-center text-sm" x-text="qty"></span>
                                <button @click="if(qty < maxStok) qty++" class="text-gray-500 hover:text-[#545523] transition w-5 h-5 flex items-center justify-center text-sm font-bold cursor-pointer">&plus;</button>
                            </div>
                            <span class="text-[10px] text-amber-600 mt-1" x-show="qty >= maxStok">Sisa stok terakhir!</span>
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
                    <span class="text-xs font-bold text-gray-400 block uppercase tracking-wider mb-3">Metode Pembayaran</span>
                    
                    <div class="border-2 border-[#545523] bg-[#F1F2CF]/10 flex items-start gap-4 p-4 rounded-xl">
                        <div class="w-12 h-12 bg-[#545523] text-white rounded-xl flex items-center justify-center text-xl shrink-0 shadow-xs">
                            <i class="fa-solid fa-money-bill-wave"></i>
                        </div>
                        <div>
                            <h5 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                                Bayar Tunai di Tempat (Cash)
                                <span class="bg-amber-100 text-amber-800 text-[10px] px-2 py-0.5 rounded-full font-bold uppercase tracking-wide">Wajib</span>
                            </h5>
                            <p class="text-xs text-gray-500 mt-1.5 leading-relaxed">
                                Pesanan ini wajib menggunakan cash. Mohon siapkan dan bawa uang tunai fisik pas saat mengambil makanan langsung di lokasi toko mitra merchant.
                            </p>
                        </div>
                    </div>
                </div>

                <form action="/proses-transaksi" method="POST">
                    @csrf
                    <input type="hidden" name="listing_id" value="{{ $listing->id }}">
                    <input type="hidden" name="jumlah" :value="qty">
                    <input type="hidden" name="metode_pembayaran" value="cash">

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
                            <span class="font-bold text-gray-800">Total Pembayaran Fisik</span>
                            <span class="text-xl font-bold text-emerald-600" x-text="'Rp ' + total.toLocaleString('id-ID')"></span>
                        </div>

                        <button type="submit" class="w-full bg-[#545523] text-white py-3.5 rounded-xl font-semibold mt-4 hover:bg-[#43441c] shadow-md transition cursor-pointer">
                            Konfirmasi Pesanan Tunai
                        </button>
                    </div>
                </form>

            </div>

        </div>
    </div>

</body>
</html>