@extends('layouts.profile')

@section('title', 'Pengaturan Pembayaran - SaveEat')
@section('page_title', 'Pengaturan Pembayaran')

@section('content')
<div class="max-w-md mx-auto space-y-6">
    
    {{-- INFORMASI SALDO TOKO --}}
    <div class="bg-gradient-to-br from-[#545523] to-[#7A7A33] text-[#F1F2CF] rounded-3xl p-6 shadow-md relative overflow-hidden">
        {{-- Hiasan Lingkaran Abstrak --}}
        <div class="absolute -right-10 -top-10 w-32 h-32 bg-white/5 rounded-full"></div>
        <div class="absolute -left-10 -bottom-10 w-24 h-24 bg-white/5 rounded-full"></div>

        <div class="relative z-10 flex flex-col h-full justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-widest text-white/75">Pendapatan Toko</p>
                <h3 class="text-3xl font-black mt-1 text-white">Rp 2.450.000</h3>
            </div>
            <div class="flex justify-between items-center mt-6">
                <span class="text-[10px] text-white/60">Pencairan Terakhir: 25 Juni 2026</span>
                <button class="bg-white text-[#545523] hover:bg-[#F1F2CF] px-4 py-2 rounded-xl text-xs font-bold transition shadow-sm active:scale-95 cursor-pointer">
                    Cairkan Saldo
                </button>
            </div>
        </div>
    </div>

    {{-- KARTU REKENING BANK TOKO --}}
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 space-y-6">
        
        <div>
            <div class="flex items-center gap-2.5 text-gray-400 mb-4">
                <i class="fa-solid fa-building-columns text-base text-[#545523]"></i>
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400">Rekening Bank Utama</h3>
            </div>

            <div class="p-4 border border-gray-100 rounded-2xl flex items-center justify-between hover:bg-gray-50 transition">
                <div class="flex items-center gap-3.5">
                    {{-- Logo Bank BCA Mock --}}
                    <div class="w-12 h-12 bg-blue-50 text-blue-700 rounded-xl flex items-center justify-center font-black text-xs shrink-0 border border-blue-100">
                        BCA
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-gray-800">Bank Central Asia (BCA)</h4>
                        <p class="text-[11px] font-mono text-gray-500 mt-0.5">8420 •••• 451</p>
                        <p class="text-[10px] text-gray-400 mt-1">A.N. Dapur Roti Nusantara</p>
                    </div>
                </div>
                <button class="text-xs font-bold text-[#545523] hover:underline cursor-pointer">
                    Ubah
                </button>
            </div>
        </div>

        <hr class="border-gray-100">

        {{-- RIWAYAT PENCAIRAN --}}
        <div>
            <div class="flex items-center gap-2.5 text-gray-400 mb-4">
                <i class="fa-solid fa-clock-rotate-left text-base text-[#545523]"></i>
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400">Riwayat Pencairan</h3>
            </div>

            <div class="space-y-3.5">
                {{-- Transaksi 1 --}}
                <div class="flex items-center justify-between py-1">
                    <div>
                        <h4 class="text-xs font-bold text-gray-800">Pencairan Saldo Berhasil</h4>
                        <p class="text-[10px] text-gray-400 mt-0.5">25 Juni 2026 • BCA</p>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-bold text-gray-800">-Rp 1.200.000</span>
                        <p class="text-[9px] text-emerald-500 font-bold mt-0.5">Selesai</p>
                    </div>
                </div>

                {{-- Transaksi 2 --}}
                <div class="flex items-center justify-between py-1 border-t border-gray-50 pt-3">
                    <div>
                        <h4 class="text-xs font-bold text-gray-800">Pencairan Saldo Berhasil</h4>
                        <p class="text-[10px] text-gray-400 mt-0.5">18 Juni 2026 • BCA</p>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-bold text-gray-800">-Rp 850.000</span>
                        <p class="text-[9px] text-emerald-500 font-bold mt-0.5">Selesai</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
