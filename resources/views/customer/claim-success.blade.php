@extends('layouts.dashboard')

@section('page_title', 'Barcode Pengambilan')

@section('content')
<div class="w-full max-w-md mx-auto my-4">
    
    {{-- BANNER NOTIFIKASI FLASH SESSION --}}
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

    {{-- KARTU STRUK / RECEIPT PENGAMBILAN --}}
    <div class="bg-[#F4F5E9] rounded-3xl shadow-xs border border-[#545523]/10 overflow-hidden flex flex-col">
        
        {{-- HEADER RECEIPT --}}
        <div class="bg-white px-6 py-4 flex items-center gap-4 border-b border-gray-100 shadow-xs">
            <a href="/dashboard" class="text-[#545523] hover:opacity-75 transition">
                <i class="fa-solid fa-arrow-left text-xl"></i>
            </a>
            <h2 class="text-sm font-bold text-[#545523] flex-1 text-center pr-6">Barcode Pengambilan</h2>
        </div>

        {{-- ISI RECEIPT KLAIM --}}
        <div class="p-6 space-y-6">
            
            {{-- AREA QR CODE --}}
            <div class="bg-white rounded-2xl p-6 shadow-xs border border-gray-100 text-center">
                
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-4">Scan Barcode</p>
                
                <div class="bg-gray-50 p-4 rounded-2xl inline-block border border-gray-100 mb-6">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ $claim->kode_klaim }}" 
                         alt="QR Code Pengambilan" 
                         class="w-44 h-44 mx-auto mix-blend-multiply mb-3">
                         
                    {{-- KODE KLAIM TEKS (Dimasukkan dari desain pertama) --}}
                    <div class="bg-white py-1.5 px-4 rounded-lg border border-gray-200 shadow-sm inline-block">
                        <span class="text-sm font-mono font-bold text-gray-800 tracking-widest">{{ $claim->kode_klaim }}</span>
                    </div>
                </div>

                <div class="mb-4">
                    <span class="text-xs text-gray-400 block mb-0.5">{{ $claim->listing->nama }}</span>
                    <h3 class="text-base font-bold text-gray-800">{{ $claim->listing->merchant->nama_usaha ?? 'Mitra SavEat' }}</h3>
                    <p class="text-base font-extrabold text-emerald-600 mt-1">
                        Rp {{ number_format($claim->total_harga, 0, ',', '.') }}
                    </p>
                </div>

                <div class="border-t-2 border-dashed border-gray-200 my-5"></div>

                {{-- RINCIAN DETAIL SUB-HARGA --}}
                <div class="space-y-2.5 text-xs text-gray-500 px-1">
                    <div class="flex justify-between">
                        <span>Subtotal ({{ $claim->jumlah }} barang)</span>
                        <span class="font-medium text-gray-700">Rp {{ number_format($claim->total_harga - 2000, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Pajak</span>
                        <span class="font-medium text-gray-700">Rp 2.000</span>
                    </div>
                    <div class="flex justify-between text-sm font-bold text-gray-800 pt-1">
                        <span>Total</span>
                        <span class="text-emerald-600">Rp {{ number_format($claim->total_harga, 0, ',', '.') }}</span>
                    </div>
                </div>

            </div>

            {{-- TOMBOL AKSI --}}
            <button onclick="window.location.href='/dashboard'" class="w-full bg-[#545523] text-[#F1F2CF] py-3.5 rounded-xl text-xs font-bold hover:bg-[#43441c] shadow-xs transition flex items-center justify-center gap-2 cursor-pointer">
                <i class="fa-solid fa-download"></i> Simpan Barcode
            </button>

            <p class="text-[10px] text-center text-gray-400 leading-relaxed px-4">
                *Jangan lupa tunjukkan barcode ini ke merchant untuk scan saat melakukan pengambilan makanan.
            </p>

        </div>
        
        {{-- FOOTER STRUK --}}
        <div class="text-center py-4 text-[9px] text-gray-400 bg-white border-t border-gray-100">
            © {{ date('Y') }} SavEat. All rights reserved.
        </div>
        
    </div>
</div>
@endsection