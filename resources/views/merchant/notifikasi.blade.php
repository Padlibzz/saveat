@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
<div class="max-w-2xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-display font-bold text-olive">Notifikasi</h1>
        @if($aktivitas->where('is_read', false)->count() + $sistem->where('is_read', false)->count() > 0)
        <form action="{{ route('notifikasi.read-all') }}" method="POST">
            @csrf
            <button type="submit" class="text-sm text-olive/70 hover:text-olive underline underline-offset-2">
                Tandai semua dibaca
            </button>
        </form>
        @endif
    </div>

    {{-- Tab Navigation --}}
    <div class="flex border-b border-gray-200 mb-6" id="tab-nav">
        <button
            onclick="switchTab('aktivitas')"
            id="tab-btn-aktivitas"
            class="tab-btn flex-1 pb-3 text-sm font-semibold text-center border-b-2 border-olive text-olive transition-all"
        >
            Aktivitas
            @php $unreadAktivitas = $aktivitas->where('is_read', false)->count() @endphp
            @if($unreadAktivitas > 0)
                <span class="ml-1 inline-flex items-center justify-center w-5 h-5 rounded-full bg-olive text-cream text-[10px]">
                    {{ $unreadAktivitas }}
                </span>
            @endif
        </button>
        <button
            onclick="switchTab('sistem')"
            id="tab-btn-sistem"
            class="tab-btn flex-1 pb-3 text-sm font-semibold text-center border-b-2 border-transparent text-gray-400 hover:text-olive transition-all"
        >
            Sistem
            @php $unreadSistem = $sistem->where('is_read', false)->count() @endphp
            @if($unreadSistem > 0)
                <span class="ml-1 inline-flex items-center justify-center w-5 h-5 rounded-full bg-orange-500 text-white text-[10px]">
                    {{ $unreadSistem }}
                </span>
            @endif
        </button>
    </div>

    {{-- TAB: Aktivitas --}}
    <div id="tab-aktivitas">
        @forelse($aktivitas as $notif)
            <div class="flex gap-4 p-4 mb-3 rounded-2xl border transition-all
                {{ !$notif->is_read ? 'bg-white border-olive/30 shadow-sm' : 'bg-white/60 border-gray-100' }}">

                {{-- Icon --}}
                <div class="flex-shrink-0 w-11 h-11 rounded-full flex items-center justify-center
                    {{ $notif->jenis === 'claims_masuk' ? 'bg-olive' : 'bg-olive/80' }}">
                    @if($notif->jenis === 'claims_masuk')
                        <i class="fa-solid fa-bag-shopping text-cream text-base"></i>
                    @elseif($notif->jenis === 'pesanan_selesai')
                        <i class="fa-solid fa-circle-check text-cream text-base"></i>
                    @else
                        <i class="fa-solid fa-bell text-cream text-base"></i>
                    @endif
                </div>

                {{-- Konten --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2">
                        <p class="text-sm font-bold text-olive leading-tight">{{ $notif->judul }}</p>
                        <span class="text-[11px] text-gray-400 whitespace-nowrap flex-shrink-0">
                            {{ $notif->created_at->diffForHumans(null, true, true) }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-600 mt-1 leading-snug">{{ $notif->pesan }}</p>

                    {{-- Action buttons hanya untuk klaim_masuk yang belum dibaca --}}
                    @if($notif->jenis === 'claims_masuk' && $notif->claim_id)
                        <div class="flex gap-2 mt-3">
                            <a href="{{ route('merchant.klaim-masuk') }}"
                               class="px-4 py-1.5 bg-olive text-cream text-xs font-semibold rounded-full hover:bg-olive-dark transition-colors">
                                Konfirmasi
                            </a>
                            <a href="{{ route('merchant.klaim-masuk') }}"
                               class="px-4 py-1.5 border border-gray-300 text-gray-600 text-xs font-semibold rounded-full hover:bg-gray-50 transition-colors">
                                Detail
                            </a>
                        </div>
                    @endif
                </div>

                {{-- Unread dot --}}
                @if(!$notif->is_read)
                    <div class="flex-shrink-0 w-2 h-2 rounded-full bg-olive mt-1.5"></div>
                @endif
            </div>
        @empty
            <div class="text-center py-16">
                <i class="fa-regular fa-bell-slash text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-400 text-sm">Belum ada aktivitas</p>
            </div>
        @endforelse
    </div>

    {{-- TAB: Sistem --}}
    <div id="tab-sistem" class="hidden">
        @forelse($sistem as $notif)
            <div class="flex gap-4 p-4 mb-3 rounded-2xl border transition-all
                {{ $notif->jenis === 'stok_menipis' ? 'bg-orange-50 border-orange-200' : 'bg-white/60 border-gray-100' }}">

                {{-- Icon --}}
                <div class="flex-shrink-0 w-11 h-11 rounded-full flex items-center justify-center
                    {{ $notif->jenis === 'stok_menipis' ? 'bg-orange-500' : 'bg-gray-400' }}">
                    @if($notif->jenis === 'stok_menipis')
                        <i class="fa-solid fa-triangle-exclamation text-white text-base"></i>
                    @else
                        <i class="fa-solid fa-clock text-white text-base"></i>
                    @endif
                </div>

                {{-- Konten --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2">
                        <p class="text-sm font-bold
                            {{ $notif->jenis === 'stok_menipis' ? 'text-orange-600' : 'text-gray-700' }} leading-tight">
                            {{ $notif->judul }}
                        </p>
                        <span class="text-[11px] text-gray-400 whitespace-nowrap flex-shrink-0">
                            {{ $notif->created_at->diffForHumans(null, true, true) }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-600 mt-1 leading-snug">{{ $notif->pesan }}</p>
                </div>

                @if(!$notif->is_read)
                    <div class="flex-shrink-0 w-2 h-2 rounded-full bg-orange-500 mt-1.5"></div>
                @endif
            </div>
        @empty
            <div class="text-center py-16">
                <i class="fa-regular fa-bell-slash text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-400 text-sm">Tidak ada notifikasi sistem</p>
            </div>
        @endforelse
    </div>

</div>

<script>
function switchTab(tab) {
    // Sembunyikan semua panel
    document.getElementById('tab-aktivitas').classList.add('hidden');
    document.getElementById('tab-sistem').classList.add('hidden');

    // Reset semua tombol
    document.getElementById('tab-btn-aktivitas').classList.remove('border-olive', 'text-olive');
    document.getElementById('tab-btn-aktivitas').classList.add('border-transparent', 'text-gray-400');
    document.getElementById('tab-btn-sistem').classList.remove('border-olive', 'text-olive');
    document.getElementById('tab-btn-sistem').classList.add('border-transparent', 'text-gray-400');

    // Aktifkan tab yang dipilih
    document.getElementById('tab-' + tab).classList.remove('hidden');
    document.getElementById('tab-btn-' + tab).classList.add('border-olive', 'text-olive');
    document.getElementById('tab-btn-' + tab).classList.remove('border-transparent', 'text-gray-400');
}
</script>
@endsection