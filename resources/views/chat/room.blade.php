<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat dengan {{ $merchant->name }} - SaveEat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-gray-100 flex justify-center items-center min-h-screen">

    <div class="w-full max-w-md h-[100dvh] bg-[#E1E6B9] relative flex flex-col shadow-xl overflow-hidden">
        
        {{-- 1. HEADER CHAT --}}
        <div class="bg-white px-5 py-4 flex items-center gap-4 z-10 shrink-0">
            <a href="{{ route('chat.index') }}" class="text-gray-400 hover:text-gray-600 transition">
                <i class="fa-solid fa-arrow-left text-lg"></i>
            </a>
            
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-gray-200 overflow-hidden shrink-0">
                    <img src="{{ $merchant->image_url ?? 'https://ui-avatars.com/api/?name='.urlencode($merchant->name).'&background=545523&color=F1F2CF' }}" 
                         alt="{{ $merchant->name }}" class="w-full h-full object-cover">
                </div>
                <h2 class="font-extrabold text-[#545523] text-base leading-none">
                    {{ $merchant->name }}
                </h2>
            </div>
        </div>

        {{-- 2. AREA PESAN (Dinamis dengan Looping) --}}
        <div id="chat-box" class="flex-1 overflow-y-auto no-scrollbar p-5 space-y-6 flex flex-col">
            <div class="h-2"></div>

            @forelse($messages as $message)
                {{-- Cek jika pengirimnya adalah User yang sedang login (Pesan Keluar - Kanan) --}}
                @if($message->sender_id === auth()->id())
                    <div class="flex justify-end">
                        <div class="bg-[#87A488] text-white px-5 py-3.5 rounded-2xl rounded-tr-sm max-w-[85%] shadow-sm">
                            <p class="text-[13px] leading-relaxed font-medium">
                                {{ $message->message }}
                            </p>
                            <span class="block text-[9px] text-emerald-100 text-right mt-1">
                                {{ $message->created_at->format('H:i') }}
                            </span>
                        </div>
                    </div>
                @else
                    {{-- Jika pengirimnya adalah Merchant/Lawan bicara (Pesan Masuk - Kiri) --}}
                    <div class="flex justify-start">
                        <div class="bg-[#4D5D4E] text-white px-5 py-3.5 rounded-2xl rounded-tl-sm max-w-[85%] shadow-sm">
                            <p class="text-[13px] leading-relaxed font-medium">
                                {{ $message->message }}
                            </p>
                            <span class="block text-[9px] text-gray-300 text-left mt-1">
                                {{ $message->created_at->format('H:i') }}
                            </span>
                        </div>
                    </div>
                @endif
            @empty
                <div class="flex-1 flex flex-col items-center justify-center text-center p-8 text-[#545523]/60">
                    <i class="fa-regular fa-comments text-4xl mb-2"></i>
                    <p class="text-xs font-semibold">Belum ada obrolan. Mulai sapa {{ $merchant->name }}!</p>
                </div>
            @endforelse

            <div class="h-4"></div>
        </div>

        {{-- 3. AREA INPUT CHAT --}}
        <div class="bg-white rounded-t-3xl p-4 shrink-0 shadow-[0_-10px_30px_rgba(0,0,0,0.03)] z-10 relative">
            <form action="{{ route('chat.send', $conversation->id) }}" method="POST" class="flex items-center gap-3" id="chat-form">
                @csrf
                
                <div class="flex-1 bg-gray-50 flex items-center rounded-full px-4 py-3 border border-gray-100 transition-all focus-within:border-[#87A488]">
                    <button type="button" class="text-gray-400 hover:text-gray-600 transition shrink-0 mr-3">
                        <i class="fa-regular fa-face-smile text-lg"></i>
                    </button>
                    
                    <input type="text" 
                           name="message" 
                           id="message-input"
                           placeholder="Ketik pesan..." 
                           class="flex-1 bg-transparent border-none focus:ring-0 text-sm outline-none text-gray-700 placeholder-gray-400"
                           required
                           autocomplete="off">
                </div>

                <button type="button" class="w-10 h-10 rounded-full flex items-center justify-center text-gray-400 hover:text-gray-600 transition shrink-0">
                    <i class="fa-solid fa-camera text-lg"></i>
                </button>
                
                {{-- Tombol kirim otomatis aktif saat form disubmit --}}
                <button type="submit" class="w-10 h-10 rounded-full bg-[#545523] flex items-center justify-center text-white hover:bg-[#43441c] transition shrink-0">
                    <i class="fa-solid fa-paper-plane text-xs"></i>
                </button>
            </form>
        </div>
    </div>

    {{-- Script agar otomatis scroll ke pesan paling bawah saat halaman dimuat --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const chatBox = document.getElementById('chat-box');
            chatBox.scrollTop = chatBox.scrollHeight;
        });
    </script>
</body>
</html>