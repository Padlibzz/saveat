@extends('layouts.dashboard')

@section('page_title', 'Verifikasi Merchant')

@section('content')
<div class="space-y-6">

    {{-- BANNER NOTIFIKASI --}}
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

    {{-- HEADER KEMBALI & JUDUL --}}
    <div class="flex items-center gap-3">
        <a href="/admin/dashboard" class="text-[#545523] hover:text-gray-700 transition-colors p-2 -ml-2 rounded-lg">
            <i class="fa-solid fa-arrow-left text-lg md:text-xl"></i>
        </a>
        <h2 class="text-xl md:text-2xl font-bold text-[#545523]">Verifikasi Merchant</h2>
    </div>

    {{-- STATISTIK VERIFIKASI (RESPONSIVE GRID) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
        
        <div class="bg-[#545523] text-[#F1F2CF] rounded-2xl p-6 shadow-xs flex flex-col justify-between relative overflow-hidden">
            <div>
                <p class="text-sm font-medium opacity-80">Verifikasi Ditunda</p>
                <h3 class="text-3xl md:text-4xl font-extrabold mt-2">{{ $totalDitunda }}</h3>
            </div>
            <div class="text-xs mt-4 flex items-center gap-1.5 opacity-90">
                <i class="fa-solid fa-clock"></i>
                <span>Butuh respon segera</span>
            </div>
            <i class="fa-solid fa-clock text-white/10 text-7xl absolute -right-4 -bottom-2 pointer-events-none"></i>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-xs border border-gray-100 flex flex-col justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Disetujui Minggu ini</p>
                <h3 class="text-3xl md:text-4xl font-extrabold text-gray-800 mt-2">{{ $totalDisetujuiMingguIni }}</h3>
            </div>
            <div class="mt-4">
                <div class="w-full bg-gray-100 rounded-full h-2">
                    <div class="bg-[#545523] h-2 rounded-full" style="width: 100%"></div>
                </div>
                <p class="text-right text-xs font-semibold text-gray-400 mt-1.5">Diperbarui mingguan</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-xs border border-gray-100 flex flex-col justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Ditolak Minggu ini</p>
                <h3 class="text-3xl md:text-4xl font-extrabold text-red-600 mt-2">{{ $totalDitolakMingguIni }}</h3>
            </div>
            <p class="text-xs text-gray-400 mt-4 leading-relaxed">
                Riwayat penolakan berdasarkan standarisasi berkas.
            </p>
        </div>
    </div>

    {{-- SEARCH BAR & FILTER --}}
    <div class="flex flex-col sm:flex-row gap-3 justify-between items-center bg-white p-3 rounded-2xl shadow-xs border border-gray-100">
        <form action="{{ route('admin.verifikasi.index') }}" method="GET" class="relative w-full sm:w-96">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Merchant atau Pemilik..." 
                class="w-full bg-gray-50 pl-4 pr-10 py-2.5 rounded-xl border border-transparent focus:border-[#545523] focus:ring-1 focus:ring-[#545523] focus:bg-white focus:outline-none text-sm text-gray-700 transition-all">
            <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#545523] p-1">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>
        <a href="{{ route('admin.verifikasi.index') }}" class="w-full sm:w-auto bg-gray-50 text-gray-600 px-4 py-2.5 rounded-xl hover:bg-gray-100 border border-gray-200 active:scale-95 transition-all flex items-center justify-center text-sm font-medium gap-2">
            <i class="fa-solid fa-rotate-left"></i> Reset
        </a>
    </div>

    {{-- GRID DAFTAR MERCHANT --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 md:gap-6">
        
        @forelse($merchants as $m)
            <div class="bg-white rounded-2xl overflow-hidden shadow-xs hover:shadow-md transition-shadow flex flex-col justify-between border border-gray-100">
                
                <div class="relative h-40 md:h-48 w-full bg-gray-100">
                    <img src="{{ $m->foto_toko ? asset('storage/'.$m->foto_toko) : 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?q=80&w=600' }}" alt="Merchant Image" class="w-full h-full object-cover">
                    
                    {{-- Badge Status --}}
                    @if($m->status_verifikasi === 'menunggu')
                        <span class="absolute top-3 right-3 bg-amber-500 text-white text-[10px] md:text-xs font-bold px-3 py-1.5 rounded-full shadow-sm animate-pulse">Ditunda</span>
                    @elseif($m->status_verifikasi === 'disetujui')
                        <span class="absolute top-3 right-3 bg-emerald-500 text-white text-[10px] md:text-xs font-bold px-3 py-1.5 rounded-full shadow-sm">Aktif</span>
                    @else
                        <span class="absolute top-3 right-3 bg-red-500 text-white text-[10px] md:text-xs font-bold px-3 py-1.5 rounded-full shadow-sm">Ditolak</span>
                    @endif
                </div>
                
                <div class="p-4 md:p-5 flex-1 flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-start mb-1 gap-2">
                            <h4 class="font-bold text-gray-800 text-base md:text-lg leading-tight line-clamp-1">
                                {{ $m->nama_toko ?? $m->user->name ?? 'Nama Toko Tidak Set' }}
                            </h4>
                            @if($m->status_verifikasi === 'disetujui')
                                <i class="fa-solid fa-circle-check text-emerald-500 text-sm mt-1 shrink-0"></i>
                            @endif
                        </div>
                        <p class="text-[11px] md:text-xs font-medium text-gray-400 mb-4">{{ $m->kategori ?? 'Kategori / Bisnis Kuliner' }}</p>
                        
                        <div class="space-y-2.5 text-[11px] md:text-xs text-gray-600 mb-6">
                            <div class="flex items-start gap-2.5">
                                <i class="fa-regular fa-calendar mt-0.5 w-3 text-center text-gray-400"></i>
                                <span>Mendaftar: {{ \Carbon\Carbon::parse($m->created_at)->format('d M Y') }}</span>
                            </div>
                            <div class="flex items-start gap-2.5">
                                <i class="fa-solid fa-location-dot mt-0.5 w-3 text-center text-gray-400"></i>
                                <span class="line-clamp-2 leading-relaxed">{{ $m->alamat ?? 'Alamat Belum Diisi' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2 mt-auto">
                        @if($m->status_verifikasi === 'menunggu')
                            <div class="grid grid-cols-2 gap-2">
                                <form action="{{ route('admin.merchant.setujui', $m->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-2.5 rounded-xl text-[11px] md:text-xs font-bold transition-colors active:scale-95">
                                        <i class="fa-solid fa-check mr-1"></i> Setujui
                                    </button>
                                </form>
                                <form action="{{ route('admin.merchant.tolak', $m->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white py-2.5 rounded-xl text-[11px] md:text-xs font-bold transition-colors active:scale-95">
                                        <i class="fa-solid fa-xmark mr-1"></i> Tolak
                                    </button>
                                </form>
                            </div>
                        @endif
                        
                        <a href="#" class="w-full bg-gray-50 text-gray-600 py-2.5 rounded-xl text-[11px] md:text-xs font-semibold text-center hover:bg-gray-100 border border-gray-200 transition-colors flex items-center justify-center gap-2">
                            <span>Detail Dokumen</span>
                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-2xl p-8 md:p-12 text-center shadow-xs border border-gray-100">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 mb-4">
                    <i class="fa-solid fa-folder-open text-2xl text-gray-400"></i>
                </div>
                <p class="text-gray-500 font-medium text-sm">Tidak ada data pengajuan merchant yang ditemukan.</p>
            </div>
        @endforelse

    </div>

    {{-- PAGINASI --}}
    @if($merchants->hasPages())
        <div class="mt-6">
            {{ $merchants->appends(request()->query())->links() }}
        </div>
    @endif

</div>