<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Landing Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
    </style>
</head>
<body class="bg-gradient-to-r from-[#CFD086] to-[#F1F2CF] min-h-screen font-[Poppins]">
    <nav class="container mx-auto px-4 py-6 flex items-center justify-between">

        <div class="flex items-center gap-3">
            <img src="{{ asset('img/logo-saveat.png') }}" alt="SaveEat Logo" class="w-10 h-10">

            <h1 class="text-3xl font-bold text-[#545523]">
                SaveEat
            </h1>
        </div>

        <div class="flex items-center gap-4">
            <a href="auth/login" class="py-2 px-6 text-[#545523]">
                Masuk
            </a>

            <a href="auth/register" class="bg-[#545523] py-2 px-6 text-white rounded-lg">
                Daftar
            </a>
        </div>

    </nav>
    <section class="bg-[#EEE0E0] m-6 rounded-lg">
        <div class="container mx-auto px-6 py-20">
            <div class="flex flex-col md:flex-row items-center gap-12">
                <div class="md:w-1/2">
                    <h1 class="text-6xl font-extrabold text-[#545523]">
                        Save Food.
                    </h1>

                    <h1 class="text-6xl text-[#6D6B2E] my-2">
                        Save Money.
                    </h1>

                    <h1 class="text-6xl font-extrabold text-[#545523]">
                        Save Earth.
                    </h1>

                    <p class="mt-6 text-lg text-[#545523] leading-relaxed max-w-xl">
                        Temukan makanan layak konsumsi dengan harga lebih terjangkau
                        sambil membantu mengurangi food waste untuk masa depan yang
                        lebih baik.
                    </p>

                    <a href="auth/login"
                        class="inline-block mt-8 bg-[#545523] text-white px-8 py-3 rounded-lg hover:opacity-90 transition">
                        Explore Now
                    </a>
                </div>

                <div class="md:w-1/2 flex justify-center">
                    <img src="{{ asset('img/sample-food.png') }}"
                        alt="Food"
                        class="w-full max-w-lg">
                </div>

            </div>

        </div>
    </section>

    <section class="bg-white m-6 rounded-lg">
         <div class="container mx-auto px-6 py-20">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 text-center">
                <div>
                    <h2 class="text-6xl font-extrabold text-[#545523]">
                        {{ number_format($stats->makanan_terselamatkan) }}+
                    </h2>
                    <p class="mt-3 text-2xl font-semibold text-gray-700">
                        Makanan Terselamatkan
                    </p>
                </div>

                <div>
                    <h2 class="text-6xl font-extrabold text-[#545523]">
                        {{ $stats->total_merchant }}+
                    </h2>
                    <p class="mt-3 text-2xl font-semibold text-gray-700">
                        Merchant
                    </p>
                </div>

                <div>
                    <h2 class="text-6xl font-extrabold text-[#545523]">
                        {{ $stats->rating_pengguna }}/5
                    </h2>
                    <p class="mt-3 text-2xl font-semibold text-gray-700">
                        Rating Pengguna
                    </p>
                </div>
            </div>

        </div>

        <div class="container mx-auto px-6 py-20 text-center">
            <h2 class="text-4xl font-bold text-[#545523]">
                Menyelamatkan makanan itu mudah
            </h2>
            <p class="mt-4 text-lg text-[#545523]">
                Tiga langkah sederhana untuk membuat perbedaan bagi dompet Anda dan planet ini.
            </p>
            
            <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-15">

    <!-- Unggahan Merchant -->
                <div class="text-center">
                    <div class="bg-[#545523] rounded-full p-4 mx-auto w-20 h-20 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-10 h-10 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 9l1.5-4.5A1.5 1.5 0 016 3h12a1.5 1.5 0 011.5 1.5L21 9m-18 0a3 3 0 003 3h12a3 3 0 003-3m-18 0v9a1.5 1.5 0 001.5 1.5h15A1.5 1.5 0 0021 18V9" />
                        </svg>
                    </div>

                    <h3 class="text-[#545523] font-semibold text-xl mt-4">
                        Unggahan Merchant
                    </h3>

                    <p class="mt-4 text-lg text-[#545523]">
                        Toko roti, kafe, dan toko lokal mencantumkan kelebihan makanan mereka di akhir hari.
                    </p>
                </div>

                <!-- Konsumen Klaim -->
                <div class="text-center">
                    <div class="bg-[#545523] rounded-full p-4 mx-auto w-20 h-20 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-10 h-10 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m21 21-4.35-4.35m0 0A7.5 7.5 0 1 0 6.04 6.04a7.5 7.5 0 0 0 10.61 10.61Z" />
                        </svg>
                    </div>

                    <h3 class="text-[#545523] font-semibold text-xl mt-4">
                        Konsumen Klaim
                    </h3>

                    <p class="mt-4 text-lg text-[#545523]">
                        Telusuri Merchant yang tersedia di dekat Anda dan pesan barang favorit Anda melalui aplikasi.
                    </p>
                </div>

                <!-- Klaim Cepat -->
                <div class="text-center">
                    <div class="bg-[#545523] rounded-full p-4 mx-auto w-20 h-20 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-10 h-10 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12.75 11.25 15 15 9.75m6 2.25a9 9 0 11-18 0 9 9 0 0118 0Z" />
                        </svg>
                    </div>

                    <h3 class="text-[#545523] font-semibold text-xl mt-4">
                        Klaim Cepat
                    </h3>

                    <p class="mt-4 text-lg text-[#545523]">
                        Datanglah ke toko pada waktu pengambilan yang telah ditentukan dan nikmati penghematan yang lezat.
                    </p>
                </div>

            </div>
        </div>


        <h2 class="text-4xl font-bold text-[#545523] text-center">
            Tersedia di sekitar Anda
        </h2>
        <p class="mt-4 text-lg text-[#545523] text-center">
            Segera dapatkan sebelum kehabisan!
        </p>

        <div class="flex px-6 py-20 gap-12 overflow-x-auto justify-center">
            @foreach($listings as $listing)
                <x-food-card
                    :foto="$listing->foto"
                    :nama="$listing->nama"
                    :merchant="$listing->merchant?->nama_usaha ?? 'Merchant'"
                    :alamat="$listing->merchant?->alamat ?? 'Alamat belum tersedia'"
                    :jarak="'-'"
                    :harga_diskon="$listing->harga_diskon"
                    :harga_asli="$listing->harga_normal"
                    :tersisa="$listing->stok_sisa"
                />
            @endforeach
        </div>
    </section>

    <section class="bg-[#F8FDD2] m-6 rounded-lg">
        <div class="bg-white px-6 py-20 rounded-lg shadow-md m-10">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-12">

                <div class="lg:w-1/2">
                    <h3 class="bg-[#DCFCE7] px-3 py-1 rounded-full text-sm font-semibold text-[#545523] inline-block">
                        Gabung Merchant
                    </h3>

                    <h2 class="text-4xl font-semibold text-black mt-4">
                        Selamatkan Makanan,
                    </h2>

                    <h2 class="text-4xl font-semibold text-black">
                        Tingkatkan Pendapatan.
                    </h2>

                    <p class="mt-4 text-gray-600">
                        Bergabunglah dengan jaringan merchant Saveat dan temukan cara baru untuk menjual makanan surplus yang masih layak konsumsi.
                    </p>

                    <button class="bg-[#545523] text-white px-6 py-3 rounded-lg mt-8">
                        Explore Merchant
                    </button>
                </div>

                <div class="lg:w-1/2 flex flex-wrap gap-4 justify-center">
                    <span class="bg-[#DCFCE7] px-6 py-3 rounded-full text-lg font-semibold text-[#545523]">
                        Mengurangi Limbah Makanan
                    </span>

                    <span class="bg-[#DCFCE7] px-6 py-3 rounded-full text-lg font-semibold text-[#545523]">
                        Menambah Pendapatan
                    </span>

                    <span class="bg-[#DCFCE7] px-6 py-3 rounded-full text-lg font-semibold text-[#545523]">
                        Ramah Lingkungan
                    </span>

                    <span class="bg-[#DCFCE7] px-6 py-3 rounded-full text-lg font-semibold text-[#545523]">
                        Pelanggan Baru
                    </span>
                </div>

            </div>
        </div>
    </section>

    <section class="m-6">
        <div>
            <h1 class="">
                Saveat
            </h1>
            <p class="">
                Setiap makanan yang terselamatkan adalah langkah kecil menuju masa depan yang lebih baik. Bergabunglah dengan Saveat dan bantu kami mengurangi food waste untuk bumi yang lebih hijau.
            </p>
        </div>
        <div>
            <a href=""></a>
            <a href=""></a>
        </div>
    </section>
</body>
</html>