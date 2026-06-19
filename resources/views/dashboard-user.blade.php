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
                <img src="svg/logo.png" alt="SaveEat Logo" class="w-10 h-10">
                <h1 class="text-3xl font-bold text-[#545523]">
                    Saveeat
                </h1>
            </div>

            <div class="flex items-center gap-3">
                <img src="svg/user-icon.png" alt="User Icon" class="w-8 h-8 rounded-full">
                <button>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-8 h-8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 6.75h16.5m-16.5 5.25h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
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