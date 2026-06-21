<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan - {{ $claim->kode_klaim }}</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
</head>
<body class="bg-gray-50 p-6">
    <div class="max-w-md mx-auto bg-white p-6 rounded-3xl shadow-lg">
        <h2 class="text-xl font-bold text-center mb-4">Detail Pesanan</h2>
        <div id="qrcode" class="flex justify-center mb-4"></div>
        <p class="text-center font-bold text-lg">{{ $claim->kode_klaim }}</p>
        <p class="text-center text-gray-500">Tunjukkan QR ini ke Merchant</p>
        
        <div class="mt-6 border-t pt-4">
            <p><strong>Merchant:</strong> {{ $claim->listing->merchant->nama_usaha }}</p>
            <p><strong>Produk:</strong> {{ $claim->listing->nama }}</p>
            <p><strong>Jumlah:</strong> {{ $claim->jumlah }}</p>
            <p><strong>Total:</strong> Rp {{ number_format($claim->total_harga, 0, ',', '.') }}</p>
        </div>
        <a href="{{ route('dashboard') }}" class="block text-center mt-6 bg-[#545523] text-white py-3 rounded-xl font-bold">Kembali ke Dashboard</a>
    </div>

    <script>
        new QRCode(document.getElementById("qrcode"), {
            text: "{{ $claim->kode_klaim }}",
            width: 200,
            height: 200
        });
    </script>
</body>
</html>