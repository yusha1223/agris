<?php $__env->startSection('title', 'Detail Produk - AGRIS'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-5xl mx-auto pt-4 pb-12">
    <div class="flex items-center justify-between mb-6 px-4 md:px-0" data-aos="fade-up">
        <div class="flex items-center gap-3">
            <a href="<?php echo e(route('admin.produk.index')); ?>" class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 transition shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h1 class="text-xl font-bold text-gray-800">Detail Produk</h1>
        </div>

        <span class="<?php echo e($item->stok > 0 ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600'); ?> px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wide">
            <?php echo e($item->stok > 0 ? 'Tersedia' : 'Stok Habis'); ?>

        </span>
    </div>

    <div class="bg-white rounded-3xl border border-gray-200 overflow-hidden mx-4 md:mx-0 shadow-sm" data-aos="fade-up" data-aos-delay="100">
        <div class="flex flex-col lg:flex-row">
            <div class="lg:w-2/5 bg-gray-50 p-4 md:p-8 flex flex-col items-center justify-start border-b lg:border-b-0 lg:border-r border-gray-100" data-aos="fade-right" data-aos-delay="200">
                <div class="w-full rounded-2xl overflow-hidden bg-white">
                    <?php if($item->fotoProduk): ?>
                        <img src="<?php echo e(asset('storage/' . $item->fotoProduk)); ?>" class="w-full h-auto max-h-125 object-contain mx-auto">
                    <?php else: ?>
                        <div class="flex flex-col items-center justify-center aspect-square text-gray-300">
                            <i class="fa-solid fa-image text-6xl mb-2"></i>
                            <p class="text-xs font-medium">Tidak ada foto</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="lg:w-3/5 p-8 md:p-10" data-aos="fade-left" data-aos-delay="200">
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="text-[10px] font-bold uppercase text-gray-800 bg-gray-800/10 px-3 py-1 rounded-lg">
                        <?php echo e($item->kategori->jenisKategori); ?>

                    </span>
                    <span class="text-[10px] font-bold uppercase text-gray-800 bg-gray-800/10 px-3 py-1 rounded-lg">
                        <?php echo e($item->kategori->karung); ?> Kg
                    </span>
                    <span class="text-[10px] font-bold uppercase text-gray-800 bg-gray-800/10 px-3 py-1 rounded-lg">
                        <?php echo e($item->kategori->mutu); ?>

                    </span>
                </div>

                <h2 class="text-3xl font-bold text-gray-800 mb-2"><?php echo e($item->namaProduk); ?></h2>

                <div class="flex items-baseline gap-1 mb-8">
                    <span class="text-3xl font-bold text-[#58CC02]">Rp <?php echo e(number_format($item->harga, 0, ',', '.')); ?></span>
                    <span class="text-gray-400 font-medium">/ Karung</span>
                </div>

                <div class="grid grid-cols-2 gap-6 mb-8">
                    <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100">
                        <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1">Stok Tersedia</p>
                        <p class="text-xl font-bold <?php echo e($item->stok > 0 ? 'text-gray-700' : 'text-red-500'); ?>">
                            <?php echo e($item->stok); ?> <span class="text-sm font-medium">Karung</span>
                        </p>
                    </div>
                    <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100">
                        <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1">Terakhir Update</p>
                        <p class="text-sm font-bold text-gray-700"><?php echo e($item->updated_at->format('d M Y')); ?></p>
                    </div>
                </div>

                <div class="mb-8">
                    <h4 class="text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                        Deskripsi Produk
                    </h4>
                    <p class="text-gray-600 leading-relaxed text-sm whitespace-pre-line">
                        <?php echo e($item->deskripsi ?? 'Tidak ada deskripsi untuk produk ini.'); ?>

                    </p>
                </div>

                <div class="flex gap-3">
                    <a href="<?php echo e(route('admin.produk.edit', $item->id)); ?>" class="flex-1 py-3 flex items-center justify-center rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold transition shadow-sm">
                        <i class="fa-solid fa-pen-to-square mr-2"></i> Ubah data produk
                    </a>

                    <button type="button" onclick="openModal('modalHapus')" class="px-6 py-3 flex items-center justify-center rounded-xl bg-red-50 text-red-600 hover:bg-red-600 hover:text-white font-bold transition border border-red-100 shadow-sm">
                        <i class="fa-solid fa-trash-can mr-2"></i> Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if (isset($component)) { $__componentOriginal9f64f32e90b9102968f2bc548315018c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f64f32e90b9102968f2bc548315018c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modal','data' => ['id' => 'modalHapus','title' => 'Konfirmasi','message' => 'Apakah anda yakin ingin menghapus produk?','confirmText' => 'Iya','cancelText' => 'Batal','confirmId' => 'btnConfirmDelete','cancelId' => 'btnCancelDelete']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'modalHapus','title' => 'Konfirmasi','message' => 'Apakah anda yakin ingin menghapus produk?','confirmText' => 'Iya','cancelText' => 'Batal','confirmId' => 'btnConfirmDelete','cancelId' => 'btnCancelDelete']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9f64f32e90b9102968f2bc548315018c)): ?>
<?php $attributes = $__attributesOriginal9f64f32e90b9102968f2bc548315018c; ?>
<?php unset($__attributesOriginal9f64f32e90b9102968f2bc548315018c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9f64f32e90b9102968f2bc548315018c)): ?>
<?php $component = $__componentOriginal9f64f32e90b9102968f2bc548315018c; ?>
<?php unset($__componentOriginal9f64f32e90b9102968f2bc548315018c); ?>
<?php endif; ?>

<form id="delete-form" action="<?php echo e(route('admin.produk.destroy', $item->id)); ?>" method="POST" class="hidden">
    <?php echo csrf_field(); ?>
    <?php echo method_field('DELETE'); ?>
</form>

<script>
    document.getElementById('btnConfirmDelete').addEventListener('click', function() {
        this.disabled = true;
        document.getElementById('delete-form').submit();
    });

    document.getElementById('btnCancelDelete').addEventListener('click', function() {
        closeModal('modalHapus');
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\project\Agris\resources\views/admin/produk/show.blade.php ENDPATH**/ ?>