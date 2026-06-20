@extends('layouts.admin.app')

@section('title', 'Dashboard')
@section('header', 'Dashboard Admin')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="font-display text-3xl font-extrabold leading-tight text-[#555524]">Ringkasan Platform</h2>
        <p class="text-xs text-charcoal/50 mt-1">Minggu, 7 Juni 2026</p>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="stat-card accent-green bg-white rounded-2xl px-5 py-4 shadow-sm flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-[#555524]">Total Pengguna</p>
                <p class="font-display text-3xl font-extrabold mt-1 text-[#555524]">12.4 K</p>
            </div>
            <span class="flex items-center gap-1 text-xs font-semibold px-3 py-1 rounded-full text-white bg-[#555524]">↑ 12%</span>
        </div>
        <!-- ... add other cards here ... -->
    </div>
</div>
@endsection
