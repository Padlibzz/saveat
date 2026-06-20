@extends('layouts.admin.app')

@section('title', 'User Management')

@section('header', 'User Management')

@section('content')
<div class="space-y-6">
    <!-- Stat Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-sage/30">
            <p class="text-[#6B8F52] text-[11px] font-semibold uppercase tracking-wider mb-1">Merchant Aktif</p>
            <p class="text-[#2D4A1E] text-[28px] font-bold leading-none">{{ $stats['merchant_aktif'] }}</p>
        </div>
        <div class="bg-[#2D4A1E] rounded-2xl p-4 shadow-sm">
            <p class="text-[#C8D8B0] text-[11px] font-semibold uppercase tracking-wider mb-1">Makanan Terselamatkan</p>
            <p class="text-white text-[28px] font-bold leading-none">{{ $stats['makanan_terselamatkan'] }}</p>
        </div>
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-sage/30">
            <p class="text-[#6B8F52] text-[11px] font-semibold uppercase tracking-wider mb-1">User Terdaftar</p>
            <p class="text-[#2D4A1E] text-[28px] font-bold leading-none">{{ $users->total() }}</p>
        </div>
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-sage/30">
            <p class="text-[#6B8F52] text-[11px] font-semibold uppercase tracking-wider mb-1">Total Transaksi</p>
            <p class="text-[#2D4A1E] text-[28px] font-bold leading-none">{{ $totalTransaksi }}</p>
        </div>
    </div>


    <!-- ===== USER LIST PANEL ===== -->
    <div class="bg-white rounded-3xl shadow-sm p-4 lg:p-6">
        <!-- Search Bar -->
        <form action="{{ route('admin.users') }}" method="GET" class="relative mb-4">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari dengan nama atau email..."
                class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-gray-200 bg-[#F5F0E8] text-[14px] text-[#1C2E12] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#C8D8B0]"
            />
        </form>

        <!-- Filter Tabs -->
        <div class="flex gap-2 overflow-x-auto pb-1 mb-5">
            @foreach(['semua' => 'Semua', 'pelanggan' => 'Pelanggan', 'merchant' => 'Merchant', 'admin' => 'Admin'] as $key => $label)
                <a href="{{ route('admin.users', ['peran' => $key !== 'semua' ? $key : null]) }}"
                    class="flex-shrink-0 px-4 py-1.5 rounded-full text-[13px] font-semibold transition-colors 
                    {{ request('peran', 'semua') === $key ? 'bg-[#2D4A1E] text-white' : 'bg-[#EBF0E4] text-[#1C2E12] hover:bg-[#C8D8B0]' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-[14px]">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="pb-3 pr-4 text-[#6B8F52] text-[11px] font-semibold uppercase tracking-wider">Pengguna</th>
                        <th class="pb-3 pr-4 text-[#6B8F52] text-[11px] font-semibold uppercase tracking-wider">Email</th>
                        <th class="pb-3 pr-4 text-[#6B8F52] text-[11px] font-semibold uppercase tracking-wider">Peran</th>
                        <th class="pb-3 pr-4 text-[#6B8F52] text-[11px] font-semibold uppercase tracking-wider">Status</th>
                        <th class="pb-3 text-[#6B8F52] text-[11px] font-semibold uppercase tracking-wider">Bergabung</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr class="border-b border-gray-50 last:border-0 hover:bg-[#F9FBF7] transition-colors">
                            <td class="py-3.5 pr-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-[#EBF0E4] flex items-center justify-center text-[#2D4A1E] font-bold">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <span class="text-[#1C2E12] font-medium">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="py-3.5 pr-4 text-[#6B8F52]">{{ $user->email }}</td>
                            <td class="py-3.5 pr-4">
                                <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-[#EBF0E4] text-[#4A6B35]">{{ ucfirst($user->peran) }}</span>
                            </td>
                            <td class="py-3.5 pr-4">
                                @php
                                    $statusValue = $user->status instanceof \BackedEnum ? $user->status->value : $user->status;
                                @endphp
                                <span class="inline-flex items-center gap-1 text-[12px] font-medium {{ $statusValue === 'aktif' ? 'text-green-600' : 'text-red-600' }}">
                                    <span class="w-1.5 h-1.5 rounded-full inline-block {{ $statusValue === 'aktif' ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                    {{ ucfirst($statusValue) }}
                                </span>
                            </td>
                            <td class="py-3.5 text-[#6B8F52] text-[13px]">{{ $user->created_at->format('d M Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
