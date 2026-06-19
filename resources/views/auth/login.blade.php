<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Login Pengguna</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
    </style>
</head>
<body class="bg-gradient-to-r from-[#CFD086] to-[#F1F2CF] min-h-screen font-[Poppins] pb-6">
    <nav class="container mx-auto px-4 py-6 flex items-center justify-between">

        <div class="flex items-center gap-3">
            <img src="svg/logo.png" alt="SaveEat Logo" class="w-10 h-10">

            <h1 class="text-3xl font-bold text-[#545523]">
                SaveEat
            </h1>
        </div>

    </nav>

    <section class="bg-[#FFFFFA] p-8 rounded-3xl shadow-lg max-w-md mx-auto mt-10">
        <h2 class="text-2xl font-bold mb-6 text-center text-[#6D6B2E]">Login</h2>
        @if(session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg mb-4">
                {{ session('success') }}
            </div>
        @endif
        <form action="/auth/login" method="POST" class="space-y-4">
            @csrf
            <h3 class="text-[#6D6B2E] text-lg">
                Mohon masukkan username atau email dan password 
            </h3>
            <div class="space-y-4">

                <div>
                    <label for="username" class="block text-md text-[#6D6B2E] mb-2">
                        Username atau Email
                    </label>

                    <div class="relative">
                        <i class="fa-regular fa-user absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>

                        <input
                            type="text"
                            id="username"
                            name="login_identifier"
                            placeholder="Masukkan username atau email Anda"
                            required
                            class="w-full bg-[#F2F3F7] rounded-full py-4 pl-14 pr-4 shadow-md outline-none focus:ring-2 focus:ring-[#6D6B2E]"
                        >
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-md text-[#6D6B2E] mb-2">
                        Password
                    </label>

                    <div class="relative">
                        <i class="fa-solid fa-lock absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Masukkan password Anda"
                            required
                            class="w-full bg-[#F2F3F7] rounded-full py-4 pl-14 pr-14 shadow-md outline-none focus:ring-2 focus:ring-[#6D6B2E]"
                        >

                        <button
                            type="button"
                            onclick="togglePassword()"
                            class="absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                        >
                            <i id="eyeIcon" class="fa-regular fa-eye"></i>
                        </button>
                    </div>

                    <a href="lupa-password.html" class="text-blue-500 hover:underline mt-2 inline-block">
                        Lupa Password?
                    </a>
                </div>

            </div>
            <button type="submit" class="w-full bg-[#E4E180] hover:bg-[#6D6B2E] text-white py-2 px-4 rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Masuk</button>
            <span class="center">atau</span>
            <button type="button" class="w-full bg-[#3D3D3D] text-white py-2 px-4 rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <img src="svg/google.png" alt="Google Icon" class="w-5 h-5 inline-block mr-2">
                Login dengan Google
            </button>
        </form>

    </section>

    <div class="text-center mt-6">
        <span class="text-gray-600">Belum punya akun?</span>
        <a href="register.html" class="text-blue-500 hover:underline">Daftar di sini</a>
    </div>
</body>

<script>
function togglePassword() {
    const password = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');

    if (password.type === 'password') {
        password.type = 'text';
        eyeIcon.classList.remove('fa-eye');
        eyeIcon.classList.add('fa-eye-slash');
    } else {
        password.type = 'password';
        eyeIcon.classList.remove('fa-eye-slash');
        eyeIcon.classList.add('fa-eye');
    }
}
</script>
</html>