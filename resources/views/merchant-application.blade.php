<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Daftar Merchant</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
    </style>
</head>
</head>

<body class="bg-gradient-to-r from-[#CFD086] to-[#F1F2CF] min-h-screen font-[Poppins]">
    <nav class="container mx-auto px-4 py-6">
        <div class="flex items-center gap-3">
            <img src="{{ asset('svg/logo.png') }}"
                alt="SaveEat Logo"
                class="w-10 h-10">
            <h1 class="text-3xl font-bold text-[#545523]">
                SaveEat
            </h1>
        </div>
    </nav>

    <section class="bg-[#FFFFFA] p-8 rounded-3xl shadow-lg max-w-2xl mx-auto mt-10">
        <h2 class="text-3xl font-bold text-center text-[#6D6B2E] mb-2">
            Daftar Merchant
        </h2>
        <p class="text-center text-gray-500 mb-8">
            Lengkapi data usaha Anda untuk mengajukan verifikasi merchant.
        </p>
        <form action="{{ route('merchant.application.submit') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-[#6D6B2E] mb-2">
                    Nama Usaha
                </label>
                <div class="relative">
                    <i class="fa-solid fa-store absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input
                        type="text"
                        id="nama_usaha"
                        name="nama_usaha"
                        required
                        class="w-full bg-[#F2F3F7] rounded-full py-4 pl-14 pr-4 shadow-md outline-none focus:ring-2 focus:ring-[#6D6B2E]"
                        placeholder="Masukkan nama usaha">
                </div>
            </div>
            <div>

                <label class="block text-[#6D6B2E] mb-2">
                    Alamat
                </label>

                <div class="relative">

                    <i class="fa-solid fa-location-dot absolute left-5 top-5 text-gray-400"></i>

                    <textarea
                        id="alamat"
                        name="alamat"
                        rows="3"
                        class="w-full bg-[#F2F3F7] rounded-3xl py-4 pl-14 pr-4 shadow-md outline-none focus:ring-2 focus:ring-[#6D6B2E]"
                        placeholder="Masukkan alamat usaha"></textarea>

                </div>

            </div>

            <!-- Deskripsi -->
            <div>

                <label class="block text-[#6D6B2E] mb-2">
                    Deskripsi Usaha
                </label>

                <textarea
                    id="deskripsi"
                    name="deskripsi"
                    rows="4"
                    class="w-full bg-[#F2F3F7] rounded-3xl p-4 shadow-md outline-none focus:ring-2 focus:ring-[#6D6B2E]"
                    placeholder="Ceritakan usaha Anda"></textarea>

            </div>

            <!-- Link Maps -->
            <div>

                <label class="block text-[#6D6B2E] mb-2">
                    Link Google Maps
                </label>
                <div class="relative">
                    <i class="fa-solid fa-map absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input
                        type="url"
                        name="link_map"
                        id="link_map"
                        class="w-full bg-[#F2F3F7] rounded-full py-4 pl-14 pr-4 shadow-md outline-none focus:ring-2 focus:ring-[#6D6B2E]"
                        placeholder="https://maps.google.com/...">

                </div>

            </div>

            <button
                type="submit"
                class="w-full bg-[#6D6B2E] hover:bg-[#545523] text-white py-4 rounded-full font-semibold transition">

                Ajukan Menjadi Merchant

            </button>

        </form>

    </section>

</body>
</html>