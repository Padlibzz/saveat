@section('page_title', 'Edit Listing')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Edit Listing - Saveat</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
    </style>
</head>
<body
    x-data="{ sidebarOpen: false }"
    class="bg-gradient-to-r from-[#CFD086] to-[#F1F2CF] min-h-screen font-[Poppins]">

    <div class="flex min-h-screen">
        <x-sidebar />

        <div class="flex-1">
            <x-navbar />

            <section class="p-6 lg:ml-64">

                <div class="bg-[#FAF7F2] rounded-2xl p-6 shadow-sm max-w-2xl mx-auto">

                    <div class="flex items-center gap-3 mb-6">
                        <div class="bg-[#545523] w-10 h-10 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-pen text-white"></i>
                        </div>
                        <h2 class="text-xl font-bold text-[#545523]">Edit Listing Makanan</h2>
                    </div>

                    @if ($errors->any())
                        <div class="bg-red-100 text-red-700 px-4 py-3 rounded-lg mb-4">
                            <ul class="list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('merchant.listing.update', $listing->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <label class="block text-sm font-semibold text-[#545523] mb-1">Nama Makanan</label>
                        <input type="text" name="nama" value="{{ old('nama', $listing->nama) }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 mb-4 focus:outline-none focus:border-[#545523]">

                        <label class="block text-sm font-semibold text-[#545523] mb-1">Kategori</label>
                        <div class="flex flex-wrap gap-2 mb-4">
                            @foreach ($kategoris as $kategori)
                                <label class="cursor-pointer">
                                    <input type="radio" name="kategori_id" value="{{ $kategori->id }}"
                                        {{ old('kategori_id', $listing->kategori_id) == $kategori->id ? 'checked' : '' }}
                                        class="hidden peer">
                                    <span class="px-4 py-2 rounded-full border border-gray-300 text-sm text-[#545523]
                                        peer-checked:bg-[#CFD086] peer-checked:border-[#545523] peer-checked:font-semibold transition">
                                        {{ $kategori->nama }}
                                    </span>
                                </label>
                            @endforeach
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-semibold text-[#545523] mb-1">Harga Normal</label>
                                <input type="number" name="harga_normal" value="{{ old('harga_normal', $listing->harga_normal) }}" min="0"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-[#545523]">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-[#545523] mb-1">Harga Saveat (Promo)</label>
                                <input type="number" name="harga_diskon" value="{{ old('harga_diskon', $listing->harga_diskon) }}" min="0"
                                    class="w-full border-2 border-green-500 rounded-lg px-4 py-2 text-green-700 font-bold focus:outline-none">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-semibold text-[#545523] mb-1">Stok Tersedia</label>
                                <input type="number" name="stok_total" value="{{ old('stok_total', $listing->stok_total) }}" min="1"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-[#545523]">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-[#545523] mb-1">Batas Waktu Ambil</label>
                                <input type="datetime-local" name="batas_waktu"
                                    value="{{ old('batas_waktu', \Carbon\Carbon::parse($listing->batas_waktu)->format('Y-m-d\TH:i')) }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-[#545523]">
                            </div>
                        </div>

                        <label class="block text-sm font-semibold text-[#545523] mb-1">Ubah Foto</label>
                        @if ($listing->foto)
                            <img src="{{ asset('storage/'.$listing->foto) }}" class="w-24 h-24 object-cover rounded-lg mb-2">
                        @endif
                        <label class="block border-2 border-dashed border-gray-300 rounded-lg p-8 text-center cursor-pointer hover:border-[#545523] transition mb-4">
                            <input type="file" name="foto" accept="image/jpeg,image/png,image/jpg" class="hidden">
                            <i class="fa-solid fa-cloud-arrow-up text-2xl text-[#545523]"></i>
                            <p class="text-sm text-[#545523] mt-2 font-semibold">Klik atau seret foto ke sini</p>
                            <p class="text-xs text-gray-400">JPG, PNG (Maks. 2MB)</p>
                        </label>

                        <label class="block text-sm font-semibold text-[#545523] mb-1">Deskripsi (Kondisi/Isi)</label>
                        <textarea name="deskripsi" rows="3"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 mb-6 focus:outline-none focus:border-[#545523]">{{ old('deskripsi', $listing->deskripsi) }}</textarea>

                        <div class="flex justify-end items-center gap-4">
                            <a href="{{ route('merchant.produk-aktif') }}" class="text-[#545523] font-semibold">Batal</a>
                            <button type="submit" class="bg-[#545523] text-white px-6 py-3 rounded-xl font-semibold hover:opacity-90 transition">
                                Edit Listing
                            </button>
                        </div>
                    </form>
                </div>

            </section>

            <x-footer/>
        </div>
    </div>

</body>
</html>
