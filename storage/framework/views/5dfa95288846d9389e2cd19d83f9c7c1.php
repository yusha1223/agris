<footer class="bg-gray-900 text-white">
    <div class="max-w-7xl mx-auto px-6 py-10">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-10">

            <div data-aos="fade-up">
                <div class="mb-4">
                    <img src="<?php echo e(asset('images/icon.svg')); ?>" class="w-40" alt="Logo AGRIS">
                </div>
                <p class="text-gray-400 text-sm leading-relaxed">
                    platform pertanian modern yang menyediakan kebutuhan terbaik untuk petani dengan sistem terpercaya.
                </p>
            </div>

            <div data-aos="fade-up" data-aos-delay="100">
                <h3 class="text-lg font-semibold mb-4 text-white">Navigasi</h3>
                <ul class="space-y-2 text-gray-400 text-sm">
                    <li><a href="<?php echo e(route('landing')); ?>" class="hover:text-[#58CC02] transition">Beranda</a></li>
                    <li><a href="<?php echo e(route('about')); ?>" class="hover:text-[#58CC02] transition">Tentang</a></li>
                    <li><a href="<?php echo e(route('guest.blog.index')); ?>" class="hover:text-[#58CC02] transition">Blog</a></li>
                    <li><a href="<?php echo e(route('contact')); ?>" class="hover:text-[#58CC02] transition">Kontak</a></li>
                </ul>
            </div>

            <div data-aos="fade-up" data-aos-delay="200">
                <h3 class="text-lg font-semibold mb-4 text-white">Kontak</h3>
                <ul class="space-y-3 text-gray-400 text-sm">
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-location-dot mt-1 text-[#04a243] shrink-0"></i>
                        <span class="capitalize">
                            <?php if($admin && (!empty($admin->detailAlamat) || !empty($admin->desa->namaDesa))): ?>
                                <?php echo e(!empty($admin->detailAlamat) ? strtolower($admin->detailAlamat) . ',' : ''); ?>

                                <?php echo e(!empty($admin->desa->namaDesa) ? strtolower($admin->desa->namaDesa) . ',' : ''); ?>

                                <?php echo e(!empty($admin->desa->kecamatan->namaKecamatan) ? strtolower($admin->desa->kecamatan->namaKecamatan) . ',' : ''); ?>

                                <?php echo e(!empty($admin->desa->kecamatan->kabupaten->namaKabupaten) ? strtolower($admin->desa->kecamatan->kabupaten->namaKabupaten) : ''); ?>

                            <?php else: ?>
                                Jember, Indonesia
                            <?php endif; ?>
                        </span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fa-solid fa-phone text-[#04a243]"></i>
                        <span><?php echo e($admin->noTelp ?? '+62 812 3456 7890'); ?></span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fa-solid fa-envelope text-[#04a243]"></i>
                        <span><?php echo e($admin->email ?? 'support@agris.com'); ?></span>
                    </li>
                </ul>
                <div class="flex gap-4 mt-8 text-xl">
                    <a href="https://www.tiktok.com/@skas.official?_t=ZS-8wWtyVtbfV7&_r=1" target="_blank" class="text-gray-400 hover:text-[#58CC02] transition"><i class="fa-brands fa-tiktok"></i></a>
                    <a href="https://www.instagram.com/skas.official/" target="_blank" class="text-gray-400 hover:text-[#58CC02] transition"><i class="fa-brands fa-instagram"></i></a>
                </div>
            </div>

            <div data-aos="fade-up" data-aos-delay="300">
                <h3 class="text-lg font-semibold mb-4 text-white">Lokasi</h3>
                <?php
                    $alamatLengkap = '';
                    if ($admin) {
                        $kabupaten = strtoupper($admin->desa->kecamatan->kabupaten->namaKabupaten ?? '');
                        $namaTempat = (str_contains($kabupaten, 'JEMBER')) ? "PT. Surya Kencana Agrifarm Sejahtera, " : "";

                        $alamatLengkap = $namaTempat .
                                        ($admin->detailAlamat ?? '') . ' ' .
                                        ($admin->desa->namaDesa ?? '') . ' ' .
                                        ($admin->desa->kecamatan->namaKecamatan ?? '') . ' ' .
                                        ($admin->desa->kecamatan->kabupaten->namaKabupaten ?? '') . ' ' .
                                        ($admin->desa->kecamatan->kabupaten->provinsi->namaProvinsi ?? '');
                    }
                    $queryMaps = urlencode(trim($alamatLengkap));
                ?>

                <?php if($admin && !empty(trim($admin->detailAlamat))): ?>
                    <iframe
                        title="Peta Lokasi AGRIS"
                        class="w-full h-48 rounded-xl border-0 shadow-lg"
                        src="https://maps.google.com/maps?q=<?php echo e($queryMaps); ?>&t=&z=16&ie=UTF8&iwloc=&output=embed"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                <?php else: ?>
                    <div class="w-full h-48 rounded-xl bg-gray-800 flex items-center justify-center text-gray-500 text-xs p-4 text-center">
                        Lokasi belum diatur oleh admin.
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="mt-10 border-t border-gray-800 pt-6 text-center text-gray-500 text-xs tracking-wider">
            © <?php echo e(date('Y')); ?> <span class="text-gray-400 font-semibold">AGRIS</span>. All rights reserved.
        </div>
    </div>
</footer>
<?php /**PATH D:\project\Agris\resources\views/components/footer.blade.php ENDPATH**/ ?>