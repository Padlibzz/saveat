@extends('layouts.profile')

@section('title', 'Notifikasi Merchant - SaveEat')
@section('page_title', 'Notifikasi')

{{-- Ikon Gear di sudut kanan atas --}}
@section('navbar_action')
    <a href="{{ route('merchant.profile.settings') ?? '#' }}" class="text-[#545523] hover:opacity-75 transition text-lg">
        <i class="fa-solid fa-gear"></i>
    </a>
@endsection

@section('content')
<div class="max-w-md mx-auto bg-white rounded-3xl border border-gray-100 shadow-xs overflow-hidden min-h-[600px]" 
     x-data="{ activeTab: 'aktivitas' }">
    
    {{-- TOMBOL TANDAI SEMUA DIBACA --}}
    @if($aktivitas->where('is_read', false)->count() + $sistem->where('is_read', false)->count() > 0)
        <div class="px-4 pt-4 flex justify-end">
            <form action="{{ route('notifikasi.read-all') }}" method="POST">
                @csrf
                <button type="submit" class="text-[11px] text-[#545523]/70 hover:text-[#545523] font-bold underline underline-offset-2 transition-colors">
                    Tandai semua dibaca
                </button>
            </form>
        </div>
    @else
        <div class="pt-4"></div> {{-- Spacer jika tidak ada tombol --}}
    @endif

    {{-- BAGIAN TABS HEADER --}}
    <div class="flex px-4 pt-2 border-b border-gray-100 mt-2">
        {{-- Tab Aktivitas --}}
        <button @click="activeTab = 'aktivitas'" 
                :class="activeTab === 'aktivitas' ? 'border-[#545523] text-[#545523] font-bold' : 'border-transparent text-gray-400 font-medium hover:text-gray-600'"
                class="flex-1 pb-3 text-sm text-center border-b-2 transition duration-300 outline-none flex justify-center items-center gap-1.5">
            Aktivitas
            @php $unreadAktivitas = $aktivitas->where('is_read', false)->count() @endphp
            @if($unreadAktivitas > 0)
                <span class="inline-flex items-center justify-center px-1.5 min-w-[1.25rem] h-5 rounded-full bg-[#545523] text-[#F1F2CF] text-[10px] font-bold shadow-sm">
                    {{ $unreadAktivitas }}
                </span>
            @endif
        </button>
        
        {{-- Tab Sistem --}}
        <button @click="activeTab = 'sistem'" 
                :class="activeTab === 'sistem' ? 'border-[#545523] text-[#545523] font-bold' : 'border-transparent text-gray-400 font-medium hover:text-gray-600'"
                class="flex-1 pb-3 text-sm text-center border-b-2 transition duration-300 outline-none flex justify-center items-center gap-1.5">
            Sistem
            @php $unreadSistem = $sistem->where('is_read', false)->count() @endphp
            @if($unreadSistem > 0)
                <span class="inline-flex items-center justify-center px-1.5 min-w-[1.25rem] h-5 rounded-full bg-amber-500 text-white text-[10px] font-bold shadow-sm">
                    {{ $unreadSistem }}
                </span>
            @endif
        </button>
    </div>

    {{-- KONTEN TAB: AKTIVITAS --}}
    <div x-show="activeTab === 'aktivitas'" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-x-2"
         x-transition:enter-end="opacity-100 translate-x-0"
         class="p-4 space-y-4">
        
        @forelse($aktivitas as $notif)
            @php
                $isUnread = !$notif->is_read;
                $isClaim = $notif->jenis === 'claims_masuk';
                
                // Menentukan kelas styling berdasarkan kondisi notifikasi
                $bgClass = $isClaim 
                            ? 'bg-white border-l-4 border-[#545523] border border-gray-100 shadow-sm' 
                            : ($isUnread ? 'bg-white border border-[#545523]/30 shadow-sm' : 'bg-white/60 border border-gray-100');
                
                $iconBg = $isClaim ? 'bg-[#545523] text-white' : 'bg-[#545523]/80 text-white';
                $icon = $isClaim ? 'fa-bag-shopping' : ($notif->jenis === 'pesanan_selesai' ? 'fa-circle-check' : 'fa-bell');
            @endphp

            <div class="{{ $bgClass }} rounded-2xl p-4 relative transition-all">
                {{-- Indikator Titik Belum Dibaca --}}
                @if($isUnread)
                    <div class="absolute top-4 right-4 w-2 h-2 rounded-full bg-[#545523]"></div>
                @endif

                <div class="flex items-start gap-3">
                    {{-- Ikon --}}
                    <div class="w-10 h-10 shrink-0 {{ $iconBg }} rounded-full flex items-center justify-center">
                        <i class="fa-solid {{ $icon }} text-sm"></i>
                    </div>
                    
                    {{-- Konten Text --}}
                    <div class="flex-1 pr-4">
                        <div class="flex justify-between items-center mb-1">
                            <h4 class="text-xs font-bold text-gray-800">{{ $notif->judul }}</h4>
                            <span class="text-[10px] text-gray-400 whitespace-nowrap">{{ $notif->created_at->diffForHumans(null, true, true) }}</span>
                        </div>
                        <p class="text-xs text-gray-600 leading-relaxed {{ $isClaim && $notif->claim_id ? 'mb-3' : '' }}">
                            {{ $notif->pesan }}
                        </p>
                        
                        {{-- Tombol Aksi (Khusus Klaim Masuk) --}}
                        @if($isClaim && $notif->claim_id)
                            <div class="flex gap-2 mt-2">
                                <a href="{{ route('merchant.klaim-masuk') }}" class="inline-block bg-[#545523] text-white px-4 py-1.5 rounded-lg text-[10px] font-bold hover:bg-[#43441c] transition text-center shadow-sm">
                                    Konfirmasi
                                </a>
                                <a href="{{ route('merchant.klaim-masuk') }}" class="inline-block bg-white border border-gray-200 text-gray-600 px-4 py-1.5 rounded-lg text-[10px] font-bold hover:bg-gray-50 transition text-center shadow-sm">
                                    Detail
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-16">
                <i class="fa-regular fa-bell-slash text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-400 text-sm font-medium">Belum ada aktivitas saat ini.</p>
            </div>
        @endforelse
        
    </div>

    {{-- KONTEN TAB: SISTEM --}}
    <div x-show="activeTab === 'sistem'" style="display: none;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-x-2"
         x-transition:enter-end="opacity-100 translate-x-0"
         class="p-4 space-y-4">
        
         @forelse($sistem as $notif)
            @php
                $isUnread = !$notif->is_read;
                $isStock = $notif->jenis === 'stok_menipis';
                
                // Styling khusus jika jenisnya peringatan stok menipis vs sistem biasa
                $bgClass = $isStock ? 'bg-amber-50/50 border border-amber-200 shadow-sm' : ($isUnread ? 'bg-blue-50/50 border border-blue-200 shadow-sm' : 'bg-gray-50 border border-gray-100');
                $iconBg = $isStock ? 'bg-amber-500 text-white' : 'bg-blue-500 text-white';
                $icon = $isStock ? 'fa-triangle-exclamation' : 'fa-circle-info';
                
                $titleColor = $isStock ? 'text-amber-700' : 'text-gray-800';
                $timeColor = $isStock ? 'text-amber-600/70' : 'text-gray-400';
                $textColor = $isStock ? 'text-amber-800' : 'text-gray-600';
            @endphp

            <div class="{{ $bgClass }} rounded-2xl p-4 relative transition-all">
                {{-- Indikator Titik Belum Dibaca --}}
                @if($isUnread)
                    <div class="absolute top-4 right-4 w-2 h-2 rounded-full {{ $isStock ? 'bg-amber-500' : 'bg-blue-500' }}"></div>
                @endif

                <div class="flex items-start gap-3">
                    {{-- Ikon --}}
                    <div class="w-10 h-10 shrink-0 {{ $iconBg }} rounded-full flex items-center justify-center">
                        <i class="fa-solid {{ $icon }} text-sm"></i>
                    </div>
                    
                    {{-- Konten Text --}}
                    <div class="flex-1 pr-4">
                        <div class="flex justify-between items-center mb-1">
                            <h4 class="text-xs font-bold {{ $titleColor }}">{{ $notif->judul }}</h4>
                            <span class="text-[10px] {{ $timeColor }} whitespace-nowrap">{{ $notif->created_at->diffForHumans(null, true, true) }}</span>
                        </div>
                        <p class="text-xs {{ $textColor }} leading-relaxed">
                            {{ $notif->pesan }}
                        </p>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-16">
                <i class="fa-regular fa-bell-slash text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-400 text-sm font-medium">Tidak ada notifikasi sistem.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection