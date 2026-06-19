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
            <img src="svg/logo.png" alt="SaveEat Logo" class="w-10 h-10">

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

                    <a href="#"
                        class="inline-block mt-8 bg-[#545523] text-white px-8 py-3 rounded-lg hover:opacity-90 transition">
                        Explore Now
                    </a>
                </div>

                <div class="md:w-1/2 flex justify-center">
                    <img src="gambar-makanan.png"
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
                        10,000+
                    </h2>
                    <p class="mt-3 text-2xl font-semibold text-gray-700">
                        Makanan Terselamatkan
                    </p>
                </div>

                <div>
                    <h2 class="text-6xl font-extrabold text-[#545523]">
                        500+
                    </h2>
                    <p class="mt-3 text-2xl font-semibold text-gray-700">
                        Merchant
                    </p>
                </div>

                <div>
                    <h2 class="text-6xl font-extrabold text-[#545523]">
                        4.8/5
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
                <div class="text-center">
                    <img src="svg/merchant.svg" alt="Merchant"
                        class="bg-[#545523] rounded-full p-4 mx-auto">
                    <h3 class="text-[#545523] font-semibold text-xl mt-4">
                        Unggahan Merchant
                    </h3>
                    <p class="mt-4 text-lg text-[#545523]">
                        Toko roti, kafe, dan toko lokal mencantumkan kelebihan makanan mereka di akhir hari.
                    </p>
                </div>

                <div class="text-center">
                    <img src="svg/merchant.svg" alt="Merchant"
                        class="bg-[#545523] rounded-full p-4 mx-auto">
                    <h3 class="text-[#545523] font-semibold text-xl mt-4">
                        Konsumen Klaim
                    </h3>
                    <p class="mt-4 text-lg text-[#545523]">
                        Telusuri Merchant yang tersedia di dekat Anda dan pesan barang favorit Anda melalui aplikasi.
                    </p>
                </div>

                <div class="text-center">
                    <img src="svg/merchant.svg" alt="Merchant"
                        class="bg-[#545523] rounded-full p-4 mx-auto">
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
            {{-- @foreach($listings as $listing)
                <x-food-card
                    :image="$listing->gambar"
                    :nama="$listing->nama"
                    :alamat="$listing->alamat"
                    :jarak="$listing->jarak . ' km'"
                    :merchant="$listing->merchant"
                    :harga-diskon="$listing->harga_diskon"
                    :harga-asli="$listing->harga_asli"
                />
            @endforeach  --}}
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