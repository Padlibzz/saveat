@extends('layouts.dashboard')

@section('page_title', 'Listing Makanan')

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

    {{-- PANEL PENCARIAN & FILTER KATEGORI --}}
    <form action="{{ route('listing-makanan') }}" method="GET" class="bg-[#FDFDF5] rounded-3xl p-5 md:p-6 border border-[#545523]/10 shadow-xs space-y-5 mb-8">
        
        {{-- Input Cari --}}
        <div class="relative w-full">
            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                <i class="fa-solid fa-magnifying-glass text-sm"></i>
            </span>
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Mau menyelamatkan makanan apa hari ini?..."
                class="w-full pl-11 pr-12 py-3.5 rounded-full bg-white text-sm text-gray-700 placeholder-gray-400 border border-gray-200 outline-none focus:border-[#6D6B2E]/50 focus:ring-4 focus:ring-[#6D6B2E]/10 transition-all">
            
            <button type="submit" class="absolute right-4 top-1/2 -translate-y-1/2 text-[#6D6B2E] hover:text-[#545523] transition-colors cursor-pointer">
                <i class="fa-solid fa-search"></i>
            </button>
        </div>

        {{-- Filter Kategori --}}
        <div class="space-y-2">
            <label class="text-xs font-bold text-[#545523] uppercase tracking-wider block">Pilih Kategori:</label>
            
            {{-- Scrollable Pills Kontainer --}}
            <div class="flex items-center gap-2 overflow-x-auto pb-1 no-scrollbar [-webkit-overflow-scrolling:touch]">
                
                {{-- Opsi Semua --}}
                <label class="cursor-pointer shrink-0">
                    <input type="checkbox" name="category[]" value="all" class="peer hidden" checked>
                    <span class="inline-block px-4 py-2 rounded-full text-xs font-semibold bg-white border border-gray-200 text-gray-600 peer-checked:bg-[#545523] peer-checked:text-[#F1F2CF] peer-checked:border-transparent transition-all shadow-2xs">
                        🍲 Semua Makanan
                    </span>
                </label>

                {{-- Opsi Makanan Berat --}}
                <label class="cursor-pointer shrink-0">
                    <input type="checkbox" name="category[]" value="berat" class="peer hidden">
                    <span class="inline-block px-4 py-2 rounded-full text-xs font-semibold bg-white border border-gray-200 text-gray-600 peer-checked:bg-[#545523] peer-checked:text-[#F1F2CF] peer-checked:border-transparent transition-all shadow-2xs">
                        🍚 Makanan Berat
                    </span>
                </label>

                {{-- Opsi Roti & Kue --}}
                <label class="cursor-pointer shrink-0">
                    <input type="checkbox" name="category[]" value="roti" class="peer hidden">
                    <span class="inline-block px-4 py-2 rounded-full text-xs font-semibold bg-white border border-gray-200 text-gray-600 peer-checked:bg-[#545523] peer-checked:text-[#F1F2CF] peer-checked:border-transparent transition-all shadow-2xs">
                        🥐 Roti & Kue
                    </span>
                </label>

                {{-- Opsi Camilan --}}
                <label class="cursor-pointer shrink-0">
                    <input type="checkbox" name="category[]" value="camilan" class="peer hidden">
                    <span class="inline-block px-4 py-2 rounded-full text-xs font-semibold bg-white border border-gray-200 text-gray-600 peer-checked:bg-[#545523] peer-checked:text-[#F1F2CF] peer-checked:border-transparent transition-all shadow-2xs">
                        🍿 Camilan
                    </span>
                </label>

                {{-- Opsi Minuman --}}
                <label class="cursor-pointer shrink-0">
                    <input type="checkbox" name="category[]" value="minuman" class="peer hidden">
                    <span class="inline-block px-4 py-2 rounded-full text-xs font-semibold bg-white border border-gray-200 text-gray-600 peer-checked:bg-[#545523] peer-checked:text-[#F1F2CF] peer-checked:border-transparent transition-all shadow-2xs">
                        ☕ Minuman
                    </span>
                </label>

            </div>
        </div>
    </form>

    {{-- DAFTAR PRODUK MAKANAN --}}
    <div class="space-y-5">
        <div class="border-b border-[#545523]/10 pb-2">
            <h2 class="text-xl font-bold text-[#545523]">Temukan Makanan Favoritmu 🗺️</h2>
            <p class="text-xs text-gray-400 font-medium">Jelajahi pangan berharga dari berbagai mitra kuliner di sekitar lokasi Anda.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse ($listings as $listing)
                <x-food-card
                    data-url="/checkout/{{ $listing->id }}" 
                    :id="$listing->id"
                    :foto="$listing->foto"
                    :nama="$listing->nama"
                    :merchant="$listing->merchant?->nama_usaha ?? 'Merchant'"
                    :alamat="$listing->merchant?->alamat ?? '-'"
                    :jarak="$listing->jarak_km ?? '-'"
                    :harga_diskon="$listing->harga_diskon"
                    :harga_asli="$listing->harga_normal"
                    :tersisa="$listing->stok_sisa"
                />
            @empty
                <div class="col-span-full">
                    <div class="bg-[#FDFDF5]/50 border border-dashed border-[#545523]/20 rounded-2xl p-12 text-center max-w-md mx-auto my-4">
                        <div class="text-[#545523]/30 text-4xl mb-2">
                            <i class="fa-solid fa-utensils"></i>
                        </div>
                        <h3 class="text-sm font-bold text-[#545523]">Tidak Ada Hasil</h3>
                        <p class="text-xs text-gray-400 mt-1">Maaf, makanan dengan kriteria tersebut belum tersedia atau sudah habis terjual.</p>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- PAGINASI HALAMAN --}}
        @if($listings->hasPages())
            <div class="mt-8 pt-4 border-t border-[#545523]/5">
                {{ $listings->links() }}
            </div>
        @endif
    </div>

@endsection