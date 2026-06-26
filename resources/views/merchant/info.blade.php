@extends('layout.profile')

@section('title', 'Informasi Merchant - SaveEat')
@section('page_title', 'Informasi Merchant')

{{-- Ikon Gear di sudut kanan atas --}}
@section('navbar_action')
    <a href="{{ route('merchant.profile.settings') ?? '#' }}" class="text-[#545523] hover:opacity-75 transition text-lg">
        <i class="fa-solid fa-gear"></i>
    </a>
@endsection

@section('content')
{{-- Bungkus utama dengan Alpine.js state untuk modal edit --}}
<div class="max-w-md mx-auto pb-8" x-data="{ isEditModalOpen: false }">
    
    {{-- 1. FOTO BANNER & NAMA TOKO --}}
    <div class="bg-white rounded-3xl border border-gray-100 shadow-xs p-4 mb-5">
        {{-- Foto Banner Toko --}}
        <div class="w-full h-40 rounded-2xl overflow-hidden bg-gray-100 mb-4">
            <img src="{{ Auth::user()->foto_banner ?? 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?q=80&w=600&auto=format&fit=crop' }}" 
                 alt="Banner Toko" class="w-full h-full object-cover">
        </div>

        {{-- Info Singkat & Tombol Aksi --}}
        <div>
            <h2 class="text-lg font-extrabold text-gray-800 leading-tight">
                {{ Auth::user()->nama_usaha ?? 'Dapur Roti Nusantara' }}
            </h2>
            <p class="text-xs text-gray-500 font-medium mb-4">
                {{ Auth::user()->kategori_usaha ?? 'Toko Roti & Kue' }}
            </p>

            <div class="flex items-center gap-3">
                {{-- Tombol Buka Modal Edit --}}
                <button @click="isEditModalOpen = true" 
                        class="bg-[#545523] text-white px-5 py-2.5 rounded-xl text-xs font-bold hover:bg-[#43441c] transition flex items-center gap-2 flex-1 justify-center shadow-sm">
                    <i class="fa-solid fa-pencil"></i> Edit Profile
                </button>
                
                {{-- Tombol Share --}}
                <button class="bg-gray-100 text-gray-600 w-10 h-10 rounded-xl hover:bg-gray-200 transition flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-share-nodes text-sm"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- 2. KARTU DESKRIPSI --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-xs p-5 mb-4 relative overflow-hidden">
        <i class="fa-solid fa-quote-left text-gray-100 text-4xl absolute -top-1 -left-1"></i>
        <p class="text-xs text-gray-600 leading-relaxed relative z-10 font-medium italic">
            "{{ Auth::user()->deskripsi ?? 'Nikmati berbagai roti dan pastry favorit dengan harga spesial! Kami menghadirkan produk yang masih segar, lezat, dan layak konsumsi agar lebih banyak makanan terselamatkan dari pemborosan. Belanja hemat, nikmati roti berkualitas, dan ikut berkontribusi menjaga lingkungan.' }}"
        </p>
    </div>

    {{-- 3. KARTU INFORMASI KONTAK --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-xs p-5 mb-4">
        <h4 class="text-xs font-bold text-gray-800 mb-4">Informasi Kontak</h4>
        <div class="space-y-3.5">
            <div class="flex items-start gap-3">
                <i class="fa-solid fa-phone text-[#545523] mt-0.5 w-4 text-center text-sm"></i>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase">No. Telpon</p>
                    <p class="text-xs font-semibold text-gray-700 mt-0.5">{{ Auth::user()->no_telphone ?? '+62-8123-4567-842' }}</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <i class="fa-solid fa-envelope text-[#545523] mt-0.5 w-4 text-center text-sm"></i>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase">Alamat Email</p>
                    <p class="text-xs font-semibold text-gray-700 mt-0.5">{{ Auth::user()->email ?? 'dapuroti.n@gmail.com' }}</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <i class="fa-solid fa-globe text-[#545523] mt-0.5 w-4 text-center text-sm"></i>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase">Website</p>
                    <p class="text-xs font-semibold text-gray-700 mt-0.5">{{ Auth::user()->website ?? 'www.dapurotinusantara.com' }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- 4. KARTU JAM OPERASIONAL --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-xs p-5 mb-4">
        <h4 class="text-xs font-bold text-gray-800 mb-4">Jam Operasional</h4>
        <div class="space-y-2 mb-4 text-xs font-medium text-gray-600">
            <div class="flex justify-between">
                <span>Senin - Jum'at</span>
                <span class="font-semibold text-gray-800">07.00 - 20.00</span>
            </div>
            <div class="flex justify-between">
                <span>Sabtu - Minggu</span>
                <span class="font-semibold text-gray-800">07.00 - 21.00</span>
            </div>
        </div>
        <div class="flex items-center gap-1.5 text-emerald-600 text-[11px] font-bold">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
            Open Now
        </div>
    </div>

    {{-- 5. KARTU LOKASI MERCHANT --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-xs p-5 mb-4">
        <h4 class="text-xs font-bold text-gray-800 mb-4">Lokasi Merchant</h4>
        
        {{-- Map Placeholder --}}
        <div class="w-full h-28 rounded-xl overflow-hidden bg-gray-100 relative flex items-center justify-center mb-4">
            <div class="absolute inset-0 opacity-30 bg-[radial-gradient(#9ca3af_1px,transparent_1px)] [background-size:12px_12px]"></div>
            <div class="w-10 h-10 bg-white/90 rounded-full shadow-sm border border-gray-200 flex items-center justify-center relative z-10">
                <i class="fa-solid fa-location-dot text-[#545523] text-lg"></i>
            </div>
        </div>

        <p class="text-xs text-gray-600 font-medium leading-relaxed mb-4">
            {{ Auth::user()->alamat ?? 'Jl. Ahmad Yani No. 45, Sumedang Utara, Sumedang Jawa Barat' }}
        </p>

        <a href="{{ Auth::user()->link_map ?? '#' }}" target="_blank" class="w-full border border-emerald-600 text-emerald-600 py-2.5 rounded-xl text-xs font-bold flex items-center justify-center gap-2 hover:bg-emerald-50 transition active:scale-[0.98]">
            <i class="fa-solid fa-diamond-turn-right"></i> Dapatkan Petunjuk Arah
        </a>
    </div>

    {{-- =======================================================
         MODAL EDIT PROFIL MERCHANT (Dikendalikan Alpine.js)
         ======================================================= --}}
    <div x-show="isEditModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        {{-- Background Overlay --}}
        <div x-show="isEditModalOpen" 
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black/50 transition-opacity" @click="isEditModalOpen = false"></div>

        {{-- Panel Modal --}}
        <div class="flex min-h-screen items-end justify-center text-center sm:items-center sm:p-0">
            <div x-show="isEditModalOpen"
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative transform overflow-hidden bg-white text-left shadow-xl transition-all w-full sm:max-w-lg rounded-t-3xl sm:rounded-3xl mt-20 sm:my-8 h-[85vh] flex flex-col">
                
                {{-- Header Modal --}}
                <div class="bg-white px-6 py-4 border-b border-gray-100 flex items-center justify-between shrink-0 rounded-t-3xl">
                    <h3 class="text-base font-bold text-gray-800">Edit Informasi Merchant</h3>
                    <button @click="isEditModalOpen = false" class="text-gray-400 hover:text-gray-600 bg-gray-50 hover:bg-gray-100 w-8 h-8 rounded-full flex items-center justify-center transition">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                {{-- Body Modal (Scrollable Form) --}}
                <div class="px-6 py-5 overflow-y-auto flex-1">
                    <form action="{{ route('merchant.profile.update') ?? '#' }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        @method('PUT')

                        {{-- Input Foto Banner --}}
                        <div>
                            <label class="text-xs font-bold text-gray-500 mb-1.5 block">Foto Banner Toko</label>
                            <input type="file" name="foto_banner" class="w-full text-xs border border-gray-200 rounded-xl p-2 file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#545523] file:text-white hover:file:bg-[#43441c] transition">
                        </div>

                        {{-- Input Nama Usaha --}}
                        <div>
                            <label class="text-xs font-bold text-gray-500 mb-1.5 block">Nama Usaha</label>
                            <input type="text" name="nama_usaha" value="{{ Auth::user()->nama_usaha ?? '' }}" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:border-[#545523] focus:ring-1 focus:ring-[#545523]/20 outline-none transition" required>
                        </div>

                        {{-- Input Kategori --}}
                        <div>
                            <label class="text-xs font-bold text-gray-500 mb-1.5 block">Kategori Usaha</label>
                            <input type="text" name="kategori_usaha" value="{{ Auth::user()->kategori_usaha ?? '' }}" placeholder="Contoh: Toko Roti & Kue" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:border-[#545523] focus:ring-1 focus:ring-[#545523]/20 outline-none transition" required>
                        </div>

                        {{-- Input Deskripsi --}}
                        <div>
                            <label class="text-xs font-bold text-gray-500 mb-1.5 block">Deskripsi Usaha</label>
                            <textarea name="deskripsi" rows="3" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:border-[#545523] focus:ring-1 focus:ring-[#545523]/20 outline-none transition">{{ Auth::user()->deskripsi ?? '' }}</textarea>
                        </div>

                        {{-- Input No Telepon --}}
                        <div>
                            <label class="text-xs font-bold text-gray-500 mb-1.5 block">No. Telepon / WA</label>
                            <input type="text" name="no_telphone" value="{{ Auth::user()->no_telphone ?? '' }}" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:border-[#545523] focus:ring-1 focus:ring-[#545523]/20 outline-none transition" required>
                        </div>

                        {{-- Input Link Gmaps --}}
                        <div>
                            <label class="text-xs font-bold text-gray-500 mb-1.5 block">Link Google Maps</label>
                            <input type="url" name="link_map" value="{{ Auth::user()->link_map ?? '' }}" placeholder="https://maps.app.goo.gl/..." class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:border-[#545523] focus:ring-1 focus:ring-[#545523]/20 outline-none transition">
                        </div>

                        {{-- Input Alamat --}}
                        <div>
                            <label class="text-xs font-bold text-gray-500 mb-1.5 block">Alamat Lengkap</label>
                            <textarea name="alamat" rows="2" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:border-[#545523] focus:ring-1 focus:ring-[#545523]/20 outline-none transition" required>{{ Auth::user()->alamat ?? '' }}</textarea>
                        </div>
                    </form>
                </div>

                {{-- Footer Modal (Tombol Simpan) --}}
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 shrink-0 flex justify-end">
                    <button type="button" @click="isEditModalOpen = false" class="mr-3 px-4 py-2 text-xs font-bold text-gray-500 bg-white border border-gray-200 rounded-xl hover:bg-gray-100 transition">
                        Batal
                    </button>
                    <button type="button" onclick="document.querySelector('form').submit()" class="px-5 py-2 text-xs font-bold text-white bg-[#545523] rounded-xl hover:bg-[#43441c] transition shadow-sm">
                        Simpan Perubahan
                    </button>
                </div>

            </div>
        </div>
    </div>

</div>
@endsection