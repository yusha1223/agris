<footer class="bg-gray-900 text-white px-6" data-aos="fade-up">
    <div class="max-w-7xl mx-auto px-6 py-8">
        <div class="grid md:grid-cols-4 gap-10">

            <div class="flex flex-col items-start" data-aos="fade-up">
                <div class="mb-4">
                    <img src="{{ asset('images/icon.svg') }}" class="w-40" alt="Logo AGRIS">
                </div>
                <p class="text-gray-400 text-sm leading-relaxed">
                    platform pertanian modern yang menyediakan kebutuhan
                    terbaik untuk petani dengan sistem terpercaya.
                </p>
            </div>

            <div data-aos="fade-up" data-aos-delay="100">
                <h3 class="text-lg font-semibold mb-4">Navigasi</h3>
                <ul class="space-y-2 text-gray-400 text-sm">
                    <li>
                        <a href="{{ route('agen.blog.index') }}" class="hover:text-white transition {{ Route::is('agen.blog.*') ? 'text-white font-bold' : '' }}">
                            Blog
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('agen.produk.index') }}" class="hover:text-white transition {{ Route::is('agen.produk.*') ? 'text-white font-bold' : '' }}">
                            Produk
                        </a>
                    </li>
                    <li>
                        <a href="#" class="hover:text-white transition">Transaksi</a>
                    </li>
                    <li>
                        <a href="{{ route('kemitraan.index') }}" class="hover:text-white transition {{ Route::is('kemitraan.*') ? 'text-white font-bold' : '' }}">
                            Kemitraan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('agen.chat.index') }}" class="hover:text-white transition {{ Route::is('agen.chat.*') ? 'text-white font-bold' : '' }}">
                            Chat
                        </a>
                    </li>
                </ul>
            </div>

            <div data-aos="fade-up" data-aos-delay="200">
                <h3 class="text-lg font-semibold mb-4">Kontak</h3>
                <ul class="space-y-3 text-gray-400 text-sm">
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-location-dot text-[#04a243] mt-1 gap-2"></i>
                        <span class="capitalize">
                            {{ !empty($admin->detailAlamat) ? strtolower($admin->detailAlamat) . ',' : '' }}
                            {{ !empty($admin->desa->namaDesa) ? strtolower($admin->desa->namaDesa) . ',' : '' }}
                            {{ !empty($admin->desa->kecamatan->namaKecamatan) ? strtolower($admin->desa->kecamatan->namaKecamatan) . ',' : '' }}
                            {{ !empty($admin->desa->kecamatan->kabupaten->namaKabupaten) ? strtolower($admin->desa->kecamatan->kabupaten->namaKabupaten) : '' }}
                        </span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fa-solid fa-phone text-[#04a243]"></i>
                        {{ $admin->noTelp ?? '-' }}
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fa-solid fa-envelope text-[#04a243]"></i>
                        {{ $admin->email ?? '-' }}
                    </li>
                </ul>

                <div class="flex gap-4 mt-8 text-xl">
                    <a href="https://www.tiktok.com/@skas.official?_t=ZS-8wWtyVtbfV7&_r=1" target="_blank" class="text-gray-400 hover:text-[#58CC02] transition"><i class="fa-brands fa-tiktok"></i></a>
                    <a href="https://www.instagram.com/skas.official/" target="_blank" class="text-gray-400 hover:text-[#58CC02] transition"><i class="fa-brands fa-instagram"></i></a>
                </div>
            </div>

            <div data-aos="fade-up" data-aos-delay="300">
                <h3 class="text-lg font-semibold mb-4">Lokasi</h3>
                @php
                    $kabupaten = strtoupper($admin->desa->kecamatan->kabupaten->namaKabupaten ?? '');
                    $namaTempat = (str_contains($kabupaten, 'JEMBER')) ? "PT. Surya Kencana Agrifarm Sejahtera, " : "";

                    $alamatLengkap = $namaTempat .
                                    ($admin->detailAlamat ?? '') . ' ' .
                                    ($admin->desa->namaDesa ?? '') . ' ' .
                                    ($admin->desa->kecamatan->namaKecamatan ?? '') . ' ' .
                                    ($admin->desa->kecamatan->kabupaten->namaKabupaten ?? '') . ' ' .
                                    ($admin->desa->kecamatan->kabupaten->provinsi->namaProvinsi ?? '');

                    $queryMaps = urlencode(trim($alamatLengkap));
                @endphp

                @if(!empty(trim($admin->detailAlamat)))
                    <iframe
                        title="Lokasi AGRIS"
                        class="w-full h-48 rounded-xl border-0"
                        src="https://maps.google.com/maps?q={{ $queryMaps }}&t=&z=16&ie=UTF8&iwloc=&output=embed"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                @else
                    <div class="w-full h-48 rounded-xl bg-gray-800 flex items-center justify-center text-gray-500 text-xs p-4 text-center">
                        Lokasi belum diatur oleh admin.
                    </div>
                @endif
            </div>

        </div>

        <div class="mt-10 border-t border-gray-800 pt-6 text-center text-gray-500 text-sm">
            © {{ date('Y') }} <span class="font-bold text-gray-400">Agris</span>. All rights reserved.
        </div>
    </div>
</footer>
