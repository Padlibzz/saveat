@section('page_title', 'Klaim Masuk')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Klaim Masuk - Saveat</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
    </style>
</head>
<body
    x-data="{ sidebarOpen: false, mode: '{{ request('kode') ? 'manual' : 'scan' }}' }"
    class="bg-gradient-to-r from-[#CFD086] to-[#F1F2CF] min-h-screen font-[Poppins]">

    <div class="flex min-h-screen">
        <x-sidebar />

        <div class="flex-1">
            <x-navbar />

            <section class="p-6 lg:ml-64">

                <div class="bg-[#FAF7F2] rounded-xl p-5 shadow-sm">
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex items-center gap-3">
                            <h2 class="text-xl font-bold text-[#545523]">Klaim Masuk</h2>
                            @php
                                $baruCount = $klaims->where('status', 'pending')->count();
                            @endphp
                            @if ($baruCount > 0)
                                <span class="bg-[#545523] text-white text-xs font-semibold px-3 py-1 rounded-full">
                                    {{ $baruCount }} Baru
                                </span>
                            @endif
                        </div>

                        <a href="{{ route('merchant.scan-qr') }}"
                            class="bg-[#545523] text-white px-4 py-2 rounded-lg text-sm font-semibold hover:opacity-90 transition flex items-center gap-2">
                            <i class="fa-solid fa-qrcode"></i>
                            Verifikasi Pesanan
                        </a>
                    </div>

                    <div class="mb-4">
                        <input type="text"
                            name="kode_klaim"
                            value="{{ old('kode_klaim', request('kode')) }}"
                            placeholder="Contoh: CLM-AB12CD34"
                            autocomplete="off"
                            class="w-full text-center uppercase tracking-wider font-semibold border-2 border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:border-[#545523]">
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="text-gray-500 border-b border-gray-200">
                                    <th class="py-2 pr-4">ID Pesanan</th>
                                    <th class="py-2 pr-4">Pelanggan</th>
                                    <th class="py-2 pr-4">Menu</th>
                                    <th class="py-2 pr-4">Status</th>
                                    <th class="py-2 pr-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($klaims as $klaim)
                                    <tr class="border-b border-gray-100">
                                        <td class="py-3 pr-4 font-semibold text-[#545523]">#{{ $klaim->kode_klaim }}</td>
                                        <td class="py-3 pr-4 text-[#A6A84F] font-medium">{{ $klaim->user->name ?? '-' }}</td>
                                        <td class="py-3 pr-4">{{ $klaim->jumlah }}x {{ $klaim->listing->nama ?? '-' }}</td>
                                        <td class="py-3 pr-4">
                                            <span @class([
                                                'px-2 py-1 rounded-full text-xs font-semibold',
                                                'bg-yellow-100 text-yellow-700' => $klaim->status === 'pending',
                                                'bg-green-100 text-green-700' => $klaim->status === 'diambil',
                                                'bg-red-100 text-red-700' => $klaim->status === 'batal',
                                            ])>
                                                {{ ucfirst($klaim->status) }}
                                            </span>
                                        </td>
                                        <td class="py-3 pr-4">
                                            @if ($klaim->status === 'pending' && $klaim->status_pembayaran === 'sudah_dibayar')
                                                <a href="{{ route('merchant.scan-qr') }}?kode={{ $klaim->kode_klaim }}"
                                                    class="text-[#545523] text-xs font-semibold underline">
                                                    Verifikasi
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-6 text-center text-gray-500">Belum ada klaim masuk.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </section>

            <x-footer/>
        </div>
    </div>

</body>
</html>