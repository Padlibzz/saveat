@extends('layouts.profile') {{-- Asumsi menggunakan layout utama aplikasi Anda --}}

@section('title', 'Pesan - SaveEat')
@section('page_title', 'Pesan Masuk')

@section('content')
<div class="max-w-md mx-auto bg-white min-h-[80vh] rounded-3xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">
    
    {{-- KOLOM PENCARIAN --}}
    <div class="p-4 border-b border-gray-100 bg-gray-50/50">
        <div class="relative flex items-center w-full h-10 rounded-xl focus-within:shadow-sm bg-white overflow-hidden border border-gray-200">
            <div class="grid place-items-center h-full w-12 text-gray-300">
                <i class="fa-solid fa-magnifying-glass text-sm"></i>
            </div>
            <input class="peer h-full w-full outline-none text-sm text-gray-700 pr-2 bg-transparent placeholder-gray-400" type="text" id="search" placeholder="Cari obrolan..." />
        </div>
    </div>

    {{-- DAFTAR OBROLAN --}}
    <div class="flex-1 overflow-y-auto">
        
        @forelse($conversations as $conversation)
            @php
                // Menentukan siapa lawan bicaranya (Merchant atau User)
                $lawanBicara = (auth()->id() === $conversation->user_id) ? $conversation->merchant : $conversation->user;
                
                // Mengambil pesan terakhir
                $pesanTerakhir = $conversation->messages->last();
                
                // Menghitung pesan yang belum dibaca (opsional)
                $unreadCount = $conversation->messages->where('sender_id', '!=', auth()->id())->where('is_read', false)->count();
            @endphp

            <a href="{{ route('chat.room', $conversation->id) }}" class="flex items-center p-4 border-b border-gray-50 hover:bg-gray-50 transition active:bg-gray-100 cursor-pointer group">
                
                {{-- Avatar --}}
                <div class="relative w-12 h-12 rounded-full overflow-hidden shrink-0 bg-gray-200 border border-gray-100">
                    <img src="{{ $lawanBicara->image_url ?? 'https://ui-avatars.com/api/?name='.urlencode($lawanBicara->name).'&background=545523&color=F1F2CF' }}" alt="{{ $lawanBicara->name }}" class="w-full h-full object-cover">
                    
                    {{-- Indikator Online (Opsional) --}}
                    {{-- <span class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-500 border-2 border-white rounded-full"></span> --}}
                </div>

                {{-- Preview Pesan --}}
                <div class="flex-1 min-w-0 ml-4">
                    <div class="flex justify-between items-baseline mb-1">
                        <h3 class="text-sm font-bold text-gray-800 truncate group-hover:text-[#545523] transition-colors">
                            {{ $lawanBicara->name }}
                        </h3>
                        @if($pesanTerakhir)
                            <span class="text-[10px] text-gray-400 shrink-0 ml-2 font-medium">
                                {{ $pesanTerakhir->created_at->isToday() ? $pesanTerakhir->created_at->format('H:i') : $pesanTerakhir->created_at->format('d M') }}
                            </span>
                        @endif
                    </div>
                    
                    <div class="flex justify-between items-center gap-2">
                        <p class="text-xs text-gray-500 truncate {{ $unreadCount > 0 ? 'font-bold text-gray-800' : '' }}">
                            @if($pesanTerakhir)
                                @if($pesanTerakhir->sender_id === auth()->id())
                                    <i class="fa-solid fa-check-double text-[10px] mr-1 {{ $pesanTerakhir->is_read ? 'text-blue-500' : 'text-gray-400' }}"></i>
                                @endif
                                {{ $pesanTerakhir->message }}
                            @else
                                <i class="italic text-gray-400">Belum ada pesan</i>
                            @endif
                        </p>

                        {{-- Badge Unread --}}
                        @if($unreadCount > 0)
                            <span class="bg-[#545523] text-white text-[9px] font-bold px-2 py-0.5 rounded-full shrink-0">
                                {{ $unreadCount }}
                            </span>
                        @endif
                    </div>
                </div>
            </a>
        @empty
            {{-- State Jika Kosong --}}
            <div class="h-64 flex flex-col items-center justify-center text-center px-4">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center text-gray-300 mb-3">
                    <i class="fa-regular fa-message text-2xl"></i>
                </div>
                <h4 class="text-sm font-bold text-gray-800 mb-1">Belum Ada Pesan</h4>
                <p class="text-xs text-gray-500">Mulai interaksi dengan merchant atau pelanggan untuk melihat daftar obrolan Anda di sini.</p>
            </div>
        @endforelse

    </div>
</div>
@endsection