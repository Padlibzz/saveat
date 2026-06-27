<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Pesan Chat - SaveEat</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Poppins', sans-serif; }
        [x-cloak] { display: none !important; }
        /* Kustomisasi scrollbar halus */
        .chat-scroll::-webkit-scrollbar { width: 5px; }
        .chat-scroll::-webkit-scrollbar-track { background: transparent; }
        .chat-scroll::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.1); border-radius: 10px; }
    </style>
</head>
<body class="bg-[#E2E4E1] min-h-screen antialiased">

    <!-- CONTAINER UTAMA (Mengisi layar penuh, responsif tanpa padding besar di HP) -->
    <div class="max-w-6xl mx-auto md:p-6 lg:p-8 h-screen md:h-[90vh]" x-data="{ activeChat: true, mobileView: 'room' }">
        
        <div class="bg-white md:rounded-2xl shadow-md border border-gray-100 h-full flex overflow-hidden">
            
            <!-- ================= KOP / PANEL KIRI: DAFTAR CHAT (Daftar Kontak Toko/Customer) ================= -->
            <div class="w-full md:w-80 lg:w-96 flex-shrink-0 border-r border-gray-100 flex flex-col bg-white"
                 :class="{ 'hidden md:flex': mobileView === 'room' }">
                
                <!-- Header Daftar Chat -->
                <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <button class="text-[#545523] md:hidden"><i class="fa-solid fa-bars text-xl"></i></button>
                        <h2 class="text-lg font-bold text-[#545523]">Pesan Anda</h2>
                    </div>
                    <span class="bg-[#415B4E] text-white text-xs px-2 py-0.5 rounded-full font-bold">1 Baru</span>
                </div>

                <!-- Kolom Cari -->
                <div class="p-3 border-b border-gray-50 bg-gray-50/50">
                    <div class="relative">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-3.5 text-xs text-gray-400"></i>
                        <input type="text" placeholder="Cari percakapan..." class="w-full bg-white border border-gray-200 rounded-xl pl-9 pr-4 py-2 text-xs focus:outline-[#545523]">
                    </div>
                </div>

                <!-- List Room Chat -->
                <div class="flex-1 overflow-y-auto divide-y divide-gray-50 chat-scroll">
                    <!-- Item 1 (Aktif sesuai mockup) -->
                    <div @click="mobileView = 'room'; activeChat = true" class="p-4 flex items-center gap-3 bg-[#F1F2CF]/40 border-l-4 border-[#545523] cursor-pointer transition">
                        <img src="https://images.unsplash.com/photo-1517433456452-f9633a875f6f?q=80&w=120" class="w-11 h-11 rounded-full object-cover border border-gray-100" alt="Dapur Roti">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between"><h4 class="text-xs font-bold text-gray-800 truncate">Dapur Roti Indonesia</h4><span class="text-[10px] text-gray-400 font-medium">11:30</span></div>
                            <p class="text-[11px] text-[#415B4E] font-medium truncate mt-0.5">Makanan Yang Kamu Pesan Udah jadi, Segera Ambil...</p>
                        </div>
                    </div>
                    
                    <!-- Dummy Item 2 -->
                    <div @click="mobileView = 'room'; activeChat = true" class="p-4 flex items-center gap-3 hover:bg-gray-50/80 cursor-pointer transition">
                        <img src="https://images.unsplash.com/photo-1563729784474-d77dbb933a9e?q=80&w=120" class="w-11 h-11 rounded-full object-cover" alt="Martabak Legit">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between"><h4 class="text-xs font-bold text-gray-700 truncate">Martabak Legit Group</h4><span class="text-[10px] text-gray-400">Kemarin</span></div>
                            <p class="text-[11px] text-gray-400 truncate mt-0.5">Terima kasih banyak kak atas pesanannya!</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ================= PANEL KANAN: RUANG CHAT AKTIF (Sesuai Gambar Mockup) ================= -->
            <div class="flex-1 flex flex-col bg-[#CCD5AE]/40" :class="{ 'hidden md:flex': mobileView === 'list' && !activeChat }">
                
                <!-- Jika Ada Chat Yang Dipilih -->
                <template x-if="activeChat">
                    <div class="flex flex-col h-full">
                        
                        <!-- Header Ruang Chat -->
                        <div class="p-4 bg-white border-b border-gray-100 flex items-center justify-between shadow-xs">
                            <div class="flex items-center gap-3 min-w-0">
                                <!-- Tombol Kembali Khusus Seluler -->
                                <button @click="mobileView = 'list'" class="md:hidden text-[#545523] mr-1 focus:outline-none"><i class="fa-solid fa-arrow-left text-lg"></i></button>
                                
                                <img src="https://images.unsplash.com/photo-1517433456452-f9633a875f6f?q=80&w=120" class="w-10 h-10 rounded-full object-cover" alt="Dapur Roti">
                                <div class="min-w-0">
                                    <h3 class="text-sm font-bold text-[#545523] truncate">Dapur Roti Indonesia</h3>
                                    <span class="text-[10px] text-green-600 flex items-center gap-1 font-medium"><span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block"></span>Online</span>
                                </div>
                            </div>
                            <button class="text-gray-400 hover:text-gray-600 px-2"><i class="fa-solid fa-ellipsis-vertical text-lg"></i></button>
                        </div>

                        <!-- Area Pesan (Warna Background Meniru Gambar Sage Terang) -->
                        <div class="flex-1 p-4 overflow-y-auto space-y-4 chat-scroll bg-[#E1E5C4]">
                            
                            <!-- Penanda Waktu -->
                            <div class="text-center"><span class="text-[10px] bg-white/60 text-gray-500 font-medium px-2.5 py-0.5 rounded-full shadow-2xs">Hari Ini</span></div>

                            <!-- Pihak Merchant/Lawan Bicara (Kiri - Hijau Gelap Sesuai Gambar) -->
                            <div class="flex items-end gap-2 max-w-[85%] md:max-w-[70%]">
                                <div class="bg-[#415B4E] text-white text-xs p-3.5 rounded-2xl rounded-bl-xs leading-relaxed shadow-xs">
                                    Makanan Yang Kamu Pesan Udah jadi, Segera Ambil Pesanmu Sebelum toko kami tutup
                                    <div class="text-[9px] text-gray-300 text-right mt-1.5 font-medium">11:30</div>
                                </div>
                            </div>

                            <!-- Pihak Kita/Konsumen (Kanan - Hijau Medium Sesuai Gambar) -->
                            <div class="flex items-end justify-end gap-2 max-w-[85%] md:max-w-[70%] ml-auto">
                                <div class="bg-[#6E9A82] text-white text-xs p-3.5 rounded-2xl rounded-br-xs leading-relaxed shadow-xs">
                                    Baik kak, segera saya ambil
                                    <div class="text-[9px] text-emerald-100 text-right mt-1.5 flex items-center justify-end gap-1 font-medium">
                                        11:31 <i class="fa-solid fa-check-double"></i>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Bar Input Pesan (Sesuai Struktur Gambar) -->
                        <div class="p-4 bg-[#E1E5C4] border-t border-transparent">
                            <form class="flex items-center gap-2 bg-white rounded-full px-4 py-2.5 shadow-sm border border-gray-100">
                                
                                <!-- Tombol Emoticon -->
                                <button type="button" class="text-gray-400 hover:text-gray-600 focus:outline-none cursor-pointer"><i class="fa-regular fa-face-smile text-lg"></i></button>
                                
                                <!-- Input Teks Utama -->
                                <input type="text" placeholder="Tulis pesan untuk Dapur Roti..." class="flex-1 bg-transparent border-none text-xs focus:outline-none px-2 text-gray-700">
                                
                                <!-- Tombol Kamera / Kirim Gambar -->
                                <button type="button" class="text-gray-400 hover:text-gray-600 focus:outline-none cursor-pointer"><i class="fa-solid fa-camera text-lg"></i></button>
                                
                                <!-- Tombol Kirim Pesan Akselerasi Desktop -->
                                <button type="submit" class="text-[#415B4E] hover:scale-105 transition ml-1 focus:outline-none cursor-pointer"><i class="fa-solid fa-paper-plane text-base"></i></button>

                            </form>
                        </div>

                    </div>
                </template>

                <!-- Splash Default (Jika Belum Pilih Chat di Versi PC) -->
                <template x-if="!activeChat">
                    <div class="hidden md:flex flex-col items-center justify-center h-full text-gray-400 p-6">
                        <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-3"><i class="fa-regular fa-comments text-2xl text-gray-400"></i></div>
                        <h4 class="font-bold text-sm text-gray-700">Belum ada chat yang dipilih</h4>
                        <p class="text-xs text-gray-400 text-center mt-1">Silakan pilih salah satu mitra toko atau konsumen di sebelah kiri untuk memulai obrolan menyelamatkan makanan.</p>
                    </div>
                </template>

            </div>

        </div>
    </div>

</body>
</html>