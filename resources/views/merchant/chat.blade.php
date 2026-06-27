@section('page_title', 'Chat Konsumen')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Chat Konsumen - Merchant SaveEat</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Poppins', sans-serif; }
        [x-cloak] { display: none !important; }
        .chat-scroll::-webkit-scrollbar { width: 5px; }
        .chat-scroll::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.1); border-radius: 10px; }
    </style>
</head>
<body x-data="{ sidebarOpen: false }" class="bg-[#F4F6F3] min-h-screen flex">

    <!-- KOMPONEN SIDEBAR MERCHANT KAMU -->
    <x-merchant-sidebar />

    <div class="flex-1 flex flex-col min-h-screen">
        
        <!-- KOMPONEN NAVBAR MERCHANT KAMU -->
        <x-merchant-navbar />

        <!-- AREA UTAMA CHAT (Disesuaikan agar muat di dalam dashboard merchant) -->
        <section class="p-4 md:p-6 lg:ml-64 flex-1 h-[calc(100vh-70px)]">
            
            <div class="bg-white rounded-2xl shadow-xs border border-gray-100 h-full flex overflow-hidden" 
                 x-data="merchantChatComponent()" 
                 x-init="initChat()">
                
                <!-- ================= PANEL KIRI: DAFTAR KONSUMEN ================= -->
                <div class="w-full md:w-80 lg:w-96 flex-shrink-0 border-r border-gray-100 flex flex-col bg-white"
                     :class="{ 'hidden md:flex': mobileView === 'room' }">
                    
                    <div class="p-4 border-b border-gray-100 bg-gray-50/50">
                        <h2 class="text-sm font-bold text-gray-700">Daftar Chat Masuk</h2>
                        <p class="text-[11px] text-gray-400 mt-0.5">Balas pesan konsumen yang menanyakan makanan penyelamatmu.</p>
                    </div>

                    <!-- List Konsumen Dinamis -->
                    <div class="flex-1 overflow-y-auto divide-y divide-gray-50 chat-scroll">
                        @foreach($contacts as $contact)
                            <div @click="selectContact({{ $contact->id }}, '{{ $contact->name }}')" 
                                 :class="activeContactId === {{ $contact->id }} ? 'bg-[#415B4E]/10 border-l-4 border-[#415B4E]' : 'hover:bg-gray-50/80'"
                                 class="p-4 flex items-center gap-3 cursor-pointer transition">
                                
                                <div class="w-10 h-10 rounded-full bg-[#6E9A82] text-white flex items-center justify-center font-bold text-xs">
                                    {{ strtoupper(substr($contact->name, 0, 2)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-xs font-bold text-gray-800 truncate">{{ $contact->name }}</h4>
                                    <p class="text-[10px] text-gray-400 truncate mt-0.5">Konsumen SaveEat</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- ================= PANEL KANAN: RUANG OBROLAN ================= -->
                <div class="flex-1 flex flex-col bg-gray-50" :class="{ 'hidden md:flex': mobileView === 'list' }">
                    
                    <div x-show="activeContactId !== null" class="flex flex-col h-full" x-cloak>
                        
                        <!-- Header Chat -->
                        <div class="p-4 bg-white border-b border-gray-100 flex items-center gap-3 shadow-2xs">
                            <button @click="mobileView = 'list'" class="md:hidden text-gray-600 mr-1"><i class="fa-solid fa-arrow-left text-base"></i></button>
                            <div class="w-9 h-9 rounded-full bg-[#415B4E] text-white flex items-center justify-center font-bold text-xs">
                                <span x-text="activeContactName.substring(0, 2).toUpperCase()"></span>
                            </div>
                            <div>
                                <h3 class="text-xs font-bold text-gray-800" x-text="activeContactName"></h3>
                                <span class="text-[9px] text-gray-400 font-medium">Pembeli / Customer</span>
                            </div>
                        </div>

                        <!-- Ruang Pesan Berbalasan -->
                        <div id="merchantChatBox" class="flex-1 p-4 overflow-y-auto space-y-4 chat-scroll bg-[#F4F6F3]">
                            <template x-for="chat in messages" :key="chat.id">
                                <div>
                                    <!-- Pesan Masuk dari Konsumen (Kiri - Putih Bersih) -->
                                    <div x-show="chat.sender_id !== {{ Auth::id() }}" class="flex items-end gap-2 max-w-[85%] md:max-w-[70%]">
                                        <div class="bg-white border border-gray-100 text-gray-800 text-xs p-3 rounded-xl rounded-bl-none shadow-2xs">
                                            <span x-text="chat.message"></span>
                                            <div class="text-[9px] text-gray-400 text-right mt-1" x-text="formatTime(chat.created_at)"></div>
                                        </div>
                                    </div>

                                    <!-- Pesan Keluar dari Merchant (Kanan - Tema Hijau SaveEat) -->
                                    <div x-show="chat.sender_id === {{ Auth::id() }}" class="flex items-end justify-end gap-2 max-w-[85%] md:max-w-[70%] ml-auto">
                                        <div class="bg-[#415B4E] text-white text-xs p-3 rounded-xl rounded-br-none shadow-2xs">
                                            <span x-text="chat.message"></span>
                                            <div class="text-[9px] text-gray-200 text-right mt-1 font-light" x-text="formatTime(chat.created_at)"></div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Form Kirim Pesan -->
                        <div class="p-3 bg-white border-t border-gray-100">
                            <form @submit.prevent="sendMessage()" class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-xl px-3 py-2">
                                <input type="text" x-model="newMessage" placeholder="Balas pesan konsumen..." class="flex-1 bg-transparent border-none text-xs focus:outline-none px-1 text-gray-700">
                                <button type="submit" class="text-[#415B4E] hover:scale-105 transition px-2"><i class="fa-solid fa-paper-plane text-sm"></i></button>
                            </form>
                        </div>

                    </div>

                    <!-- Tampilan Awal jika Belum Ada Chat yang Dibuka -->
                    <div x-show="activeContactId === null" class="flex flex-col items-center justify-center h-full text-gray-400 p-6">
                        <i class="fa-regular fa-comment-dots text-3xl mb-2 text-gray-300"></i>
                        <h4 class="font-bold text-xs text-gray-600">Belum Ada Chat Terpilih</h4>
                        <p class="text-[11px] text-gray-400 text-center max-w-xs mt-0.5">Pilih salah satu konsumen untuk membalas pertanyaan atau koordinasi pengambilan makanan.</p>
                    </div>

                </div>

            </div>

        </section>
    </div>

    <!-- REUSE LOGIKANYA -->
    <script>
        function merchantChatComponent() {
            return {
                activeContactId: null,
                activeContactName: '',
                mobileView: 'list',
                messages: [],
                newMessage: '',
                pollingTimer: null,

                initChat() {
                    const urlParams = new URLSearchParams(window.location.search);
                    const targetUserId = urlParams.get('user_id');
                    if (targetUserId) {
                        this.selectContact(parseInt(targetUserId), 'Konsumen');
                    }
                },
                selectContact(id, name) {
                    this.activeContactId = id;
                    this.activeContactName = name;
                    this.mobileView = 'room';
                    this.fetchMessages();

                    if (this.pollingTimer) clearInterval(this.pollingTimer);
                    this.pollingTimer = setInterval(() => { this.fetchMessages(); }, 3000);
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
                    fetch('/chat/send', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ receiver_id: this.activeContactId, message: this.newMessage })
                    })
                    .then(res => res.json())
                    .then(() => {
                        this.newMessage = '';
                        this.fetchMessages();
                    });
                },
                scrollToBottom() {
                    setTimeout(() => {
                        const container = document.getElementById('merchantChatBox');
                        if (container) container.scrollTop = container.scrollHeight;
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