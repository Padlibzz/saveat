<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>User Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
    </style>
</head>
<body class="bg-gradient-to-r from-[#CFD086] to-[#F1F2CF] min-h-screen font-[Poppins]">
    <nav class="bg-white shadow-md p-4">
        <div class="container mx-auto flex items-center justify-between">

            <div class="flex items-center gap-3">
                <img src="{{ asset('svg/logo.png') }}" alt="SaveEat Logo" class="w-10 h-10">
                <h1 class="text-3xl font-bold text-[#545523]">
                    SaveEat
                </h1>
            </div>

            <div class="flex items-center gap-3">

                <div class="flex items-center gap-3 border border-gray-200 rounded-full px-4 py-2 bg-white">
                    <img
                        src="{{ asset('svg/user-icon.png') }}"
                        alt="User"
                        class="w-10 h-10 rounded-full object-cover">

                    <div class="leading-tight">
                        <p class="text-xs text-gray-500">Hi,</p>
                        <p class="font-semibold text-gray-800">
                            {{ Auth::user()->name }}
                        </p>
                    </div>
                </div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button
                        type="submit"
                        class="flex items-center justify-center w-11 h-11 rounded-full border border-red-200 bg-red-50 text-red-600 hover:bg-red-100 transition"
                        title="Logout">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.8"
                            stroke="currentColor"
                            class="w-6 h-6">
                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6A2.25 2.25 0 005.25 5.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m-6-3h11.25m0 0l-3-3m3 3l-3 3" />
                        </svg>

                    </button>
                </form>

            </div>

        </div>
    </nav>

    <section class="container mx-auto p-4 mt-6">
        <div>
            <input type="text" placeholder="Temukan Makananmu" class="w-full p-4 rounded-full mt-6 shadow-md bg-white outline-none focus:ring-2 focus:ring-[#6D6B2E]">
        </div>

        <div class="flex items-center gap-4 mt-6">
            <button class="bg-white p-4">
                <svg></svg>
                <span>Listing Makanan</span>
            </button>
            <button class="bg-white p-4">
                <svg></svg>
                <span>Merchant</span>
            </button>
        </div>

        <a href="#" class="block mt-6 p-4 bg-[#6D6B2E] text-white rounded-lg text-center shadow-md">
            Join Merchant Now
        </a>
    </section>

</body>
</html>