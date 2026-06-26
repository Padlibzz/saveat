@extends('layouts.public')

@section('title', 'Makanan')

@section('content')
<!-- Search -->
<form action="{{ route('listing-makanan') }}" method="GET" class="mb-8">
    <input
        type="text"
        name="search"
        placeholder="Temukan Makananmu"
        value="{{ request('search') }}"
        class="w-full p-4 rounded-full shadow-md bg-white outline-none focus:ring-2 focus:ring-[#6D6B2E]">
</form>

<!-- Recommendation -->
<div class="mt-8">
    <h2 class="text-2xl font-semibold text-[#545523] mb-5">
        Temukan Makanan Favorit mu
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse ($listings as $listing)
            <x-food-card
                data-url="{{ route('listing.show', $listing->id) }}"
                :foto="$listing->foto"
                :nama="$listing->nama"
                :merchant="$listing->merchant?->nama_usaha ?? 'Merchant'"
                :alamat="$listing->merchant?->alamat ?? '-'"
                :jarak="$listing->jarak_km ?? '-'"
                :harga_diskon="$listing->harga_diskon"
                :harga_asli="$listing->harga_normal"
                :tersisa="$listing->stok_sisa"
            />
        @empty
            <div class="col-span-full">
                <div class="bg-white rounded-xl p-8 text-center shadow">
                    <i class="fa-solid fa-utensils text-4xl text-gray-400 mb-3"></i>
                    <p class="text-gray-500">
                        Tidak ada makanan yang ditemukan.
                    </p>
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $listings->appends(request()->query())->links() }}
    </div>
</div>
@endsection
