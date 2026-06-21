@section('page_title', 'Scan & Verifikasi Pesanan')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Verifikasi Pesanan - Saveat</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
    </style>
</head>
<body
    x-data="{ sidebarOpen: false, mode: 'scan' }"
    class="bg-gradient-to-r from-[#CFD086] to-[#F1F2CF] min-h-screen font-[Poppins]">

    <div class="flex min-h-screen">
        <x-sidebar />

        <div class="flex-1">
            <x-navbar />

            <section class="p-6 lg:ml-64 flex justify-center">

                <div class="bg-[#FAF7F2] rounded-2xl p-6 shadow-sm max-w-md w-full">

                    <h2 class="text-xl font-bold text-[#545523] text-center mb-5">Verifikasi Pesanan</h2>

                    @if (session('success'))
                        <div class="bg-green-100 text-green-800 px-4 py-3 rounded-lg mb-4 text-sm">
                            <i class="fa-solid fa-circle-check mr-1"></i> {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="bg-red-100 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm">
                            <i class="fa-solid fa-circle-exclamation mr-1"></i> {{ session('error') }}
                        </div>
                    @endif

                    <!-- Tab Switcher -->
                    <div class="flex bg-white rounded-xl p-1 mb-6">
                        <button type="button" @click="mode = 'scan'"
                            :class="mode === 'scan' ? 'bg-[#545523] text-white' : 'text-[#545523]'"
                            class="flex-1 py-2 rounded-lg text-sm font-semibold transition">
                            <i class="fa-solid fa-camera mr-1"></i> Scan QR
                        </button>
                        <button type="button" @click="mode = 'manual'"
                            :class="mode === 'manual' ? 'bg-[#545523] text-white' : 'text-[#545523]'"
                            class="flex-1 py-2 rounded-lg text-sm font-semibold transition">
                            <i class="fa-solid fa-keyboard mr-1"></i> Input Manual
                        </button>
                    </div>

                    <!-- Mode: Scan Barcode -->
                    <div x-show="mode === 'scan'">
                        <p class="text-center text-[#545523] font-semibold mb-3">Scan Barcode</p>

                        <div id="qr-reader" class="rounded-xl overflow-hidden border-2 border-gray-200"></div>
                        <p class="text-center text-xs text-gray-400 mt-2">Tunjukkan kamera ke QR Code konsumen</p>

                        <!-- Form ini di-submit otomatis via JS setelah scan berhasil -->
                        <form id="scan-form" action="{{ route('merchant.scan-qr.submit') }}" method="POST" class="hidden">
                            @csrf
                            <input type="hidden" name="kode_klaim" id="scan-kode-klaim">
                        </form>
                    </div>

                    <!-- Mode: Input Manual -->
                    <div x-show="mode === 'manual'" x-cloak>
                        <p class="text-center text-[#545523] font-semibold mb-3">Masukkan Kode Klaim</p>

                        <form action="{{ route('merchant.scan-qr.submit') }}" method="POST">
                            @csrf
                            <input type="text" name="kode_klaim" value="{{ old('kode_klaim') }}"
                                placeholder="Contoh: CLM-AB12CD34"
                                autocomplete="off"
                                class="w-full text-center uppercase tracking-wider font-semibold border-2 border-gray-300 rounded-lg px-4 py-3 mb-4 focus:outline-none focus:border-[#545523]">

                            <button type="submit"
                                class="w-full bg-[#545523] text-white py-3 rounded-xl font-semibold hover:opacity-90 transition">
                                Verifikasi Pesanan
                            </button>
                        </form>
                    </div>

                </div>

            </section>

            <x-footer/>
        </div>
    </div>

    <script>
        function ekstrakKodeKlaim(decodedText) {
            const teks = decodedText.trim();

            try {
                const parsed = JSON.parse(teks);
                if (parsed.kode_klaim) return parsed.kode_klaim;
                if (parsed.kode) return parsed.kode;
                if (parsed.qr_data) return parsed.qr_data;
            } catch (e) {}

            try {
                const url = new URL(teks);
                const kodeDariQuery = url.searchParams.get('kode_klaim') || url.searchParams.get('kode');
                if (kodeDariQuery) return kodeDariQuery;
            } catch (e) {}

            return teks;
        }

        document.addEventListener('DOMContentLoaded', function () {
            const qrReader = new Html5Qrcode('qr-reader');
            let isScanning = false;

            function startScanner() {
                if (isScanning) return;
                isScanning = true;

                qrReader.start(
                    { facingMode: 'environment' },
                    { fps: 10, qrbox: { width: 220, height: 220 } },
                    (decodedText) => {
                        const kodeKlaim = ekstrakKodeKlaim(decodedText);
                        document.getElementById('scan-kode-klaim').value = kodeKlaim;
                        qrReader.stop().then(() => {
                            document.getElementById('scan-form').submit();
                        });
                    },
                    (errorMessage) => {}
                ).catch((err) => {
                    console.error('Gagal mengakses kamera:', err);
                });
            }

            startScanner();
        });
    </script>

</body>
</html>