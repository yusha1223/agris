<?php $__env->startSection('title', 'Detail Pesanan - AGRIS'); ?>

<?php $__env->startSection('content'); ?>
<script src="<?php echo e(config('services.midtrans.is_production', false) ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js'); ?>" data-client-key="<?php echo e(config('services.midtrans.client_key')); ?>"></script>

<?php
    $pembayaran = $pesanan->pembayaran;
    $paymentInfo = null;
    if ($pembayaran && $pembayaran->payment_info) {
        $paymentInfo = json_decode($pembayaran->payment_info, true);
    }
    $isMock = empty(config('services.midtrans.server_key'));

    $deskripsi = $pesanan->deskripsi;
    $courierInfo = 'Kurir Pengiriman';
    $noResi = '';
    $ongkirText = '';
    $biteshipOrderId = '';

    if ($deskripsi) {
        $parts = explode('|', $deskripsi);
        foreach ($parts as $part) {
            $part = trim($part);
            if (str_starts_with(strtolower($part), 'opsi:')) {
                $courierInfo = trim(substr($part, 5));
            } elseif (str_starts_with(strtolower($part), 'ongkir:')) {
                $ongkirText = trim(substr($part, 7));
            } elseif (str_starts_with(strtolower($part), 'no resi:')) {
                $noResi = trim(substr($part, 8));
            } elseif (str_starts_with(strtolower($part), 'biteship order id:')) {
                $biteshipOrderId = trim(substr($part, 18));
            }
        }
    }
?>

<div id="order-detail-container" class="max-w-5xl mx-auto pb-16 px-6 pt-5">
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4" data-aos="fade-up">
        <div>
            <a href="<?php echo e(route('agen.pesanan.index')); ?>" class="text-xs font-bold text-gray-400 hover:text-gray-600 flex items-center gap-1.5 mb-2 uppercase tracking-wider transition-colors">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
            </a>
            <h1 class="text-2xl pt-1 font-extrabold text-gray-800 tracking-tight">Detail Pesanan</h1>
        </div>

        <div>
            <?php if($pesanan->status === 'pending'): ?>
                <span class="bg-amber-50 text-amber-600 border border-amber-100 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider">Menunggu Pembayaran</span>
            <?php elseif($pesanan->status === 'diproses'): ?>
                <span class="bg-blue-50 text-blue-600 border border-blue-100 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider">Sedang Dikemas</span>
            <?php elseif($pesanan->status === 'dikirim'): ?>
                <span class="bg-purple-50 text-purple-600 border border-purple-100 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider">Sedang Dikirim</span>
            <?php elseif($pesanan->status === 'selesai'): ?>
                <span class="bg-green-50 text-green-600 border border-green-100 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider" data-order-finished="true">Selesai</span>
            <?php else: ?>
                <span class="bg-red-50 text-red-600 border border-red-100 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider" data-order-finished="true">Dibatalkan</span>
            <?php endif; ?>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-xl mb-6 text-sm font-bold flex items-center gap-3">
            <i class="fa-solid fa-circle-check text-lg text-green-500"></i>
            <span><?php echo e(session('success')); ?></span>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-xl mb-6 text-sm font-bold flex items-center gap-3">
            <i class="fa-solid fa-triangle-exclamation text-lg text-red-500"></i>
            <span><?php echo e(session('error')); ?></span>
        </div>
    <?php endif; ?>

    <?php if(session('info')): ?>
        <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-4 rounded-xl mb-6 text-sm font-bold flex items-center gap-3">
            <i class="fa-solid fa-circle-info text-lg text-blue-500"></i>
            <span><?php echo e(session('info')); ?></span>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

        <div class="lg:col-span-2 space-y-6">

            <?php if($pesanan->status !== 'dibatalkan'): ?>
            <?php
                $courierInfo = 'Kurir Pengiriman';
                $noResi = '';
                $ongkirText = '';
                $biteshipOrderId = '';
                if ($pesanan->deskripsi) {
                    $parts = explode('|', $pesanan->deskripsi);
                    foreach ($parts as $part) {
                        $part = trim($part);
                        if (str_starts_with(strtolower($part), 'opsi:')) {
                            $courierInfo = trim(substr($part, 5));
                        } elseif (str_starts_with(strtolower($part), 'ongkir:')) {
                            $ongkirText = trim(substr($part, 7));
                        } elseif (str_starts_with(strtolower($part), 'no resi:')) {
                            $noResi = trim(substr($part, 8));
                        } elseif (str_starts_with(strtolower($part), 'biteship order id:')) {
                            $biteshipOrderId = trim(substr($part, 18));
                        }
                    }
                }
                $isPickup = str_contains(strtolower($courierInfo), 'ambil');

                $biteshipStatus = $trackingData['status'] ?? null;



                $stage1Status = 'waiting';
                $stage2Status = 'waiting';
                $stage3Status = 'waiting';

                $stage1Time = null;
                $stage2Time = null;
                $stage3Time = null;

                if ($pesanan->status_pesanan !== 'dibatalkan') {
                    if ($isPickup) {
                        // Local Pickup Flow (Ambil di Tempat)
                        if ($pesanan->status_pesanan === 'diproses') {
                            $stage1Status = 'active';
                        } elseif ($pesanan->status_pesanan === 'dikirim') {
                            $stage1Status = 'completed';
                            $stage2Status = 'active';
                        } elseif ($pesanan->status_pesanan === 'selesai') {
                            $stage1Status = 'completed';
                            $stage2Status = 'completed';
                            $stage3Status = 'completed';
                        }
                    } else {
                        // Courier Shipping Flow (Biteship or fallback)
                        if ($pesanan->status_pesanan === 'diproses') {
                            $stage1Status = 'active';
                        } elseif ($pesanan->status_pesanan === 'dikirim') {
                            $stage1Status = 'completed';
                            if (!empty($biteshipStatus)) {
                                $pickingUpStatuses = ['confirmed', 'allocated', 'scheduled', 'picking_up', 'pickingUp', 'picked'];
                                $inTransitStatuses = ['in_transit', 'inTransit', 'dropping_off', 'droppingOff', 'returned', 'rejected'];

                                if (in_array($biteshipStatus, $pickingUpStatuses)) {
                                    $stage1Status = 'active';
                                } elseif (in_array($biteshipStatus, $inTransitStatuses)) {
                                    $stage1Status = 'completed';
                                    $stage2Status = 'active';
                                } else {
                                    $stage1Status = 'completed';
                                    $stage2Status = 'completed';
                                    $stage3Status = 'completed';
                                }
                            } else {
                                $stage1Status = 'active';
                            }
                        } elseif ($pesanan->status_pesanan === 'selesai') {
                            $stage1Status = 'completed';
                            $stage2Status = 'completed';
                            $stage3Status = 'completed';
                        }
                    }
                }

                if (isset($trackingData['history']) && !empty($trackingData['history'])) {
                    foreach ($trackingData['history'] as $historyEvent) {
                        $histStatus = $historyEvent['status'] ?? '';
                        $histTime = \Carbon\Carbon::parse($historyEvent['updated_at'])->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d M Y, H:i') . ' WIB';

                        if (in_array($histStatus, ['confirmed', 'allocated', 'scheduled', 'picking_up', 'pickingUp', 'picked'])) {
                            $stage1Time = $histTime;
                        }
                        if (in_array($histStatus, ['in_transit', 'inTransit', 'dropping_off', 'droppingOff'])) {
                            $stage2Time = $histTime;
                        }
                        if ($histStatus === 'delivered') {
                            $stage3Time = $histTime;
                        }
                    }
                }

                if (!$stage1Time && in_array($stage1Status, ['active', 'completed'])) {
                    $stage1Time = \Carbon\Carbon::parse($pesanan->updated_at)->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d M Y, H:i') . ' WIB';
                }
                if (!$stage2Time && in_array($stage2Status, ['active', 'completed']) && $pesanan->status_pesanan === 'dikirim') {
                    $stage2Time = \Carbon\Carbon::parse($pesanan->updated_at)->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d M Y, H:i') . ' WIB';
                }
                if (!$stage3Time && $stage3Status === 'completed' && $pesanan->status_pesanan === 'selesai') {
                    $stage3Time = \Carbon\Carbon::parse($pesanan->updated_at)->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d M Y, H:i') . ' WIB';
                }
            ?>

            <!-- Status Tracking Pengiriman (Vertical Timeline) -->
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-6" data-aos="fade-up" data-aos-delay="100">
                <div class="flex items-center justify-between pb-3 border-b border-gray-50">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-2xl bg-[#58CC02]/10 flex items-center justify-center text-[#58CC02]">
                            <i class="fa-solid fa-map-location-dot text-base"></i>
                        </div>
                        <h2 class="font-extrabold text-gray-800 text-sm uppercase tracking-wider font-extrabold">Status Tracking Pengiriman</h2>
                    </div>
                    <?php
                        $trackId = $biteshipOrderId ?: $noResi;
                    ?>
                </div>

                <div class="relative pl-8 space-y-8 border-l-2 border-gray-100 ml-4 py-2">
                    <!-- Step 1: Penjemputan / Dikemas -->
                    <div class="relative">
                        <span class="absolute -left-12 top-0.5 flex h-8 w-8 items-center justify-center rounded-full <?php echo e($stage1Status === 'completed' ? 'bg-[#58CC02] text-white ring-4 ring-[#58CC02]/20' : ($stage1Status === 'active' ? 'bg-blue-600 text-white ring-4 ring-blue-100 animate-pulse' : 'bg-gray-100 text-gray-400 ring-4 ring-gray-50')); ?> shadow-sm">
                            <?php if($stage1Status === 'completed'): ?>
                                <i class="fa-solid fa-check text-xs"></i>
                            <?php elseif($stage1Status === 'active'): ?>
                                <i class="fa-solid fa-truck-ramp-box text-xs"></i>
                            <?php else: ?>
                                <i class="fa-solid fa-truck-ramp-box text-xs"></i>
                            <?php endif; ?>
                        </span>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-sm font-extrabold <?php echo e($stage1Status !== 'waiting' ? 'text-gray-800' : 'text-gray-400'); ?>">
                                    <?php echo e($isPickup ? 'Pesanan Dikemas' : 'Penjemputan (Pickup)'); ?>

                                </h3>
                                <?php if($stage1Status === 'completed'): ?>
                                    <span class="bg-green-50 text-green-600 border border-green-200 px-2 py-0.5 rounded-md text-[9px] uppercase font-black tracking-wider">Selesai</span>
                                <?php elseif($stage1Status === 'active'): ?>
                                    <span class="bg-blue-50 text-blue-600 border border-blue-200 px-2 py-0.5 rounded-md text-[9px] uppercase font-black tracking-wider">Aktif</span>
                                <?php else: ?>
                                    <span class="bg-gray-50 text-gray-400 border border-gray-200 px-2 py-0.5 rounded-md text-[9px] uppercase font-black tracking-wider">Menunggu</span>
                                <?php endif; ?>
                            </div>
                            <p class="text-xs <?php echo e($stage1Status !== 'waiting' ? 'text-gray-500' : 'text-gray-400'); ?> mt-1">
                                <?php if($isPickup): ?>
                                    <?php if($stage1Status === 'completed'): ?>
                                        Pesanan telah selesai dikemas oleh staf gudang.
                                    <?php elseif($stage1Status === 'active'): ?>
                                        Pesanan sedang dikemas oleh staf gudang.
                                    <?php else: ?>
                                        Menunggu proses pembayaran selesai dikonfirmasi.
                                    <?php endif; ?>
                                <?php else: ?>
                                    <?php if($stage1Status === 'completed'): ?>
                                        Paket telah dijemput dan diserahkan ke kurir ekspedisi.
                                    <?php elseif($stage1Status === 'active'): ?>
                                        Paket sedang disiapkan dan menunggu penjemputan oleh kurir.
                                    <?php else: ?>
                                        Menunggu proses pembayaran selesai dikonfirmasi.
                                    <?php endif; ?>
                                <?php endif; ?>
                            </p>
                            <?php if($stage1Time): ?>
                                <span class="inline-block text-[10px] font-bold text-gray-400 mt-1.5 bg-gray-50 px-2 py-0.5 rounded border border-gray-150"><?php echo e($stage1Time); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Step 2: Dalam Pengiriman / Siap Diambil -->
                    <div class="relative">
                        <span class="absolute -left-12 top-0.5 flex h-8 w-8 items-center justify-center rounded-full <?php echo e($stage2Status === 'completed' ? 'bg-[#58CC02] text-white ring-4 ring-[#58CC02]/20' : ($stage2Status === 'active' ? 'bg-blue-600 text-white ring-4 ring-blue-100 animate-pulse' : 'bg-gray-100 text-gray-400 ring-4 ring-gray-50')); ?> shadow-sm">
                            <?php if($stage2Status === 'completed'): ?>
                                <i class="fa-solid fa-check text-xs"></i>
                            <?php elseif($stage2Status === 'active'): ?>
                                <i class="fa-solid fa-truck text-xs"></i>
                            <?php else: ?>
                                <i class="fa-solid fa-truck text-xs"></i>
                            <?php endif; ?>
                        </span>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-sm font-extrabold <?php echo e($stage2Status !== 'waiting' ? 'text-gray-800' : 'text-gray-400'); ?>">
                                    <?php echo e($isPickup ? 'Siap Diambil' : 'Dalam Pengiriman'); ?>

                                </h3>
                                <?php if($stage2Status === 'completed'): ?>
                                    <span class="bg-green-50 text-green-600 border border-green-200 px-2 py-0.5 rounded-md text-[9px] uppercase font-black tracking-wider">Selesai</span>
                                <?php elseif($stage2Status === 'active'): ?>
                                    <span class="bg-blue-50 text-blue-600 border border-blue-200 px-2 py-0.5 rounded-md text-[9px] uppercase font-black tracking-wider">Aktif</span>
                                <?php else: ?>
                                    <span class="bg-gray-50 text-gray-400 border border-gray-200 px-2 py-0.5 rounded-md text-[9px] uppercase font-black tracking-wider">Menunggu</span>
                                <?php endif; ?>
                            </div>
                            <p class="text-xs <?php echo e($stage2Status !== 'waiting' ? 'text-gray-500' : 'text-gray-400'); ?> mt-1">
                                <?php if($isPickup): ?>
                                    <?php if($stage2Status === 'completed'): ?>
                                        Pesanan telah selesai diserahkan ke Agen.
                                    <?php elseif($stage2Status === 'active'): ?>
                                        Pesanan siap diambil di counter Gudang Utama AGRIS.
                                    <?php else: ?>
                                        Menunggu pesanan selesai dikemas.
                                    <?php endif; ?>
                                <?php else: ?>
                                    <?php if($stage2Status === 'completed'): ?>
                                        Paket telah sampai di kota / daerah tujuan pengiriman.
                                    <?php elseif($stage2Status === 'active'): ?>
                                        Paket sedang dalam perjalanan menuju alamat Anda. (Kurir: <?php echo e(strtoupper($courierInfo)); ?>)
                                    <?php else: ?>
                                        Menunggu paket dijemput oleh pihak ekspedisi.
                                    <?php endif; ?>
                                <?php endif; ?>
                            </p>
                            <?php if($stage2Time): ?>
                                <span class="inline-block text-[10px] font-bold text-gray-400 mt-1.5 bg-gray-50 px-2 py-0.5 rounded border border-gray-150"><?php echo e($stage2Time); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Step 3: Diterima / Selesai Diambil -->
                    <div class="relative">
                        <span class="absolute -left-12 top-0.5 flex h-8 w-8 items-center justify-center rounded-full <?php echo e($stage3Status === 'completed' ? 'bg-[#58CC02] text-white ring-4 ring-[#58CC02]/20' : 'bg-gray-100 text-gray-400 ring-4 ring-gray-50'); ?> shadow-sm">
                            <?php if($stage3Status === 'completed'): ?>
                                <i class="fa-solid fa-check text-xs"></i>
                            <?php else: ?>
                                <i class="fa-solid fa-circle-check text-xs"></i>
                            <?php endif; ?>
                        </span>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-sm font-extrabold <?php echo e($stage3Status !== 'waiting' ? 'text-gray-800' : 'text-gray-400'); ?>">
                                    <?php echo e($isPickup ? 'Selesai Diambil' : 'Diterima'); ?>

                                </h3>
                                <?php if($stage3Status === 'completed'): ?>
                                    <span class="bg-green-50 text-green-600 border border-green-200 px-2 py-0.5 rounded-md text-[9px] uppercase font-black tracking-wider">Selesai</span>
                                <?php elseif($stage3Status === 'active'): ?>
                                    <span class="bg-blue-50 text-blue-600 border border-blue-200 px-2 py-0.5 rounded-md text-[9px] uppercase font-black tracking-wider">Aktif</span>
                                <?php else: ?>
                                    <span class="bg-gray-50 text-gray-400 border border-gray-200 px-2 py-0.5 rounded-md text-[9px] uppercase font-black tracking-wider">Menunggu</span>
                                <?php endif; ?>
                            </div>
                            <p class="text-xs <?php echo e($stage3Status !== 'waiting' ? 'text-gray-500' : 'text-gray-400'); ?> mt-1">
                                <?php if($isPickup): ?>
                                    <?php if($stage3Status === 'completed'): ?>
                                        Seluruh proses transaksi selesai. Produk telah diterima oleh Agen.
                                    <?php else: ?>
                                        Menunggu pengambilan selesai dilakukan.
                                    <?php endif; ?>
                                <?php else: ?>
                                    <?php if($stage3Status === 'completed'): ?>
                                        Transaksi selesai. Paket telah diterima oleh Agen.
                                    <?php else: ?>
                                        Menunggu paket sampai di alamat tujuan and dikonfirmasi Agen.
                                    <?php endif; ?>
                                <?php endif; ?>
                            </p>
                            <?php if($stage3Time): ?>
                                <span class="inline-block text-[10px] font-bold text-gray-400 mt-1.5 bg-gray-50 px-2 py-0.5 rounded border border-gray-150"><?php echo e($stage3Time); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <?php if(isset($trackingData) && !empty($trackingData['history'])): ?>
                    <div class="mt-6 pt-4 border-t border-gray-100">
                        <div class="flex justify-between items-center text-xs font-extrabold text-gray-500 uppercase tracking-wider select-none mb-4">
                            <span>Histori Log Pengiriman Detail</span>
                        </div>
                        <div class="relative border-l-2 border-dashed border-gray-200 pl-6 ml-2.5 mt-4 space-y-4">
                            <?php $__currentLoopData = array_reverse($trackingData['history']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="relative">
                                    <?php
                                        $isLatest = $loop->first;
                                        $statusLabel = match($event['status']) {
                                            'confirmed' => 'Pesanan Dikonfirmasi',
                                            'allocated' => 'Kurir Dialokasikan',
                                            'pickingUp', 'picking_up' => 'Proses Penjemputan',
                                            'picked' => 'Paket Dijemput Kurir',
                                            'inTransit', 'in_transit' => 'Dalam Transit / Pengiriman',
                                            'droppingOff', 'dropping_off' => 'Kurir Menuju Lokasi Anda',
                                            'delivered' => 'Paket Diterima',
                                            'rejected' => 'Paket Ditolak/Bermasalah',
                                            'cancelled' => 'Pengiriman Dibatalkan',
                                            'returned' => 'Paket Dikembalikan',
                                            default => strtoupper($event['status'])
                                        };
                                    ?>
                                    <span class="absolute -left-8.5 top-0.5 w-3.5 h-3.5 rounded-full <?php echo e($isLatest ? 'bg-[#58CC02]' : 'bg-gray-300'); ?> border-2 border-white shadow-sm"></span>
                                    <div class="text-xs">
                                        <span class="text-gray-400 font-bold block mb-0.5"><?php echo e(\Carbon\Carbon::parse($event['updated_at'])->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d M Y, H:i')); ?> WIB</span>
                                        <p class="font-extrabold <?php echo e($isLatest ? 'text-[#58CC02]' : 'text-gray-700'); ?>"><?php echo e($statusLabel); ?></p>
                                        <p class="text-gray-500 mt-0.5 font-medium leading-relaxed"><?php echo e($event['note']); ?></p>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm" data-aos="fade-up" data-aos-delay="200">
                <h2 class="font-extrabold text-gray-800 text-xs mb-4 uppercase tracking-wider text-gray-400 pb-2 border-b border-gray-50">Daftar Produk</h2>
                <div class="divide-y divide-gray-100">
                    <?php $__currentLoopData = $pesanan->detailPesanans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center gap-3 md:gap-4 py-4 first:pt-0 last:pb-0">
                            <div class="w-12 h-12 md:w-14 md:h-14 bg-gray-50 rounded-xl overflow-hidden border border-gray-100 flex items-center justify-center p-1 shrink-0">
                                <?php if($detail->produk && $detail->produk->fotoProduk): ?>
                                    <img src="<?php echo e(asset('storage/' . $detail->produk->fotoProduk)); ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <i class="fa-solid fa-image text-xl text-gray-300"></i>
                                <?php endif; ?>
                            </div>

                            <div class="grow min-w-0">
                                <h4 class="font-bold text-gray-800 text-[11px] md:text-xs truncate">
                                    <?php echo e($detail->produk ? $detail->produk->namaProduk : 'Produk Telah Dihapus'); ?>

                                </h4>
                                <p class="text-[10px] md:text-[11px] text-gray-400 font-semibold mt-0.5">
                                    <?php echo e($detail->jumlahPesanan); ?> barang x Rp <?php echo e(number_format($detail->harga_satuan, 0, ',', '.')); ?>

                                </p>
                            </div>

                            <div class="text-right shrink-0">
                                <div class="text-right">
                                    <span class="font-bold text-gray-800 text-[11px] md:text-xs block">
                                        Rp <?php echo e(number_format($detail->subtotal, 0, ',', '.')); ?>

                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-4" data-aos="fade-up" data-aos-delay="300">
                <div class="flex items-center gap-3 pb-3 border-b border-gray-50">
                    <div class="w-8 h-8 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400">
                        <i class="fa-solid fa-location-dot text-sm"></i>
                    </div>
                    <h2 class="font-extrabold text-gray-800 text-xs uppercase tracking-wider text-gray-400">Alamat Penerima</h2>
                </div>
                <div class="text-xs space-y-1.5 leading-relaxed">
                    <p class="font-extrabold text-gray-800 text-sm"><?php echo e($pesanan->user->namaLengkap); ?></p>
                    <p class="text-gray-500 font-bold"><?php echo e($pesanan->user->noTelp); ?></p>
                    <p class="text-gray-600 mt-1 font-medium"><?php echo e($pesanan->alamat_pengiriman); ?></p>
                </div>
            </div>

        </div>

        <div class="space-y-6" data-aos="fade-up" data-aos-delay="150">

            <?php if(in_array($pesanan->status_pesanan, ['diproses', 'dikirim', 'selesai'])): ?>
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <div class="flex items-center gap-3 mb-4 pb-3 border-b border-gray-50">
                    <div class="w-8 h-8 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                        <i class="fa-solid fa-truck-fast text-sm"></i>
                    </div>
                    <h3 class="font-extrabold text-gray-800 text-xs uppercase tracking-wider text-gray-400">Info Pengiriman</h3>
                </div>
                <div class="space-y-3">
                    <div class="bg-gray-50 rounded-2xl p-3 border border-gray-100">
                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Kurir Pengiriman</div>
                        <span class="font-extrabold text-gray-800 text-sm uppercase"><?php echo e($courierInfo); ?></span>
                    </div>
                    <?php if($noResi && !str_contains($noResi, 'AMBIL')): ?>
                    <div class="bg-gray-50 rounded-2xl p-3 border border-gray-100">
                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Nomor Resi</div>
                        <div class="flex items-center justify-between gap-2">
                            <span class="font-mono font-extrabold text-gray-800 text-sm select-all"><?php echo e($noResi); ?></span>
                            <button onclick="copyResiSidebar(this, '<?php echo e($noResi); ?>')" class="text-gray-400 hover:text-[#58CC02] transition cursor-pointer" title="Salin Resi">
                                <i class="fa-regular fa-copy text-xs"></i>
                            </button>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php if($ongkirText): ?>
                    <div class="bg-gray-50 rounded-2xl p-3 border border-gray-100">
                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Ongkos Kirim</div>
                        <span class="font-extrabold text-gray-800 text-sm"><?php echo e($ongkirText); ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if($noResi && !str_contains($noResi, 'AMBIL')): ?>
                    <a href="<?php echo e(route('agen.pesanan.lacak', $pesanan->id)); ?>" target="_blank" class="block w-full bg-blue-50 hover:bg-blue-100 text-blue-600 py-2.5 rounded-2xl font-black text-xs flex items-center justify-center gap-2 transition border border-blue-100 cursor-pointer">
                        <i class="fa-solid fa-map-location-dot"></i> Lacak Pengiriman<?php echo e($biteshipOrderId ? '' : ''); ?>

                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-bl from-green-50 to-transparent rounded-bl-full pointer-events-none"></div>
                <h2 class="font-extrabold text-gray-800 text-xs mb-4 uppercase tracking-wider text-gray-400">Ringkasan Transaksi</h2>

                <div class="space-y-3 pb-3 border-b border-gray-50 text-xs">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400 font-semibold">Tanggal Transaksi</span>
                        <span class="text-gray-700 font-extrabold"><?php echo e(\Carbon\Carbon::parse($pesanan->created_at)->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d F Y, H:i')); ?> WIB</span>
                    </div>
                    <?php if($pembayaran && $pembayaran->waktuDibayar): ?>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400 font-semibold">Tanggal Pembayaran</span>
                            <span class="text-gray-700 font-extrabold"><?php echo e(\Carbon\Carbon::parse($pembayaran->waktuDibayar)->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d F Y, H:i')); ?> WIB</span>
                        </div>
                    <?php endif; ?>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400 font-semibold">Pilihan Metode Pembayaran</span>
                        <span class="text-gray-700 font-black uppercase text-right">
                            <?php if($pembayaran): ?>
                                <?php
                                    $type = strtolower($pembayaran->paymentType ?? '');
                                    if ($type === 'midtrans_snap' || $type === 'simulasi_midtrans' || !$type) {
                                        $displayMethod = 'MIDTRANS ONLINE';
                                    } else {
                                        $displayMethod = strtoupper(str_replace('_', ' ', $pembayaran->paymentType));
                                    }
                                ?>
                                <?php echo e($displayMethod); ?>

                            <?php else: ?>
                                Transfer
                            <?php endif; ?>
                        </span>
                    </div>
                    <?php if($noResi): ?>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400 font-semibold">No Resi</span>
                            <span class="text-gray-700 font-mono font-bold select-all bg-gray-50 px-1.5 py-0.5 rounded border border-gray-150"><?php echo e($noResi); ?></span>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="flex justify-between items-center pt-4">
                    <span class="text-gray-800 font-extrabold text-xs">Total Tagihan</span>
                    <span class="text-lg font-black text-[#58CC02]">
                        Rp <?php echo e(number_format($pesanan->total_harga, 0, ',', '.')); ?>

                    </span>
                </div>
            </div>

            <?php if($pesanan->status === 'diproses'): ?>
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-3">
                    <h3 class="font-extrabold text-gray-800 text-xs mb-4 uppercase tracking-wider text-gray-400 pb-2 border-b border-gray-50">Aksi Pesanan</h3>

                    <form id="batalForm" action="<?php echo e(route('agen.pesanan.batal', $pesanan->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                    </form>

                    <button type="button" onclick="openModal('batalPesananModal')"
                            class="w-full border border-red-200 text-red-600 hover:bg-red-50 py-2.5 rounded-2xl font-black text-xs flex items-center justify-center gap-2 transition duration-200 cursor-pointer">
                        <i class="fa-solid fa-ban"></i> Batalkan Pesanan
                    </button>
                </div>
            <?php endif; ?>

            <?php
                $biteshipStatus = $trackingData['status'] ?? null;
                if (!empty($biteshipStatus)) {

                    $deliveringStatuses = ['inTransit', 'in_transit', 'droppingOff', 'dropping_off', 'delivered'];
                    $canConfirmReceipt = in_array($biteshipStatus, $deliveringStatuses) && $pesanan->status_pesanan !== 'selesai';
                } else {

                    $canConfirmReceipt = $pesanan->status_pesanan === 'dikirim';
                }
            ?>
            <?php if($canConfirmReceipt): ?>
                <div class="bg-white p-6 rounded-3xl shadow-sm space-y-3">
                    <h3 class="font-extrabold text-gray-800 text-xs mb-4 uppercase tracking-wider text-gray-400 pb-2 border-b border-gray-50">Konfirmasi Penerimaan</h3>
                    <p class="text-xs text-gray-400 font-semibold leading-relaxed">Pesanan Anda telah dikirimkan. Harap klik tombol di bawah ini jika barang sudah Anda terima dengan baik.</p>

                    <form id="formDiterima" action="<?php echo e(route('agen.pesanan.diterima', $pesanan->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <button type="button" onclick="openModal('modalConfirmDiterima')" class="w-full bg-[#0f8629] hover:bg-[#0c6b20] text-white py-3 rounded-2xl font-black text-xs flex items-center justify-center gap-2 shadow-sm transition duration-200 cursor-pointer">
                            <i class="fa-solid fa-circle-check"></i> Pesanan Sudah Diterima
                        </button>
                    </form>
                </div>
            <?php endif; ?>

            <?php if($pesanan->status === 'pending'): ?>
                <div class="bg-white p-6 rounded-3xl border border-gray-150 shadow-sm space-y-3">
                    <h3 class="font-extrabold text-gray-800 text-xs mb-4 uppercase tracking-wider text-gray-400 pb-2 border-b border-gray-50">Selesaikan Pembayaran</h3>
                    <p class="text-xs text-gray-400 font-semibold leading-relaxed">Pesanan Anda telah disimpan. Silakan lakukan pembayaran agar pesanan dapat segera diproses.</p>

                    <?php if($pembayaran && $pembayaran->snapToken): ?>
                        <?php if($isMock || str_starts_with($pembayaran->snapToken, 'MOCK-SNAP-TOKEN-')): ?>
                            <button id="btnSimulatePay" class="w-full bg-[#3b82f6] hover:bg-[#2563eb] text-white py-3 rounded-2xl font-black text-xs flex items-center justify-center gap-2 shadow-sm transition duration-200 cursor-pointer">
                                <i class="fa-solid fa-laptop-code"></i> Simulasikan Pembayaran (Offline)
                            </button>
                        <?php else: ?>
                            <button id="btnPayNow" class="w-full bg-[#58CC02] hover:bg-[#46a302] text-white py-3 rounded-2xl font-black text-xs flex items-center justify-center gap-2 shadow-sm transition duration-200 cursor-pointer">
                                <i class="fa-solid fa-credit-card"></i> Bayar Sekarang
                            </button>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="p-3 bg-red-50 text-red-600 rounded-2xl text-[11px] font-bold text-center">
                            Gagal memuat Snap token pembayaran.
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if($pesanan->status === 'pending' && $pembayaran && $pembayaran->snapToken): ?>
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        const btnPay = document.getElementById('btnPayNow');
        if (btnPay) {
            btnPay.addEventListener('click', function() {

                btnPay.disabled = true;
                btnPay.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Memproses...';
                btnPay.className = "w-full bg-slate-300 text-slate-500 py-3 rounded-2xl font-black text-xs flex items-center justify-center gap-2 cursor-wait transition duration-200";

                window.snap.pay('<?php echo e($pembayaran->snapToken); ?>', {
                    onSuccess: function(result) {
                        window.location.href = `/agen/pesanan/<?php echo e($pesanan->id); ?>?status=success`;
                    },
                    onPending: function(result) {
                        window.location.href = `/agen/pesanan/<?php echo e($pesanan->id); ?>`;
                    },
                    onError: function(result) {

                        btnPay.disabled = false;
                        btnPay.innerHTML = '<i class="fa-solid fa-credit-card"></i> Bayar Sekarang';
                        btnPay.className = "w-full bg-[#58CC02] hover:bg-[#46a302] text-white py-3 rounded-2xl font-black text-xs flex items-center justify-center gap-2 shadow-sm transition duration-200 cursor-pointer";
                        window.location.href = `/agen/pesanan/<?php echo e($pesanan->id); ?>`;
                    },
                    onClose: function() {

                        btnPay.disabled = false;
                        btnPay.innerHTML = '<i class="fa-solid fa-credit-card"></i> Bayar Sekarang';
                        btnPay.className = "w-full bg-[#58CC02] hover:bg-[#46a302] text-white py-3 rounded-2xl font-black text-xs flex items-center justify-center gap-2 shadow-sm transition duration-200 cursor-pointer";
                        window.location.href = `/agen/pesanan/<?php echo e($pesanan->id); ?>`;
                    }
                });
            });
        }

        const btnSimulate = document.getElementById('btnSimulatePay');
        if (btnSimulate) {
            btnSimulate.addEventListener('click', function() {
                btnSimulate.disabled = true;
                btnSimulate.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Memproses...';
                btnSimulate.className = "w-full bg-slate-300 text-slate-500 py-3 rounded-2xl font-black text-xs flex items-center justify-center gap-2 cursor-wait transition duration-200";

                fetch('<?php echo e(route('agen.pesanan.bayar-simulasi', $pesanan->id)); ?>', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = `/agen/pesanan/<?php echo e($pesanan->id); ?>?status=success`;
                    } else {
                        alert(data.message || 'Gagal memproses simulasi pembayaran.');
                        btnSimulate.disabled = false;
                        btnSimulate.innerHTML = '<i class="fa-solid fa-laptop-code"></i> Simulasikan Pembayaran (Offline)';
                        btnSimulate.className = "w-full bg-[#3b82f6] hover:bg-[#2563eb] text-white py-3 rounded-2xl font-black text-xs flex items-center justify-center gap-2 shadow-sm transition duration-200 cursor-pointer";
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Terjadi kesalahan koneksi.');
                    btnSimulate.disabled = false;
                    btnSimulate.innerHTML = '<i class="fa-solid fa-laptop-code"></i> Simulasikan Pembayaran (Offline)';
                    btnSimulate.className = "w-full bg-[#3b82f6] hover:bg-[#2563eb] text-white py-3 rounded-2xl font-black text-xs flex items-center justify-center gap-2 shadow-sm transition duration-200 cursor-pointer";
                });
            });
        }
    });
</script>
<?php endif; ?>

<?php if($pesanan->status === 'diproses'): ?>
<?php if (isset($component)) { $__componentOriginal9f64f32e90b9102968f2bc548315018c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f64f32e90b9102968f2bc548315018c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modal','data' => ['id' => 'batalPesananModal','title' => 'Batalkan Pesanan?','message' => 'Apakah Anda yakin ingin membatalkan pesanan ini? Stok produk akan dikembalikan secara otomatis. Aksi ini tidak dapat dibatalkan.','confirmText' => 'Ya','cancelText' => 'Batal','confirmId' => 'btnSubmitBatal','cancelId' => 'btnCloseBatal']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'batalPesananModal','title' => 'Batalkan Pesanan?','message' => 'Apakah Anda yakin ingin membatalkan pesanan ini? Stok produk akan dikembalikan secara otomatis. Aksi ini tidak dapat dibatalkan.','confirmText' => 'Ya','cancelText' => 'Batal','confirmId' => 'btnSubmitBatal','cancelId' => 'btnCloseBatal']); ?>
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
<?php endif; ?>

<?php if (isset($component)) { $__componentOriginal9f64f32e90b9102968f2bc548315018c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f64f32e90b9102968f2bc548315018c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modal','data' => ['id' => 'modalConfirmDiterima','title' => 'Konfirmasi Penerimaan','message' => 'Apakah Anda yakin telah menerima pesanan ini? Aksi ini tidak dapat dibatalkan.','confirmText' => 'Iya','cancelText' => 'Batal','confirmId' => 'btnSubmitDiterima','cancelId' => 'btnCloseDiterima']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'modalConfirmDiterima','title' => 'Konfirmasi Penerimaan','message' => 'Apakah Anda yakin telah menerima pesanan ini? Aksi ini tidak dapat dibatalkan.','confirmText' => 'Iya','cancelText' => 'Batal','confirmId' => 'btnSubmitDiterima','cancelId' => 'btnCloseDiterima']); ?>
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
    document.addEventListener('DOMContentLoaded', function() {
        const btnSubmitDiterima = document.getElementById('btnSubmitDiterima');
        if (btnSubmitDiterima) {
            btnSubmitDiterima.addEventListener('click', function() {
                const form = document.getElementById('formDiterima');
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
                const form = document.getElementById('batalForm');
                if (form) form.submit();
            });
        }

        const btnCloseBatal = document.getElementById('btnCloseBatal');
        if (btnCloseBatal) {
            btnCloseBatal.addEventListener('click', function() {
                closeModal('batalPesananModal');
            });
        }
    });
function copyResiSidebar(btn, resi) {
    navigator.clipboard.writeText(resi).then(() => {
        btn.innerHTML = '<i class="fa-solid fa-check text-[#58CC02] text-xs"></i>';
        setTimeout(() => {
            btn.innerHTML = '<i class="fa-regular fa-copy text-xs"></i>';
        }, 1500);
    });
}

    if (window.Echo) {
        window.Echo.channel('order.<?php echo e($pesanan->id); ?>')
            .listen('.OrderStatusUpdated', (e) => {
                console.log('Order status updated via Reverb:', e);
                updateOrderContent();
            });
    }

    <?php if(in_array($pesanan->status_pesanan, ['pending', 'diproses', 'dikirim'])): ?>
    const pollInterval = setInterval(function() {
        updateOrderContent(function(isFinished) {
            if (isFinished) {
                clearInterval(pollInterval);
            }
        });
    }, 30000); // Poll every 30 seconds as fallback
    <?php endif; ?>

    function updateOrderContent(callback) {
        fetch(window.location.href)
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newContainer = doc.getElementById('order-detail-container');
                const oldContainer = document.getElementById('order-detail-container');
                if (newContainer && oldContainer) {
                    if (newContainer.innerHTML !== oldContainer.innerHTML) {
                        oldContainer.innerHTML = newContainer.innerHTML;
                    }
                    const isFinished = newContainer.querySelector('[data-order-finished="true"]') !== null;
                    if (callback) callback(isFinished);
                }
            })
            .catch(err => console.error('Error updating order status:', err));
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.agen', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\project\Agris\resources\views/agen/pesanan/show.blade.php ENDPATH**/ ?>