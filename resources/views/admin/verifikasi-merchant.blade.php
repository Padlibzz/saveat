@section('page_title', 'Verifikasi Merchant')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Verifikasi Merchant - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
    </style>
</head>
<body
    x-data="{ sidebarOpen: false }"
    class="bg-gradient-to-r from-[#CFD086] to-[#F1F2CF] min-h-screen font-[Poppins] flex">

    <x-sidebar />

    <div class="flex-1 flex flex-col min-h-screen">

        <x-navbar />

        <section class="p-6 mb-10 lg:ml-64 flex-1">
            
            @if(session('success'))
                <x-alert type="success" :message="session('success')" />
            @endif

            @if(session('error'))
                <x-alert type="error" :message="session('error')" />
            @endif                
            
            {{-- HEADER HALAMAN --}}
            <div class="flex items-center gap-3 mb-6">
                <a href="{{ route('admin.dashboard') }}" class="text-[#545523] hover:text-gray-700 transition">
                    <i class="fa-solid fa-arrow-left text-xl"></i>
                </a>
                <h2 class="text-2xl font-bold text-[#545523]">Verifikasi Merchant</h2>
            </div>

            {{-- 3 KARTU STATISTIK ATAS --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-[#545523] text-[#F1F2CF] rounded-2xl p-6 shadow-md flex flex-col justify-between relative overflow-hidden">
                    <div>
                        <p class="text-sm font-medium opacity-80">Verifikasi Ditunda</p>
                        <h3 class="text-4xl font-extrabold mt-2">{{ $totalDitunda }}</h3>
                    </div>
                    <div class="text-xs mt-4 flex items-center gap-1 opacity-90">
                        <i class="fa-solid fa-clock"></i>
                        <span>Butuh respon segera</span>
                    </div>
                    <i class="fa-solid fa-clock text-white/10 text-7xl absolute -right-4 -bottom-2 pointer-events-none"></i>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Disetujui Minggu Ini</p>
                        <h3 class="text-4xl font-extrabold text-gray-800 mt-2">{{ $totalDisetujuiMingguIni }}</h3>
                    </div>
                    <div class="mt-4">
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="bg-[#545523] h-2 rounded-full" style="width: 100%"></div>
                        </div>
                        <p class="text-right text-xs font-semibold text-gray-400 mt-1">Diperbarui Mingguan</p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Ditolak Minggu Ini</p>
                        <h3 class="text-4xl font-extrabold text-red-600 mt-2">{{ $totalDitolakMingguIni }}</h3>
                    </div>
                    <p class="text-xs text-gray-400 mt-4 leading-relaxed">
                        Riwayat penolakan berdasarkan standarisasi berkas.
                    </p>
                </div>
            </div>

            {{-- BARIS PENCARIAN & RESET --}}
            <div class="flex flex-col sm:flex-row gap-4 justify-between items-center mb-6">
                <form action="{{ route('admin.verifikasi.index') }}" method="GET" class="relative w-full sm:w-96">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama Usaha atau Pemilik..." 
                        class="w-full bg-white pl-4 pr-10 py-2.5 rounded-xl shadow-sm border border-transparent focus:border-[#545523] focus:outline-none text-sm text-gray-700">
                    <button type="submit" class="absolute right-3.5 top-3.5 text-gray-400 hover:text-[#545523] cursor-pointer">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>
                <a href="{{ route('admin.verifikasi.index') }}" class="bg-white text-gray-700 p-2.5 px-4 rounded-xl shadow-sm hover:bg-gray-50 border border-gray-100 active:scale-95 transition flex items-center justify-center text-sm font-bold gap-2">
                    <i class="fa-solid fa-rotate-left"></i> Reset
                </a>
            </div>

            {{-- GRID DAFTAR MERCHANT --}}
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
                
                @forelse($merchants as $m)
                    <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition flex flex-col justify-between border border-gray-100">
                        
                        {{-- GAMBAR TOKO & BADGE STATUS --}}
                        <div class="relative h-48 w-full bg-gray-100 shrink-0">
                            {{-- Menggunakan kolom yang benar, bisa jadi 'foto' atau disesuaikan --}}
                            <img src="{{ $m->foto ? asset('storage/'.$m->foto) : 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?q=80&w=600' }}" alt="Merchant Image" class="w-full h-full object-cover">
                            
                            @if($m->status_verifikasi === 'menunggu')
                                <span class="absolute top-3 right-3 bg-amber-500 text-white text-[10px] font-bold px-3 py-1 rounded-full shadow-sm uppercase tracking-wider animate-pulse">Menunggu</span>
                            @elseif($m->status_verifikasi === 'disetujui')
                                <span class="absolute top-3 right-3 bg-emerald-500 text-white text-[10px] font-bold px-3 py-1 rounded-full shadow-sm uppercase tracking-wider">Disetujui</span>
                            @else
                                <span class="absolute top-3 right-3 bg-red-500 text-white text-[10px] font-bold px-3 py-1 rounded-full shadow-sm uppercase tracking-wider">Ditolak</span>
                            @endif
                        </div>
                        
                        {{-- KONTEN & DETAIL MERCHANT --}}
                        <div class="p-5 flex-1 flex flex-col justify-between">
                            <div>
                                <div class="flex justify-between items-start mb-1">
                                    <h4 class="font-bold text-gray-800 text-lg leading-tight">
                                        {{ $m->nama_usaha ?? $m->user->name ?? 'Usaha Tidak Bernama' }}
                                    </h4>
                                    @if($m->status_verifikasi === 'disetujui')
                                        <i class="fa-solid fa-circle-check text-emerald-500 mt-1" title="Terverifikasi"></i>
                                    @endif
                                </div>
                                <p class="text-xs font-bold text-[#545523] mb-4 uppercase tracking-wider">{{ $m->user->name ?? 'Pemilik Tidak Diketahui' }}</p>
                                
                                <div class="space-y-2 text-xs text-gray-600 mb-6">
                                    <div class="flex items-center gap-2">
                                        <i class="fa-regular fa-calendar w-4 text-gray-400"></i>
                                        <span>Mendaftar: {{ \Carbon\Carbon::parse($m->created_at)->translatedFormat('d M Y') }}</span>
                                    </div>
                                    <div class="flex items-start gap-2">
                                        <i class="fa-solid fa-location-dot w-4 text-gray-400 mt-0.5"></i>
                                        <span class="leading-relaxed">{{ $m->alamat ?? 'Alamat Belum Diisi' }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- AKSI BUTTONS --}}
                            <div class="space-y-2">
                                @if($m->status_verifikasi === 'menunggu')
                                    <div class="grid grid-cols-2 gap-2">
                                        <form action="{{ route('admin.merchant.setujui', $m->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white py-2.5 rounded-xl text-xs font-bold transition cursor-pointer shadow-sm">
                                                <i class="fa-solid fa-check mr-1"></i> Setujui
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.merchant.tolak', $m->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white py-2.5 rounded-xl text-xs font-bold transition cursor-pointer shadow-sm">
                                                <i class="fa-solid fa-xmark mr-1"></i> Tolak
                                            </button>
                                        </form>
                                    </div>
                                @endif
                                
                                {{-- Jika ada rute untuk melihat dokumen pendaftaran --}}
                                <a href="#" class="w-full bg-gray-50 text-gray-600 py-2.5 rounded-xl text-xs font-bold border border-gray-100 text-center hover:bg-gray-100 transition flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-file-lines text-[10px]"></i>
                                    <span>Lihat Dokumen</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white/80 rounded-2xl p-12 text-center shadow-sm border border-gray-50">
                        <i class="fa-solid fa-folder-open text-5xl text-gray-300 mb-4"></i>
                        <h3 class="text-sm font-bold text-gray-600">Pencarian Tidak Ditemukan</h3>
                        <p class="text-xs text-gray-400 mt-1">Tidak ada data pengajuan merchant yang sesuai dengan kriteria.</p>
                    </div>
                @endforelse

            </div>

            {{-- PAGINATION BAWAAN LARAVEL (Jika menggunakan Tailwind, pastikan tailwind pagination dipublish) --}}
            <div class="mt-6">
                {{ $merchants->appends(request()->query())->links() }}
            </div>

        </section>

        <x-footer/>
    </div>

</body>
</html>