@extends('layouts.profile')

@section('title', 'Metode Pembayaran - SaveEat')
@section('page_title', 'Metode Pembayaran')

@section('content')
<div class="max-w-md mx-auto space-y-6">
    
    {{-- KARTU UTAMA METODE PEMBAYARAN --}}
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 space-y-6">
        
        <div>
            <div class="flex items-center gap-2.5 text-gray-400 mb-4">
                <i class="fa-solid fa-credit-card text-base text-[#545523]"></i>
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400">Kartu Tersimpan</h3>
            </div>

            {{-- Kartu Debit/Kredit --}}
            <div class="space-y-4">
                <div class="p-4 bg-gradient-to-r from-[#545523] to-[#7A7A33] text-white rounded-2xl shadow-sm relative overflow-hidden flex flex-col justify-between h-36">
                    <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-white/5 rounded-full"></div>
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[10px] text-white/70 uppercase font-bold tracking-wider">Debit Card</p>
                            <p class="text-xs font-semibold mt-0.5">Bank Central Asia (BCA)</p>
                        </div>
                        <i class="fa-brands fa-cc-mastercard text-2xl text-white/80"></i>
                    </div>
                    <div class="flex justify-between items-end">
                        <div>
                            <p class="text-sm font-mono tracking-widest">•••• •••• •••• 4289</p>
                            <p class="text-[10px] text-white/60 mt-1">Expiry: 12/28</p>
                        </div>
                        <span class="text-[10px] bg-white/20 px-2.5 py-1 rounded-lg font-bold">UTAMA</span>
                    </div>
                </div>
            </div>
        </div>

        <hr class="border-gray-100">

        {{-- E-Wallet & Lainnya --}}
        <div>
            <div class="flex items-center gap-2.5 text-gray-400 mb-4">
                <i class="fa-solid fa-wallet text-base text-[#545523]"></i>
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400">Dompet Digital</h3>
            </div>

            <div class="space-y-3">
                {{-- GoPay --}}
                <div class="flex items-center justify-between p-3.5 border border-gray-100 rounded-xl hover:bg-gray-50 transition">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center font-bold text-sm">
                            GP
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-gray-800">GoPay</h4>
                            <p class="text-[10px] text-gray-500">+62 812-••••-5678</p>
                        </div>
                    </div>
                    <span class="text-xs font-bold text-[#545523]">Terhubung</span>
                </div>

                {{-- ShopeePay --}}
                <div class="flex items-center justify-between p-3.5 border border-gray-100 rounded-xl hover:bg-gray-50 transition">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center font-bold text-sm">
                            SP
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-gray-800">ShopeePay</h4>
                            <p class="text-[10px] text-gray-500">+62 812-••••-5678</p>
                        </div>
                    </div>
                    <span class="text-xs font-bold text-[#545523]">Terhubung</span>
                </div>
            </div>
        </div>

        {{-- Tambah Metode --}}
        <button class="w-full border-2 border-dashed border-[#545523]/30 text-[#545523] py-3 rounded-2xl text-xs font-bold hover:bg-[#F1F2CF]/30 transition flex items-center justify-center gap-2 cursor-pointer">
            <i class="fa-solid fa-plus text-xs"></i> Tambah Metode Pembayaran
        </button>

    </div>

</div>
@endsection
