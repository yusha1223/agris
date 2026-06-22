<?php $__env->startSection('title', 'Riwayat Transaksi - AGRIS'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-5xl mx-auto pt-5 pb-12 px-4 sm:px-6">
    <div class="mb-8" data-aos="fade-up">
        <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">Daftar Transaksi</h1>
        <p class="text-gray-500 text-xs md:text-sm mt-1">Pantau status pesanan dan riwayat belanja Anda dengan mudah</p>
    </div>

    <?php if(session('success')): ?>
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-xl mb-6 text-sm font-bold flex items-center gap-3" data-aos="fade-up">
            <i class="fa-solid fa-circle-check text-lg text-green-500"></i>
            <span><?php echo e(session('success')); ?></span>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-xl mb-6 text-sm font-bold flex items-center gap-3" data-aos="fade-up">
            <i class="fa-solid fa-triangle-exclamation text-lg text-red-500"></i>
            <span><?php echo e(session('error')); ?></span>
        </div>
    <?php endif; ?>

    <?php if(session('info')): ?>
        <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-4 rounded-xl mb-6 text-sm font-bold flex items-center gap-3" data-aos="fade-up">
            <i class="fa-solid fa-circle-info text-lg text-blue-500"></i>
            <span><?php echo e(session('info')); ?></span>
        </div>
    <?php endif; ?>

    <?php
        $activeTab = $activeTab ?? 'transaksi';
    ?>

    <div class="flex bg-gray-100 rounded-2xl p-1 mb-8 max-w-xs md:max-w-sm" data-aos="fade-up" data-aos-delay="100">
        <a href="<?php echo e(route('agen.pesanan.index', ['tab' => 'transaksi'])); ?>" class="flex-1 text-center py-2 px-3 rounded-xl text-xs font-black transition <?php echo e($activeTab === 'transaksi' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-900'); ?>">
            Transaksi Saya
        </a>
        <a href="<?php echo e(route('agen.pesanan.index', ['tab' => 'keuangan'])); ?>" class="flex-1 text-center py-2 px-3 rounded-xl text-xs font-black transition <?php echo e($activeTab === 'keuangan' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-900'); ?>">
            Riwayat Transaksi
        </a>
    </div>

    <div id="orders-list-container">
        <?php if($activeTab === 'transaksi'): ?>
        <?php $activeStatus = $status ?? 'all'; ?>
        <div class="flex overflow-x-auto bg-white rounded-2xl border border-gray-200 p-1 mb-8 shadow-sm scrollbar-none">
            <a href="<?php echo e(route('agen.pesanan.index', ['tab' => 'transaksi', 'status' => 'all'])); ?>" class="flex-1 text-center py-3 px-4 rounded-xl text-xs font-black transition-all whitespace-nowrap <?php echo e($activeStatus === 'all' ? 'bg-[#58CC02] text-white shadow-sm' : 'text-gray-500 hover:text-gray-800'); ?>">
                Semua
            </a>
            <a href="<?php echo e(route('agen.pesanan.index', ['tab' => 'transaksi', 'status' => 'diproses'])); ?>" class="flex-1 text-center py-3 px-4 rounded-xl text-xs font-black transition-all whitespace-nowrap <?php echo e($activeStatus === 'diproses' ? 'bg-[#58CC02] text-white shadow-sm' : 'text-gray-500 hover:text-gray-800'); ?>">
                Dikemas
            </a>
            <a href="<?php echo e(route('agen.pesanan.index', ['tab' => 'transaksi', 'status' => 'dikirim'])); ?>" class="flex-1 text-center py-3 px-4 rounded-xl text-xs font-black transition-all whitespace-nowrap <?php echo e($activeStatus === 'dikirim' ? 'bg-[#58CC02] text-white shadow-sm' : 'text-gray-500 hover:text-gray-800'); ?>">
                Dikirim
            </a>
        </div>

        <?php if($pesanans->isEmpty()): ?>
            <div class="py-24 text-center bg-white rounded-3xl border border-gray-100 shadow-sm px-4" data-aos="zoom-in">
                <i class="fa-solid fa-receipt text-5xl text-gray-200 mb-4"></i>
                <p class="text-gray-400 font-extrabold uppercase text-xs tracking-widest mb-4">Tidak Ada Transaksi Aktif.</p>
                <a href="<?php echo e(route('agen.produk.index')); ?>" class="inline-block bg-[#58CC02] text-white px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-[#46A302] transition">
                    Mulai Belanja
                </a>
            </div>
        <?php else: ?>
            <div class="space-y-6">
                <?php $__currentLoopData = $pesanans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pesanan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-4 md:p-6 hover:shadow-md transition duration-200" data-aos="fade-up" data-aos-delay="<?php echo e($loop->index * 50); ?>">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-gray-50 mb-4">
                            <div class="flex items-center gap-3">
                                <span class="text-[10px] md:text-xs font-black text-gray-400 font-mono">No. <?php echo e($loop->iteration); ?></span>
                                <span class="text-xs font-medium text-gray-300">•</span>
                                <span class="text-[10px] md:text-xs text-gray-500 font-bold"><?php echo e(\Carbon\Carbon::parse($pesanan->created_at)->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d F Y H:i')); ?> WIB</span>
                            </div>
                            <div>
                                <?php if($pesanan->status_pesanan === 'diproses'): ?>
                                    <span class="bg-blue-50 text-blue-600 border border-blue-100 px-3.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider">Dikemas</span>
                                <?php elseif($pesanan->status_pesanan === 'dikirim'): ?>
                                    <span class="bg-[#58CC02]/5 text-[#58CC02] border border-[#58CC02]/20 px-3.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider">Dikirim</span>
                                <?php elseif($pesanan->status_pesanan === 'selesai'): ?>
                                    <span class="bg-green-50 text-green-600 border border-green-100 px-3.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider">Selesai</span>
                                <?php else: ?>
                                    <span class="bg-red-50 text-red-600 border border-red-100 px-3.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider">Dibatalkan</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                            <div class="grow min-w-0">
                                <?php $firstDetail = $pesanan->detailPesanans->first(); ?>
                                <?php if($firstDetail && $firstDetail->produk): ?>
                                    <div class="flex items-center gap-4">
                                        <div class="w-14 h-14 md:w-16 md:h-16 bg-gray-50 rounded-2xl overflow-hidden border border-gray-100 flex items-center justify-center p-1 shrink-0">
                                            <?php if($firstDetail->produk->fotoProduk): ?>
                                                <img src="<?php echo e(asset('storage/' . $firstDetail->produk->fotoProduk)); ?>" class="w-full h-full object-cover rounded-xl">
                                            <?php else: ?>
                                                <i class="fa-solid fa-image text-xl text-gray-300"></i>
                                            <?php endif; ?>
                                        </div>
                                        <div class="min-w-0">
                                            <h4 class="font-extrabold text-gray-800 text-xs md:text-sm truncate"><?php echo e($firstDetail->produk->namaProduk); ?></h4>
                                            <p class="text-[10px] md:text-xs text-gray-400 font-bold mt-1">
                                                <?php echo e($firstDetail->jumlahPesanan); ?> barang x Rp <?php echo e(number_format($firstDetail->harga_satuan, 0, ',', '.')); ?>

                                            </p>
                                            <?php if($pesanan->detailPesanans->count() > 1): ?>
                                                <p class="text-[10px] md:text-xs text-[#58CC02] font-black mt-1.5 flex items-center gap-1">
                                                    <i class="fa-solid fa-layer-group text-[10px]"></i> +<?php echo e($pesanan->detailPesanans->count() - 1); ?> produk lainnya
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <p class="text-gray-400 text-xs md:text-sm font-medium">Produk telah dihapus</p>
                                <?php endif; ?>
                            </div>

                            <div class="border-t border-gray-100 md:hidden my-2"></div>

                            <div class="flex flex-col sm:flex-row sm:items-center justify-between md:justify-end gap-4 shrink-0 w-full md:w-auto">
                                <div class="text-left md:text-right">
                                    <span class="text-[9px] font-black text-gray-400 block uppercase tracking-wider">Total Tagihan</span>
                                    <span class="font-black text-gray-900 text-base md:text-lg">
                                        Rp <?php echo e(number_format($pesanan->total_harga, 0, ',', '.')); ?>

                                    </span>
                                </div>
                                <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
                                    <a href="<?php echo e(route('agen.pesanan.show', $pesanan->id)); ?>" class="flex-1 sm:flex-initial text-center border border-gray-200 hover:border-gray-300 text-gray-700 bg-white px-3.5 py-2 rounded-xl text-xs font-black transition">
                                        Detail
                                    </a>

                                    <?php if($pesanan->status_pesanan === 'diproses'): ?>
                                        <button type="button" onclick="confirmBatal('<?php echo e(route('agen.pesanan.batal', $pesanan->id)); ?>')" class="flex-1 sm:flex-initial text-center border border-red-200 text-red-600 hover:bg-red-50 px-3.5 py-2 rounded-xl text-xs font-black transition">
                                            Batal
                                        </button>
                                    <?php endif; ?>

                                    <?php if($pesanan->status_pesanan === 'dikirim'): ?>
                                        <?php
                                            $bStatus = $biteshipStatuses[$pesanan->id] ?? null;
                                            $canConfirm = in_array($bStatus, ['dropping_off', 'droppingOff', 'delivered']);
                                        ?>
                                        <?php if($canConfirm): ?>
                                        <button type="button" onclick="confirmDiterima('<?php echo e(route('agen.pesanan.diterima', $pesanan->id)); ?>')" class="flex-1 sm:flex-initial w-full text-center bg-blue-600 hover:bg-blue-700 text-white px-3.5 py-2 rounded-xl text-xs font-black transition shadow-sm">
                                            Diterima
                                        </button>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <?php $subTab = $subTab ?? 'selesai'; ?>
        <div class="flex overflow-x-auto bg-white rounded-2xl border border-gray-200 p-1 mb-8 shadow-sm scrollbar-none" data-aos="fade-up" data-aos-delay="100">
            <a href="<?php echo e(route('agen.pesanan.index', ['tab' => 'keuangan', 'sub' => 'selesai'])); ?>" class="flex-1 text-center py-3 px-4 rounded-xl text-xs font-black transition-all whitespace-nowrap <?php echo e($subTab === 'selesai' ? 'bg-[#58CC02] text-white shadow-sm' : 'text-gray-500 hover:text-gray-800'); ?>">
                Selesai
            </a>
            <a href="<?php echo e(route('agen.pesanan.index', ['tab' => 'keuangan', 'sub' => 'batal'])); ?>" class="flex-1 text-center py-3 px-4 rounded-xl text-xs font-black transition-all whitespace-nowrap <?php echo e($subTab === 'batal' ? 'bg-[#58CC02] text-white shadow-sm' : 'text-gray-500 hover:text-gray-800'); ?>">
                Dibatalkan
            </a>
        </div>

        <?php if(empty($pesanans) || count($pesanans) === 0): ?>
                <div class="py-24 text-center bg-white rounded-3xl border border-gray-100 shadow-sm px-4" data-aos="zoom-in">
                    <i class="fa-solid fa-receipt text-5xl text-gray-200 mb-4"></i>
                    <p class="text-gray-400 font-extrabold uppercase text-xs tracking-widest">Tidak Ada Transaksi.</p>
                </div>
            <?php else: ?>
                <div class="space-y-6">
                    <?php $__currentLoopData = $pesanans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pesanan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-4 md:p-6 hover:shadow-md transition duration-200" data-aos="fade-up" data-aos-delay="<?php echo e($loop->index * 50); ?>">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-gray-50 mb-4">
                                <div class="flex items-center gap-3">
                                    <span class="text-[10px] md:text-xs font-black text-gray-400 font-mono">No. <?php echo e($loop->iteration); ?></span>
                                    <span class="text-xs font-medium text-gray-300">•</span>
                                    <span class="text-[10px] md:text-xs text-gray-500 font-bold"><?php echo e(\Carbon\Carbon::parse($pesanan->created_at)->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d F Y H:i')); ?> WIB</span>
                                </div>
                                <div>
                                    <?php if($pesanan->status_pesanan === 'selesai'): ?>
                                        <span class="bg-green-50 text-green-600 border border-green-100 px-3.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider">Selesai</span>
                                    <?php else: ?>
                                        <span class="bg-red-50 text-red-600 border border-red-100 px-3.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider">Dibatalkan</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                                <div class="grow min-w-0">
                                    <?php $firstDetail = $pesanan->detailPesanans->first(); ?>
                                    <?php if($firstDetail && $firstDetail->produk): ?>
                                        <div class="flex items-center gap-4">
                                            <div class="w-14 h-14 md:w-16 md:h-16 bg-gray-50 rounded-2xl overflow-hidden border border-gray-100 flex items-center justify-center p-1 shrink-0">
                                                <?php if($firstDetail->produk->fotoProduk): ?>
                                                    <img src="<?php echo e(asset('storage/' . $firstDetail->produk->fotoProduk)); ?>" class="w-full h-full object-cover rounded-xl">
                                                <?php else: ?>
                                                    <i class="fa-solid fa-image text-xl text-gray-300"></i>
                                                <?php endif; ?>
                                            </div>
                                            <div class="min-w-0">
                                                <h4 class="font-extrabold text-gray-800 text-xs md:text-sm truncate"><?php echo e($firstDetail->produk->namaProduk); ?></h4>
                                                <p class="text-[10px] md:text-xs text-gray-400 font-bold mt-1">
                                                    <?php echo e($firstDetail->jumlahPesanan); ?> barang x Rp <?php echo e(number_format($firstDetail->harga_satuan, 0, ',', '.')); ?>

                                                </p>
                                                <?php if($pesanan->detailPesanans->count() > 1): ?>
                                                    <p class="text-[10px] md:text-xs text-[#58CC02] font-black mt-1.5 flex items-center gap-1">
                                                        <i class="fa-solid fa-layer-group text-[10px]"></i> +<?php echo e($pesanan->detailPesanans->count() - 1); ?> produk lainnya
                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <p class="text-gray-400 text-xs md:text-sm font-medium">Produk telah dihapus</p>
                                    <?php endif; ?>
                                </div>

                                <div class="border-t border-gray-100 md:hidden my-2"></div>

                                <div class="flex flex-col sm:flex-row sm:items-center justify-between md:justify-end gap-4 shrink-0 w-full md:w-auto">
                                    <div class="text-left md:text-right">
                                        <span class="text-[9px] font-black text-gray-400 block uppercase tracking-wider">Total Tagihan</span>
                                        <span class="font-black text-gray-900 text-base md:text-lg">
                                            Rp <?php echo e(number_format($pesanan->total_harga, 0, ',', '.')); ?>

                                        </span>
                                    </div>
                                    <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
                                        <a href="<?php echo e(route('agen.pesanan.show', $pesanan->id)); ?>" class="flex-1 sm:flex-initial text-center border border-gray-200 hover:border-gray-300 text-gray-700 bg-white px-3.5 py-2 rounded-xl text-xs font-black transition">
                                            Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
    <?php endif; ?>
    </div>
</div>

<?php if (isset($component)) { $__componentOriginal9f64f32e90b9102968f2bc548315018c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f64f32e90b9102968f2bc548315018c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modal','data' => ['id' => 'modalConfirmDiterima','title' => 'Konfirmasi Penerimaan','message' => 'Apakah Anda yakin pesanan sudah sampai dan diterima dengan baik? Transaksi akan diselesaikan.','confirmText' => 'Iya','cancelText' => 'Batal','confirmId' => 'btnSubmitDiterima','cancelId' => 'btnCloseDiterima']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'modalConfirmDiterima','title' => 'Konfirmasi Penerimaan','message' => 'Apakah Anda yakin pesanan sudah sampai dan diterima dengan baik? Transaksi akan diselesaikan.','confirmText' => 'Iya','cancelText' => 'Batal','confirmId' => 'btnSubmitDiterima','cancelId' => 'btnCloseDiterima']); ?>
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

<?php if (isset($component)) { $__componentOriginal9f64f32e90b9102968f2bc548315018c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f64f32e90b9102968f2bc548315018c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modal','data' => ['id' => 'modalConfirmBatal','title' => 'Konfirmasi Pembatalan','message' => 'Apakah Anda yakin ingin membatalkan pesanan ini? Stok produk akan dikembalikan secara otomatis.','confirmText' => 'Ya','cancelText' => 'Batal','confirmId' => 'btnSubmitBatal','cancelId' => 'btnCloseBatal']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'modalConfirmBatal','title' => 'Konfirmasi Pembatalan','message' => 'Apakah Anda yakin ingin membatalkan pesanan ini? Stok produk akan dikembalikan secara otomatis.','confirmText' => 'Ya','cancelText' => 'Batal','confirmId' => 'btnSubmitBatal','cancelId' => 'btnCloseBatal']); ?>
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

<form id="formConfirmDiterima" method="POST" style="display: none;">
    <?php echo csrf_field(); ?>
</form>

<form id="formConfirmBatal" method="POST" style="display: none;">
    <?php echo csrf_field(); ?>
</form>

<script>
    window.confirmDiterima = function(actionUrl) {
        const form = document.getElementById('formConfirmDiterima');
        if (form) {
            form.action = actionUrl;
            openModal('modalConfirmDiterima');
        }
    };

    window.confirmBatal = function(actionUrl) {
        const form = document.getElementById('formConfirmBatal');
        if (form) {
            form.action = actionUrl;
            openModal('modalConfirmBatal');
        }
    };

    document.addEventListener('DOMContentLoaded', function() {
        const btnSubmitDiterima = document.getElementById('btnSubmitDiterima');
        if (btnSubmitDiterima) {
            btnSubmitDiterima.addEventListener('click', function() {
                const form = document.getElementById('formConfirmDiterima');
                if (form) form.submit();
            });
        }

        const btnCloseDiterima = document.getElementById('btnCloseDiterima');
        if (btnCloseDiterima) {
            btnCloseDiterima.addEventListener('click', function() {
                closeModal('modalConfirmDiterima');
            });
        }

        const btnSubmitBatal = document.getElementById('btnSubmitBatal');
        if (btnSubmitBatal) {
            btnSubmitBatal.addEventListener('click', function() {
                const form = document.getElementById('formConfirmBatal');
                if (form) form.submit();
            });
        }

        const btnCloseBatal = document.getElementById('btnCloseBatal');
        if (btnCloseBatal) {
            btnCloseBatal.addEventListener('click', function() {
                closeModal('modalConfirmBatal');
            });
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const activeOrderIds = <?php echo json_encode(
            $activeTab === 'keuangan'
                ? ($pesanans->pluck('id')->all() ?? [])
                : ($pesanans->pluck('id')->all() ?? [])
        , 15, 512) ?>;

        if (window.Echo && activeOrderIds.length > 0) {
            activeOrderIds.forEach(orderId => {
                window.Echo.channel('order.' + orderId)
                    .listen('.OrderStatusUpdated', (e) => {
                        console.log('Order status updated via Reverb on agent index page for order #' + orderId, e);

                        // Fetch the current page content and update the list without refreshing the page
                        fetch(window.location.href)
                            .then(response => response.text())
                            .then(html => {
                                const parser = new DOMParser();
                                const doc = parser.parseFromString(html, 'text/html');
                                const newContainer = doc.getElementById('orders-list-container');
                                const oldContainer = document.getElementById('orders-list-container');
                                if (newContainer && oldContainer) {
                                    oldContainer.innerHTML = newContainer.innerHTML;
                                }
                            })
                            .catch(err => console.error('Error fetching updated orders list:', err));
                    });
            });
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.agen', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\project\Agris\resources\views/agen/pesanan/index.blade.php ENDPATH**/ ?>