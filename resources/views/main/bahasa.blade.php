@extends('layout.pengaturan')

@section('title', 'Bahasa - SaveEat')
@section('page_title', 'Bahasa')

@section('content')
{{-- Gunakan Alpine.js untuk menyimpan status bahasa yang dipilih (default: 'id') --}}
<div class="max-w-md mx-auto" x-data="{ selectedLanguage: 'id' }">
    
    <form action="#" method="POST" class="flex flex-col">
        @csrf
        {{-- Input hidden ini yang akan dikirim ke backend Laravel Anda --}}
        <input type="hidden" name="language" x-model="selectedLanguage">

        {{-- Container Pilihan Bahasa --}}
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden mb-8">
            
            {{-- Pilihan 1: Bahasa Indonesia --}}
            <button type="button" @click="selectedLanguage = 'id'" 
                    class="w-full flex items-center justify-between p-4.5 border-b border-gray-50 hover:bg-gray-50 transition-colors">
                <span class="text-sm font-medium text-[#545523]">Bahasa Indonesia</span>
                
                {{-- Ikon Ceklis (Aktif) --}}
                <i x-show="selectedLanguage === 'id'" class="fa-solid fa-circle-check text-lg text-[#545523]"></i>
                {{-- Ikon Lingkaran Kosong (Tidak Aktif) --}}
                <i x-show="selectedLanguage !== 'id'" style="display: none;" class="fa-regular fa-circle text-lg text-[#a1a382]"></i>
            </button>

            {{-- Pilihan 2: English --}}
            <button type="button" @click="selectedLanguage = 'en'" 
                    class="w-full flex items-center justify-between p-4.5 border-b border-gray-50 hover:bg-gray-50 transition-colors">
                <span class="text-sm font-medium text-[#545523]">English</span>
                
                <i x-show="selectedLanguage === 'en'" style="display: none;" class="fa-solid fa-circle-check text-lg text-[#545523]"></i>
                <i x-show="selectedLanguage !== 'en'" class="fa-regular fa-circle text-lg text-[#a1a382]"></i>
            </button>

            {{-- Pilihan 3: Melayu --}}
            <button type="button" @click="selectedLanguage = 'ms'" 
                    class="w-full flex items-center justify-between p-4.5 hover:bg-gray-50 transition-colors">
                <span class="text-sm font-medium text-[#545523]">Melayu</span>
                
                <i x-show="selectedLanguage === 'ms'" style="display: none;" class="fa-solid fa-circle-check text-lg text-[#545523]"></i>
                <i x-show="selectedLanguage !== 'ms'" class="fa-regular fa-circle text-lg text-[#a1a382]"></i>
            </button>

        </div>

        {{-- Tombol Simpan --}}
        <button type="submit" class="w-full bg-[#545523] text-white py-3.5 rounded-2xl text-sm font-bold hover:bg-[#43441c] transition-colors shadow-sm active:scale-[0.98]">
            Simpan Perubahan
        </button>
        
    </form>

</div>
@endsection