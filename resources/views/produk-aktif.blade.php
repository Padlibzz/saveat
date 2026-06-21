@section('page_title', 'Listing Aktif')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Listing Aktif - Saveat</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
    </style>
</head>
<body
    x-data="{ sidebarOpen: false }"
    class="bg-gradient-to-r from-[#CFD086] to-[#F1F2CF] min-h-screen font-[Poppins]">

    <div class="flex min-h-screen">
        <x-sidebar />

        <div class="flex-1 flex flex-col justify-between min-h-screen">
            
            <div>
                <x-navbar />

                <section class="p-6 lg:ml-64 max-w-5xl mx-auto w-full space-y-6">
                    
                    @if(session('success'))
                        <x-alert type="success" :message="session('success')" />
                    @endif
                    @if(session('error'))
                        <x-alert type="error" :message="session('error')" />
                    @endif

                    <div class="flex justify-between items-center border-b border-[#545523]/10 pb-2">
                        <h1 class="text-xl font-bold text-[#545523]">Listing Aktif</h1>
                        <a href="#" class="text-xs font-semibold text-[#545523] hover:underline flex items-center gap-1">
                            Lihat Semua <i class="fa-solid fa-chevron-right text-[10px]"></i>
                        </a>
                    </div>

                    @if($listings->isEmpty())
                        <div class="bg-[#FDFDF5]/50 border-2 border-dashed border-[#545523]/20 rounded-2xl p-12 text-center">
                            <div class="text-[#545523]/40 text-4xl mb-3">
                                <i class="fa-solid fa-utensils"></i>
                            </div>
                            <p class="text-sm font-medium text-[#545523]/70">Belum ada makanan yang di-listing hari ini.</p>
                            <a href="/merchant/upload-makanan" class="mt-3 inline-block text-xs bg-[#545523] text-[#F1F2CF] px-4 py-2 rounded-xl font-medium shadow-sm">
                                Tambah Produk Baru
                            </a>
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($listings as $item)
                                <div class="bg-[#FDFDF5] rounded-2xl shadow-sm border border-[#545523]/10 overflow-hidden flex flex-col justify-between group hover:shadow-md transition-all">
                                    
                                    <div class="relative w-full aspect-video bg-gray-100">
                                        @if($item->foto)
                                            <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-300 bg-gray-50">
                                                <i class="fa-solid fa-image text-3xl"></i>
                                            </div>
                                        @endif

                                        @if($item->stok_sisa <= 1)
                                            <span class="absolute top-3 right-3 bg-red-600 text-white text-[11px] font-bold px-3 py-1 rounded-full shadow-sm animate-pulse">
                                                Sisa {{ $item->stok_sisa }}
                                            </span>
                                        @else
                                            <span class="absolute top-3 right-3 bg-[#545523]/90 text-[#F1F2CF] text-[11px] font-medium px-3 py-1 rounded-full shadow-sm">
                                                Stok: {{ $item->stok_sisa }}
                                            </span>
                                        @endif

                                        <div class="absolute bottom-3 left-3 bg-[#e69c24] text-white text-[11px] font-bold px-2.5 py-1 rounded-lg flex items-center gap-1.5 shadow-sm">
                                            <i class="fa-solid fa-clock"></i>
                                            <span>{{ date('H:i', strtotime($item->batas_waktu)) }}</span>
                                        </div>
                                    </div>

                                    <div class="p-4 flex-1 flex flex-col justify-between space-y-3">
                                        <div>
                                            <h3 class="font-bold text-gray-800 text-base line-clamp-1 group-hover:text-[#545523] transition-colors">
                                                {{ $item->nama }}
                                            </h3>
                                            
                                            <div class="flex items-center gap-2 mt-1">
                                                <span class="text-xs text-gray-400 line-through">
                                                    Rp {{ number_format($item->harga_normal, 0, ',', '.') }}
                                                </span>
                                                <span class="text-sm font-extrabold text-[#545523]">
                                                    Rp {{ number_format($item->harga_diskon, 0, ',', '.') }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-2 pt-1">
                                            <a href="/merchant/listing/{{ $item->id }}/edit" class="flex-1 text-center border border-gray-300 text-gray-600 rounded-xl py-2 text-xs font-semibold hover:bg-gray-50 hover:text-gray-800 transition-all">
                                                Edit
                                            </a>

                                            <form action="/merchant/listing/{{ $item->id }}/tutup" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menutup listing makanan ini?')">
                                                @csrf
                                                <button type="submit" class="border border-red-200 text-red-500 hover:bg-red-50 p-2 rounded-xl transition-all w-9 h-9 flex items-center justify-center" title="Tutup Listing">
                                                    <i class="fa-regular fa-trash-can text-sm"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                </div>
                            @endforeach
                        </div>
                    @endif

                </section>
            </div>

            <x-footer />
        </div>
    </div>

</body>
</html>