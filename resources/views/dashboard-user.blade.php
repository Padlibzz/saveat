@section('page_title', 'Dashboard')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>User Dashboard</title>
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

            <section class="p-6 mb-2 lg:ml-64">
            @if(session('success'))
                <x-alert type="success" :message="session('success')" />
            @endif

            @if(session('error'))
                <x-alert type="error" :message="session('error')" />
            @endif
                <!-- Search -->
                <input
                    type="text"
                    placeholder="Temukan Makananmu"
                    class="w-full p-4 rounded-full shadow-md bg-white outline-none focus:ring-2 focus:ring-[#6D6B2E]">

                <!-- Recommendation -->
                <div class="mt-8">

                    <h2 class="text-2xl font-semibold text-[#545523] mb-5">
                        Rekomendasi Untukmu
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

                       @foreach($listings as $listing)
                            <x-food-card
                                data-url="/checkout/{{ $listing->id }}" :foto="$listing->foto"
                                :nama="$listing->nama"
                                :merchant="$listing->merchant?->nama_usaha ?? 'Merchant'"
                                :alamat="$listing->merchant?->alamat ?? '-'"
                                :jarak="$listing->jarak_km ?? '-'"
                                :harga_diskon="$listing->harga_diskon"
                                :harga_asli="$listing->harga_normal"
                                :tersisa="$listing->stok_sisa"
                            />
                        @endforeach

                    </div>

                </div>

                <!-- Join Merchant -->
                @if(Auth::user()->peran !== 'merchant')

                <div class="mt-10 bg-white rounded-3xl shadow-md p-6">

                    <h3 class="text-xl font-semibold text-[#545523]">
                        Mulai Jual Makanan Anda
                    </h3>

                    <p class="text-gray-500 mt-2">
                        Lengkapi data usaha Anda dan ajukan verifikasi merchant.
                    </p>

                    <a
                        href="{{ route('merchant.application') }}"
                        class="inline-block mt-4 bg-[#6D6B2E] text-white px-6 py-3 rounded-xl hover:bg-[#545523]">

                        Daftar Merchant

                    </a>

                </div>

                @endif

            </section>

            <x-footer/>
        </div>

    </div>

</body>
</html>