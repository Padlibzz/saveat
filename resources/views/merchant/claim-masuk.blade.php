@section('page_title', 'Klaim Masuk')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Klaim Masuk - Merchant</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
    </style>
</head>
<body
    x-data="{ sidebarOpen: false }"
    class="bg-gradient-to-r from-[#CFD086] to-[#F1F2CF] min-h-screen font-[Poppins] flex">

    <x-sidebar />

    <div class="flex-1 flex flex-col min-h-screen">

        <x-navbar />

        <section class="p-6 mb-10 lg:ml-64 flex-1">
            
            @if(session('success'))
                <x-alert type="success" :message="session('success')" />
            @endif

            @if(session('error'))
                <x-alert type="error" :message="session('error')" />
            @endif

            {{-- HEADER HALAMAN --}}
            <div class="mb-6 border-b border-[#545523]/10 pb-4">
                <h1 class="text-2xl font-bold text-[#545523]">Pesanan Masuk</h1>
                <p class="text-sm text-gray-600 mt-1">Pantau dan verifikasi klaim makanan dari pelanggan di sini.</p>
            </div>

            {{-- KARTU UTAMA: KLAIM MASUK --}}
            <div class="bg-[#FDFDF5] rounded-3xl border border-[#545523]/10 shadow-sm overflow-hidden max-w-4xl">
                
                {{-- HEADER KARTU --}}
                <div class="p-5 md:p-6 flex justify-between items-center border-b border-gray-100">
                    <h2 class="text-xl font-bold text-[#545523] tracking-tight">Daftar Klaim</h2>
                    <span class="bg-[#545523] text-[#F1F2CF] text-xs font-bold px-3 py-1 rounded-full shadow-xs">
                        {{ $claims->count() ?? '0' }} Baru
                    </span>
                </div>

                {{-- JIKA TIDAK ADA KLAIM MASUK --}}
                @if($claims->isEmpty())
                    <div class="p-12 text-center text-gray-400 text-sm flex flex-col items-center">
                        <i class="fa-solid fa-clipboard-list text-5xl mb-4 text-gray-300"></i>
                        <p>Belum ada klaim pesanan masuk saat ini.</p>
                    </div>
                @else
                    {{-- VERSI DESKTOP: TABEL (Muncul di layar md ke atas) --}}
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/60 text-xs font-bold text-[#545523]/80 uppercase tracking-wider border-b border-gray-100">
                                    <th class="py-4 px-6">ID Pesanan</th>
                                    <th class="py-4 px-6">Pelanggan</th>
                                    <th class="py-4 px-6">Menu</th>
                                    <th class="py-4 px-6 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100/70">
                                @foreach($claims as $item)
                                    <tr class="hover:bg-gray-50/40 transition-colors">
                                        <td class="py-4 px-6 text-sm font-semibold text-gray-500">
                                            {{ $item->id_pesanan }}
                                        </td>
                                        <td class="py-4 px-6 text-sm font-bold text-[#545523]">
                                            {{ $item->nama_pelanggan }}
                                        </td>
                                        <td class="py-4 px-6 text-sm text-gray-700 font-medium whitespace-pre-line">
                                            {{ $item->nama_menu }}
                                        </td>
                                        <td class="py-4 px-6 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('pesanan.detail', $item->id) }}" class="text-xs bg-gray-100 text-gray-600 px-3 py-1.5 rounded-xl font-bold hover:bg-gray-200 transition-all cursor-pointer">
                                                    Detail
                                                </a>
                                                <form action="{{ route('merchant.claim.verifikasi', $item->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="text-xs bg-[#545523] text-[#F1F2CF] px-3 py-1.5 rounded-xl font-bold hover:bg-[#43441c] transition-all cursor-pointer shadow-sm">
                                                        Selesai
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- VERSI MOBILE: KARTU LIST (Muncul hanya di layar kecil / HP) --}}
                    <div class="block md:hidden divide-y divide-gray-100">
                        @foreach($claims as $item)
                            <div class="p-5 space-y-3 bg-[#FDFDF5]">
                                <div class="flex justify-between items-center">
                                    <span class="text-xs font-semibold text-gray-400">{{ $item->id_pesanan }}</span>
                                    <span class="text-xs font-bold text-[#545523]">{{ $item->nama_pelanggan }}</span>
                                </div>
                                
                                <div class="text-sm text-gray-700 font-medium bg-gray-50/50 p-3 rounded-xl border border-gray-100">
                                    <span class="text-[10px] text-gray-400 block font-bold uppercase mb-0.5">Item Pesanan</span>
                                    {{ $item->nama_menu }}
                                </div>

                                <div class="flex items-center gap-2 pt-1">
                                    <a href="{{ route('pesanan.detail', $item->id) }}" class="flex-1 text-center text-xs bg-gray-100 text-gray-600 py-2.5 rounded-xl font-bold hover:bg-gray-200 transition-all">
                                        Detail
                                    </a>
                                    <form action="{{ route('merchant.claim.verifikasi', $item->id) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" class="w-full text-center text-xs bg-[#545523] text-[#F1F2CF] py-2.5 rounded-xl font-bold hover:bg-[#43441c] transition-all cursor-pointer shadow-sm">
                                            Selesai Ambil
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>
        </section>
    </div>
</body>
</html>