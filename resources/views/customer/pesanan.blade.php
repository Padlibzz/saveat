@extends('layouts.dashboard')

@section('page_title', 'Aktivitas & Pesanan')

@section('content')
<div class="w-full max-w-3xl mx-auto">
    
    {{-- JUDUL HALAMAN --}}
    <div class="mb-6 border-b border-[#545523]/10 pb-4">
        <h2 class="text-xl font-bold text-[#545523]">Aktivitas & Riwayat Pesanan 📋</h2>
        <p class="text-xs text-gray-400 font-medium">Pantau terus status klaim makanan penyelamatmu dan tumpukan kontribusi lingkunganmu.</p>
    </div>

    {{-- SIMULASI DATA BACKEND (MOCK DATA UNTUK TESTING) --}}
    {{-- Nanti kalau backend sudah siap, hapus blok @php ini dan ganti $riwayatPesanan dengan variabel dari Controller --}}
    @php
        $riwayatPesanan = [
            (object)[
                'id' => 1,
                'tipe' => 'status_pesanan', // indikator pakai ikon font-awesome
                'ikon' => 'fa-basket-shopping',
                'foto' => null,
                'judul' => 'Pesanan Diterima',
                'deskripsi' => 'Pesanan yang kamu klaim sudah diterima oleh <span class="font-semibold text-[#545523]">"Dapur Roti Nusantara"</span>. Ambil di jam 18:30 WIB.',
                'waktu' => '2m',
                'punya_notif_hijau' => true,
                'efek_pulse' => true,
                'bg_tambahan' => ''
            ],
            (object)[
                'id' => 2,
                'tipe' => 'mitra', // indikator pakai foto toko/makanan
                'ikon' => null,
                'foto' => 'https://images.unsplash.com/photo-1513104890138-7c749659a591?q=80&w=150&auto=format&fit=crop',
                'judul' => 'Dapur Roti Indonesia',
                'deskripsi' => 'makanan yang Kamu Pesan Udah Jadi, Segera ambilpesananmu sebelum toko...',
                'waktu' => '1j',
                'punya_notif_hijau' => true,
                'efek_pulse' => false,
                'bg_tambahan' => 'bg-gray-50/40'
            ],
            (object)[
                'id' => 3,
                'tipe' => 'dampak_lingkungan', // indikator ikon daun
                'ikon' => 'fa-solid fa-leaf',
                'foto' => null,
                'judul' => 'Tonggak Dampak',
                'deskripsi' => 'Anda telah menghemat <span class="font-semibold text-emerald-600">5 kg CO2</span> minggu ini. Anda termasuk dalam <span class="font-semibold text-[#545523]">5% penyelamat teratas</span> di wilayah Anda!',
                'waktu' => '4j',
                'punya_notif_hijau' => false,
                'efek_pulse' => false,
                'bg_tambahan' => ''
            ],
            (object)[
                'id' => 4,
                'tipe' => 'status_pesanan',
                'ikon' => 'fa-bag-shopping',
                'foto' => null,
                'judul' => 'Pesanan siap diambil',
                'deskripsi' => 'Pesanan yang sudah kamu pesan di <span class="font-semibold text-[#545523]">"Dapur Roti Nusantara"</span> Sudah siap untuk diambil.',
                'waktu' => '2m',
                'punya_notif_hijau' => true,
                'efek_pulse' => false,
                'bg_tambahan' => ''
            ],
        ];
    @endphp

    {{-- KONTEN FEED UTAMA --}}
    <div class="bg-white rounded-3xl shadow-xs border border-gray-100 overflow-hidden divide-y divide-gray-50">
        
        {{-- LOOPING DINAMIS MENGGUNAKAN DATA SIMULASI --}}
        @foreach($riwayatPesanan as $item)
            <div class="p-5 flex items-start gap-4 hover:bg-gray-50/50 transition-all relative {{ $item->bg_tambahan }}">
                
                {{-- KONDISI 1: JIKA INGIN MENAMPILKAN FOTO MITRA --}}
                @if($item->tipe === 'mitra' && $item->foto)
                    <div class="w-12 h-12 rounded-full overflow-hidden shrink-0 border border-gray-100 shadow-2xs">
                        <img src="{{ $item->foto }}" alt="{{ $item->judul }}" class="w-full h-full object-cover">
                    </div>
                {{-- KONDISI 2: JIKA INGIN MENAMPILKAN IKON STANDAR --}}
                @else
                    <div class="w-12 h-12 rounded-full bg-[#545523] flex items-center justify-center text-white text-lg shrink-0 shadow-2xs">
                        <i class="fa-solid {{ $item->ikon }}"></i>
                    </div>
                @endif
                
                {{-- TEKS DESKRIPSI (Menggunakan {!! !!} agar tag HTML b/span di dalam string bisa terbaca) --}}
                <div class="flex-1 min-w-0 pr-8">
                    <h4 class="text-sm font-bold text-gray-800 tracking-wide">{{ $item->judul }}</h4>
                    <p class="text-xs text-gray-600 mt-1 leading-relaxed">
                        {!! $item->deskripsi !!}
                    </p>
                </div>

                {{-- METADATA WAKTU & INDIKATOR HIJAU --}}
                <div class="absolute right-5 top-5 flex flex-col items-end gap-2">
                    <span class="text-[11px] font-medium text-gray-400">{{ $item->waktu }}</span>
                    
                    @if($item->punya_notif_hijau)
                        <span class="w-2 h-2 rounded-full bg-emerald-600 block {{ $item->efek_pulse ? 'animate-pulse' : '' }}"></span>
                    @endif
                </div>
            </div>
        @endforeach

    </div>
</div>
@endsection