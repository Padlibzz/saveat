@extends('layout.profile')

@section('title', 'Edit Profil - SaveEat')
@section('page_title', 'Edit Profil') {{-- Otomatis mengisi judul di navbar layout --}}

@section('content')
<div class="max-w-md mx-auto bg-white rounded-3xl border border-gray-100 shadow-xs p-6 md:p-8">
    
    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" x-data="{ photoPreview: null }">
        @csrf
        @method('PUT')

        {{-- 1. BAGIAN UBAH FOTO PROFIL --}}
        <div class="flex flex-col items-center mb-6">
            <div class="relative w-28 h-28">
                {{-- Preview foto menggunakan Alpine.js agar langsung berubah saat user memilih file baru --}}
                <img :src="photoPreview ? photoPreview : '{{ Auth::user()->profil_image ? asset('storage/'.Auth::user()->profil_image) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=F1F2CF&color=545523&rounded=true&bold=true&size=128' }}'" 
                     alt="Foto Profil" 
                     class="w-full h-full object-cover rounded-full border border-gray-100">
                
                {{-- Tombol badge pensil kecil --}}
                <label for="avatar_input" class="absolute bottom-0 right-0 bg-[#545523] text-white p-2 rounded-full cursor-pointer hover:bg-[#43441c] transition border-2 border-white shadow-sm flex items-center justify-center w-8 h-8">
                    <i class="fa-solid fa-pencil text-[10px]"></i>
                </label>
                
                {{-- Input file asli yang disembunyikan --}}
                <input type="file" id="avatar_input" name="profil_image" class="hidden" accept="image/*" 
                       @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { photoPreview = e.target.result; }; reader.readAsDataURL(file); }">
            </div>
            
            {{-- Label teks di bawah foto --}}
            <label for="avatar_input" class="text-xs font-semibold text-gray-500 mt-2.5 cursor-pointer hover:text-[#545523] transition tracking-wide">
                Change Photo
            </label>
            
            @error('profil_image')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- 2. FORM INPUT DATA USER --}}
        <div class="space-y-4">
            
            {{-- Input Nama Lengkap --}}
            <div>
                <label for="name" class="text-xs font-semibold text-gray-500 mb-1.5 block">Nama Lengkap</label>
                <div class="flex items-center border border-gray-200 rounded-xl px-3 bg-gray-50/50 focus-within:border-[#545523] focus-within:bg-white focus-within:ring-1 focus-within:ring-[#545523]/20 transition h-12">
                    <i class="fa-regular fa-user text-gray-400 mr-2.5 text-sm"></i>
                    <input type="text" id="name" name="name" 
                           value="{{ old('name', Auth::user()->name) }}" 
                           placeholder="Nama Lengkap"
                           class="w-full bg-transparent text-sm text-gray-700 outline-none placeholder-gray-400 font-medium" required>
                </div>
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Input Alamat Email --}}
            <div>
                <label for="email" class="text-xs font-semibold text-gray-500 mb-1.5 block">Alamat Email</label>
                <div class="flex items-center border border-gray-200 rounded-xl px-3 bg-gray-50/50 focus-within:border-[#545523] focus-within:bg-white focus-within:ring-1 focus-within:ring-[#545523]/20 transition h-12">
                    <i class="fa-regular fa-envelope text-gray-400 mr-2.5 text-sm"></i>
                    <input type="email" id="email" name="email" 
                           value="{{ old('email', Auth::user()->email) }}" 
                           placeholder="nama@email.com"
                           class="w-full bg-transparent text-sm text-gray-700 outline-none placeholder-gray-400 font-medium" required>
                </div>
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Input Lokasi + Preview Peta Muckup --}}
            <div>
                <label for="lokasi" class="text-xs font-semibold text-gray-500 mb-1.5 block">Lokasi</label>
                <div class="flex items-center border border-gray-200 rounded-xl px-3 bg-gray-50/50 focus-within:border-[#545523] focus-within:bg-white focus-within:ring-1 focus-within:ring-[#545523]/20 transition h-12 mb-2">
                    <i class="fa-solid fa-location-dot text-gray-400 mr-2.5 text-sm"></i>
                    <input type="text" id="lokasi" name="lokasi" 
                           value="{{ old('lokasi', Auth::user()->lokasi ?? 'Jakarta Barat') }}" 
                           placeholder="Pilih lokasi rumah/toko"
                           class="w-full bg-transparent text-sm text-gray-700 outline-none placeholder-gray-400 font-medium" required>
                </div>
                
                {{-- Kartu Placeholder Peta/Maps Sesuai Gambar Mockup --}}
                <div class="relative w-full h-24 rounded-xl overflow-hidden border border-gray-100 bg-gray-100 flex items-center justify-center group cursor-pointer">
                    {{-- Gambar latar belakang placeholder peta abstrak --}}
                    <div class="absolute inset-0 opacity-40 bg-[radial-gradient(#9ca3af_1px,transparent_1px)] [background-size:16px_16px]"></div>
                    {{-- Ikon Pin Besar di Tengah --}}
                    <div class="relative w-12 h-12 rounded-full bg-white/90 shadow-xs flex items-center justify-center border border-gray-200 group-hover:scale-105 transition">
                        <i class="fa-solid fa-location-crosshairs text-[#545523] text-lg"></i>
                    </div>
                </div>
                @error('lokasi')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

        </div>

        {{-- 3. TOMBOL SIMPAN UTAMA --}}
        <button type="submit" class="bg-[#545523] text-white font-bold w-full py-3.5 rounded-xl hover:bg-[#43441c] active:scale-[0.99] transition shadow-xs mt-6 text-sm cursor-pointer tracking-wide">
            Simpan Perubahan
        </button>

    </form>
</div>
@endsection