<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Barcode Pengambilan - SavEat</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-[#E2E4B1]/40 min-h-screen flex items-center justify-center p-0 md:p-6">

    <div class="w-full max-w-md bg-[#F4F5E9] min-h-screen shadow-lg flex flex-col justify-between overflow-hidden">
        
        <div>
            <div class="bg-white px-6 py-4 flex items-center gap-4 border-b border-gray-100 shadow-xs">
                <a href="/dashboard" class="text-[#545523] hover:opacity-75 transition">
                    <i class="fa-solid fa-arrow-left text-xl"></i>
                </a>
                <h2 class="text-lg font-bold text-[#545523] flex-1 text-center pr-6">Barcode Pengambilan</h2>
            </div>

            <div class="p-6 space-y-6">
                
                <div class="bg-white rounded-3xl p-6 shadow-xs border border-gray-100 text-center">
                    
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Scan Barcode</p>
                    
                    <div class="bg-gray-50 p-4 rounded-2xl inline-block border border-gray-100 mb-6">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ $claim->kode_klaim }}" 
                             alt="QR Code Pengambilan" 
                             class="w-44 h-44 mx-auto mix-blend-multiply">
                    </div>

                    <div class="mb-4">
                        <span class="text-xs text-gray-400 block mb-0.5">{{ $claim->listing->nama }}</span>
                        <h3 class="text-lg font-bold text-gray-800">{{ $claim->listing->merchant->nama_usaha ?? 'Mitra Saveat' }}</h3>
                        <p class="text-base font-extrabold text-emerald-600 mt-1">
                            Rp {{ number_format($claim->total_harga, 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="border-t-2 border-dashed border-gray-200 my-5"></div>

                    <div class="space-y-2.5 text-xs text-gray-500 px-1">
                        <div class="flex justify-between">
                            <span>Subtotal ({{ $claim->jumlah }} barang)</span>
                            <span class="font-medium text-gray-700">Rp {{ number_format($claim->total_harga - 2000, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Pajak</span>
                            <span class="font-medium text-gray-700">Rp 2.000</span>
                        </div>
                        <div class="flex justify-between text-sm font-bold text-gray-800 pt-1">
                            <span>Total</span>
                            <span class="text-emerald-600">Rp {{ number_format($claim->total_harga, 0, ',', '.') }}</span>
                        </div>
                    </div>

                </div>

                <button onclick="window.location.href='/dashboard'" class="w-full bg-[#545523] text-white py-3.5 rounded-xl font-semibold hover:bg-[#43441c] shadow-md transition flex items-center justify-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-download text-sm"></i> Simpan Barcode
                </button>

                <p class="text-[11px] text-center text-gray-400 leading-relaxed px-4">
                    *Jangan lupa tunjukkan barcode ini ke merchant untuk scan saat melakukan pengambilan makanan.
                </p>

            </div>
        </div>

        <div class="text-center py-4 text-[10px] text-gray-400 bg-white border-t border-gray-100">
            © 2026 SavEat. All rights reserved.
        </div>

    </div>

</body>
</html>