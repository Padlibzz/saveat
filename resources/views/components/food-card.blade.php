<div class="bg-[#F9FAFB] p-6 rounded-lg shadow-md w-80">

                <img src="svg/donnut.png" alt="Product Image" class="mx-auto">

                <div class="px-4 mt-4">

                    <h3 class="text-lg font-semibold text-black">
                        {{ $nama }}
                    </h3>

                    <div class="flex justify-between">
                        <h4 class="text-sm text-gray-500">
                            {{ $alamat }}
                        </h4>

                        <h4 class="text-sm text-gray-500">
                            {{ $jarak }}
                        </h4>
                    </div>

                    <h3 class="text-lg font-semibold text-black">
                        {{ $merchant }}
                    </h3>

                    <div class="flex items-center gap-2 mt-10">
                        <h3 class="text-lg font-bold text-[#545523]">
                            Rp {{ number_format($harga_asli, 0, ',', '.') }}
                        </h3>

                        <h4 class="text-sm text-gray-500 line-through">
                            Rp {{ number_format($harga_diskon, 0, ',', '.') }}
                        </h4>
                    </div>

                </div>

            </div>