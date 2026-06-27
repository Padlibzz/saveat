<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Reset Password - SaveEat</title>
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
        <h2 class="text-2xl font-bold mb-6 text-center text-[#6D6B2E]">Reset Password</h2>
        
        <form action="{{ route('password.update') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            @if($errors->any())
                <div class="bg-red-100 text-red-700 px-4 py-3 rounded-2xl text-xs font-semibold border border-red-200">
                    {{ $errors->first() }}
                </div>
            @endif

            <div>
                <label for="email" class="block text-md text-[#6D6B2E] mb-2">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', request()->email) }}" required class="w-full bg-[#F2F3F7] rounded-full py-4 px-6 shadow-md outline-none focus:ring-2 focus:ring-[#6D6B2E]">
            </div>

            <div>
                <label for="password" class="block text-md text-[#6D6B2E] mb-2">Password Baru</label>
                <input type="password" id="password" name="password" required class="w-full bg-[#F2F3F7] rounded-full py-4 px-6 shadow-md outline-none focus:ring-2 focus:ring-[#6D6B2E]">
            </div>

            <div>
                <label for="password_confirmation" class="block text-md text-[#6D6B2E] mb-2">Konfirmasi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required class="w-full bg-[#F2F3F7] rounded-full py-4 px-6 shadow-md outline-none focus:ring-2 focus:ring-[#6D6B2E]">
            </div>

            <button type="submit" class="w-full bg-[#E4E180] hover:bg-[#6D6B2E] text-white py-4 px-4 rounded-full font-bold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Reset Password
            </button>
        </form>
    </section>
</body>
</html>
