@section('page_title', 'Listing Makanan')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Makanan</title>
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

        <!-- Main Content -->
        <div class="flex-1">

            <!-- Navbar -->
            <x-navbar />

            <!-- Content -->
            <section class="p-6 mb-2 lg:ml-64">
            @if(session('success'))
                <x-alert type="success" :message="session('success')" />
            @endif

            @if(session('error'))
                <x-alert type="error" :message="session('error')" />
            @endif
                <!-- Search -->
                <form action="{{ route('listing-makanan') }}" method="GET" class="mb-4">
                    <div class="relative">
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Temukan Makananmu"
                            class="w-full p-4 rounded-full shadow-md bg-white outline-none focus:ring-2 focus:ring-[#6D6B2E]">
                        <button type="submit" class="absolute right-4 top-1/2 -translate-y-1/2 text-[#6D6B2E]">
                            <i class="fa-solid fa-search"></i>
                        </button>
                    </div>
                </form>

                {{-- kategori ceklis --}}

                <!-- Recommendation -->
                <div class="mt-8">

                    <h2 class="text-2xl font-semibold text-[#545523] mb-5">
                        Temukan Makanan Favorit mu
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

                        @forelse ($listings as $listing)
                            <x-food-card
                                :id="$listing->id" :foto="$listing->foto"
                                :nama="$listing->nama"
                                :merchant="$listing->merchant?->nama_usaha ?? 'Merchant'"
                                :alamat="$listing->merchant?->alamat ?? '-'"
                                :jarak="$listing->jarak_km ?? '-'"
                                :harga_diskon="$listing->harga_diskon"
                                :harga_asli="$listing->harga_normal"
                                :tersisa="$listing->stok_sisa"
                            />
                        @empty
                            <div class="col-span-full">
                                <div class="bg-white rounded-xl p-8 text-center shadow">
                                    <i class="fa-solid fa-utensils text-4xl text-gray-400 mb-3"></i>
                                    <p class="text-gray-500">
                                        Belum ada makanan tersedia saat ini.
                                    </p>
                                </div>
                            </div>
                        @endforelse

                    </div>

                    <div class="mt-8">
                        {{ $listings->links() }}
                    </div>

                </div>

            </section>

        </div>

    </div>

</body>
</html>