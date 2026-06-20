@props([
    'foto' => null,
    'nama' => '',
    'merchant' => '',
    'alamat' => '',
    'jarak' => '-',
    'harga_diskon' => 0,
    'harga_asli' => 0,
    'tersisa' => 0,
])

<div class="bg-white rounded-2xl shadow-md overflow-hidden w-80 hover:shadow-lg transition">

    <!-- Foto -->
    <div class="relative">

        @if(!empty($foto))
            <img
                src="{{ asset('storage/' . $foto) }}"
                alt="{{ $nama }}"
                class="w-full h-52 object-cover">
        @else
            <div class="w-full h-52 bg-gradient-to-br from-[#545523] to-[#7A7A33] flex flex-col items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="w-12 h-12 text-white mb-2">
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 3v18m-4.5-15v12m9-12v12M5.25 7.5h13.5" />
                </svg>

                <p class="text-white font-medium">
                    Surplus Food
                </p>
            </div>
        @endif

        <!-- Badge Tersisa -->
        <div class="absolute top-3 right-3">
            <span class="bg-yellow-400 text-yellow-900 text-xs font-semibold px-3 py-1 rounded-full">
                Tersisa {{ $tersisa ?? 0 }}
            </span>
        </div>

    </div>

    <!-- Konten -->
    <div class="p-5">

        <h3 class="text-lg font-bold text-gray-900">
            {{ $nama }}
        </h3>

        <p class="text-sm text-[#545523] font-medium mt-1">
            {{ $merchant }}
        </p>

        <div class="flex justify-between items-center mt-2 text-sm text-gray-500">
            <span>{{ $alamat }}</span>
            <span>{{ $jarak }}</span>
        </div>

        <div class="flex items-center gap-2 mt-5">

            <span class="text-xl font-bold text-[#545523]">
                Rp {{ number_format($harga_diskon, 0, ',', '.') }}
            </span>

            <span class="text-sm text-gray-400 line-through">
                Rp {{ number_format($harga_asli, 0, ',', '.') }}
            </span>

        </div>

        <a href="" class="inline-block mt-4 bg-[#6D6B2E] text-white px-6 py-3 rounded-xl hover:bg-[#545523]"> Beli Sekarang</a>

    </div>

</div>