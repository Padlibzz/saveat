@extends('layouts.dashboard')

@section('page_title', 'Listing Aktif')

@section('content')
{{-- WRAPPER ALPINE.JS --}}
<div x-data="listingAktifComponent()">

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

                        {{-- PANEL AKSI MODUL (EDIT / TUTUP MENGGUNAKAN ALPINE) --}}
                        <div class="flex items-center gap-2 pt-1 border-t border-gray-50">
                            {{-- TRIGGER MODAL EDIT --}}
                            <button type="button" @click="bukaModalEdit({{ json_encode($item) }})" class="flex-1 text-center border border-gray-200 text-gray-600 rounded-xl py-2 text-xs font-bold hover:bg-gray-50 hover:text-gray-800 transition-all cursor-pointer">
                                <i class="fa-solid fa-pen-to-square mr-1 text-[10px]"></i> Edit
                            </button>

                            {{-- TRIGGER MODAL HAPUS/TUTUP --}}
                            <button type="button" @click="bukaModalHapus({{ json_encode($item) }})" class="border border-red-100 text-red-500 bg-red-50/30 hover:bg-red-50 hover:text-red-600 p-2 rounded-xl transition-all w-9 h-9 flex items-center justify-center cursor-pointer" title="Hapus / Tutup Listing">
                                <i class="fa-regular fa-trash-can text-sm"></i>
                            </button>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>
    @endif

    {{-- ========================================== --}}
    {{-- MODAL EDIT LISTING                         --}}
    {{-- ========================================== --}}
    <div x-show="modalEditOpen" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 backdrop-blur-sm bg-black/60"
         x-transition.opacity x-cloak>
        <div @click.away="tutupModal()" 
             class="bg-white w-full max-w-lg rounded-3xl overflow-hidden shadow-2xl relative flex flex-col max-h-[90vh]"
             x-show="modalEditOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-8 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100">
            
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-[#FDFDF5]">
                <h3 class="font-bold text-[#545523]">Edit Listing Makanan</h3>
                <button type="button" @click="tutupModal()" class="text-gray-400 hover:text-red-500 cursor-pointer">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            {{-- Form Edit Area --}}
            {{-- Action URL akan diisi otomatis oleh Alpine menggunakan x-bind:action --}}
            <form method="POST" :action="'/merchant/listing/' + formEdit.id + '/update'" class="flex flex-col overflow-hidden">
                @csrf
                @method('PUT') {{-- Sesuaikan dengan method route backend Anda, hapus jika pakai POST --}}
                
                <div class="p-6 overflow-y-auto custom-scrollbar space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Nama Makanan</label>
                        <input type="text" name="nama" x-model="formEdit.nama" required class="w-full text-sm px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-[#545523] focus:ring-1 focus:ring-[#545523] transition bg-gray-50/50">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Harga Normal (Rp)</label>
                            <input type="number" name="harga_normal" x-model="formEdit.harga_normal" required class="w-full text-sm px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-[#545523] focus:ring-1 focus:ring-[#545523] transition bg-gray-50/50">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Harga Diskon (Rp)</label>
                            <input type="number" name="harga_diskon" x-model="formEdit.harga_diskon" required class="w-full text-sm px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-[#545523] focus:ring-1 focus:ring-[#545523] transition bg-gray-50/50">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Stok Sisa</label>
                            <input type="number" name="stok_sisa" x-model="formEdit.stok_sisa" required class="w-full text-sm px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-[#545523] focus:ring-1 focus:ring-[#545523] transition bg-gray-50/50">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Batas Waktu Ambil</label>
                            <input type="time" name="batas_waktu" x-model="formEdit.batas_waktu" required class="w-full text-sm px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-[#545523] focus:ring-1 focus:ring-[#545523] transition bg-gray-50/50">
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 flex gap-3 border-t border-gray-100 justify-end mt-auto">
                    <button type="button" @click="tutupModal()" class="px-5 py-2 text-xs font-bold text-gray-600 bg-white border border-gray-300 rounded-xl hover:bg-gray-100 transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 text-xs font-bold text-[#F1F2CF] bg-[#545523] rounded-xl hover:bg-[#43441c] shadow-xs transition cursor-pointer">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- MODAL KONFIRMASI TUTUP/HAPUS               --}}
    {{-- ========================================== --}}
    <div x-show="modalHapusOpen" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 backdrop-blur-sm bg-black/60"
         x-transition.opacity x-cloak>
        <div @click.away="tutupModal()" 
             class="bg-white w-full max-w-sm rounded-3xl overflow-hidden shadow-2xl relative p-6 text-center"
             x-show="modalHapusOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-90"
             x-transition:enter-end="opacity-100 scale-100">
            
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-triangle-exclamation text-3xl text-red-500"></i>
            </div>
            
            <h3 class="text-lg font-bold text-gray-800 mb-2">Tutup Listing?</h3>
            <p class="text-xs text-gray-500 mb-6">
                Apakah Anda yakin ingin menutup listing <br>
                <span class="font-bold text-gray-700" x-text="itemPilih?.nama"></span>?<br>
                Pelanggan tidak akan bisa memesannya lagi.
            </p>

            <div class="flex gap-3 w-full">
                <button type="button" @click="tutupModal()" class="flex-1 py-2.5 text-xs font-bold text-gray-600 bg-gray-100 rounded-xl hover:bg-gray-200 transition cursor-pointer">
                    Batal
                </button>
                
                {{-- Form action akan diset otomatis ke route hapus yang Anda miliki --}}
                <form method="POST" :action="'/merchant/listing/' + itemPilih?.id + '/tutup'" class="flex-1 m-0">
                    @csrf
                    <button type="submit" class="w-full py-2.5 text-xs font-bold text-white bg-red-600 rounded-xl hover:bg-red-700 shadow-xs transition cursor-pointer">
                        Ya, Tutup
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection

{{-- SCRIPT ALPINE.JS COMPONENT --}}
@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('listingAktifComponent', () => ({
                modalEditOpen: false,
                modalHapusOpen: false,
                itemPilih: null,
                formEdit: {
                    id: '',
                    nama: '',
                    harga_normal: '',
                    harga_diskon: '',
                    stok_sisa: '',
                    batas_waktu: ''
                },

                bukaModalEdit(item) {
                    let waktuMurni = item.batas_waktu ? item.batas_waktu.substring(0, 5) : '';

                    this.formEdit = { 
                        ...item,
                        batas_waktu: waktuMurni 
                    };
                    this.modalEditOpen = true;
                    document.body.style.overflow = 'hidden';
                },

                bukaModalHapus(item) {
                    this.itemPilih = item;
                    this.modalHapusOpen = true;
                    document.body.style.overflow = 'hidden';
                },

                tutupModal() {
                    this.modalEditOpen = false;
                    this.modalHapusOpen = false;
                    setTimeout(() => { 
                        document.body.style.overflow = 'auto';
                    }, 300);
                }
            }));
        });
    </script>
@endpush