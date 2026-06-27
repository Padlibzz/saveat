@extends('layouts.profile')

@section('title', 'Notifikasi - SaveEat')
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
    @if($aktivitas->where('is_read', false)->count() + $promo->where('is_read', false)->count() > 0)
        <div class="px-4 pt-4 flex justify-end">
            <form action="{{ route('notifikasi.read-all') }}" method="POST">
                @csrf
                <button type="submit" class="text-[11px] text-[#545523]/70 hover:text-[#545523] font-bold underline underline-offset-2 transition-colors">
                    Tandai semua dibaca
                </button>
            </form>
        </div>
    @else
        <div class="pt-4"></div> {{-- Spacer jika tidak ada notifikasi baru --}}
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
        
        {{-- Tab Promo --}}
        <button @click="activeTab = 'promo'" 
                :class="activeTab === 'promo' ? 'border-[#545523] text-[#545523] font-bold' : 'border-transparent text-gray-400 font-medium hover:text-gray-600'"
                class="flex-1 pb-3 text-sm text-center border-b-2 transition duration-300 outline-none flex justify-center items-center gap-1.5">
            Promo
            @php $unreadPromo = $promo->where('is_read', false)->count() @endphp
            @if($unreadPromo > 0)
                <span class="inline-flex items-center justify-center px-1.5 min-w-[1.25rem] h-5 rounded-full bg-[#545523] text-[#F1F2CF] text-[10px] font-bold shadow-sm">
                    {{ $unreadPromo }}
                </span>
            @endif
        </button>
    </div>

    {{-- KONTEN TAB: AKTIVITAS --}}
    <div x-show="activeTab === 'aktivitas'" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-x-2"
         x-transition:enter-end="opacity-100 translate-x-0"
         class="flex flex-col divide-y divide-gray-50 p-2">
        
        @forelse($aktivitas as $notif)
            @php
                $isUnread = !$notif->is_read;
                $iconClass = 'fa-bell';
                if($notif->jenis === 'claims_berhasil') $iconClass = 'fa-bag-shopping';
                elseif($notif->jenis === 'pesanan_selesai') $iconClass = 'fa-suitcase';
                elseif($notif->jenis === 'menunggu_pembayaran') $iconClass = 'fa-credit-card';
            @endphp

            <div class="flex items-start gap-3.5 p-3 transition rounded-xl {{ $isUnread ? 'bg-gray-50/70' : 'hover:bg-gray-50/70 cursor-pointer' }}">
                
                {{-- Ikon --}}
                <div class="w-11 h-11 shrink-0 bg-[#545523] rounded-full flex items-center justify-center text-white shadow-sm">
                    <i class="fa-solid {{ $iconClass }} text-sm"></i>
                </div>
                
                {{-- Konten --}}
                <div class="flex-1 pt-0.5 min-w-0">
                    <h4 class="text-sm font-bold text-gray-800 leading-tight mb-1">{{ $notif->judul }}</h4>
                    <p class="text-xs text-gray-600 leading-relaxed">{{ $notif->pesan }}</p>

                    {{-- Link Aksi Dinamis --}}
                    @if($notif->claim_id && in_array($notif->jenis, ['claims_berhasil', 'pesanan_selesai']))
                        <a href="{{ route('pesanan.detail', $notif->claim_id) }}" class="inline-block mt-2 text-[11px] text-[#545523] font-bold hover:underline">
                            Lihat Detail &rarr;
                        </a>
                    @endif
                    @if($notif->claim_id && $notif->jenis === 'menunggu_pembayaran')
                        <a href="{{ route('pesanan.detail', $notif->claim_id) }}" class="inline-block mt-2 text-[11px] text-[#545523] font-bold hover:underline">
                            Selesaikan Pembayaran &rarr;
                        </a>
                    @endif
                </div>

                {{-- Waktu & Indikator Unread --}}
                <div class="flex flex-col items-end gap-1.5 shrink-0 pt-0.5">
                    <span class="text-[10px] font-medium text-gray-400 whitespace-nowrap">{{ $notif->created_at->diffForHumans(null, true, true) }}</span>
                    @if($isUnread)
                        <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                    @endif
                </div>
            </div>
        @empty
            {{-- State Kosong --}}
            <div class="flex flex-col items-center justify-center text-center h-64">
                <div class="w-16 h-16 bg-gray-50 text-gray-400 rounded-full flex items-center justify-center mb-4">
                    <i class="fa-regular fa-bell-slash text-2xl"></i>
                </div>
                <h4 class="font-bold text-gray-800 mb-2">Belum Ada Aktivitas</h4>
                <p class="text-xs text-gray-500 px-6">Notifikasi tentang pesanan dan aktivitas Anda akan muncul di sini.</p>
            </div>
        @endforelse

    </div>

    {{-- KONTEN TAB: PROMO --}}
    <div x-show="activeTab === 'promo'" style="display: none;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-x-2"
         x-transition:enter-end="opacity-100 translate-x-0"
         class="flex flex-col divide-y divide-gray-50 p-2">
        
        @forelse($promo as $notif)
            @php
                $isUnread = !$notif->is_read;
                $iconClass = 'fa-tag';
                if($notif->jenis === 'listing_baru') $iconClass = 'fa-utensils';
                elseif($notif->jenis === 'tonggak_dampak') $iconClass = 'fa-leaf';
            @endphp

            <div class="flex items-start gap-3.5 p-3 transition rounded-xl {{ $isUnread ? 'bg-amber-50/40' : 'hover:bg-gray-50/70 cursor-pointer' }}">
                
                {{-- Ikon --}}
                <div class="w-11 h-11 shrink-0 bg-[#545523] rounded-full flex items-center justify-center text-white shadow-sm">
                    <i class="fa-solid {{ $iconClass }} text-sm"></i>
                </div>
                
                {{-- Konten --}}
                <div class="flex-1 pt-0.5 min-w-0">
                    <h4 class="text-sm font-bold text-gray-800 leading-tight mb-1">{{ $notif->judul }}</h4>
                    <p class="text-xs text-gray-600 leading-relaxed">{{ $notif->pesan }}</p>

                    {{-- Link Aksi Dinamis --}}
                    @if($notif->jenis === 'listing_baru')
                        <a href="{{ route('listing-makanan') }}" class="inline-block mt-2 text-[11px] text-[#545523] font-bold hover:underline">
                            Lihat Listing &rarr;
                        </a>
                    @endif
                </div>

                {{-- Waktu & Indikator Unread --}}
                <div class="flex flex-col items-end gap-1.5 shrink-0 pt-0.5">
                    <span class="text-[10px] font-medium text-gray-400 whitespace-nowrap">{{ $notif->created_at->diffForHumans(null, true, true) }}</span>
                    @if($isUnread)
                        <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                    @endif
                </div>
            </div>
        @empty
            {{-- State Kosong --}}
            <div class="flex flex-col items-center justify-center text-center h-64">
                <div class="w-16 h-16 bg-amber-50 text-amber-500 rounded-full flex items-center justify-center mb-4">
                    <i class="fa-solid fa-ticket text-2xl"></i>
                </div>
                <h4 class="font-bold text-gray-800 mb-2">Belum Ada Promo</h4>
                <p class="text-xs text-gray-500 px-6">Voucher dan promo spesial untukmu akan muncul di sini nanti.</p>
            </div>
        @endforelse

    </div>

</div>
@endsection