@extends('layouts.dashboard')

@section('page_title', 'Notifikasi')

@section('content')
<div class="w-full max-w-5xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        @if($aktivitas->where('is_read', false)->count() + $promo->where('is_read', false)->count() > 0)
        <form action="{{ route('notifikasi.read-all') }}" method="POST">
            @csrf
            <button type="submit" class="text-sm text-olive/70 hover:text-olive underline underline-offset-2">
                Tandai semua dibaca
            </button>
        </form>
        @endif
    </div>

    {{-- Tab Navigation --}}
    <div class="flex border-b border-gray-200 mb-6">
        <button
            onclick="switchTab('aktivitas')"
            id="tab-btn-aktivitas"
            class="flex-1 pb-3 text-sm font-semibold text-center border-b-2 border-olive text-olive transition-all"
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
            onclick="switchTab('promo')"
            id="tab-btn-promo"
            class="flex-1 pb-3 text-sm font-semibold text-center border-b-2 border-transparent text-gray-400 hover:text-olive transition-all"
        >
            Promo
            @php $unreadPromo = $promo->where('is_read', false)->count() @endphp
            @if($unreadPromo > 0)
                <span class="ml-1 inline-flex items-center justify-center w-5 h-5 rounded-full bg-olive text-cream text-[10px]">
                    {{ $unreadPromo }}
                </span>
            @endif
        </button>
    </div>

    {{-- TAB: Aktivitas --}}
    <div id="tab-aktivitas">
        @forelse($aktivitas as $notif)
            <div class="flex gap-4 p-4 mb-2 rounded-2xl bg-white border
                {{ !$notif->is_read ? 'border-olive/20 shadow-sm' : 'border-gray-100' }}">

                {{-- Icon --}}
                <div class="flex-shrink-0 w-11 h-11 rounded-full bg-olive flex items-center justify-center">
                    @if($notif->jenis === 'claims_berhasil')
                        <i class="fa-solid fa-bag-shopping text-cream text-base"></i>
                    @elseif($notif->jenis === 'pesanan_selesai')
                        <i class="fa-solid fa-suitcase text-cream text-base"></i>
                    @elseif($notif->jenis === 'menunggu_pembayaran')
                        <i class="fa-solid fa-credit-card text-cream text-base"></i>
                    @else
                        <i class="fa-solid fa-bell text-cream text-base"></i>
                    @endif
                </div>

                {{-- Konten --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2">
                        <p class="text-sm font-bold text-charcoal leading-tight">{{ $notif->judul }}</p>
                        <div class="flex items-center gap-1.5 flex-shrink-0">
                            <span class="text-[11px] text-gray-400">
                                {{ $notif->created_at->diffForHumans(null, true, true) }}
                            </span>
                            @if(!$notif->is_read)
                                <div class="w-2 h-2 rounded-full bg-olive"></div>
                            @endif
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mt-1 leading-snug">{{ $notif->pesan }}</p>

                    {{-- Link ke detail pesanan --}}
                    @if($notif->claim_id && in_array($notif->jenis, ['claims_berhasil', 'pesanan_selesai']))
                        <a href="{{ route('pesanan.detail', $notif->claim_id) }}"
                           class="inline-block mt-2 text-xs text-olive font-semibold hover:underline">
                            Lihat Detail →
                        </a>
                    @endif
                    @if($notif->claim_id && $notif->jenis === 'menunggu_pembayaran')
                        <a href="{{ route('pesanan.detail', $notif->claim_id) }}"
                           class="inline-block mt-2 text-xs text-olive font-semibold hover:underline">
                            Selesaikan Pembayaran →
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-16">
                <i class="fa-regular fa-bell-slash text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-400 text-sm">Belum ada aktivitas</p>
            </div>
        @endforelse
    </div>

    {{-- TAB: Promo --}}
    <div id="tab-promo" class="hidden">
        @forelse($promo as $notif)
            <div class="flex gap-4 p-4 mb-2 rounded-2xl bg-white border
                {{ !$notif->is_read ? 'border-olive/20 shadow-sm' : 'border-gray-100' }}">

                {{-- Icon --}}
                <div class="flex-shrink-0 w-11 h-11 rounded-full overflow-hidden
                    {{ $notif->jenis === 'tonggak_dampak' ? 'bg-olive' : 'bg-olive' }} flex items-center justify-center">
                    @if($notif->jenis === 'listing_baru')
                        {{-- Tampilkan icon makanan / foto listing jika ada --}}
                        <i class="fa-solid fa-utensils text-cream text-base"></i>
                    @elseif($notif->jenis === 'tonggak_dampak')
                        <i class="fa-solid fa-leaf text-cream text-base"></i>
                    @else
                        <i class="fa-solid fa-tag text-cream text-base"></i>
                    @endif
                </div>

                {{-- Konten --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2">
                        <p class="text-sm font-bold text-charcoal leading-tight">{{ $notif->judul }}</p>
                        <div class="flex items-center gap-1.5 flex-shrink-0">
                            <span class="text-[11px] text-gray-400">
                                {{ $notif->created_at->diffForHumans(null, true, true) }}
                            </span>
                            @if(!$notif->is_read)
                                <div class="w-2 h-2 rounded-full bg-olive"></div>
                            @endif
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mt-1 leading-snug">{{ $notif->pesan }}</p>

                    @if($notif->jenis === 'listing_baru')
                        <a href="{{ route('listing-makanan') }}"
                           class="inline-block mt-2 text-xs text-olive font-semibold hover:underline">
                            Lihat Listing →
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-16">
                <i class="fa-regular fa-tag text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-400 text-sm">Belum ada promo atau notifikasi dampak</p>
            </div>
        @endforelse
    </div>

</div>

<script>
function switchTab(tab) {
    document.getElementById('tab-aktivitas').classList.add('hidden');
    document.getElementById('tab-promo').classList.add('hidden');

    document.getElementById('tab-btn-aktivitas').classList.remove('border-olive', 'text-olive');
    document.getElementById('tab-btn-aktivitas').classList.add('border-transparent', 'text-gray-400');
    document.getElementById('tab-btn-promo').classList.remove('border-olive', 'text-olive');
    document.getElementById('tab-btn-promo').classList.add('border-transparent', 'text-gray-400');

    document.getElementById('tab-' + tab).classList.remove('hidden');
    document.getElementById('tab-btn-' + tab).classList.add('border-olive', 'text-olive');
    document.getElementById('tab-btn-' + tab).classList.remove('border-transparent', 'text-gray-400');
}
</script>
@endsection