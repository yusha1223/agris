<?php $__env->startSection('title', 'Daftar Produk - AGRIS'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto pt-5 pb-12 px-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4" data-aos="fade-up">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Daftar Produk</h1>
            <p class="text-gray-500 text-sm">Cari dan pilih produk berdasarkan kategori yang tersedia</p>
        </div>
    </div>

    <div class="bg-white p-4 md:p-5 rounded-2xl shadow-sm border border-gray-100 mb-8" data-aos="fade-up" data-aos-delay="100">
        <form action="<?php echo e(route('agen.produk.index')); ?>" method="GET" class="flex flex-col md:flex-row items-end gap-4">
            <?php if(request('search')): ?>
                <input type="hidden" name="search" value="<?php echo e(request('search')); ?>">
            <?php endif; ?>
            <div class="w-full md:flex-1">
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1 ml-1">Jenis</label>
                <select name="jenis" class="w-full px-4 py-2.5 rounded-xl border border-gray-100 bg-gray-50 outline-none focus:ring-2 focus:ring-[#58CC02] text-sm cursor-pointer appearance-none">
                    <option value="">Semua Jenis</option>
                    <?php $__currentLoopData = $daftarJenis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $j): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($j); ?>" <?php echo e(request('jenis') == $j ? 'selected' : ''); ?>><?php echo e(strtoupper($j)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="w-full md:flex-1">
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1 ml-1">Mutu</label>
                <select name="mutu" class="w-full px-4 py-2.5 rounded-xl border border-gray-100 bg-gray-50 outline-none focus:ring-2 focus:ring-[#58CC02] text-sm cursor-pointer appearance-none">
                    <option value="">Semua Mutu</option>
                    <?php $__currentLoopData = $daftarMutu; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($m); ?>" <?php echo e(request('mutu') == $m ? 'selected' : ''); ?>>MUTU <?php echo e(strtoupper($m)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="w-full md:flex-1">
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1 ml-1">Isi Karung</label>
                <select name="karung" class="w-full px-4 py-2.5 rounded-xl border border-gray-100 bg-gray-50 outline-none focus:ring-2 focus:ring-[#58CC02] text-sm cursor-pointer appearance-none">
                    <option value="">Semua Ukuran</option>
                    <?php $__currentLoopData = $daftarKarung; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($k); ?>" <?php echo e(request('karung') == $k ? 'selected' : ''); ?>><?php echo e($k); ?> Kg</option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="w-full md:w-auto">
                <button type="submit" class="w-full md:w-auto bg-gray-800 hover:bg-black text-white px-8 py-2.5 rounded-xl transition font-bold text-sm flex items-center justify-center shadow-sm">
                    <i class="fa-solid fa-filter mr-2"></i> Filter
                </button>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-2 md:gap-3">
        <?php $__empty_1 = true; $__currentLoopData = $produks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div id="product-card-<?php echo e($item->id); ?>" class="group bg-white rounded-lg overflow-hidden border border-gray-100 shadow-sm hover:shadow-md transition flex flex-col h-full relative" data-aos="fade-up" data-aos-delay="<?php echo e(($loop->iteration - 1) * 50); ?>">
            <a href="<?php echo e(route('agen.produk.show', $item->id)); ?>" class="relative aspect-square bg-gray-50 flex items-center justify-center overflow-hidden">
                <?php if($item->fotoProduk): ?>
                    <img src="<?php echo e(asset('storage/' . $item->fotoProduk)); ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" alt="<?php echo e($item->namaProduk); ?>">
                <?php else: ?>
                    <div class="flex items-center justify-center h-full text-gray-200">
                        <i class="fa-solid fa-image text-4xl"></i>
                    </div>
                <?php endif; ?>
                <div id="out-of-stock-badge-<?php echo e($item->id); ?>" class="absolute inset-0 bg-black/40 flex items-center justify-center z-10 <?php echo e($item->stok <= 0 ? '' : 'hidden'); ?>">
                    <span class="bg-red-500 text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase">Stok Habis</span>
                </div>
            </a>

            <div class="p-2.5 flex flex-col grow">
                <div class="flex flex-wrap gap-1 mb-2">
                    <span class="text-[9px] font-bold uppercase text-gray-800 bg-gray-800/10 px-1.5 py-0.5 rounded"><?php echo e($item->kategori->jenisKategori); ?></span>
                    <span class="text-[9px] font-bold uppercase text-gray-800 bg-gray-800/10 px-1.5 py-0.5 rounded"><?php echo e($item->kategori->karung); ?> Kg</span>
                    <span class="text-[9px] font-bold uppercase text-gray-800 bg-gray-800/10 px-1.5 py-0.5 rounded"><?php echo e($item->kategori->mutu); ?></span>
                </div>

                <a href="<?php echo e(route('agen.produk.show', $item->id)); ?>" class="grow">
                    <h3 class="text-gray-800 text-15 font-normal line-clamp-2 leading-snug mb-1 min-h-9.5"><?php echo e($item->namaProduk); ?></h3>
                    <p class="text-gray-900 font-bold text-base mb-0.5">Rp <?php echo e(number_format($item->harga, 0, ',', '.')); ?></p>
                </a>

                <div class="mt-auto">
                    <div class="flex items-center justify-between pt-2 border-t border-gray-100 flex-wrap gap-1 mb-3">
                        <div class="flex items-center gap-1 text-[11px] text-gray-500 truncate max-w-[70%]">
                            <div class="bg-violet-600 text-white rounded w-3.5 h-3.5 flex items-center justify-center text-[8px] shrink-0">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <span class="truncate font-medium text-gray-500">Tersedia</span>
                        </div>
                        <span id="product-stock-<?php echo e($item->id); ?>" class="text-[10px] font-bold <?php echo e($item->stok > 5 ? 'text-gray-500' : 'text-orange-500'); ?> uppercase tracking-tight shrink-0">Stok: <?php echo e($item->stok); ?></span>
                    </div>

                    <form class="add-to-cart-form">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="produkId" value="<?php echo e($item->id); ?>">
                        <input type="hidden" name="jumlah" value="1">
                        <button id="product-btn-<?php echo e($item->id); ?>" type="button" onclick="addToCart(this)" <?php echo e($item->stok <= 0 ? 'disabled' : ''); ?> class="w-full <?php echo e($item->stok <= 0 ? 'bg-gray-300 cursor-not-allowed' : 'bg-[#58CC02] hover:bg-[#46A302]'); ?> text-white py-2 rounded-xl transition font-bold text-xs flex items-center justify-center gap-2 shadow-sm">
                            <i class="fa-solid fa-cart-plus"></i> <span class="btn-text"><?php echo e($item->stok <= 0 ? 'Habis' : 'Tambah Pesanan'); ?></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-span-full py-20 text-center bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
            <i class="fa-solid fa-box-open text-5xl text-gray-200 mb-4"></i>
            <p class="text-gray-400 font-bold uppercase text-sm tracking-widest">Produk tidak ditemukan.</p>
        </div>
        <?php endif; ?>
    </div>

    <div class="mt-10 px-4 md:px-0">
        <?php echo e($produks->links()); ?>

    </div>
</div>

<?php if (isset($component)) { $__componentOriginal9f64f32e90b9102968f2bc548315018c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f64f32e90b9102968f2bc548315018c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modal','data' => ['id' => 'modalAksesMitra','title' => 'Anda Belum Bermitra?','message' => 'Anda harus menjadi mitra aktif untuk menambahkan produk ke keranjang.','confirmText' => 'Baik','cancelText' => 'Batal','confirmId' => 'btnConfirmMitra','cancelId' => 'btnCancelMitra']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'modalAksesMitra','title' => 'Anda Belum Bermitra?','message' => 'Anda harus menjadi mitra aktif untuk menambahkan produk ke keranjang.','confirmText' => 'Baik','cancelText' => 'Batal','confirmId' => 'btnConfirmMitra','cancelId' => 'btnCancelMitra']); ?>
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

<script>
function showNotification(title, message, type) {
    const container = document.querySelector('.fixed.bottom-5.right-5');
    if (!container) return;

    const alertDiv = document.createElement('div');
    const isSuccess = type === 'success';

    alertDiv.className = `alert-info flex items-center w-full max-w-xs p-4 rounded-2xl shadow-xl border ${isSuccess ? 'border-green-200 bg-green-50' : 'border-red-200 bg-red-50'}`;
    alertDiv.innerHTML = `
        <div class="inline-flex items-center justify-center shrink-0 w-10 h-10 rounded-full ${isSuccess ? 'bg-green-600' : 'bg-red-600'} text-white">
            <i class="fa-solid ${isSuccess ? 'fa-check' : 'fa-xmark'} text-sm"></i>
        </div>
        <div class="ms-3">
            <div class="text-sm font-bold ${isSuccess ? 'text-green-800' : 'text-red-800'}">${title}</div>
            <div class="text-xs ${isSuccess ? 'text-green-700' : 'text-red-700'} mt-0.5">${message}</div>
        </div>
    `;

    container.appendChild(alertDiv);
    alertDiv.style.opacity = '0';
    alertDiv.style.transform = 'translateX(20px)';
    alertDiv.style.transition = "all 0.5s ease";

    setTimeout(() => {
        alertDiv.style.opacity = '1';
        alertDiv.style.transform = 'translateX(0)';
    }, 100);

    setTimeout(() => {
        alertDiv.style.opacity = '0';
        alertDiv.style.transform = 'translateX(20px)';
        setTimeout(() => { alertDiv.remove(); }, 500);
    }, 4000);
}

function addToCart(btn) {
    const isMitra = <?php echo e(auth()->user()->isActive == 1 ? 'true' : 'false'); ?>;

    if (!isMitra) {
        openModal('modalAksesMitra');
        return;
    }

    let form = btn.closest('.add-to-cart-form');
    let formData = new FormData(form);

    fetch("<?php echo e(route('agen.produk.add-to-cart')); ?>", {
        method: "POST",
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (response.status === 403) {
            openModal('modalAksesMitra');
            throw new Error('Unauthorized');
        }
        return response.json();
    })
    .then(data => {
        if (data.cartCount !== undefined) updateCartBadge(data.cartCount);
        showNotification('Informasi', data.message, 'success');
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Gagal', 'Terjadi kesalahan sistem', 'error');
    });
}

document.getElementById('btnConfirmMitra').addEventListener('click', () => closeModal('modalAksesMitra'));
document.getElementById('btnCancelMitra').addEventListener('click', () => closeModal('modalAksesMitra'));

if (window.Echo) {
    window.Echo.channel('produk-channel')
        .listen('.ProdukUpdated', (e) => {
            const prod = e.produk;

            const stockEl = document.getElementById(`product-stock-${prod.id}`);
            if (stockEl) {
                stockEl.textContent = `Stok: ${prod.stok}`;
                if (prod.stok > 5) {
                    stockEl.className = "text-[10px] font-bold text-gray-500 uppercase tracking-tight shrink-0";
                } else {
                    stockEl.className = "text-[10px] font-bold text-orange-500 uppercase tracking-tight shrink-0";
                }
            }

            const badgeEl = document.getElementById(`out-of-stock-badge-${prod.id}`);
            if (badgeEl) {
                if (prod.stok <= 0) {
                    badgeEl.classList.remove('hidden');
                } else {
                    badgeEl.classList.add('hidden');
                }
            }

            const btnEl = document.getElementById(`product-btn-${prod.id}`);
            if (btnEl) {
                const textEl = btnEl.querySelector('.btn-text');
                if (prod.stok <= 0) {
                    btnEl.disabled = true;
                    btnEl.className = "w-full bg-gray-300 cursor-not-allowed text-white py-2 rounded-xl transition font-bold text-xs flex items-center justify-center gap-2 shadow-sm";
                    if (textEl) textEl.textContent = 'Habis';
                } else {
                    btnEl.disabled = false;
                    btnEl.className = "w-full bg-[#58CC02] hover:bg-[#46A302] text-white py-2 rounded-xl transition font-bold text-xs flex items-center justify-center gap-2 shadow-sm";
                    if (textEl) textEl.textContent = 'Tambah Pesanan';
                }
            }
        });
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.agen', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\project\Agris\resources\views/agen/produk/index.blade.php ENDPATH**/ ?>