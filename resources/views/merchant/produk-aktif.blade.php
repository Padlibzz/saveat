@extends('layouts.dashboard')

@section('page_title', 'Listing Aktif')

@section('content')

    {{-- BANNER NOTIFIKASI FLASH SESSION --}}
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

    {{-- HEADER KONTEN --}}
    <div class="flex justify-between items-center border-b border-[#545523]/10 pb-3 mb-6">
        <div>
            <h1 class="text-lg md:text-xl font-bold text-[#545523]">Listing Aktif 🟢</h1>
            <p class="text-xs text-gray-400 font-medium">Daftar makanan Anda yang sedang aktif dipasarkan saat ini.</p>
        </div>
        <a href="/merchant/upload-makanan" class="inline-flex items-center gap-1.5 bg-[#545523] text-[#F1F2CF] text-xs font-bold px-4 py-2.5 rounded-xl shadow-xs hover:bg-[#43441c] transition-all">
            <i class="fa-solid fa-plus text-[10px]"></i> Tambah Baru
        </a>
    </div>

    {{-- KONDISI 1: JIKA LISTING KOSONG --}}
    @if($listings->isEmpty())
        <div class="bg-[#FDFDF5]/50 border-2 border-dashed border-[#545523]/20 rounded-2xl p-12 text-center max-w-xl mx-auto my-8">
            <div class="text-[#545523]/30 text-5xl mb-4">
                <i class="fa-solid fa-utensils"></i>
            </div>
            <h3 class="text-sm font-bold text-[#545523]">Belum Ada Makanan Aktif</h3>
            <p class="text-xs text-gray-400 mt-1 max-w-xs mx-auto">Makanan yang Anda unggah dan masih dalam batas waktu pengambilan akan muncul di sini.</p>
            <a href="/merchant/upload-makanan" class="mt-4 inline-block text-xs bg-[#545523] text-[#F1F2CF] px-5 py-2.5 rounded-xl font-bold shadow-xs hover:bg-[#43441c] transition-all">
                Mulai Unggah Makanan
            </a>
        </div>
    @else
        {{-- KONDISI 2: GRID LISTING PRODUK --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($listings as $item)
                <div class="bg-[#FDFDF5] rounded-2xl shadow-xs border border-[#545523]/10 overflow-hidden flex flex-col justify-between group hover:shadow-md transition-all duration-200">
                    
                    {{-- AREA GAMBAR & BADGE STATUS --}}
                    <div class="relative w-full aspect-video bg-gray-100 overflow-hidden">
                        @if($item->foto)
                            <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-gray-300 bg-gray-50 gap-1">
                                <i class="fa-solid fa-image text-2xl"></i>
                                <span class="text-[10px] text-gray-400">Tanpa Foto</span>
                            </div>
                        @endif

                        {{-- BADGE JUMLAH STOK --}}
                        @if($item->stok_sisa <= 1)
                            <span class="absolute top-3 right-3 bg-red-600 text-white text-[10px] font-extrabold px-2.5 py-1 rounded-full shadow-xs animate-pulse z-10">
                                Sisa {{ $item->stok_sisa }} Porsi!
                            </span>
                        @else
                            <span class="absolute top-3 right-3 bg-[#545523]/90 text-[#F1F2CF] text-[10px] font-bold px-2.5 py-1 rounded-full shadow-xs z-10">
                                Stok: {{ $item->stok_sisa }}
                            </span>
                        @endif

                        {{-- BADGE BATAS JALUR AMBIL --}}
                        <div class="absolute bottom-3 left-3 bg-amber-600/90 backdrop-blur-xs text-white text-[10px] font-bold px-2.5 py-1 rounded-lg flex items-center gap-1 shadow-xs z-10">
                            <i class="fa-solid fa-clock text-[9px]"></i>
                            <span>Ambil s/d {{ date('H:i', strtotime($item->batas_waktu)) }}</span>
                        </div>
                    </div>

                    {{-- INFORMASI DATA PRODUK --}}
                    <div class="p-4 flex-1 flex flex-col justify-between space-y-4">
                        <div>
                            <h3 class="font-bold text-gray-800 text-sm md:text-base line-clamp-1 group-hover:text-[#545523] transition-colors">
                                {{ $item->nama }}
                            </h3>
                            
                            {{-- HARGA PROMO VS NORMAL --}}
                            <div class="flex items-baseline gap-2 mt-1.5">
                                <span class="text-sm font-black text-[#545523]">
                                    Rp{{ number_format($item->harga_diskon, 0, ',', '.') }}
                                </span>
                                <span class="text-xs text-gray-400 line-through decoration-red-400/60">
                                    Rp{{ number_format($item->harga_normal, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        {{-- PANEL AKSI MODUL (EDIT / TUTUP) --}}
                        <div class="flex items-center gap-2 pt-1 border-t border-gray-50">
                            <a href="/merchant/listing/{{ $item->id }}/edit" class="flex-1 text-center border border-gray-200 text-gray-600 rounded-xl py-2 text-xs font-bold hover:bg-gray-50 hover:text-gray-800 transition-all">
                                <i class="fa-solid fa-pen-to-square mr-1 text-[10px]"></i> Edit
                            </a>

                            <form action="/merchant/listing/{{ $item->id }}/tutup" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menutup listing makanan ini?')">
                                @csrf
                                <button type="submit" class="border border-red-100 text-red-500 bg-red-50/30 hover:bg-red-50 hover:text-red-600 p-2 rounded-xl transition-all w-9 h-9 flex items-center justify-center cursor-pointer" title="Hapus / Tutup Listing">
                                    <i class="fa-regular fa-trash-can text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>
    @endif

@endsection