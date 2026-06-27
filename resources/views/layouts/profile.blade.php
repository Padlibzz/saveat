<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>User Profile - SaveEat</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
        
        /* Menerapkan font Poppins ke seluruh halaman */
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50/50">

    <div class="p-4 md:p-8 max-w-6xl mx-auto">
        
        <div class="flex items-center justify-between mb-8">
            <a href="javascript:history.back()" class="text-[#545523] hover:opacity-75 transition">
                <i class="fa-solid fa-arrow-left text-xl"></i>
            </a>
            <h2 class="text-xl font-bold text-[#545523]">Profil</h2>
            <button class="text-[#545523] hover:opacity-75 transition">
                <i class="fa-solid fa-gear text-xl"></i>
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="flex flex-col items-center bg-white p-6 rounded-2xl shadow-sm border border-gray-100 h-fit">
                <div class="relative w-32 h-32 mb-4">
                    <img src="{{ Auth::user()->profil_image
                        ? asset('storage/' . Auth::user()->profil_image)
                        : asset('img/default-avatar.png') }}" 
                         onerror="this.src='https://ui-avatars.com/api/?name=Xaviera+Putri&background=F1F2CF&color=545523'"
                         alt="Foto Profil" 
                         class="w-full h-full object-cover rounded-full border-2 border-gray-200">
                    
                    <button class="absolute bottom-1 right-1 bg-[#545523] text-white p-2 rounded-full hover:bg-[#43441c] shadow transition flex items-center justify-center w-8 h-8 cursor-pointer">
                        <i class="fa-solid fa-pencil text-xs"></i>
                    </button>
                </div>

                <h3 class="text-xl font-bold text-gray-800 text-center">
                    {{ Auth::user()->name ?? 'Xaviera Putri' }}
                </h3>
                <p class="text-sm text-gray-500 text-center mt-1">
                    {{ Auth::user()->email ?? 'xav.putri@gmail.com' }}
                </p>

                <div class="hidden lg:block w-full mt-8">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center gap-2 border border-red-200 text-red-500 py-3 rounded-xl hover:bg-red-50 transition font-medium cursor-pointer">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            Sign Out
                        </button>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-2">
                <h4 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-3 px-2">
                    Account Settings
                </h4>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden divide-y divide-gray-100">
                    
                    <a href="{{ route('profile.edit') }}" class="flex items-center justify-between p-4 hover:bg-gray-50 transition group">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-gray-600 group-hover:bg-[#F1F2CF] group-hover:text-[#545523] transition">
                                <i class="fa-solid fa-user-pen"></i>
                            </div>
                            <span class="font-medium text-gray-700">Edit Profil</span>
                        </div>
                        <i class="fa-solid fa-chevron-right text-gray-400 text-sm"></i>
                    </a>

                    <a href="#" class="flex items-center justify-between p-4 hover:bg-gray-50 transition group">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-gray-600 group-hover:bg-[#F1F2CF] group-hover:text-[#545523] transition">
                                <i class="fa-solid fa-credit-card"></i>
                            </div>
                            <span class="font-medium text-gray-700">Metode Pembayaran</span>
                        </div>
                        <i class="fa-solid fa-chevron-right text-gray-400 text-sm"></i>
                    </a>

                    <a href="#" class="flex items-center justify-between p-4 hover:bg-gray-50 transition group">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-gray-600 group-hover:bg-[#F1F2CF] group-hover:text-[#545523] transition">
                                <i class="fa-solid fa-bell"></i>
                            </div>
                            <span class="font-medium text-gray-700">Notifikasi</span>
                        </div>
                        <i class="fa-solid fa-chevron-right text-gray-400 text-sm"></i>
                    </a>

                    <a href="#" class="flex items-center justify-between p-4 hover:bg-gray-50 transition group">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-gray-600 group-hover:bg-[#F1F2CF] group-hover:text-[#545523] transition">
                                <i class="fa-solid fa-shield-halved"></i>
                            </div>
                            <span class="font-medium text-gray-700">Privacy & Security</span>
                        </div>
                        <i class="fa-solid fa-chevron-right text-gray-400 text-sm"></i>
                    </a>

                </div>

                <div class="block lg:hidden w-full mt-6">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center gap-2 border border-red-200 text-red-500 py-3 rounded-xl hover:bg-red-50 transition font-medium cursor-pointer">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            Sign Out
                        </button>
                    </form>
                </div>

            </div>

        </div>
    </div>

</body>
</html>