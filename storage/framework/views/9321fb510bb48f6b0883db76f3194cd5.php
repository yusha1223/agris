<?php $__env->startSection('title', 'Stok Kosong - AGRIS'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto pt-3 pb-10">
    <div class="flex items-center gap-4 mb-8 px-4 md:px-0" data-aos="fade-up">
        <a href="<?php echo e(route('admin.produk.index')); ?>" class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 transition shadow-sm shrink-0">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-extrabold text-gray-800">Stok Kosong</h1>
            <p class="text-gray-500 text-sm">Daftar benih kosong</p>
        </div>
    </div>

    <div class="bg-white md:rounded-3xl shadow-sm border border-gray-100 overflow-hidden mx-4 md:mx-0" data-aos="fade-up" data-aos-delay="100">
        <div class="hidden md:block">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 text-gray-500 text-[10px] font-bold uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Informasi Produk</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Mutu</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php $__empty_1 = true; $__currentLoopData = $produks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl bg-gray-100 overflow-hidden shrink-0">
                                    <?php if($item->fotoProduk): ?>
                                        <img src="<?php echo e(asset('storage/' . $item->fotoProduk)); ?>" class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <div class="flex items-center justify-center h-full text-gray-300 text-[8px]">NO IMG</div>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <span class="font-bold text-gray-800 block"><?php echo e($item->namaProduk); ?></span>
                                    <span class="text-xs text-red-500 font-bold">Stok: <?php echo e($item->stok); ?> Karung</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 uppercase text-xs font-bold text-gray-500"><?php echo e($item->kategori->jenisKategori); ?></td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full bg-red-50 text-red-600 uppercase text-[10px] font-bold">
                                <?php echo e($item->kategori->mutu); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-400 text-xs italic">Dihapus <?php echo e($item->deleted_at->diffForHumans()); ?></td>
                        <td class="px-6 py-3 text-right">
                            <a href="<?php echo e(route('admin.produk.edit', $item->id)); ?>" class="inline-block bg-blue-50 text-blue-600 px-4 py-2 rounded-xl text-xs font-bold hover:bg-blue-600 hover:text-white transition">
                                Edit
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="py-20 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fa-solid fa-circle-check text-5xl text-[#58CC02]/20 mb-4"></i>
                                <p class="text-gray-400 font-medium">Tidak Ada Stok Benih yang Habis</p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="md:hidden divide-y divide-gray-100">
            <?php $__empty_1 = true; $__currentLoopData = $produks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="p-4 flex flex-col gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-gray-100 overflow-hidden shrink-0">
                        <?php if($item->fotoProduk): ?>
                            <img src="<?php echo e(asset('storage/' . $item->fotoProduk)); ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="flex items-center justify-center h-full text-gray-300 text-[10px]">NO IMG</div>
                        <?php endif; ?>
                    </div>
                    <div class="flex-1">
                        <span class="font-bold text-gray-800 block text-sm"><?php echo e($item->namaProduk); ?></span>
                        <div class="flex gap-2 mt-1">
                            <span class="text-[9px] font-bold uppercase text-gray-500 bg-gray-100 px-2 py-0.5 rounded">
                                <?php echo e($item->kategori->jenisKategori); ?>

                            </span>
                            <span class="text-[9px] font-bold uppercase text-red-600 bg-red-50 px-2 py-0.5 rounded">
                                <?php echo e($item->kategori->mutu); ?>

                            </span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between bg-gray-50 p-3 rounded-xl">
                    <div>
                        <p class="text-[10px] text-gray-400 uppercase font-bold">Status</p>
                        <p class="text-xs text-red-500 font-bold">Stok <?php echo e($item->stok); ?> · <span class="text-gray-400 font-normal italic"><?php echo e($item->deleted_at->diffForHumans()); ?></span></p>
                    </div>
                    <a href="<?php echo e(route('admin.produk.edit', $item->id)); ?>" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-xs font-bold shadow-sm">
                        Edit
                    </a>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="py-16 text-center px-4">
                <i class="fa-solid fa-circle-check text-5xl text-[#58CC02]/20 mb-4"></i>
                <p class="text-gray-400 text-sm font-medium">Tidak Ada Stok Benih yang Habis</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\project\Agris\resources\views/admin/produk/trash.blade.php ENDPATH**/ ?>