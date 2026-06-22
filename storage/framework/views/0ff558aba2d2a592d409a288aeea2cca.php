<?php $__env->startSection('title', 'Manajemen Transaksi - Admin AGRIS'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto pt-2 pb-10 px-4 md:px-0">

    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4" data-aos="fade-up">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Daftar Transaksi</h1>
            <p class="text-gray-500 text-sm">Kelola pesanan, pengiriman, dan status pembayaran pelanggan</p>
        </div>
    </div>

    <div class="flex border-b border-gray-200 mb-6 gap-2 text-xs md:text-sm font-bold" data-aos="fade-up" data-aos-delay="100">
        <a href="<?php echo e(route('admin.pesanan.index', ['tab' => 'aktif'])); ?>"
            class="pb-2.5 px-4 transition-all relative <?php echo e($activeTab === 'aktif' ? 'text-gray-950 after:absolute after:bottom-0 after:left-0 after:w-full after:h-0.5 after:bg-[#58CC02]' : 'text-gray-400 hover:text-gray-600'); ?>">
            Transaksi Aktif
        </a>
        <a href="<?php echo e(route('admin.pesanan.index', ['tab' => 'riwayat'])); ?>"
            class="pb-2.5 px-4 transition-all relative <?php echo e($activeTab === 'riwayat' ? 'text-gray-950 after:absolute after:bottom-0 after:left-0 after:w-full after:h-0.5 after:bg-[#58CC02]' : 'text-gray-400 hover:text-gray-600'); ?>">
            Riwayat Transaksi
        </a>
    </div>

    <div class="bg-white p-4 md:p-5 rounded-xl shadow-sm border border-gray-100 mb-8" data-aos="fade-up" data-aos-delay="200">
        <form action="<?php echo e(route('admin.pesanan.index')); ?>" method="GET" class="flex flex-col md:flex-row items-end gap-4">
            <input type="hidden" name="tab" value="<?php echo e($activeTab); ?>">

            <div class="w-full md:flex-1">
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1 ml-1">Cari</label>
                <div class="relative w-full">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari nomor, nama pelanggan..."
                        class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-gray-100 bg-gray-50 outline-none focus:ring-2 focus:ring-[#58CC02] text-sm transition">
                </div>
            </div>

            <div class="w-full md:w-52">
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1 ml-1">Status</label>
                <select name="status" onchange="this.form.submit()"
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-100 bg-gray-50 outline-none focus:ring-2 focus:ring-[#58CC02] text-sm cursor-pointer appearance-none">
                    <option value="all" <?php echo e(request('status') === 'all' ? 'selected' : ''); ?>>Semua Status</option>
                    <?php if($activeTab === 'aktif'): ?>
                        <option value="diproses" <?php echo e(request('status') === 'diproses' ? 'selected' : ''); ?>>Dikemas (Diproses)</option>
                        <option value="dikirim" <?php echo e(request('status') === 'dikirim' ? 'selected' : ''); ?>>Dikirim</option>
                    <?php else: ?>
                        <option value="selesai" <?php echo e(request('status') === 'selesai' ? 'selected' : ''); ?>>Selesai</option>
                        <option value="dibatalkan" <?php echo e(request('status') === 'dibatalkan' ? 'selected' : ''); ?>>Dibatalkan</option>
                    <?php endif; ?>
                </select>
            </div>

            <div class="w-full md:w-auto flex gap-2">
                <button type="submit" class="w-full md:w-auto bg-gray-800 hover:bg-black text-white px-8 py-2.5 rounded-xl transition font-bold text-sm flex items-center justify-center shadow-sm">
                    <i class="fa-solid fa-filter mr-2"></i> Filter
                </button>

                <?php if(request()->anyFilled(['search', 'status'])): ?>
                    <a href="<?php echo e(route('admin.pesanan.index', ['tab' => $activeTab])); ?>" class="text-center border border-gray-200 hover:bg-gray-50 text-gray-600 px-6 py-2.5 rounded-xl font-bold text-xs transition flex items-center justify-center">
                        Reset
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div id="orders-list-container">

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden hidden md:block">
            <table class="w-full text-left border-collapse table-fixed">
                <colgroup>
                    <col class="w-12">
                    <col class="w-36">
                    <col class="w-32">
                    <col class="w-32">
                    <col class="w-auto">
                    <col class="w-24">
                    <col class="w-24">
                    <col class="w-20">
                </colgroup>
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="py-3 px-3 text-xs font-black text-gray-400 uppercase tracking-wider">No.</th>
                        <th class="py-3 px-3 text-xs font-black text-gray-400 uppercase tracking-wider">Pelanggan</th>
                        <th class="py-3 px-3 text-xs font-black text-gray-400 uppercase tracking-wider">Tanggal</th>
                        <th class="py-3 px-3 text-xs font-black text-gray-400 uppercase tracking-wider">Total Tagihan</th>
                        <th class="py-3 px-3 text-xs font-black text-gray-400 uppercase tracking-wider">Opsi Pengiriman</th>
                        <th class="py-3 px-3 text-xs font-black text-gray-400 uppercase tracking-wider">Status Order</th>
                        <th class="py-3 px-3 text-xs font-black text-gray-400 uppercase tracking-wider">Status Bayar</th>
                        <th class="py-3 px-3 text-xs font-black text-gray-400 uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm font-bold text-gray-700">
                    <?php $__empty_1 = true; $__currentLoopData = $pesanans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pesanan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-3 px-3 text-xs font-black text-gray-500">
                                <?php echo e(($pesanans->firstItem() ?? 1) + $loop->index); ?>

                            </td>
                            <td class="py-3 px-3">
                                <div class="flex flex-col">
                                    <span class="truncate text-sm"><?php echo e($pesanan->user->namaLengkap ?? 'Dihapus'); ?></span>
                                    <span class="text-xs text-gray-400 font-bold mt-0.5 truncate"><?php echo e('@' . ($pesanan->user->username ?? 'user')); ?></span>
                                </div>
                            </td>
                            <td class="py-3 px-3 text-xs text-gray-500">
                                <?php echo e(\Carbon\Carbon::parse($pesanan->created_at)->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d M Y H:i')); ?> WIB
                            </td>
                            <td class="py-3 px-3 text-gray-900">
                                Rp <?php echo e(number_format($pesanan->total_harga, 0, ',', '.')); ?>

                            </td>
                            <td class="py-3 px-3">
                                <span class="block truncate text-xs text-gray-500" title="<?php echo e($pesanan->deskripsi); ?>"><?php echo e($pesanan->deskripsi); ?></span>
                            </td>
                            <td class="py-3 px-3">
                                <?php if($pesanan->status_pesanan === 'pending'): ?>
                                    <span class="bg-amber-50 text-amber-600 border border-amber-100 px-2 py-0.5 rounded-full text-[10px] uppercase font-black tracking-wide whitespace-nowrap">Menunggu</span>
                                <?php elseif($pesanan->status_pesanan === 'diproses'): ?>
                                    <span class="bg-blue-50 text-blue-600 border border-blue-100 px-2 py-0.5 rounded-full text-[10px] uppercase font-black tracking-wide whitespace-nowrap">Dikemas</span>
                                <?php elseif($pesanan->status_pesanan === 'dikirim'): ?>
                                    <span class="bg-purple-50 text-purple-600 border border-purple-100 px-2 py-0.5 rounded-full text-[10px] uppercase font-black tracking-wide whitespace-nowrap">Dikirim</span>
                                <?php elseif($pesanan->status_pesanan === 'selesai'): ?>
                                    <span class="bg-green-50 text-green-600 border border-green-100 px-2 py-0.5 rounded-full text-[10px] uppercase font-black tracking-wide whitespace-nowrap">Selesai</span>
                                <?php else: ?>
                                    <span class="bg-red-50 text-red-600 border border-red-100 px-2 py-0.5 rounded-full text-[10px] uppercase font-black tracking-wide whitespace-nowrap">Batal</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 px-3">
                                <?php if($pesanan->pembayaran): ?>
                                    <?php if($pesanan->pembayaran->statusPembayaran === 'berhasil'): ?>
                                        <span class="text-green-600 text-xs flex items-center gap-1">
                                            <i class="fa-solid fa-circle-check"></i> Lunas
                                        </span>
                                    <?php elseif($pesanan->pembayaran->statusPembayaran === 'pending'): ?>
                                        <span class="text-amber-600 text-xs flex items-center gap-1">
                                            <i class="fa-solid fa-clock"></i> Pending
                                        </span>
                                    <?php else: ?>
                                        <span class="text-red-600 text-xs flex items-center gap-1">
                                            <i class="fa-solid fa-circle-xmark"></i> Gagal
                                        </span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-gray-400 text-xs">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 px-3 text-center">
                                <a href="<?php echo e(route('admin.pesanan.show', $pesanan->id)); ?>" class="inline-block bg-[#58CC02] hover:bg-[#46a302] text-white px-3 py-1.5 rounded-xl text-xs font-black transition shadow-sm whitespace-nowrap">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="py-16 text-center text-gray-400">
                                <i class="fa-solid fa-box-open text-4xl mb-3 block"></i>
                                Tidak ditemukan data transaksi.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <?php if($pesanans->hasPages()): ?>
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
                    <?php echo e($pesanans->links()); ?>

                </div>
            <?php endif; ?>
        </div>

        <div class="space-y-4 md:hidden">
            <?php $__empty_1 = true; $__currentLoopData = $pesanans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pesanan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 space-y-4">

                    <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                        <span class="font-bold text-xs text-gray-500">No: <?php echo e(($pesanans->firstItem() ?? 1) + $loop->index); ?></span>
                        <span class="text-[10px] text-gray-400 font-bold">
                            <?php echo e(\Carbon\Carbon::parse($pesanan->created_at)->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d M Y H:i')); ?> WIB
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-3 text-xs">
                        <div>
                            <span class="text-[9px] text-gray-400 font-black uppercase tracking-wider block mb-0.5">Pelanggan</span>
                            <div class="flex flex-col">
                                <span class="font-bold text-gray-800 text-xs"><?php echo e($pesanan->user->namaLengkap ?? 'Dihapus'); ?></span>
                                <span class="text-[10px] text-gray-400">{{ $pesanan->user->username ?? 'user' }}</span>
                            </div>
                        </div>
                        <div>
                            <span class="text-[9px] text-gray-400 font-black uppercase tracking-wider block mb-0.5">Pengiriman</span>
                            <span class="font-medium text-gray-700 text-[11px] truncate block"><?php echo e($pesanan->deskripsi); ?></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 pt-2">
                        <div>
                            <span class="text-[9px] text-gray-400 font-black uppercase tracking-wider block mb-0.5">Total Tagihan</span>
                            <span class="font-black text-gray-900 text-xs">Rp <?php echo e(number_format($pesanan->total_harga, 0, ',', '.')); ?></span>
                        </div>
                        <div class="space-y-1">
                            <span class="text-[9px] text-gray-400 font-black uppercase tracking-wider block mb-0.5">Status</span>
                            <div class="flex flex-wrap gap-1">

                                <?php if($pesanan->status_pesanan === 'pending'): ?>
                                    <span class="bg-amber-50 text-amber-600 border border-amber-100 px-2 py-0.5 rounded-full text-[9px] uppercase font-black tracking-wide">Menunggu</span>
                                <?php elseif($pesanan->status_pesanan === 'diproses'): ?>
                                    <span class="bg-blue-50 text-blue-600 border border-blue-100 px-2 py-0.5 rounded-full text-[9px] uppercase font-black tracking-wide">Dikemas</span>
                                <?php elseif($pesanan->status_pesanan === 'dikirim'): ?>
                                    <span class="bg-purple-50 text-purple-600 border border-purple-100 px-2 py-0.5 rounded-full text-[9px] uppercase font-black tracking-wide">Dikirim</span>
                                <?php elseif($pesanan->status_pesanan === 'selesai'): ?>
                                    <span class="bg-green-50 text-green-600 border border-green-100 px-2 py-0.5 rounded-full text-[9px] uppercase font-black tracking-wide">Selesai</span>
                                <?php else: ?>
                                    <span class="bg-red-50 text-red-600 border border-red-100 px-2 py-0.5 rounded-full text-[9px] uppercase font-black tracking-wide">Batal</span>
                                <?php endif; ?>

                                <?php if($pesanan->pembayaran): ?>
                                    <?php if($pesanan->pembayaran->statusPembayaran === 'berhasil'): ?>
                                        <span class="bg-green-50 text-green-700 border border-green-100 px-2 py-0.5 rounded-full text-[9px] uppercase font-black tracking-wide">Lunas</span>
                                    <?php elseif($pesanan->pembayaran->statusPembayaran === 'pending'): ?>
                                        <span class="bg-amber-50 text-amber-700 border border-amber-100 px-2 py-0.5 rounded-full text-[9px] uppercase font-black tracking-wide">Pending</span>
                                    <?php else: ?>
                                        <span class="bg-red-50 text-red-700 border border-red-100 px-2 py-0.5 rounded-full text-[9px] uppercase font-black tracking-wide">Gagal</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="bg-gray-50 text-gray-500 border border-gray-200 px-2 py-0.5 rounded-full text-[9px] uppercase font-black tracking-wide">-</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-gray-100 flex justify-end">
                        <a href="<?php echo e(route('admin.pesanan.show', $pesanan->id)); ?>" class="w-full text-center bg-[#58CC02] hover:bg-[#46a302] text-white py-2.5 rounded-xl text-xs font-black transition shadow-sm">
                            Respon
                        </a>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-10 text-center text-gray-400">
                    <i class="fa-solid fa-box-open text-4xl mb-3 block"></i>
                    Tidak ditemukan data transaksi.
                </div>
            <?php endif; ?>

            <?php if($pesanans->hasPages()): ?>
                <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm mt-4">
                    <?php echo e($pesanans->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const activeOrderIds = <?php echo json_encode($pesanans->pluck('id')->all() ?? [], 15, 512) ?>;

        if (window.Echo && activeOrderIds.length > 0) {
            activeOrderIds.forEach(orderId => {
                window.Echo.channel('order.' + orderId)
                    .listen('.OrderStatusUpdated', (e) => {
                        console.log('Order status updated via Reverb on admin index page for order #' + orderId, e);

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

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\project\Agris\resources\views/admin/pesanan/index.blade.php ENDPATH**/ ?>