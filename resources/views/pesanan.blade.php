@extends('layouts.app')

@section('title', 'Pesanan Aktif')

@section('content')
<div class="max-w-4xl mx-auto">
    <h2 class="text-2xl font-bold mb-6">Pesanan Aktif</h2>
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-4 text-left">Foto</th>
                    <th class="p-4 text-left">Kode</th>
                    <th class="p-4 text-left">Merchant</th>
                    <th class="p-4 text-left">Status</th>
                    <th class="p-4 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($claims as $claim)
                <tr class="border-b">
                    <td class="p-4">
                        <img src="{{ asset('storage/' . $claim->listing->foto) }}" alt="{{ $claim->listing->nama }}" class="w-16 h-16 object-cover rounded-lg">
                    </td>
                    <td class="p-4">{{ $claim->kode_klaim }}</td>
                    <td class="p-4">{{ $claim->listing->merchant->nama_usaha }}</td>
                    <td class="p-4">{{ $claim->status }}</td>
                    <td class="p-4">
                        <a href="{{ route('pesanan.detail', $claim->id) }}" class="bg-[#545523] text-white px-4 py-2 rounded-lg font-bold">Lihat QR</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection