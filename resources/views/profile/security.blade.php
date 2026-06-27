@extends('layouts.profile')

@section('profile_content')
{{-- x-data untuk kontrol view: 'menu' (tampilan utama) atau 'change_password' (form ganti password) --}}
{{-- Diubah menjadi max-w-xl agar di desktop tidak terlalu melar ke samping --}}
<div x-data="{ view: 'menu', showOld: false, showNew: false, showConfirm: false }" class="w-full max-w-xl mx-auto">
    
    <!-- ==================== 1. TAMPILAN UTAMA PRIVASI & KEAMANAN ==================== -->
    <div x-show="view === 'menu'" x-transition:enter="transition ease-out duration-200" class="space-y-4">
        
        <!-- Header Sub-Menu (Tombol back internal dihapus karena sudah ada back di paling atas layout) -->
        <div class="py-1">
            <h3 class="text-xl font-bold text-[#545523]">Privasi & Keamanan</h3>
        </div>

        @if(session('success'))
            <div class="p-3 bg-green-100 text-green-800 text-xs rounded-xl border border-green-200 shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Card Putih Utama (Lebih presisi & compact) -->
        <div class="bg-white rounded-2xl p-5 md:p-6 shadow-xs border border-gray-100 space-y-6">
            
            <!-- SEKSI: Keamanan Akun -->
            <div>
                <div class="flex items-center gap-2 text-[#8C9163] font-semibold text-xs tracking-wider uppercase mb-3">
                    <i class="fa-solid fa-shield text-xs"></i>
                    <span>Keamanan Akun</span>
                </div>
                
                <!-- Tombol Ubah Password -->
                <button type="button" @click="view = 'change_password'" class="w-full flex items-center justify-between py-1 text-left group cursor-pointer focus:outline-none">
                    <div class="pr-4">
                        <h4 class="font-bold text-gray-800 text-sm md:text-base group-hover:text-[#545523] transition">Ubah Password</h4>
                        <p class="text-xs text-gray-400 mt-0.5 leading-relaxed">Perbarui kata sandi akun Anda secara berkala.</p>
                    </div>
                    <i class="fa-solid fa-chevron-right text-gray-400 text-xs transition group-hover:translate-x-1"></i>
                </button>
            </div>

            <!-- Divider halus -->
            <div class="h-px bg-gray-100 w-full"></div>

            <!-- SEKSI: Status Izin -->
            <div class="space-y-5">
                <div class="flex items-center gap-2 text-[#8C9163] font-semibold text-xs tracking-wider uppercase mb-1">
                    <i class="fa-solid fa-circle-check text-xs"></i>
                    <span>Status Izin</span>
                </div>

                <!-- Layanan Lokasi -->
                <div class="flex items-center justify-between gap-4">
                    <div class="max-w-[80%]">
                        <h4 class="font-bold text-gray-800 text-sm md:text-base">Layanan Lokasi</h4>
                        <p class="text-xs text-gray-400 mt-0.5 leading-relaxed">Izinkan Saveat untuk menemukan peluang penyelamatan makanan di dekat Anda</p>
                    </div>
                    <!-- Toggle Switch -->
                    <label class="relative inline-flex items-center cursor-pointer select-none shrink-0">
                        <input type="checkbox" name="lokasi_permit" class="sr-only peer" checked>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#1E6237]"></div>
                    </label>
                </div>

                <!-- Notifikasi -->
                <div class="flex items-center justify-between gap-4">
                    <div class="max-w-[80%]">
                        <h4 class="font-bold text-gray-800 text-sm md:text-base">Notifikasi</h4>
                        <p class="text-xs text-gray-400 mt-0.5 leading-relaxed">Pemberitahuan untuk penawaran "Hampir Habis" dan pengingat pengambilan Makanan</p>
                    </div>
                    <!-- Toggle Switch -->
                    <label class="relative inline-flex items-center cursor-pointer select-none shrink-0">
                        <input type="checkbox" name="notif_permit" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#1E6237]"></div>
                    </label>
                </div>
            </div>

        </div>

        <!-- Box Hijau Edukasi Data Aman (Sejajar & tidak terlalu lebar) -->
        <div class="bg-[#E4F4EA] text-[#1E6237] rounded-2xl p-5 border border-[#C6E6D2] flex gap-3 items-start shadow-2xs">
            <i class="fa-solid fa-lock text-base mt-0.5 shrink-0"></i>
            <div class="space-y-1 text-xs md:text-sm leading-relaxed">
                <h5 class="font-bold text-gray-900">Data Anda aman bersama Kami</h5>
                <p class="opacity-90">Data Anda aman bersama kami Saveat menggunakan enkripsi AES-256 standar industri untuk melindungi informasi pribadi Anda. Kami tidak pernah menjual data Anda kepada pihak ketiga.</p>
                <p class="font-medium opacity-95">Misi kami adalah menyelamatkan makanan, bukan mengorbankan privasi Anda.</p>
            </div>
        </div>

    </div>

    <!-- ==================== 2. FORM DINAMIS UBAH PASSWORD ==================== -->
    <div x-show="view === 'change_password'" x-transition:enter="transition ease-out duration-200" class="space-y-4" style="display: none;">
        
        <!-- Header Form (Hanya ada tombol back jika sedang di dalam sub-form ubah password) -->
        <div class="flex items-center gap-3 py-1">
            <button type="button" @click="view = 'menu'" class="text-[#545523] hover:opacity-75 transition cursor-pointer focus:outline-none">
                <i class="fa-solid fa-arrow-left text-lg"></i>
            </button>
            <h3 class="text-xl font-bold text-[#545523]">Ubah Kata Sandi</h3>
        </div>

        <form action="{{ route('keamanan.password.update') }}" method="POST" class="bg-white rounded-2xl p-5 md:p-6 shadow-xs border border-gray-100 space-y-4">
            @csrf
            @method('PUT')

            <!-- Password Lama -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Kata Sandi Saat Ini</label>
                <div class="relative">
                    <input :type="showOld ? 'text' : 'password'" name="current_password" required
                           class="w-full bg-gray-50/50 border border-gray-200 rounded-xl py-3 pl-4 pr-10 text-xs md:text-sm focus:outline-none focus:border-[#545523] focus:bg-white transition">
                    <button type="button" @click="showOld = !showOld" class="absolute right-3 top-3.5 text-gray-400 hover:text-gray-600 focus:outline-none">
                        <i class="fa-solid text-xs md:text-sm" :class="showOld ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
            </div>

            <!-- Password Baru -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Kata Sandi Baru</label>
                <div class="relative">
                    <input :type="showNew ? 'text' : 'password'" name="password" required
                           class="w-full bg-gray-50/50 border border-gray-200 rounded-xl py-3 pl-4 pr-10 text-xs md:text-sm focus:outline-none focus:border-[#545523] focus:bg-white transition">
                    <button type="button" @click="showNew = !showNew" class="absolute right-3 top-3.5 text-gray-400 hover:text-gray-600 focus:outline-none">
                        <i class="fa-solid text-xs md:text-sm" :class="showNew ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
            </div>

            <!-- Konfirmasi Password -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Konfirmasi Kata Sandi</label>
                <div class="relative">
                    <input :type="showConfirm ? 'text' : 'password'" name="password_confirmation" required
                           class="w-full bg-gray-50/50 border border-gray-200 rounded-xl py-3 pl-4 pr-10 text-xs md:text-sm focus:outline-none focus:border-[#545523] focus:bg-white transition">
                    <button type="button" @click="showConfirm = !showConfirm" class="absolute right-3 top-3.5 text-gray-400 hover:text-gray-600 focus:outline-none">
                        <i class="fa-solid text-xs md:text-sm" :class="showConfirm ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="pt-2 flex flex-col-reverse sm:flex-row gap-3">
                <button type="button" @click="view = 'menu'" class="w-full sm:w-1/2 border border-gray-200 text-gray-500 font-medium py-3 rounded-xl text-xs md:text-sm hover:bg-gray-50 transition cursor-pointer text-center">
                    Batal
                </button>
                <button type="submit" class="w-full sm:w-1/2 bg-[#545523] hover:bg-[#41421a] text-white font-semibold py-3 rounded-xl text-xs md:text-sm transition cursor-pointer shadow-sm text-center">
                    Simpan Password
                </button>
            </div>
        </form>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggleLokasi = document.querySelector('input[name="lokasi_permit"]');
    
    if (toggleLokasi) {
        toggleLokasi.addEventListener('change', function () {
            if (this.checked) {
                // Minta izin akses lokasi perangkat (GPS)
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            sendLocationToServer(true, position.coords.latitude, position.coords.longitude);
                        },
                        (error) => {
                            alert('Gagal mengakses lokasi Anda. Mohon aktifkan izin GPS di browser.');
                            this.checked = false;
                        }
                    );
                } else {
                    alert('Browser Anda tidak mendukung layanan lokasi.');
                    this.checked = false;
                }
            } else {
                // Nonaktifkan lokasi
                sendLocationToServer(false, null, null);
            }
        });
    }

    function sendLocationToServer(isChecked, lat, lng) {
        fetch("{{ url('/api/profile/update-lokasi') }}", { // Sesuaikan route API atau web kamu
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json"
            },
            body: JSON.stringify({
                izin_lokasi: isChecked,
                latitude: lat,
                longitude: lng
            })
        })
        .then(res => res.json())
        .then(data => {
            console.log(data.message);
        })
        .catch(err => console.error('Error update lokasi:', err));
    }
});
</script>
@endsection