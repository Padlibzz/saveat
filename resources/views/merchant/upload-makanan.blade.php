@section('page_title', 'Tambah Listing Baru')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Tambah Listing Baru - Saveat</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
    </style>
</head>
<body
    x-data="{ sidebarOpen: false }"
    class="bg-gradient-to-r from-[#CFD086] to-[#F1F2CF] min-h-screen font-[Poppins]">

    <div class="flex min-h-screen">
        <x-sidebar />

        <div class="flex-1 flex flex-col justify-between min-h-screen">
            <div>
                <x-navbar />

                <section class="p-4 md:p-6 lg:ml-64 max-w-6xl w-full mx-auto">
                    
                    <div x-data="uploadForm()" class="bg-[#FDFDF5] p-5 md:p-8 rounded-3xl border border-[#545523]/10 shadow-sm space-y-6">
                        
                        <div class="flex items-center gap-4 border-b border-gray-100 pb-4">
                            <div class="bg-[#545523] text-[#F1F2CF] w-12 h-12 rounded-2xl flex items-center justify-center shadow-md shadow-[#545523]/20 shrink-0">
                                <i class="fa-solid fa-square-plus text-xl"></i>
                            </div>
                            <div>
                                <h1 class="text-base md:text-lg font-bold text-[#545523]">Tambah Listing Baru</h1>
                                <p class="text-[11px] md:text-xs text-gray-400 font-medium">Kurangi limbah, tambah berkah.</p>
                            </div>
                        </div>

                        <div x-show="message" 
                             :class="status === 'success' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-red-50 text-red-700 border-red-200'"
                             class="p-4 rounded-xl text-xs font-medium border" x-cloak>
                            <span x-text="message"></span>
                        </div>

                        <form @submit.prevent="submitData" enctype="multipart/form-data">
                            @csrf

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
                                
                                <div class="space-y-4">
                                    <div class="flex flex-col gap-1.5">
                                        <label class="text-xs font-bold text-[#545523]">Nama Makanan</label>
                                        <input type="text" x-model="formData.nama" placeholder="Contoh: Paket Nasi Ayam Bakar"
                                            class="w-full border border-gray-200 bg-gray-50/50 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#545523] focus:bg-white transition-all">
                                        <span class="text-[11px] text-red-500 font-medium" x-text="errors.nama" x-show="errors.nama"></span>
                                    </div>

                                    <div class="flex flex-col gap-1.5">
                                        <label class="text-xs font-bold text-[#545523]">Kategori</label>
                                        <div class="flex flex-wrap gap-2">
                                            <button type="button" @click="formData.kategori_id = 1" :class="formData.kategori_id === 1 ? 'bg-[#545523] text-white border-[#545523]' : 'bg-white text-gray-600 border-gray-200'" class="text-xs font-medium px-4 py-2 border rounded-full transition-all cursor-pointer">Nasi</button>
                                            <button type="button" @click="formData.kategori_id = 2" :class="formData.kategori_id === 2 ? 'bg-[#545523] text-white border-[#545523]' : 'bg-white text-gray-600 border-gray-200'" class="text-xs font-medium px-4 py-2 border rounded-full transition-all cursor-pointer">Roti & Kue</button>
                                            <button type="button" @click="formData.kategori_id = 3" :class="formData.kategori_id === 3 ? 'bg-[#545523] text-white border-[#545523]' : 'bg-white text-gray-600 border-gray-200'" class="text-xs font-medium px-4 py-2 border rounded-full transition-all cursor-pointer">Sayur/Buah</button>
                                            <button type="button" @click="formData.kategori_id = 4" :class="formData.kategori_id === 4 ? 'bg-[#545523] text-white border-[#545523]' : 'bg-white text-gray-600 border-gray-200'" class="text-xs font-medium px-4 py-2 border rounded-full transition-all cursor-pointer">Minuman</button>
                                        </div>
                                        <span class="text-[11px] text-red-500 font-medium" x-text="errors.kategori_id" x-show="errors.kategori_id"></span>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="flex flex-col gap-1.5">
                                            <label class="text-xs font-bold text-[#545523]">Harga Normal</label>
                                            <div class="relative flex items-center">
                                                <span class="absolute left-4 text-xs font-bold text-gray-400">Rp</span>
                                                <input type="number" x-model="formData.harga_normal" placeholder="0"
                                                    class="w-full border border-gray-200 bg-gray-50/50 rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none focus:border-[#545523] focus:bg-white transition-all">
                                            </div>
                                            <span class="text-[11px] text-red-500 font-medium" x-text="errors.harga_normal" x-show="errors.harga_normal"></span>
                                        </div>

                                        <div class="flex flex-col gap-1.5">
                                            <label class="text-xs font-bold text-[#545523]">Harga Saveat (Promo)</label>
                                            <div class="relative flex items-center">
                                                <span class="absolute left-4 text-xs font-bold text-[#2bb16a]">Rp</span>
                                                <input type="number" x-model="formData.harga_diskon" placeholder="0"
                                                    class="w-full border-2 border-[#a1ebd0] bg-emerald-50/10 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:border-[#2bb16a] transition-all font-semibold text-gray-700">
                                            </div>
                                            <span class="text-[11px] text-red-500 font-medium" x-text="errors.harga_diskon" x-show="errors.harga_diskon"></span>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="flex flex-col gap-1.5">
                                            <label class="text-xs font-bold text-[#545523]">Stok Tersedia</label>
                                            <input type="number" x-model="formData.stok_total" placeholder="5"
                                                class="w-full border border-gray-200 bg-gray-50/50 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#545523] focus:bg-white transition-all">
                                            <span class="text-[11px] text-red-500 font-medium" x-text="errors.stok_total" x-show="errors.stok_total"></span>
                                        </div>

                                        <div class="flex flex-col gap-1.5">
                                            <label class="text-xs font-bold text-[#545523]">Batas Waktu Ambil</label>
                                            <input type="datetime-local" x-model="formData.batas_waktu"
                                                class="w-full border border-gray-200 bg-gray-50/50 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-[#545523] focus:bg-white transition-all text-gray-500">
                                            <span class="text-[11px] text-red-500 font-medium" x-text="errors.batas_waktu" x-show="errors.batas_waktu"></span>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                                                {{-- Pickup Date --}}
                                                <div>
                                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                        Tanggal Pengambilan
                                                    </label>

                                                    <input type="date" x-model="formData.pickup_date" min="{{ date('Y-m-d') }}" class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-[#545523] focus:border-[#545523]">
                                                </div>

                                                {{-- Interval --}}
                                                <div>
                                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                        Interval Pickup
                                                    </label>

                                                    <select x-model="formData.pickup_interval" required class="w-full rounded-xl border border-gray-300 px-4 py-3">
                                                        <option value="15">15 Menit</option>
                                                        <option value="30" selected>30 Menit</option>
                                                        <option value="60">60 Menit</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-2 gap-6 mt-6">
                                                <div>
                                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                        Jam Mulai Pickup
                                                    </label>

                                                    <input type="time" x-model="formData.pickup_start" required class="w-full rounded-xl border border-gray-300 px-4 py-3">
                                                </div>

                                                <div>
                                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                        Jam Selesai Pickup
                                                    </label>

                                                    <input type="time" x-model="formData.pickup_end" required class="w-full rounded-xl border border-gray-300 px-4 py-3">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-4 h-full flex flex-col justify-between">
                                    <div class="flex flex-col gap-1.5 flex-1">
                                        <label class="text-xs font-bold text-[#545523]">Upload Foto</label>
                                        <div class="relative border-2 border-dashed border-gray-200 bg-gray-50/30 rounded-2xl p-6 text-center hover:bg-gray-50 transition-all cursor-pointer group flex flex-col justify-center items-center min-h-[160px] lg:h-full">
                                            <input type="file" @change="handleFileChange" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                            
                                            <div class="space-y-1" x-show="!imagePreview">
                                                <div class="text-gray-400 text-2xl group-hover:text-[#545523] transition-colors">
                                                    <i class="fa-solid fa-cloud-arrow-up"></i>
                                                </div>
                                                <p class="text-xs font-bold text-gray-700">Klik atau seret foto ke sini</p>
                                                <p class="text-[10px] text-gray-400">JPG, PNG (Maks. 2MB)</p>
                                            </div>

                                            <div x-show="imagePreview" x-cloak class="flex flex-col items-center gap-2">
                                                <img :src="imagePreview" class="max-h-36 object-cover rounded-xl shadow-sm border">
                                                <span class="text-[11px] text-gray-500 font-medium bg-white px-2 py-1 rounded-md shadow-xs border">Ubah Gambar</span>
                                            </div>
                                        </div>
                                        <span class="text-[11px] text-red-500 font-medium" x-text="errors.foto" x-show="errors.foto"></span>
                                    </div>

                                    <div class="flex flex-col gap-1.5 mt-2">
                                        <label class="text-xs font-bold text-[#545523]">Deskripsi (Kondisi/Isi)</label>
                                        <textarea x-model="formData.deskripsi" rows="3" placeholder="Jelaskan isi paket atau kondisi makanan..."
                                            class="w-full border border-gray-200 bg-gray-50/50 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#545523] focus:bg-white transition-all resize-none"></textarea>
                                    </div>
                                </div>

                            </div>

                            <div class="flex items-center justify-between pt-6 border-t border-gray-100 mt-6">
                                <a href="/merchant/dashboard" class="text-xs font-bold text-gray-400 hover:text-gray-600 transition-colors">
                                    Batal
                                </a>
                                <button type="submit" :disabled="loading"
                                    class="bg-[#545523] text-[#F1F2CF] text-xs font-bold px-6 py-3 rounded-xl shadow-md hover:bg-[#43441c] disabled:bg-gray-400 transition-all flex items-center gap-2 cursor-pointer">
                                    <i class="fa-solid fa-spinner animate-spin" x-show="loading" x-cloak></i>
                                    <span x-text="loading ? 'Memproses...' : 'Terbitkan Listing'"></span>
                                </button>
                            </div>

                        </form>
                    </div>

                </section>
            </div>

            <x-footer />
        </div>
    </div>

    <script>
        function uploadForm() {
            return {
                formData: {
                    nama: '',
                    kategori_id: '',
                    harga_normal: '',
                    harga_diskon: '',
                    stok_total: '5',
                    batas_waktu: '',
                    deskripsi: '',

                    pickup_date: '',
                    pickup_start: '',
                    pickup_end: '',
                    pickup_interval: '30'
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
                        this.fileFoto = file;
                        this.imagePreview = URL.createObjectURL(file);
                    }
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
                            
                            this.formData = { nama: '', kategori_id: '', harga_normal: '', harga_diskon: '', stok_total: '5', batas_waktu: '', deskripsi: '' };
                            this.fileFoto = null;
                            this.imagePreview = null;
                            
                            setTimeout(() => {
                                window.location.href = '/merchant/produk-aktif';
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
            }
        }
    </script>
</body>
</html>