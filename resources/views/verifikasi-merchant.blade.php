@section('page_title', 'Merchant Verification')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Verifikasi Merchant</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
    </style>
</head>
<body
    x-data="{ sidebarOpen: false }"
    class="bg-gradient-to-r from-[#CFD086] to-[#F1F2CF] min-h-screen font-[Poppins]">

    <div class="flex min-h-screen">
        <x-sidebar />

        <div class="flex-1">
            <x-navbar />

            <section class="p-6 mb-2 lg:ml-64">
            @if(session('success'))
                <x-alert type="success" :message="session('success')" />
            @endif

            @if(session('error'))
                <x-alert type="error" :message="session('error')" />
            @endif                
                <div class="flex items-center gap-3 mb-6">
                    <a href="/admin/dashboard" class="text-[#545523] hover:text-gray-700 transition">
                        <i class="fa-solid fa-arrow-left text-xl"></i>
                    </a>
                    <h2 class="text-2xl font-bold text-[#545523]">Verifikasi Merchant</h2>
                </div>

                @if(session('success'))
                    <div class="mb-4 p-4 bg-emerald-100 text-emerald-800 rounded-xl text-sm font-medium shadow-sm">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-100 text-red-800 rounded-xl text-sm font-medium shadow-sm">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-[#545523] text-[#F1F2CF] rounded-2xl p-6 shadow-md flex flex-col justify-between relative overflow-hidden">
                        <div>
                            <p class="text-sm font-medium opacity-80">Verifikasi ditunda</p>
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
                            <p class="text-sm font-medium text-gray-500">Disetujui Minggu ini</p>
                            <h3 class="text-4xl font-extrabold text-gray-800 mt-2">{{ $totalDisetujuiMingguIni }}</h3>
                        </div>
                        <div class="mt-4">
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="bg-[#545523] h-2 rounded-full" style="width: 100%"></div>
                            </div>
                            <p class="text-right text-xs font-semibold text-gray-400 mt-1">Updated weekly</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Ditolak Minggu ini</p>
                            <h3 class="text-4xl font-extrabold text-red-600 mt-2">{{ $totalDitolakMingguIni }}</h3>
                        </div>
                        <p class="text-xs text-gray-400 mt-4 leading-relaxed">
                            Riwayat penolakan berdasarkan standarisasi berkas.
                        </p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 justify-between items-center mb-6">
                    <form action="{{ route('admin.verifikasi.index') }}" method="GET" class="relative w-full sm:w-96">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Merchant atau Pemilik..." 
                            class="w-full bg-white pl-4 pr-10 py-2.5 rounded-xl shadow-sm border border-transparent focus:border-[#545523] focus:outline-none text-sm text-gray-700">
                        <button type="submit" class="absolute right-3.5 top-3.5 text-gray-400 hover:text-[#545523]">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </form>
                    <a href="{{ route('admin.verifikasi.index') }}" class="bg-white text-gray-700 p-2.5 rounded-xl shadow-sm hover:bg-gray-50 border border-gray-100 active:scale-95 transition flex items-center justify-center text-sm gap-2">
                        <i class="fa-solid fa-rotate-left"></i> Reset
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
                    
                    @forelse($merchants as $m)
                        <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition flex flex-col justify-between border border-gray-100">
                            <div class="relative h-48 w-full bg-gray-100">
                                <img src="{{ $m->foto_toko ? asset('storage/'.$m->foto_toko) : 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?q=80&w=600' }}" alt="Merchant Image" class="w-full h-full object-cover">
                                
                                @if($m->status_verifikasi === 'menunggu')
                                    <span class="absolute top-3 right-3 bg-amber-500 text-white text-xs font-bold px-2.5 py-1 rounded-full shadow-sm animate-pulse">Ditunda</span>
                                @elseif($m->status_verifikasi === 'disetujui')
                                    <span class="absolute top-3 right-3 bg-emerald-500 text-white text-xs font-bold px-2.5 py-1 rounded-full shadow-sm">Aktif</span>
                                @else
                                    <span class="absolute top-3 right-3 bg-red-500 text-white text-xs font-bold px-2.5 py-1 rounded-full shadow-sm">Ditolak</span>
                                @endif
                            </div>
                            
                            <div class="p-5 flex-1 flex flex-col justify-between">
                                <div>
                                    <div class="flex justify-between items-start mb-1">
                                        <h4 class="font-bold text-gray-800 text-lg leading-tight">
                                            {{ $m->nama_toko ?? $m->user->name ?? 'Nama Toko Tidak Set' }}
                                        </h4>
                                        @if($m->status_verifikasi === 'disetujui')
                                            <i class="fa-solid fa-circle-check text-emerald-500 mt-1"></i>
                                        @endif
                                    </div>
                                    <p class="text-xs font-medium text-gray-400 mb-4">{{ $m->kategori ?? 'Kategori / Bisnis Kuliner' }}</p>
                                    
                                    <div class="space-y-2 text-xs text-gray-600 mb-6">
                                        <div class="flex items-center gap-2">
                                            <i class="fa-regular fa-calendar w-4 text-gray-400"></i>
                                            <span>Mendaftar: {{ \Carbon\Carbon::parse($m->created_at)->format('d M Y') }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <i class="fa-solid fa-location-dot w-4 text-gray-400"></i>
                                            <span class="truncate max-w-[200px]">{{ $m->alamat ?? 'Alamat Belum Diisi' }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    @if($m->status_verifikasi === 'menunggu')
                                        <div class="grid grid-cols-2 gap-2">
                                            <form action="{{ route('admin.merchant.setujui', $m->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-2 rounded-xl text-xs font-bold transition">
                                                    <i class="fa-solid fa-check mr-1"></i> Setujui
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.merchant.tolak', $m->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-xl text-xs font-bold transition">
                                                    <i class="fa-solid fa-xmark mr-1"></i> Tolak
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                    
                                    <a href="#" class="w-full bg-gray-100 text-gray-700 py-2 rounded-xl text-xs font-semibold text-center hover:bg-gray-200 transition flex items-center justify-center gap-2">
                                        <span>Detail Dokumen</span>
                                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full bg-white/80 rounded-2xl p-12 text-center shadow-sm">
                            <i class="fa-solid fa-folder-open text-4xl text-gray-400 mb-3"></i>
                            <p class="text-gray-600 font-medium">Tidak ada data pengajuan merchant ditemukan.</p>
                        </div>
                    @endforelse

                </div>

                <div class="mt-6">
                    {{ $merchants->appends(request()->query())->links() }}
                </div>

            </section>

            <x-footer/>
        </div>
    </div>
</body>
</html>