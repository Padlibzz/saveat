@extends('layouts.dashboard')

@section('page_title', 'Tambah Listing Baru')

@section('content')

    {{-- TOMBOL KEMBALI --}}
    <div class="flex items-center justify-between mb-2">
        <a href="{{ route('merchant.dashboard') }}" class="inline-flex items-center text-xs font-bold text-[#545523] hover:underline gap-2">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>

    {{-- FORM CONTAINER DENGAN ALPINE.JS --}}
    <div x-data="uploadForm()" class="bg-[#FDFDF5] p-5 md:p-8 rounded-3xl border border-[#545523]/10 shadow-sm space-y-6 max-w-5xl mx-auto">
        
        {{-- HEADER FORM --}}
        <div class="flex items-center gap-4 border-b border-gray-100 pb-4">
            <div class="bg-[#545523] text-[#F1F2CF] w-12 h-12 rounded-2xl flex items-center justify-center shadow-md shadow-[#545523]/20 shrink-0">
                <i class="fa-solid fa-square-plus text-xl"></i>
            </div>
            <div>
                <h1 class="text-base md:text-lg font-bold text-[#545523]">Tambah Listing Baru</h1>
                <p class="text-[11px] md:text-xs text-gray-400 font-medium">Kurangi limbah, tambah berkah.</p>
            </div>
        </div>

        {{-- STATUS ALERTS BANNER --}}
        <div x-show="message" 
             :class="status === 'success' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-red-50 text-red-700 border-red-200'"
             class="p-4 rounded-xl text-xs font-medium border transition-all" x-cloak>
            <div class="flex items-center gap-2">
                <i :class="status === 'success' ? 'fa-solid fa-circle-check text-emerald-600' : 'fa-solid fa-circle-exclamation text-red-600'"></i>
                <span x-text="message"></span>
            </div>
        </div>

        {{-- FORM UTAMA --}}
        <form @submit.prevent="submitData" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
                
                {{-- KOLOM KIRI: DATA ATRIBUT PRODUK --}}
                <div class="space-y-4">
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-[#545523]">Nama Makanan</label>
                        <input type="text" x-model="formData.nama" placeholder="Contoh: Paket Nasi Ayam Bakar"
                            class="w-full border border-gray-200 bg-gray-50/50 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#545523] focus:bg-white transition-all">
                        <span class="text-[11px] text-red-500 font-medium flex items-center gap-1" x-show="errors.nama" x-cloak>
                            <i class="fa-solid fa-circle-xmark"></i> <span x-text="errors.nama"></span>
                        </span>
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-[#545523]">Kategori</label>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="kat in daftarKategori">
                                <button type="button" @click="formData.kategori_id = kat.id" 
                                    :class="formData.kategori_id === kat.id ? 'bg-[#545523] text-white border-[#545523] shadow-xs' : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50'" 
                                    class="text-xs font-medium px-4 py-2 border rounded-full transition-all cursor-pointer"
                                    x-text="kat.nama">
                                </button>
                            </template>
                        </div>
                        <span class="text-[11px] text-red-500 font-medium flex items-center gap-1" x-show="errors.kategori_id" x-cloak>
                            <i class="fa-solid fa-circle-xmark"></i> <span x-text="errors.kategori_id"></span>
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-bold text-[#545523]">Harga Normal</label>
                            <div class="relative flex items-center">
                                <span class="absolute left-4 text-xs font-bold text-gray-400">Rp</span>
                                <input type="number" x-model="formData.harga_normal" placeholder="0"
                                    class="w-full border border-gray-200 bg-gray-50/50 rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none focus:border-[#545523] focus:bg-white transition-all">
                            </div>
                            <span class="text-[11px] text-red-500 font-medium flex items-center gap-1" x-show="errors.harga_normal" x-cloak>
                                <i class="fa-solid fa-circle-xmark"></i> <span x-text="errors.harga_normal"></span>
                            </span>
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-bold text-[#545523]">Harga Saveat (Promo)</label>
                            <div class="relative flex items-center">
                                <span class="absolute left-4 text-xs font-bold text-[#2bb16a]">Rp</span>
                                <input type="number" x-model="formData.harga_diskon" placeholder="0"
                                    class="w-full border-2 border-[#a1ebd0] bg-emerald-50/10 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:border-[#2bb16a] transition-all font-semibold text-gray-700">
                            </div>
                            <span class="text-[11px] text-red-500 font-medium flex items-center gap-1" x-show="errors.harga_diskon" x-cloak>
                                <i class="fa-solid fa-circle-xmark"></i> <span x-text="errors.harga_diskon"></span>
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-bold text-[#545523]">Stok Tersedia (Stok Total)</label>
                            <input type="number" name="stok_total" x-model="formData.stok_total" placeholder="5"
                                class="w-full border border-gray-200 bg-gray-50/50 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#545523] focus:bg-white transition-all">
                            <span class="text-[11px] text-red-500 font-medium flex items-center gap-1" x-show="errors.stok_total" x-cloak>
                                <i class="fa-solid fa-circle-xmark"></i> <span x-text="errors.stok_total"></span>
                            </span>
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-bold text-[#545523]">Batas Waktu Ambil</label>
                            <input type="datetime-local" x-model="formData.batas_waktu"
                                class="w-full border border-gray-200 bg-gray-50/50 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-[#545523] focus:bg-white transition-all text-gray-500">
                            <span class="text-[11px] text-red-500 font-medium flex items-center gap-1" x-show="errors.batas_waktu" x-cloak>
                                <i class="fa-solid fa-circle-xmark"></i> <span x-text="errors.batas_waktu"></span>
                            </span>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN: MEDIA & DESKRIPSI --}}
                <div class="space-y-4 h-full flex flex-col justify-between">
                    <div class="flex flex-col gap-1.5 flex-1">
                        <label class="text-xs font-bold text-[#545523]">Upload Foto</label>
                        <div class="relative border-2 border-dashed border-gray-200 bg-gray-50/30 rounded-2xl p-6 text-center hover:bg-gray-50 transition-all cursor-pointer group flex flex-col justify-center items-center min-h-[180px] lg:h-full">
                            <input type="file" @change="handleFileChange" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            
                            <div class="space-y-1" x-show="!imagePreview">
                                <div class="text-gray-400 text-2xl group-hover:text-[#545523] transition-colors">
                                    <i class="fa-solid fa-cloud-arrow-up"></i>
                                </div>
                                <p class="text-xs font-bold text-gray-700">Klik atau seret foto ke sini</p>
                                <p class="text-[10px] text-gray-400">JPG, JPEG, PNG (Maks. 2MB)</p>
                            </div>

                            <div x-show="imagePreview" x-cloak class="flex flex-col items-center gap-3 relative z-20">
                                <img :src="imagePreview" class="max-h-40 sm:max-h-52 object-cover rounded-xl shadow-xs border">
                                <div class="flex gap-2">
                                    <span class="text-[11px] text-gray-600 font-bold bg-white px-3 py-1.5 rounded-lg shadow-xs border">Ubah Gambar</span>
                                    <button type="button" @click.stop="removeFile" class="text-[11px] text-red-600 font-bold bg-red-50 px-3 py-1.5 rounded-lg border border-red-200 hover:bg-red-100">Hapus</button>
                                </div>
                            </div>
                        </div>
                        <span class="text-[11px] text-red-500 font-medium flex items-center gap-1" x-show="errors.foto" x-cloak>
                            <i class="fa-solid fa-circle-xmark"></i> <span x-text="errors.foto"></span>
                        </span>
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-[#545523]">Deskripsi (Kondisi/Isi)</label>
                        <textarea x-model="formData.deskripsi" rows="3" placeholder="Jelaskan isi paket atau kondisi makanan..."
                            class="w-full border border-gray-200 bg-gray-50/50 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#545523] focus:bg-white transition-all resize-none"></textarea>
                    </div>
                </div>

            </div>

            {{-- SUBMIT ACTIONS CONTROLLER --}}
            <div class="flex items-center justify-between pt-6 border-t border-gray-100 mt-6">
                <a href="{{ route('merchant.dashboard') }}" class="text-xs font-bold text-gray-400 hover:text-gray-600 transition-colors">
                    Batal
                </a>
                <button type="submit" :disabled="loading"
                    class="bg-[#545523] text-[#F1F2CF] text-xs font-bold px-6 py-3 rounded-xl shadow-md hover:bg-[#43441c] disabled:bg-gray-400 disabled:cursor-not-allowed transition-all flex items-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-spinner animate-spin" x-show="loading" x-cloak></i>
                    <span x-text="loading ? 'Memproses...' : 'Terbitkan Listing'"></span>
                </button>
            </div>

        </form>
    </div>

@endsection

{{-- INJEKSI SKRIP ALPINE FORM HANDLER --}}
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('uploadForm', () => ({
            daftarKategori: @json($kategoris),
            formData: {
                nama: '',
                kategori_id: '',
                harga_normal: '',
                harga_diskon: '',
                stok_total: '5',
                batas_waktu: '',
                deskripsi: ''
            },
            fileFoto: null,
            imagePreview: null,
            loading: false,
            message: '',
            status: '',
            errors: {},

            handleFileChange(e) {
                const file = e.target.files[0];
                if (file) {
                    if (file.size > 2 * 1024 * 1024) {
                        this.errors.foto = "Ukuran berkas terlalu besar! Maksimal 2MB.";
                        return;
                    }
                    this.errors.foto = null;
                    this.fileFoto = file;
                    this.imagePreview = URL.createObjectURL(file);
                }
            },

            removeFile() {
                this.fileFoto = null;
                this.imagePreview = null;
                if(this.errors.foto) this.errors.foto = null;
            },

            async submitData() {
                this.loading = true;
                this.message = '';
                this.errors = {};

                const payload = new FormData();
                for (const key in this.formData) {
                    payload.append(key, this.formData[key]);
                }
                if (this.fileFoto) {
                    payload.append('foto', this.fileFoto);
                }

                try {
                    const response = await fetch('/api/merchant/listings', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            'Accept': 'application/json'
                        },
                        body: payload
                    });

                    const result = await response.json();

                    if (response.ok && result.status === 'success') {
                        this.status = 'success';
                        this.message = result.message;
                        
                        // Reset Form
                        this.formData = { nama: '', kategori_id: '', harga_normal: '', harga_diskon: '', stok_total: '5', batas_waktu: '', deskripsi: '' };
                        this.removeFile();
                        
                        setTimeout(() => {
                            window.location.href = '{{ route('merchant.produk-aktif') }}';
                        }, 1500);
                    } else {
                        this.status = 'error';
                        if (result.errors) {
                            Object.keys(result.errors).forEach(key => {
                                this.errors[key] = result.errors[key][0];
                            });
                            this.message = 'Harap periksa kembali isian form Anda.';
                        } else {
                            this.message = result.message || 'Gagal menerbitkan listing.';
                        }
                    }
                } catch (error) {
                    this.status = 'error';
                    this.message = 'Terjadi gangguan jaringan pada server.';
                } finally {
                    this.loading = false;
                }
            }
        }));
    });
</script>
