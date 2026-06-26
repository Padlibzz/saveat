@extends('layouts.dashboard')

@section('page_title', 'Analisis Penjualan')

@section('content')
<div class="space-y-6">

    {{-- HEADER & SUBTITLE --}}
    <div>
        <h2 class="text-xl md:text-2xl font-extrabold text-[#545523] tracking-wide">
            Analisis Penjualan
        </h2>
        <p class="text-gray-500 text-xs md:text-sm mt-1 font-medium">
            Tinjau kinerja pedagang Anda dan dampak lingkungan.
        </p>
    </div>

    {{-- TAB SWITCHER PERIODE WAKTU --}}
    <div class="inline-flex bg-gray-100 p-1 rounded-2xl border border-gray-200 shadow-2xs w-full sm:w-auto">
        <a href="{{ request()->fullUrlWithQuery(['period' => '7_days']) }}" 
           class="flex-1 sm:flex-none text-center px-4 py-2 rounded-xl text-xs font-semibold transition-all
           {{ $currentPeriod === '7_days' ? 'bg-white text-[#545523] shadow-xs' : 'text-gray-500 hover:text-gray-800' }}">
            7 Hari terakhir
        </a>
        <a href="{{ request()->fullUrlWithQuery(['period' => 'this_month']) }}" 
           class="flex-1 sm:flex-none text-center px-4 py-2 rounded-xl text-xs font-semibold transition-all
           {{ $currentPeriod === 'this_month' ? 'bg-white text-[#545523] shadow-xs' : 'text-gray-500 hover:text-gray-800' }}">
            Bulan ini
        </a>
        <a href="{{ request()->fullUrlWithQuery(['period' => 'this_year']) }}" 
           class="flex-1 sm:flex-none text-center px-4 py-2 rounded-xl text-xs font-semibold transition-all
           {{ $currentPeriod === 'this_year' ? 'bg-white text-[#545523] shadow-xs' : 'text-gray-500 hover:text-gray-800' }}">
            Tahun Ini
        </a>
    </div>

    {{-- METRIC STAT CARDS (Grid 1x3) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-2xl shadow-xs border border-gray-100 relative overflow-hidden flex flex-col justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400">Total Pendapatan</p>
                <h3 class="text-2xl font-black text-[#545523] mt-2">
                    Rp.{{ number_format($stats->total_pendapatan, 0, ',', '.') }}
                </h3>
            </div>
            <p class="text-emerald-600 text-xs font-medium mt-3 flex items-center gap-1">
                <i class="fa-solid fa-arrow-trend-up text-[10px]"></i> {{ $stats->pendapatan_trend }}
            </p>
            <div class="absolute top-4 right-4 w-9 h-9 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center text-gray-500">
                <i class="fa-solid fa-wallet text-sm"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl shadow-xs border border-gray-100 relative overflow-hidden flex flex-col justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400">Hemat Makanan</p>
                <h3 class="text-2xl font-black text-[#545523] mt-2">
                    {{ $stats->hemat_makanan }} kg
                </h3>
            </div>
            <p class="text-emerald-600 text-xs font-medium mt-3 flex items-center gap-1">
                <i class="fa-solid fa-arrow-trend-up text-[10px]"></i> {{ $stats->hemat_makanan_trend }}
            </p>
            <div class="absolute top-4 right-4 w-9 h-9 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600">
                <i class="fa-solid fa-leaf text-sm"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl shadow-xs border border-gray-100 relative overflow-hidden flex flex-col justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400">Listing Aktif</p>
                <h3 class="text-2xl font-black text-[#545523] mt-2">
                    {{ $stats->listing_aktif }}
                </h3>
            </div>
            <p class="text-amber-600 text-xs font-medium mt-3 flex items-center gap-1">
                {{ $stats->hampir_habis_count }} barang hampir habis
            </p>
            <div class="absolute top-4 right-4 w-9 h-9 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600">
                <i class="fa-solid fa-box-archive text-sm"></i>
            </div>
        </div>
    </div>

    {{-- MIDDLE SECTION: GRAPH AND CATEGORIES --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <div class="bg-white p-5 rounded-2xl shadow-xs border border-gray-100 flex flex-col justify-between">
            <div class="flex justify-between items-center mb-6">
                <h4 class="font-bold text-gray-800 text-sm md:text-base">Trending Penjualan Mingguan</h4>
                <div class="flex items-center gap-1.5 text-xs text-gray-500 font-medium">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#545523]"></span> Penjualan
                </div>
            </div>
            
            {{-- CANVAS PLACEMENT / RESPONSIVE SVG GRAPH TREN --}}
            <div class="relative w-full h-44 flex items-end">
                <div class="absolute top-2 left-[58%] -translate-x-1/2 bg-[#545523] text-white font-bold text-[10px] px-2 py-1 rounded-lg shadow-sm z-10">
                    Jum: Rp.2jt
                    <div class="absolute bottom-[-3px] left-1/2 -translate-x-1/2 w-1.5 h-1.5 bg-[#545523] rotate-45"></div>
                </div>

                <svg class="w-full h-full overflow-visible" viewBox="0 0 700 150" preserveAspectRatio="none">
                    <path d="M 0 140 Q 116 130 233 60 T 466 40 T 700 10" fill="none" stroke="#545523" stroke-width="3"/>
                    <circle cx="116" cy="135" r="4" fill="#d9be3b" stroke="#ffffff" stroke-width="1.5" />
                    <circle cx="233" cy="60" r="4" fill="#d9be3b" stroke="#ffffff" stroke-width="1.5" />
                    <circle cx="350" cy="50" r="4" fill="#d9be3b" stroke="#ffffff" stroke-width="1.5" />
                    <circle cx="466" cy="40" r="4" fill="#d9be3b" stroke="#ffffff" stroke-width="1.5" />
                    <circle cx="583" cy="30" r="4" fill="#d9be3b" stroke="#ffffff" stroke-width="1.5" />
                </svg>
            </div>

            <div class="flex justify-between text-gray-400 text-xs font-semibold mt-4 px-1">
                <span>Sen</span><span>Sel</span><span>Rab</span><span>Kam</span><span>Jum</span><span>Sab</span><span>Min</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl shadow-xs border border-gray-100 flex flex-col justify-between space-y-5">
            <div>
                <h4 class="font-bold text-gray-800 text-sm md:text-base mb-4">Pemisahan Kategori</h4>
                
                {{-- Loop Kategori Dinamis --}}
                <div class="space-y-4">
                    @foreach($categories as $category)
                        <div class="space-y-1.5">
                            <div class="flex justify-between items-center text-xs font-bold text-gray-700">
                                <span>{{ $category->name }}</span>
                                <span class="text-gray-400">{{ $category->percentage }}%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="bg-[#545523] h-2 rounded-full transition-all duration-500" 
                                     style="width: {{ $category->percentage }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- BOX INSIGHT PINTAR --}}
            @if($smartInsight)
                <div class="bg-gray-50 border border-gray-100 rounded-2xl p-4 flex items-start gap-3">
                    <div class="w-7 h-7 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-500 shrink-0">
                        <i class="fa-regular fa-lightbulb text-sm"></i>
                    </div>
                    <p class="text-xs text-gray-600 font-medium leading-relaxed">
                        {{ $smartInsight }}
                    </p>
                </div>
            @endif
        </div>
    </div>

    {{-- BOTTOM SECTION: TOP PERFORMING ITEMS --}}
    <div class="bg-white p-5 rounded-2xl shadow-xs border border-gray-100">
        <div class="flex justify-between items-center mb-4">
            <h4 class="font-bold text-gray-800 text-sm md:text-base">Top Performing Items</h4>
            <a href="#" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 transition-colors">
                Lihat Semua
            </a>
        </div>

        {{-- LIST CONTAINER --}}
        <div class="divide-y divide-gray-100">
            @foreach($topItems as $item)
                <div class="flex items-center justify-between py-4.5 first:pt-2 last:pb-2">
                    <div class="flex items-center gap-3">
                        <img src="{{ $item->image_url }}" alt="{{ $item->name }}" 
                             class="w-12 h-12 rounded-xl object-cover border border-gray-100 shadow-2xs">
                        
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <h5 class="font-bold text-gray-800 text-sm leading-tight">{{ $item->name }}</h5>
                                @if($item->is_popular)
                                    <span class="bg-amber-500 text-white font-extrabold text-[8px] px-1.5 py-0.5 rounded uppercase tracking-wide">
                                        POPULAR
                                    </span>
                                @endif
                            </div>
                            <div class="flex items-center gap-3 mt-1 text-xs font-semibold">
                                <span class="text-emerald-600">Rp.{{ number_format($item->price, 0, ',', '.') }}</span>
                                <span class="text-gray-400 text-[11px]">{{ $item->sold_count }} sold</span>
                            </div>
                        </div>
                    </div>

                    <div class="text-emerald-500 p-2">
                        <i class="fa-solid fa-arrow-trend-up text-sm"></i>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection