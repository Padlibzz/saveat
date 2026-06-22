@extends('layouts.app')

@section('title', 'Riwayat Pesanan')

@section('content')
<div class="max-w-4xl mx-auto">
    <h2 class="text-2xl font-bold mb-6">Riwayat Pesanan</h2>
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-4 text-left">Foto</th>
                    <th class="p-4 text-left">Kode</th>
                    <th class="p-4 text-left">Merchant</th>
                    <th class="p-4 text-left">Total</th>
                    <th class="p-4 text-left">Status</th>
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
                    <td class="p-4">Rp {{ number_format($claim->total_harga, 0, ',', '.') }}</td>
                    <td class="p-4 text-emerald-600 font-bold">Selesai</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
