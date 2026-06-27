<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Lupa Password - SaveEat</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
    </style>
</head>
<body class="bg-gradient-to-r from-[#CFD086] to-[#F1F2CF] min-h-screen font-[Poppins] pb-6">
    <nav class="container mx-auto px-4 py-6 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <img src="{{ asset('img/logo-saveat.png') }}" alt="SaveEat Logo" class="w-10 h-10">
            <h1 class="text-3xl font-bold text-[#545523]">SaveEat</h1>
        </div>
    </nav>

    <section class="bg-[#FFFFFA] p-8 rounded-3xl shadow-lg max-w-md mx-auto mt-10">
        <h2 class="text-2xl font-bold mb-6 text-center text-[#6D6B2E]">Lupa Password</h2>
        
        @if(session('status'))
            <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm">
                {{ session('status') }}
            </div>
        @endif

        <form action="{{ route('password.email') }}" method="POST" class="space-y-4">
            @csrf
            <p class="text-[#6D6B2E] text-sm text-center">
                Masukkan email Anda dan kami akan mengirimkan tautan untuk mengatur ulang password Anda.
            </p>

            @if($errors->any())
                <div class="bg-red-100 text-red-700 px-4 py-3 rounded-2xl text-xs font-semibold border border-red-200">
                    {{ $errors->first() }}
                </div>
            @endif

            <div>
                <label for="email" class="block text-md text-[#6D6B2E] mb-2">Email</label>
                <div class="relative">
                    <i class="fa-regular fa-envelope absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Masukkan email Anda"
                        required
                        class="w-full bg-[#F2F3F7] rounded-full py-4 pl-14 pr-4 shadow-md outline-none focus:ring-2 focus:ring-[#6D6B2E]"
                    >
                </div>
            </div>

            <button type="submit" class="w-full bg-[#E4E180] hover:bg-[#6D6B2E] text-white py-4 px-4 rounded-full font-bold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Kirim Tautan Reset
            </button>
        </form>

    </section>

    <div class="text-center mt-6">
        <a href="{{ route('login') }}" class="text-blue-500 hover:underline">Kembali ke Login</a>
    </div>
</body>
</html>
