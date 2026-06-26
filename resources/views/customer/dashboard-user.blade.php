@extends('layouts.dashboard')

@section('page_title', 'Dashboard')

@section('content')

    {{-- NOTIFIKASI FLASH SESSION --}}
    @if(session('success'))
        <div class="mb-4">
            <x-alert type="success" :message="session('success')" />
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4">
            <x-alert type="error" :message="session('error')" />
        </div>
    @endif

    {{-- FORUM PENCARIAN (SEARCH BAR) --}}
    <div class="relative w-full max-w-2xl mx-auto mb-8">
        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
            <i class="fa-solid fa-magnifying-glass text-sm"></i>
        </span>
        <input
            type="text"
            placeholder="Temukan makanan penyelamat bumi di sekitarmu..."
            class="w-full pl-11 pr-4 py-3.5 rounded-full shadow-xs bg-white text-sm text-gray-700 placeholder-gray-400 border border-transparent outline-none focus:border-[#6D6B2E]/30 focus:ring-4 focus:ring-[#6D6B2E]/10 transition-all">
    </div>

    {{-- KONTEN REKOMENDASI MAKANAN --}}
    <div class="space-y-5">
        <div class="border-b border-[#545523]/10 pb-2">
            <h2 class="text-xl font-bold text-[#545523]">Rekomendasi Untukmu</h2>
            <p class="text-xs text-white font-medium">Makanan lezat yang siap diselamatkan hari ini dengan harga miring.</p>
        </div>

        @if($listings->isEmpty())
            {{-- SEANDAINYA TIDAK ADA PRODUK YANG TERSEDIA --}}
            <div class="bg-[#FDFDF5]/50 border border-dashed border-[#545523]/20 rounded-2xl p-10 text-center max-w-md mx-auto">
                <div class="text-[#545523]/30 text-4xl mb-2">
                    <i class="fa-solid fa-store-slash"></i>
                </div>
                <h3 class="text-sm font-bold text-[#545523]">Belum Ada Makanan Tersedia</h3>
                <p class="text-xs text-gray-400 mt-1">Pantau terus ya! Merchant di sekitarmu akan segera mengunggah produk surplus mereka.</p>
            </div>
        @else
            {{-- GRID CARD MAKANAN --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($listings as $listing)
                    <x-food-card
                        data-url="/checkout/{{ $listing->id }}" 
                        :foto="$listing->foto"
                        :nama="$listing->nama"
                        :merchant="$listing->merchant?->nama_usaha ?? 'Merchant'"
                        :alamat="$listing->merchant?->alamat ?? '-'"
                        :jarak="$listing->jarak_km ?? '-'"
                        :harga_diskon="$listing->harga_diskon"
                        :harga_asli="$listing->harga_normal"
                        :tersisa="$listing->stok_sisa"
                    />
                @endforeach
            </div>
        @endif
    </div>

    {{-- BANNER BANNER CTA: DAFTAR MERCHANT (Hanya muncul jika user biasa) --}}
    @if(Auth::user()->peran !== 'merchant')
        <div class="mt-12 bg-[#FDFDF5] border border-[#545523]/10 rounded-2xl p-6 shadow-xs flex flex-col md:flex-row md:items-center md:justify-between gap-4 transition-all hover:shadow-md">
            <div class="space-y-1">
                <h3 class="text-base font-bold text-[#545523] flex items-center gap-1.5">
                    <i class="fa-solid fa-store text-sm text-[#6D6B2E]"></i> Punya Usaha Kuliner Sendiri?
                </h3>
                <p class="text-xs text-gray-500 max-w-xl">
                    Kurangi limbah makanan bisnis Anda sekaligus raih keuntungan tambahan. Daftarkan usaha Anda menjadi mitra merchant Saveat sekarang juga!
                </p>
            </div>
            <div class="shrink-0">
                <a
                    href="{{ route('merchant.application') }}"
                    class="inline-block w-full md:w-auto text-center text-xs bg-[#6D6B2E] text-[#F1F2CF] font-bold px-5 py-3 rounded-xl hover:bg-[#545523] transition-all shadow-xs">
                    Mulai Daftar Merchant <i class="fa-solid fa-arrow-right text-[10px] ml-1"></i>
                </a>
            </div>
        </div>
    @endif

@endsection