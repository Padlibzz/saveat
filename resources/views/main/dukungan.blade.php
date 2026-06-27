@extends('layouts.pengaturan')

@section('title', 'Dukungan - SaveEat')
@section('page_title', 'Kotak Masuk Dukungan')

@section('content')
<div class="max-w-md mx-auto space-y-6" x-data="{ pesan: '' }">
    
    {{-- LIST TICKET / INBOX DUKUNGAN --}}
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-5 space-y-5">
        
        <div class="flex items-center gap-2.5 text-gray-400 mb-2">
            <i class="fa-solid fa-headset text-base text-[#545523]"></i>
            <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400">Pesan Tiket Bantuan</h3>
        </div>

        {{-- Pesan 1 (Staff) --}}
        <div class="flex items-start gap-3">
            <div class="w-8 h-8 rounded-full bg-[#F1F2CF] flex items-center justify-center text-[#545523] font-bold text-xs shrink-0">
                CS
            </div>
            <div class="bg-gray-100 text-gray-800 p-3.5 rounded-2xl rounded-tl-none text-xs leading-relaxed font-medium flex-1">
                Halo, ada yang bisa kami bantu terkait transaksi atau merchant di Saveeat? Beritahu kami masalah Anda secara detail.
                <p class="text-[9px] text-gray-400 font-semibold mt-1.5 text-right">09:12 WIB</p>
            </div>
        </div>

        {{-- Pesan 2 (User) --}}
        <div class="flex items-start gap-3 justify-end">
            <div class="bg-[#545523] text-white p-3.5 rounded-2xl rounded-tr-none text-xs leading-relaxed font-medium flex-1 max-w-[80%]">
                Halo CS, saya mengalami kendala saat melakukan pembayaran pada claim nomor SVAT-832FJD, statusnya masih menggantung. Mohon bantuannya.
                <p class="text-[9px] text-[#F1F2CF]/80 font-semibold mt-1.5 text-right">09:15 WIB</p>
            </div>
            <div class="w-8 h-8 rounded-full bg-gray-200 overflow-hidden shrink-0 border border-gray-100">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=545523&color=F1F2CF" class="w-full h-full object-cover">
            </div>
        </div>

    </div>

    {{-- FORM KIRIM PESAN BARU --}}
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-4">
        <form action="#" method="POST" class="flex gap-2 items-center">
            @csrf
            <input type="text" x-model="pesan" placeholder="Tulis keluhan atau pertanyaan..." class="w-full bg-gray-50 text-xs border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-[#545523] focus:bg-white transition-all font-medium text-gray-700">
            <button type="submit" class="bg-[#545523] hover:bg-[#43441c] text-white p-3 rounded-xl transition cursor-pointer flex items-center justify-center">
                <i class="fa-solid fa-paper-plane text-xs"></i>
            </button>
        </form>
    </div>

</div>
@endsection
