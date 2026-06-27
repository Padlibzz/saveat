<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Pesan Chat - SaveEat</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Poppins', sans-serif; }
        [x-cloak] { display: none !important; }
        .chat-scroll::-webkit-scrollbar { width: 5px; }
        .chat-scroll::-webkit-scrollbar-track { background: transparent; }
        .chat-scroll::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.1); border-radius: 10px; }
    </style>
</head>
<body class="bg-[#E2E4E1] min-h-screen antialiased">

    <!-- CONTAINER UTAMA DENGAN LOGIKA ALPINE.JS -->
    <div class="max-w-6xl mx-auto md:p-6 lg:p-8 h-screen md:h-[90vh]" 
         x-data="chatComponent()" 
         x-init="initChat()">
        
        <div class="bg-white md:rounded-2xl shadow-md border border-gray-100 h-full flex overflow-hidden">
            
            <!-- ================= PANEL KIRI: DAFTAR KONTAK ================= -->
            <div class="w-full md:w-80 lg:w-96 flex-shrink-0 border-r border-gray-100 flex flex-col bg-white"
                 :class="{ 'hidden md:flex': mobileView === 'room' }">
                
                <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <h2 class="text-lg font-bold text-[#545523]">Pesan Anda</h2>
                    </div>
                </div>

                <!-- List Kontak Dinamis -->
                <div class="flex-1 overflow-y-auto divide-y divide-gray-50 chat-scroll">
                    @foreach($contacts as $contact)
                        <div @click="selectContact({{ $contact->id }}, '{{ $contact->nama_usaha ?? $contact->name }}')" 
                             :class="activeContactId === {{ $contact->id }} ? 'bg-[#F1F2CF]/40 border-l-4 border-[#545523]' : 'hover:bg-gray-50/80'"
                             class="p-4 flex items-center gap-3 cursor-pointer transition">
                            
                            <div class="w-11 h-11 rounded-full bg-[#415B4E] text-white flex items-center justify-center font-bold text-sm">
                                {{ strtoupper(substr($contact->nama_usaha ?? $contact->name, 0, 2)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-xs font-bold text-gray-800 truncate">{{ $contact->nama_usaha ?? $contact->name }}</h4>
                                <p class="text-[11px] text-gray-400 truncate mt-0.5">Klik untuk membuka obrolan</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- ================= PANEL KANAN: RUANG CHAT AKTIF ================= -->
            <div class="flex-1 flex flex-col bg-[#CCD5AE]/40" :class="{ 'hidden md:flex': mobileView === 'list' }">
                
                <!-- Jika Ada Chat Yang Dipilih -->
                <div x-show="activeContactId !== null" class="flex flex-col h-full" x-cloak>
                    
                    <!-- Header Ruang Chat -->
                    <div class="p-4 bg-white border-b border-gray-100 flex items-center justify-between shadow-xs">
                        <div class="flex items-center gap-3 min-w-0">
                            <button @click="mobileView = 'list'" class="md:hidden text-[#545523] mr-1"><i class="fa-solid fa-arrow-left text-lg"></i></button>
                            
                            <div class="w-10 h-10 rounded-full bg-[#415B4E] text-white flex items-center justify-center font-bold text-xs">
                                <span x-text="activeContactName.substring(0, 2).toUpperCase()"></span>
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-sm font-bold text-[#545523] truncate" x-text="activeContactName"></h3>
                                <span class="text-[10px] text-green-600 flex items-center gap-1 font-medium"><span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block"></span>Terhubung</span>
                            </div>
                        </div>
                    </div>

                    <!-- Area Pesan (Sesuai Gambar WhatsApp Image 2026-06-27 at 19.40.39.jpeg) -->
                    <div id="chatContainer" class="flex-1 p-4 overflow-y-auto space-y-4 chat-scroll bg-[#E1E5C4]">
                        
                        <template x-for="chat in messages" :key="chat.id">
                            <div>
                                <!-- Pihak Lawan Bicara / Merchant / Pengirim Lain (Kiri - Hijau Gelap) -->
                                <div x-show="chat.sender_id !== {{ Auth::id() }}" class="flex items-end gap-2 max-w-[85%] md:max-w-[70%]">
                                    <div class="bg-[#415B4E] text-white text-xs p-3.5 rounded-2xl rounded-bl-xs leading-relaxed shadow-xs">
                                        <span x-text="chat.message"></span>
                                        <div class="text-[9px] text-gray-300 text-right mt-1.5 font-medium" x-text="formatTime(chat.created_at)"></div>
                                    </div>
                                </div>

                                <!-- Pihak Kita / Konsumen (Kanan - Hijau Medium) -->
                                <div x-show="chat.sender_id === {{ Auth::id() }}" class="flex items-end justify-end gap-2 max-w-[85%] md:max-w-[70%] ml-auto">
                                    <div class="bg-[#6E9A82] text-white text-xs p-3.5 rounded-2xl rounded-br-xs leading-relaxed shadow-xs">
                                        <span x-text="chat.message"></span>
                                        <div class="text-[9px] text-emerald-100 text-right mt-1.5 flex items-center justify-end gap-1 font-medium">
                                            <span x-text="formatTime(chat.created_at)"></span>
                                            <i class="fa-solid fa-check-double"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>

                    </div>

                    <!-- Input Pesan -->
                    <div class="p-4 bg-[#E1E5C4] border-t border-transparent">
                        <form @submit.prevent="sendMessage()" class="flex items-center gap-2 bg-white rounded-full px-4 py-2.5 shadow-sm border border-gray-100">
                            <button type="button" class="text-gray-400 hover:text-gray-600"><i class="fa-regular fa-face-smile text-lg"></i></button>
                            
                            <input type="text" x-model="newMessage" placeholder="Tulis pesan..." class="flex-1 bg-transparent border-none text-xs focus:outline-none px-2 text-gray-700">
                            
                            <button type="button" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-camera text-lg"></i></button>
                            <button type="submit" class="text-[#415B4E] hover:scale-105 transition ml-1"><i class="fa-solid fa-paper-plane text-base"></i></button>
                        </form>
                    </div>

                </div>

                <!-- Splash Default Jika Belum Pilih Obrolan -->
                <div x-show="activeContactId === null" class="flex flex-col items-center justify-center h-full text-gray-400 p-6">
                    <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-3"><i class="fa-regular fa-comments text-2xl text-gray-400"></i></div>
                    <h4 class="font-bold text-sm text-gray-700">Belum ada chat yang dipilih</h4>
                    <p class="text-xs text-gray-400 text-center mt-1">Pilih salah satu kontak di sebelah kiri untuk memulai obrolan.</p>
                </div>

            </div>

        </div>
    </div>

    <!-- SCRIPT LOGIKA ANTAR MUKA (AJAX POLLING) -->
    <script>
        function chatComponent() {
            return {
                activeContactId: null,
                activeContactName: '',
                mobileView: 'list',
                messages: [],
                newMessage: '',
                pollingTimer: null,

                initChat() {
                    // Cek jika dilempar dari tombol detail halaman lain lewat parameter ?user_id=X
                    const urlParams = new URLSearchParams(window.location.search);
                    const targetUserId = urlParams.get('user_id');
                    if (targetUserId) {
                        this.selectContact(parseInt(targetUserId), 'Obrolan Baru');
                    }
                },

                selectContact(id, name) {
                    this.activeContactId = id;
                    this.activeContactName = name;
                    this.mobileView = 'room';
                    this.fetchMessages();

                    // Bersihkan interval lama jika ada, lalu set interval baru (Polling tiap 3 detik)
                    if (this.pollingTimer) clearInterval(this.pollingTimer);
                    this.pollingTimer = setInterval(() => {
                        this.fetchMessages();
                    }, 3000);
                },

                fetchMessages() {
                    if (!this.activeContactId) return;
                    
                    fetch(`/chat/messages/${this.activeContactId}`)
                        .then(res => res.json())
                        .then(data => {
                            this.messages = data;
                            this.scrollToBottom();
                        });
                },

                sendMessage() {
                    if (this.newMessage.trim() === '') return;

                    const payload = {
                        receiver_id: this.activeContactId,
                        message: this.newMessage
                    };

                    fetch('/chat/send', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(payload)
                    })
                    .then(res => res.json())
                    .then(resData => {
                        this.newMessage = '';
                        this.fetchMessages(); // Ambil ulang data chat terbaru
                    });
                },

                scrollToBottom() {
                    setTimeout(() => {
                        const container = document.getElementById('chatContainer');
                        if (container) {
                            container.scrollTop = container.scrollHeight;
                        }
                    }, 50);
                },

                formatTime(dateString) {
                    const date = new Date(dateString);
                    return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                }
            }
        }
    </script>

</body>
</html>