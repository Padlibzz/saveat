@extends('layouts.dashboard')

@section('page_title', 'Daftar Merchant')

@section('content')
<div class="max-w-2xl mx-auto my-4">

    {{-- BANNER NOTIFIKASI FLASH SESSION & ERROR VALIDASI --}}
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

    {{-- Menampilkan error validasi form jika ada input yang tidak sesuai --}}
    @if ($errors->any())
        <div class="mb-4 space-y-2">
            @foreach ($errors->all() as $error)
                <x-alert type="error" :message="$error" />
            @endforeach
        </div>
    @endif

    {{-- KARTU UTAMA FORM PENDAFTARAN --}}
    <div class="bg-[#FFFFFA] p-6 md:p-8 rounded-3xl shadow-xs border border-[#545523]/10">
        
        {{-- Header Form --}}
        <div class="text-center mb-8">
            <h2 class="text-xl md:text-2xl font-bold text-[#545523] mb-1">
                Daftar Merchant 🏪
            </h2>
            <p class="text-xs text-gray-400 font-medium max-w-sm mx-auto">
                Lengkapi data usaha Anda untuk mengajukan verifikasi kemitraan merchant SaveEat.
            </p>
        </div>

        {{-- Form Pendaftaran --}}
        <form action="{{ route('merchant.application.submit') }}" method="POST" class="space-y-5">
            @csrf
            
            {{-- FIELD: NAMA USAHA --}}
            <div>
                <label for="nama_usaha" class="block text-xs font-bold text-[#545523] mb-1.5">
                    Nama Usaha / Toko
                </label>
                <div class="relative">
                    <i class="fa-solid fa-store absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input
                        type="text"
                        id="nama_usaha"
                        name="nama_usaha"
                        value="{{ old('nama_usaha') }}"
                        required
                        class="w-full text-sm bg-gray-50/50 border border-gray-200 rounded-xl py-3 pl-12 pr-4 outline-none focus:border-[#545523] focus:ring-1 focus:ring-[#545523] transition-all"
                        placeholder="Masukkan nama resmi usaha Anda">
                </div>
            </div>

            {{-- FIELD: ALAMAT --}}
            <div>
                <label for="alamat" class="block text-xs font-bold text-[#545523] mb-1.5">
                    Alamat Lengkap Toko
                </label>
                <div class="relative">
                    <i class="fa-solid fa-location-dot absolute left-4 top-4 text-gray-400 text-sm"></i>
                    <textarea
                        id="alamat"
                        name="alamat"
                        rows="3"
                        required
                        class="w-full text-sm bg-gray-50/50 border border-gray-200 rounded-xl py-3 pl-12 pr-4 outline-none focus:border-[#545523] focus:ring-1 focus:ring-[#545523] transition-all"
                        placeholder="Masukkan alamat fisik lengkap lokasi usaha Anda">{{ old('alamat') }}</textarea>
                </div>
            </div>

            {{-- FIELD: DESKRIPSI --}}
            <div>
                <label for="deskripsi" class="block text-xs font-bold text-[#545523] mb-1.5">
                    Deskripsi Singkat Usaha
                </label>
                <textarea
                    id="deskripsi"
                    name="deskripsi"
                    rows="4"
                    required
                    class="w-full text-sm bg-gray-50/50 border border-gray-200 rounded-xl p-4 outline-none focus:border-[#545523] focus:ring-1 focus:ring-[#545523] transition-all"
                    placeholder="Ceritakan jenis surplus makanan atau produk kuliner khas yang usaha Anda tawarkan...">{{ old('deskripsi') }}</textarea>
            </div>

            {{-- FIELD: LINK GOOGLE MAPS --}}
            <div>
                <label for="link_map" class="block text-xs font-bold text-[#545523] mb-1.5">
                    Link Google Maps (Opsional)
                </label>
                <div class="relative">
                    <i class="fa-solid fa-map absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input
                        type="url"
                        name="link_map"
                        id="link_map"
                        value="{{ old('link_map') }}"
                        class="w-full text-sm bg-gray-50/50 border border-gray-200 rounded-xl py-3 pl-12 pr-4 outline-none focus:border-[#545523] focus:ring-1 focus:ring-[#545523] transition-all"
                        placeholder="https://maps.google.com/...">
                </div>
            </div>

            {{-- BUTTON SUBMIT --}}
            <div class="pt-2">
                <button
                    type="submit"
                    class="w-full bg-[#545523] hover:bg-[#43441c] text-[#F1F2CF] py-3.5 rounded-xl text-xs font-bold shadow-xs transition-all cursor-pointer">
                    Ajukan Kemitraan Merchant
                </button>
            </div>

        </form>
    </div>
</div>
@endsection