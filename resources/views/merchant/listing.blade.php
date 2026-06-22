@section('page_title', 'Produk Aktif')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Produk Aktif - Saveat</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
    </style>
</head>
<body
    x-data="{ sidebarOpen: false, showDeleteModal: false, deleteFormId: null }"
    class="bg-gradient-to-r from-[#CFD086] to-[#F1F2CF] min-h-screen font-[Poppins]">

    <div class="flex min-h-screen">
        <x-sidebar />

        <div class="flex-1">
            <x-navbar />

            <section class="p-6 lg:ml-64">

                @if (session('success'))
                    <div class="bg-green-100 text-green-800 px-4 py-3 rounded-lg mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="bg-red-100 text-red-700 px-4 py-3 rounded-lg mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-[#545523]">Listing Aktif</h2>
                    <a href="{{ route('merchant.upload') }}" class="bg-[#545523] text-white px-5 py-2 rounded-lg text-sm font-semibold">
                        + Tambah Listing
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @forelse ($listings as $listing)
                        <div class="bg-[#FAF7F2] rounded-xl overflow-hidden shadow-sm">
                            <div class="relative">
                                <img src="{{ $listing->foto ? asset('storage/'.$listing->foto) : asset('img/sample-food.png') }}"
                                     alt="{{ $listing->nama }}" class="w-full h-36 object-cover">

                                <span class="absolute top-2 right-2 text-xs font-semibold px-2 py-1 rounded-full
                                    {{ $listing->stok_sisa <= 1 ? 'bg-red-500 text-white' : 'bg-white/90 text-[#545523]' }}">
                                    {{ $listing->stok_sisa <= 1 ? 'Sisa '.$listing->stok_sisa : 'Stok: '.$listing->stok_sisa }}
                                </span>

                                <span class="absolute bottom-2 left-2 bg-[#545523]/90 text-white text-xs px-2 py-1 rounded-full">
                                    <i class="fa-regular fa-clock"></i>
                                    {{ \Carbon\Carbon::parse($listing->batas_waktu)->format('H:i') }}
                                </span>
                            </div>

                            <div class="p-4">
                                <h3 class="font-semibold text-[#545523]">{{ $listing->nama }}</h3>
                                <p class="text-sm mt-1">
                                    <span class="text-gray-400 line-through">Rp{{ number_format($listing->harga_normal, 0, ',', '.') }}</span>
                                    <span class="text-[#545523] font-bold ml-1">Rp{{ number_format($listing->harga_diskon, 0, ',', '.') }}</span>
                                </p>

                                <div class="flex items-center gap-2 mt-3">
                                    <a href="{{ route('merchant.listing.edit', $listing->id) }}"
                                       class="flex-1 text-center border border-gray-300 rounded-lg py-2 text-sm font-semibold text-[#545523] hover:bg-gray-50">
                                        Edit
                                    </a>
                                    <button type="button"
                                        @click="showDeleteModal = true; deleteFormId = {{ $listing->id }}"
                                        class="border border-red-300 text-red-500 rounded-lg px-3 py-2 hover:bg-red-50">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-[#545523] col-span-full text-center py-10">Belum ada listing. Yuk tambahkan listing pertama Anda!</p>
                    @endforelse
                </div>

            </section>

            <!-- Modal Konfirmasi Hapus (Gambar 1) -->
            <div x-show="showDeleteModal" x-cloak
                 class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
                <div @click.outside="showDeleteModal = false"
                     class="bg-[#FAF7F2] rounded-2xl p-8 max-w-sm w-full text-center">
                    <h3 class="font-bold text-[#545523] text-lg">Anda yakin menghapus listing ini?</h3>
                    <p class="text-gray-500 text-sm mt-2">
                        Listing yang sudah anda hapus tidak bisa dipulihkan kembali
                    </p>

                    <div class="flex items-center justify-center gap-6 mt-6">
                        <button type="button" @click="showDeleteModal = false"
                            class="text-[#545523] font-semibold">
                            Batal
                        </button>

                        <span class="text-gray-300">|</span>

                        <form :action="'/merchant/produk-aktif/' + deleteFormId" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 font-bold">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <x-footer/>
        </div>
    </div>

</body>
</html>
