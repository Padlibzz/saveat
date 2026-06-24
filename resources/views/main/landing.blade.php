@extends('layouts.landing')

@section('page_title', 'SaveEat - Save Food, Save Money, Save Earth')

@section('content')
    {{-- HERO SECTION --}}
    <section class="bg-[#EEE0E0] m-4 md:m-6 rounded-3xl shadow-sm">
        <div class="container mx-auto px-6 py-12 md:py-20">
            <div class="flex flex-col lg:flex-row items-center gap-12">
                
                <div class="w-full lg:w-1/2 text-center lg:text-left order-2 lg:order-1">
                    <h1 class="text-4xl md:text-5xl xl:text-6xl font-extrabold text-[#545523]">
                        Save Food.
                    </h1>
                    <h1 class="text-4xl md:text-5xl xl:text-6xl text-[#6D6B2E] my-2 font-medium">
                        Save Money.
                    </h1>
                    <h1 class="text-4xl md:text-5xl xl:text-6xl font-extrabold text-[#545523]">
                        Save Earth.
                    </h1>

                    <p class="mt-6 text-base md:text-lg text-[#545523] leading-relaxed max-w-xl mx-auto lg:mx-0">
                        Temukan makanan layak konsumsi dengan harga lebih terjangkau sambil membantu mengurangi food waste untuk masa depan yang lebih baik.
                    </p>

                    <a href="auth/login"
                        class="inline-block mt-8 bg-[#545523] text-white px-8 py-3 rounded-xl font-semibold shadow-md hover:bg-opacity-90 transition w-full sm:w-auto">
                        Explore Now
                    </a>
                </div>

                <div class="w-full lg:w-1/2 flex justify-center order-1 lg:order-2">
                    <img src="{{ asset('img/sample-food.png') }}" alt="Food" class="w-full max-w-xs md:max-w-md lg:max-w-lg">
                </div>

            </div>
        </div>
    </section>

    {{-- STATISTIK SECTION --}}
    <section class="bg-white m-4 md:m-6 rounded-3xl shadow-sm">
        <div class="container mx-auto px-6 py-12 md:py-16">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 text-center divide-y sm:divide-y-0 sm:divide-x divide-gray-100">
                <div class="py-4 sm:py-0">
                    <h2 class="text-4xl md:text-5xl font-extrabold text-[#545523]">10,000+</h2>
                    <p class="mt-2 text-base md:text-lg font-medium text-gray-600">Makanan Terselamatkan</p>
                </div>
                <div class="py-4 sm:py-0">
                    <h2 class="text-4xl md:text-5xl font-extrabold text-[#545523]">500+</h2>
                    <p class="mt-2 text-base md:text-lg font-medium text-gray-600">Merchant</p>
                </div>
                <div class="py-4 sm:py-0">
                    <h2 class="text-4xl md:text-5xl font-extrabold text-[#545523]">4.8/5</h2>
                    <p class="mt-2 text-base md:text-lg font-medium text-gray-600">Rating Pengguna</p>
                </div>
            </div>
        </div>

        {{-- CARA KERJA SECTION --}}
        <div class="container mx-auto px-6 py-12 md:py-16 text-center border-t border-gray-50">
            <h2 class="text-2xl md:text-4xl font-bold text-[#545523]">
                Menyelamatkan makanan itu mudah
            </h2>
            <p class="mt-3 text-sm md:text-base text-gray-500 max-w-xl mx-auto">
                Tiga langkah sederhana untuk membuat perbedaan bagi dompet Anda dan planet ini.
            </p>
            
            <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-10 lg:gap-16">
                <div class="flex flex-col items-center">
                    <div class="bg-[#545523] rounded-2xl p-4 w-16 h-16 flex items-center justify-center shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 9l1.5-4.5A1.5 1.5 0 016 3h12a1.5 1.5 0 011.5 1.5L21 9m-18 0a3 3 0 003 3h12a3 3 0 003-3m-18 0v9a1.5 1.5 0 001.5 1.5h15A1.5 1.5 0 0021 18V9" />
                        </svg>
                    </div>
                    <h3 class="text-[#545523] font-bold text-lg mt-4">Unggahan Merchant</h3>
                    <p class="mt-2 text-sm text-gray-500 max-w-sm">Toko roti, kafe, dan toko lokal mencantumkan kelebihan makanan mereka di akhir hari.</p>
                </div>

                <div class="flex flex-col items-center">
                    <div class="bg-[#545523] rounded-2xl p-4 w-16 h-16 flex items-center justify-center shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35m0 0A7.5 7.5 0 1 0 6.04 6.04a7.5 7.5 0 0 0 10.61 10.61Z" />
                        </svg>
                    </div>
                    <h3 class="text-[#545523] font-bold text-lg mt-4">Konsumen Klaim</h3>
                    <p class="mt-2 text-sm text-gray-500 max-w-sm">Telusuri Merchant yang tersedia di dekat Anda dan pesan barang favorit Anda melalui aplikasi.</p>
                </div>

                <div class="flex flex-col items-center">
                    <div class="bg-[#545523] rounded-2xl p-4 w-16 h-16 flex items-center justify-center shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m6 2.25a9 9 0 11-18 0 9 9 0 0118 0Z" />
                        </svg>
                    </div>
                    <h3 class="text-[#545523] font-bold text-lg mt-4">Klaim Cepat</h3>
                    <p class="mt-2 text-sm text-gray-500 max-w-sm">Datanglah ke toko pada waktu pengambilan yang telah ditentukan dan nikmati penghematan yang lezat.</p>
                </div>
            </div>
        </div>

        {{-- LISTING MAKANAN --}}
        <div class="pb-16 px-4">
            <h2 class="text-2xl md:text-3xl font-bold text-[#545523] text-center">Tersedia di sekitar Anda</h2>
            <p class="mt-2 text-sm text-gray-500 text-center">Segera dapatkan sebelum kehabisan!</p>

            <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 max-w-7xl mx-auto px-4">
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
    </section>

    {{-- 5. CTAs GABUNG MITRA --}}
    <section class="bg-[#F8FDD2] p-4 md:p-6 rounded-3xl m-4 md:m-6">
        <div class="bg-white px-6 py-12 md:py-20 rounded-2xl shadow-sm">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-12 max-w-6xl mx-auto">
                <div class="w-full lg:w-1/2 text-center lg:text-left">
                    <span class="bg-[#DCFCE7] px-3 py-1 rounded-full text-xs font-semibold text-[#545523] inline-block">
                        Gabung Merchant
                    </span>
                    <h2 class="text-3xl md:text-4xl font-bold text-black mt-4 leading-tight">
                        Selamatkan Makanan, <br class="hidden sm:inline" /> Tingkatkan Pendapatan.
                    </h2>
                    <p class="mt-4 text-sm md:text-base text-gray-600">
                        Bergabunglah dengan jaringan merchant Saveat dan temukan cara baru untuk menjual makanan surplus yang masih layak konsumsi.
                    </p>
                    <button class="bg-[#545523] text-white px-6 py-3 rounded-xl mt-8 font-semibold shadow-md hover:bg-[#3f401a] transition w-full sm:w-auto">
                        Explore Merchant
                    </button>
                </div>

                <div class="w-full lg:w-1/2 flex flex-wrap gap-3 justify-center">
                    @foreach(['Mengurangi Limbah Makanan', 'Menambah Pendapatan', 'Ramah Lingkungan', 'Pelanggan Baru'] as $tag)
                        <span class="bg-[#DCFCE7] px-4 py-2 rounded-full text-sm md:text-base font-medium text-[#545523] shadow-xs">
                            {{ $tag }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- FOOTER SECTION --}}
    <footer class="bg-white border-t border-gray-100 mx-4 md:m-6 rounded-3xl shadow-xs">
        <div class="max-w-7xl mx-auto px-6 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-10">
                <div class="md:col-span-2 lg:col-span-2">
                    <h1 class="text-xl font-bold text-[#545523]">Saveat</h1>
                    <p class="mt-4 text-gray-500 text-sm leading-relaxed max-w-md">
                        Setiap makanan yang terselamatkan adalah langkah kecil menuju masa depan yang lebih baik. Bergabunglah dengan Saveat dan bantu kami mengurangi food waste untuk bumi yang lebih hijau.
                    </p>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-gray-400 tracking-wider uppercase mb-4">Perusahaan</h4>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li><a href="#" class="hover:text-[#545523]">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-[#545523]">Karir</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-gray-400 tracking-wider uppercase mb-4">Layanan</h4>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li><a href="#" class="hover:text-[#545523]">Cari Makanan</a></li>
                        <li><a href="#" class="hover:text-[#545523]">Gabung Merchant</a></li>
                    </ul>
                </div>
            </div>

            <div class="mt-12 pt-8 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-gray-400">
                <p>&copy; 2026 Saveat. Hak Cipta Dilindungi.</p>
                <div class="flex gap-6">
                    <a href="#" class="hover:underline">Kebijakan Privasi</a>
                    <a href="#" class="hover:underline">Syarat & Ketentuan</a>
                </div>
            </div>
        </div>
    </footer>
@endsection