<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - {{ $listing->nama }}</title>
    
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-[#E2E4B1]/40 min-h-screen p-0 md:p-6"
      x-data="checkoutData({{ $listing->stok_sisa }}, {{ $listing->harga_diskon }})">

    <div class="max-w-5xl mx-auto bg-[#E2E4B1]/30 md:bg-white rounded-none md:rounded-3xl shadow-none md:shadow-lg overflow-hidden min-h-screen md:min-h-0">
        
        <header class="bg-white md:bg-gray-50/50 px-6 py-4 flex items-center gap-4 border-b border-gray-100">
            <button onclick="history.back()" class="text-[#545523] hover:opacity-75 transition">
                <i class="fa-solid fa-arrow-left text-xl"></i>
            </button>
            <h2 class="text-xl font-bold text-[#545523]">Checkout</h2>
        </header>

        <main class="p-4 md:p-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <section class="space-y-4">
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                    <!-- Image/Listing Details -->
                    <div class="relative w-full h-52 rounded-xl overflow-hidden mb-4">
                        @if($listing->foto)
                            <img src="{{ asset('storage/' . $listing->foto) }}" alt="{{ $listing->nama }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-[#545523] flex items-center justify-center text-white">No Image</div>
                        @endif
                        <span class="absolute top-3 right-3 bg-black/60 text-white text-xs px-3 py-1 rounded-full">{{ $listing->kategori->nama ?? 'Umum' }}</span>
                    </div>

                    <h3 class="text-xl font-bold text-gray-800">{{ $listing->merchant->nama_usaha }}</h3>
                    <p class="text-xs text-gray-400 block mb-1">{{ $listing->nama }}</p>
                    
                    <p class="text-xs text-gray-500 flex items-center gap-1 mt-1">
                        <i class="fa-solid fa-location-dot text-[#545523]"></i> 
                        {{ $listing->merchant->alamat ?? 'Alamat tidak tersedia' }} 
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

                    <div class="flex justify-between items-center mt-5 pt-4 border-t border-gray-100">
                        <span class="text-xs text-gray-500">Jumlah Pesanan</span>
                        <div class="flex items-center gap-3 bg-gray-50 px-3 py-1 rounded-full border">
                            <button type="button" @click="decrement()" class="text-gray-500 w-5 h-5">&minus;</button>
                            <span class="font-bold text-gray-700 w-4 text-center" x-text="qty"></span>
                            <button type="button" @click="increment()" class="text-gray-500 w-5 h-5">&plus;</button>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                    <span class="text-xs font-bold text-gray-400 block uppercase tracking-wider mb-2">Ambil Pesanan Sebelum</span>
                    <p class="text-sm font-medium text-gray-700 flex items-center gap-2">
                        <i class="fa-regular fa-clock text-amber-600"></i>
                        {{ \Carbon\Carbon::parse($listing->batas_waktu)->translatedFormat('d F Y, H:i') }} WIB
                    </p>
                </div>
            </section>

            <section class="space-y-4">
                <form id="checkoutForm" action="{{ route('transaksi.proses') }}" @submit.prevent="submitForm($event)">
                    @csrf
                    <input type="hidden" name="listing_id" value="{{ $listing->id }}">
                    <input type="hidden" name="jumlah" x-model="qty">

                    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                        <h4 class="font-bold text-gray-400 text-xs uppercase mb-3">Metode Pembayaran</h4>
                        <div class="space-y-2">
                            <label class="flex items-center gap-3 p-3 rounded-xl border cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="metode_pembayaran" value="midtrans" x-model="metode" class="accent-[#545523]" checked>
                                <span>Bayar Online</span>
                            </label>
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-2xl shadow-sm mt-4 space-y-3">
                        <div class="flex justify-between text-sm text-gray-500">
                            <span>Subtotal (<span x-text="qty"></span> barang)</span>
                            <span class="font-medium text-gray-700" x-text="'Rp ' + (qty * hargaSatuan).toLocaleString('id-ID')"></span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-500">
                            <span>Pajak Aplikasi</span>
                            <span class="font-medium text-gray-700" x-text="'Rp ' + pajak.toLocaleString('id-ID')"></span>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t border-dashed border-gray-200">
                            <span class="font-bold text-gray-800">Total Pembayaran</span>
                            <span class="text-xl font-bold text-emerald-600" x-text="'Rp ' + total.toLocaleString('id-ID')"></span>
                        </div>
                        <button type="submit" class="w-full bg-[#545523] text-white py-3 rounded-xl font-bold hover:bg-[#43441c] transition">Konfirmasi Pesanan</button>
                    </div>
                </form>
            </section>
        </main>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('checkoutData', (maxStok, hargaSatuan) => ({
                qty: 1,
                maxStok: parseInt(maxStok),
                hargaSatuan: parseFloat(hargaSatuan),
                pajak: 2000,
                metode: 'midtrans',
                get total() { return (this.qty * this.hargaSatuan) + this.pajak },
                increment() { if (this.qty < this.maxStok) this.qty++ },
                decrement() { if (this.qty > 1) this.qty-- },
                async submitForm(event) {
                    let form = event.target;
                    let res = await fetch(form.action, {
                        method: 'POST',
                        body: new FormData(form),
                        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                    });
                    let data = await res.json();
                    if (data.status === 'midtrans') { snap.pay(data.snap_token); }
                    else if (data.status === 'success') { window.location.href = data.url; }
                    else { alert(data.message || 'Terjadi kesalahan.'); }
                }
            }));
        });
    </script>
</body>
</html>',file_path: