@extends('layouts.admin.app')

@section('title', 'Profil Admin')

@section('header', 'Profil Admin')

@section('content')
<div class="space-y-6">
    <!-- Avatar + Info Card -->
    <div class="bg-white rounded-3xl shadow-sm p-6 mb-6">
        <div class="flex flex-col items-center gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex flex-col items-center gap-3 lg:flex-row lg:gap-5">
                <div class="relative inline-block">
                    <div class="w-[82px] h-[82px] rounded-full overflow-hidden border-2 border-white bg-olive/20 flex items-center justify-center text-olive text-2xl font-bold">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                </div>
                <div class="text-center lg:text-left">
                    <p class="text-gray-900 text-[18px] font-bold leading-tight">{{ auth()->user()->name }}</p>
                    <p class="text-gray-500 text-[13px] font-medium">{{ auth()->user()->email }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Management Section -->
    <div class="bg-white rounded-3xl shadow-sm p-6">
        <p class="text-gray-500 text-[11px] font-bold uppercase tracking-widest mb-3">Management</p>
        <div class="bg-white rounded-2xl px-4 shadow-sm border border-gray-100">
            <a href="{{ route('admin.users') }}" class="flex items-center justify-between p-4 border-b border-gray-100 hover:bg-gray-50 transition-colors">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-olive/10 flex items-center justify-center">
                        <i class="fa-solid fa-users text-olive"></i>
                    </div>
                    <span class="text-gray-900 font-medium">User Management</span>
                </div>
                <i class="fa-solid fa-chevron-right text-gray-400"></i>
            </a>
            
            <a href="#" class="flex items-center justify-between p-4 border-b border-gray-100 hover:bg-gray-50 transition-colors">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-olive/10 flex items-center justify-center">
                        <i class="fa-solid fa-shield-halved text-olive"></i>
                    </div>
                    <span class="text-gray-900 font-medium">Privacy & Security</span>
                </div>
                <i class="fa-solid fa-chevron-right text-gray-400"></i>
            </a>
        </div>
    </div>

    <!-- Sign Out -->
    <div class="px-0 py-6 mt-2">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center gap-2 text-red-600 font-semibold text-[15px] py-3 rounded-2xl border border-red-100 bg-red-50 hover:bg-red-100 transition-colors">
                <i class="fa-solid fa-right-from-bracket"></i>
                Sign Out
            </button>
        </form>
    </div>
</div>
@endsection
