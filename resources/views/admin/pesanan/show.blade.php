@extends('layouts.admin')

@section('title', 'Detail Transaksi - Admin AGRIS')

@section('content')
@php
    $pembayaran = $pesanan->pembayaran;
    $paymentInfo = null;
    if ($pembayaran && $pembayaran->payment_info) {
        $paymentInfo = json_decode($pembayaran->payment_info, true);
    }

    $biteshipOrderId = null;
    $noResi = null;
    $courierInfo = 'Kurir';
    $ongkirText = '';
    if ($pesanan->deskripsi) {
        $parts = explode('|', $pesanan->deskripsi);
        foreach ($parts as $part) {
            $part = trim($part);
            $lowerPart = strtolower($part);
            if (str_starts_with($lowerPart, 'biteship order id:')) {
                $biteshipOrderId = trim(substr($part, 18));
            } elseif (str_starts_with($lowerPart, 'no resi:')) {
                $noResi = trim(substr($part, 8));
            } elseif (str_starts_with($lowerPart, 'opsi:')) {
                $courierInfo = trim(substr($part, 5));
            } elseif (str_starts_with($lowerPart, 'ongkir:')) {
                $ongkirText = trim(substr($part, 7));
            }
        }
    }
    $isPickup = str_contains(strtolower($courierInfo), 'ambil');

    $getFriendlyPaymentMethod = function($pembayaran) {
        if (!$pembayaran) {
            return 'Transfer Bank';
        }
        $type = strtolower($pembayaran->paymentType ?? '');
        
        $details = null;
        if ($pembayaran->payment_info) {
            $details = json_decode($pembayaran->payment_info, true);
        }

        if ($type === 'simulasi_midtrans') {
            return 'Simulasi Offline';
        }
        
        if ($type === 'midtrans_snap' || !$type) {
            return 'Midtrans Online';
        }

        if ($details && isset($details['payment_type'])) {
            $type = strtolower($details['payment_type']);
        }

        switch ($type) {
            case 'bank_transfer':
                if (isset($details['va_numbers'][0]['bank'])) {
                    return 'Transfer Bank (' . strtoupper($details['va_numbers'][0]['bank']) . ')';
                }
                if (isset($details['permata_va_number'])) {
                    return 'Transfer Bank (Permata)';
                }
                return 'Transfer Bank';
            case 'echannel':
                return 'Mandiri Bill Payment';
            case 'gopay':
                return 'GoPay';
            case 'qris':
                return 'QRIS';
            case 'shopeepay':
                return 'ShopeePay';
            case 'akulaku':
                return 'Akulaku';
            case 'kredivo':
                return 'Kredivo';
            case 'cstore':
                if (isset($details['store'])) {
                    $store = strtolower($details['store']);
                    if ($store === 'indomaret') {
                        return 'Indomaret';
                    }
                    if ($store === 'alfamart') {
                        return 'Alfamart';
                    }
                }
                return 'Gerai Retail';
            case 'credit_card':
                return 'Kartu Kredit';
            case 'bca_klikbca':
                return 'KlikBCA';
            case 'bca_klikpay':
                return 'BCA KlikPay';
            case 'bri_epay':
                return 'BRImo';
            case 'cimb_clicks':
                return 'CIMB Clicks';
            case 'mandiri_clickpay':
                return 'Mandiri Clickpay';
            default:
                if ($details && isset($details['payment_type'])) {
                    return strtoupper(str_replace('_', ' ', $details['payment_type']));
                }
                return strtoupper(str_replace('_', ' ', $pembayaran->paymentType));
        }
    };
@endphp
<div id="order-detail-container" class="max-w-6xl mx-auto pt-5 pb-12">

    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4" data-aos="fade-up">
        <div>
            <a href="{{ route('admin.pesanan.index') }}" class="inline-flex items-center gap-1 text-[10px] md:text-xs font-bold text-gray-400 hover:text-gray-600 transition uppercase tracking-wider mb-2">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Transaksi
            </a>
            <h1 class="text-xl md:text-2xl font-extrabold text-gray-950 tracking-tight">Detail Pesanan</h1>
        </div>
        <div>
            <span class="text-[10px] md:text-xs text-gray-400 font-bold">Tanggal: {{ \Carbon\Carbon::parse($pesanan->created_at)->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d F Y H:i') }} WIB</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

        <div class="lg:col-span-2 space-y-6" data-aos="fade-right" data-aos-delay="100">

            <div class="bg-white p-4 md:p-6 rounded-3xl shadow-md">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                    <div class="w-8 h-8 rounded-xl bg-green-50 flex items-center justify-center text-[#0f8629]">
                        <i class="fa-solid fa-seedling text-sm"></i>
                    </div>
                    <h2 class="font-extrabold text-gray-800 text-xs md:text-sm">Manifest Produk Dipesan</h2>
                </div>

                <div class="divide-y divide-gray-100">
                    @foreach($pesanan->detailPesanans as $detail)
                        <div class="flex items-center gap-3 md:gap-4 py-4 first:pt-0 last:pb-0">
                            <div class="w-12 h-12 md:w-14 md:h-14 bg-gray-50 rounded-xl overflow-hidden border border-gray-100 flex flex-col items-center justify-center shrink-0">
                                @if($detail->produk && $detail->produk->fotoProduk)
                                    <img src="{{ storage_url($detail->produk->fotoProduk) }}" class="w-full h-full object-cover rounded-xl">
                                @else
                                    <i class="fa-regular fa-image text-base text-gray-300"></i>
                                    <span class="text-[7px] font-bold text-gray-300 mt-0.5 leading-none text-center">Gambar<br>Kosong</span>
                                @endif
                            </div>
                            <div class="grow min-w-0">
                                <h4 class="font-extrabold text-gray-800 text-[11px] md:text-xs truncate">{{ $detail->produk->namaProduk ?? 'Produk Dihapus' }}</h4>
                                <p class="text-[10px] md:text-[11px] text-gray-400 mt-1 font-bold">
                                    {{ $detail->jumlahPesanan }} unit • Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="font-black text-gray-900 text-xs md:text-sm">
                                    Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white p-4 md:p-6 rounded-3xl shadow-md">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                    <div class="w-8 h-8 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                        <i class="fa-solid fa-truck-fast text-sm"></i>
                    </div>
                    <h2 class="font-extrabold text-gray-800 text-xs md:text-sm">Informasi & Alamat Pengiriman</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs text-gray-600 font-bold leading-relaxed">
                    <div class="space-y-3">
                        <div>
                            <span class="text-[9px] text-gray-400 font-black uppercase tracking-wider block">Penerima</span>
                            <span class="text-gray-800 text-xs md:text-sm font-black">{{ $pesanan->user->namaLengkap }}</span>
                        </div>
                        <div>
                            <span class="text-[9px] text-gray-400 font-black uppercase tracking-wider block">Nomor Telepon</span>
                            <span class="text-gray-800 text-xs md:text-sm">{{ $pesanan->user->noTelp }}</span>
                        </div>
                        <div>
                            <span class="text-[9px] text-gray-400 font-black uppercase tracking-wider block">Metode Pengiriman</span>
                            <span class="text-gray-800 bg-gray-50 border border-gray-100 rounded-lg px-2.5 py-1.5 inline-block font-mono mt-1 text-[10px] md:text-[11px] uppercase">
                                {{ $courierInfo }}
                            </span>
                        </div>
                        @if($ongkirText && !$isPickup)
                        <div>
                            <span class="text-[9px] text-gray-400 font-black uppercase tracking-wider block">Ongkos Kirim</span>
                            <span class="text-gray-800 bg-gray-50 border border-gray-100 rounded-lg px-2.5 py-1.5 inline-block font-mono mt-1 text-[10px] md:text-[11px]">
                                {{ $ongkirText }}
                            </span>
                        </div>
                        @endif
                        @if($noResi && !$isPickup && !str_contains(strtoupper($noResi), 'AMBIL'))
                        <div>
                            <span class="text-[9px] text-gray-400 font-black uppercase tracking-wider block">Nomor Resi</span>
                            <span class="text-gray-700 bg-gray-50 border border-gray-100 rounded-lg px-2.5 py-1.5 inline-block font-mono mt-1 text-[10px] md:text-[11px] font-bold select-all">
                                {{ $noResi }}
                            </span>
                        </div>
                        @endif
                    </div>

                    <div>
                        <span class="text-[9px] text-gray-400 font-black uppercase tracking-wider block mb-1">Alamat Tujuan</span>
                        <div class="bg-gray-50 border border-gray-100 p-3 rounded-2xl">
                            <p class="text-gray-700 text-xs md:text-sm leading-normal">{{ $pesanan->alamat_pengiriman }}</p>
                            @if($pesanan->user->desa)
                                <p class="text-gray-400 mt-2 font-semibold text-[10px] md:text-xs">
                                    Desa {{ $pesanan->user->desa->namaDesa }}, Kec. {{ $pesanan->user->desa->kecamatan->namaKecamatan ?? '' }}, {{ $pesanan->user->desa->kecamatan->kabupaten->namaKabupaten ?? '' }}, {{ $pesanan->user->desa->kecamatan->kabupaten->provinsi->namaProvinsi ?? '' }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
                       @if($pesanan->status_pesanan !== 'dibatalkan')
            @php
                $biteshipOrderId = null;
                $noResi = null;
                $courierInfo = 'Kurir';
                if ($pesanan->deskripsi) {
                    $parts = explode('|', $pesanan->deskripsi);
                    foreach ($parts as $part) {
                        $part = trim($part);
                        $lowerPart = strtolower($part);
                        if (str_starts_with($lowerPart, 'biteship order id:')) {
                            $biteshipOrderId = trim(substr($part, 18));
                        } elseif (str_starts_with($lowerPart, 'no resi:')) {
                            $noResi = trim(substr($part, 8));
                        } elseif (str_starts_with($lowerPart, 'opsi:')) {
                            $courierInfo = trim(substr($part, 5));
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
                        // Local Pickup Flow (Ambal di Tempat)
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
            @endphp
            <div class="bg-white p-4 md:p-6 rounded-3xl shadow-md space-y-6">
                <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600">
                            <i class="fa-solid fa-map-location-dot text-sm"></i>
                        </div>
                        <h2 class="font-extrabold text-gray-800 text-xs md:text-sm">Status Tracking Pengiriman</h2>
                    </div>
                    @php
                        $trackId = $biteshipOrderId ?: $noResi;
                        $isBiteshipTesting = empty(config('services.biteship.key')) || str_starts_with(config('services.biteship.key'), 'biteship_test.') || config('app.env') === 'local';
                        $biteshipTrackUrl = $isBiteshipTesting ? 'https://track.biteship.com/tracking-test' : 'https://track.biteship.com/' . $trackId;
                    @endphp
                    @if(!empty($trackId) && !str_contains(strtoupper($trackId), 'AMBIL'))
                        <a href="{{ $biteshipTrackUrl }}" target="_blank" class="inline-flex items-center gap-1.5 bg-blue-50 border border-blue-100 px-3 py-1 rounded-lg text-blue-650 font-bold text-[10px] hover:bg-blue-100 transition">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i> Lacak di Paket
                        </a>
                    @endif
                </div>

                <div class="relative pl-8 space-y-8 border-l-2 border-gray-100 ml-4 py-2">
                    <!-- Step 1: Penjemputan / Dikemas -->
                    <div class="relative">
                        <span class="absolute -left-12 top-0.5 flex h-8 w-8 items-center justify-center rounded-full {{ $stage1Status === 'completed' ? 'bg-[#0f8629] text-white ring-4 ring-green-100' : ($stage1Status === 'active' ? 'bg-blue-600 text-white ring-4 ring-blue-100 animate-pulse' : 'bg-gray-100 text-gray-400 ring-4 ring-gray-50') }} shadow-sm">
                            @if($stage1Status === 'completed')
                                <i class="fa-solid fa-check text-xs"></i>
                            @elseif($stage1Status === 'active')
                                <i class="fa-solid fa-truck-ramp-box text-xs"></i>
                            @else
                                <i class="fa-solid fa-truck-ramp-box text-xs"></i>
                            @endif
                        </span>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-sm font-extrabold {{ $stage1Status !== 'waiting' ? 'text-gray-800' : 'text-gray-400' }}">
                                    {{ $isPickup ? 'Pesanan Dikemas' : 'Penjemputan (Pickup)' }}
                                </h3>
                                @if($stage1Status === 'completed')
                                    <span class="bg-green-50 text-green-600 border border-green-200 px-2 py-0.5 rounded-md text-[9px] uppercase font-black tracking-wider">Selesai</span>
                                @elseif($stage1Status === 'active')
                                    <span class="bg-blue-50 text-blue-600 border border-blue-200 px-2 py-0.5 rounded-md text-[9px] uppercase font-black tracking-wider">Aktif</span>
                                @else
                                    <span class="bg-gray-50 text-gray-400 border border-gray-200 px-2 py-0.5 rounded-md text-[9px] uppercase font-black tracking-wider">Menunggu</span>
                                @endif
                            </div>
                            <p class="text-xs {{ $stage1Status !== 'waiting' ? 'text-gray-500' : 'text-gray-400' }} mt-1">
                                @if($isPickup)
                                    @if($stage1Status === 'completed')
                                        Pesanan telah selesai dikemas oleh staf gudang.
                                    @elseif($stage1Status === 'active')
                                        Pesanan sedang dikemas oleh staf gudang.
                                    @else
                                        Menunggu proses pembayaran selesai dikonfirmasi.
                                    @endif
                                @else
                                    @if($stage1Status === 'completed')
                                        Paket telah dijemput dan diserahkan ke kurir ekspedisi.
                                    @elseif($stage1Status === 'active')
                                        Paket sedang disiapkan dan menunggu penjemputan oleh kurir.
                                    @else
                                        Menunggu proses pembayaran selesai dikonfirmasi.
                                    @endif
                                @endif
                            </p>
                            @if($stage1Time)
                                <span class="inline-block text-[10px] font-bold text-gray-400 mt-1.5 bg-gray-50 px-2 py-0.5 rounded border border-gray-150">{{ $stage1Time }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Step 2: Dalam Pengiriman / Siap Diambil -->
                    <div class="relative">
                        <span class="absolute -left-12 top-0.5 flex h-8 w-8 items-center justify-center rounded-full {{ $stage2Status === 'completed' ? 'bg-[#0f8629] text-white ring-4 ring-green-100' : ($stage2Status === 'active' ? 'bg-blue-600 text-white ring-4 ring-blue-100 animate-pulse' : 'bg-gray-100 text-gray-400 ring-4 ring-gray-50') }} shadow-sm">
                            @if($stage2Status === 'completed')
                                <i class="fa-solid fa-check text-xs"></i>
                            @elseif($stage2Status === 'active')
                                <i class="fa-solid fa-truck text-xs"></i>
                            @else
                                <i class="fa-solid fa-truck text-xs"></i>
                            @endif
                        </span>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-sm font-extrabold {{ $stage2Status !== 'waiting' ? 'text-gray-800' : 'text-gray-400' }}">
                                    {{ $isPickup ? 'Siap Diambil' : 'Dalam Pengiriman' }}
                                </h3>
                                @if($stage2Status === 'completed')
                                    <span class="bg-green-50 text-green-600 border border-green-200 px-2 py-0.5 rounded-md text-[9px] uppercase font-black tracking-wider">Selesai</span>
                                @elseif($stage2Status === 'active')
                                    <span class="bg-blue-50 text-blue-600 border border-blue-200 px-2 py-0.5 rounded-md text-[9px] uppercase font-black tracking-wider">Aktif</span>
                                @else
                                    <span class="bg-gray-50 text-gray-400 border border-gray-200 px-2 py-0.5 rounded-md text-[9px] uppercase font-black tracking-wider">Menunggu</span>
                                @endif
                            </div>
                            <p class="text-xs {{ $stage2Status !== 'waiting' ? 'text-gray-500' : 'text-gray-400' }} mt-1">
                                @if($isPickup)
                                    @if($stage2Status === 'completed')
                                        Pesanan telah selesai diserahkan ke Agen.
                                    @elseif($stage2Status === 'active')
                                        Pesanan siap diambil di counter Gudang Utama AGRIS.
                                    @else
                                        Menunggu pesanan selesai dikemas.
                                    @endif
                                @else
                                    @if($stage2Status === 'completed')
                                        Paket telah sampai di kota / daerah tujuan pengiriman.
                                    @elseif($stage2Status === 'active')
                                        Paket sedang dalam perjalanan menuju alamat Agen. (Kurir: {{ strtoupper($courierInfo) }})
                                    @else
                                        Menunggu paket dijemput oleh pihak ekspedisi.
                                    @endif
                                @endif
                            </p>
                            @if($stage2Time)
                                <span class="inline-block text-[10px] font-bold text-gray-400 mt-1.5 bg-gray-50 px-2 py-0.5 rounded border border-gray-150">{{ $stage2Time }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Step 3: Diterima / Selesai Diambil -->
                    <div class="relative">
                        <span class="absolute -left-12 top-0.5 flex h-8 w-8 items-center justify-center rounded-full {{ $stage3Status === 'completed' ? 'bg-[#0f8629] text-white ring-4 ring-green-100' : 'bg-gray-100 text-gray-400 ring-4 ring-gray-50' }} shadow-sm">
                            @if($stage3Status === 'completed')
                                <i class="fa-solid fa-check text-xs"></i>
                            @else
                                <i class="fa-solid fa-circle-check text-xs"></i>
                            @endif
                        </span>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-sm font-extrabold {{ $stage3Status !== 'waiting' ? 'text-gray-800' : 'text-gray-400' }}">
                                    {{ $isPickup ? 'Selesai Diambil' : 'Diterima' }}
                                </h3>
                                @if($stage3Status === 'completed')
                                    <span class="bg-green-50 text-green-600 border border-green-200 px-2 py-0.5 rounded-md text-[9px] uppercase font-black tracking-wider">Selesai</span>
                                @elseif($stage3Status === 'active')
                                    <span class="bg-blue-50 text-blue-600 border border-blue-200 px-2 py-0.5 rounded-md text-[9px] uppercase font-black tracking-wider">Aktif</span>
                                @else
                                    <span class="bg-gray-50 text-gray-400 border border-gray-200 px-2 py-0.5 rounded-md text-[9px] uppercase font-black tracking-wider">Menunggu</span>
                                @endif
                            </div>
                            <p class="text-xs {{ $stage3Status !== 'waiting' ? 'text-gray-500' : 'text-gray-400' }} mt-1">
                                @if($isPickup)
                                    @if($stage3Status === 'completed')
                                        Seluruh proses transaksi selesai. Produk telah diterima oleh Agen.
                                    @else
                                        Menunggu pengambilan selesai dilakukan.
                                    @endif
                                @else
                                    @if($stage3Status === 'completed')
                                        Transaksi selesai. Paket telah diterima oleh Agen.
                                    @else
                                        Menunggu paket sampai di alamat tujuan dan dikonfirmasi Agen.
                                    @endif
                                @endif
                            </p>
                            @if($stage3Time)
                                <span class="inline-block text-[10px] font-bold text-gray-400 mt-1.5 bg-gray-50 px-2 py-0.5 rounded border border-gray-150">{{ $stage3Time }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                @if(isset($trackingData) && !empty($trackingData['history']))
                    <div class="mt-6 pt-4 border-t border-gray-100">
                        <div class="flex justify-between items-center text-xs font-extrabold text-gray-500 uppercase tracking-wider select-none mb-4">
                            <span>Histori Log Pengiriman Detail</span>
                        </div>
                        <div class="relative border-l-2 border-dashed border-gray-200 pl-6 ml-2.5 mt-4 space-y-4">
                            @foreach(array_reverse($trackingData['history']) as $event)
                                <div class="relative">
                                    @php
                                        $isLatest = $loop->first;
                                        $statusLabel = match($event['status']) {
                                            'confirmed' => 'Pesanan Dikonfirmasi',
                                            'allocated' => 'Kurir Dialokasikan',
                                            'pickingUp', 'picking_up' => 'Proses Penjemputan',
                                            'picked' => 'Paket Dijemput Kurir',
                                            'inTransit', 'in_transit' => 'Dalam Transit / Pengiriman',
                                            'droppingOff', 'dropping_off' => 'Kurir Menuju Lokasi Tujuan',
                                            'delivered' => 'Paket Diterima',
                                            'rejected' => 'Paket Ditolak/Bermasalah',
                                            'cancelled' => 'Pengiriman Dibatalkan',
                                            'returned' => 'Paket Dikembalikan',
                                            default => strtoupper($event['status'])
                                        };
                                    @endphp
                                    <span class="absolute -left-8.5 top-0.5 w-3.5 h-3.5 rounded-full {{ $isLatest ? 'bg-purple-600' : 'bg-gray-300' }} border-2 border-white shadow-sm"></span>
                                    <div class="text-xs">
                                        <span class="text-gray-400 font-bold block mb-0.5">{{ \Carbon\Carbon::parse($event['updated_at'])->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d M Y, H:i') }} WIB</span>
                                        <p class="font-extrabold {{ $isLatest ? 'text-purple-600' : 'text-gray-700' }}">{{ $statusLabel }}</p>
                                        <p class="text-gray-500 mt-0.5 font-medium leading-relaxed">{{ $event['note'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
            @endif

            <div class="bg-white p-4 md:p-6 rounded-3xl shadow-md">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                    <div class="w-8 h-8 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600">
                        <i class="fa-solid fa-wallet text-sm"></i>
                    </div>
                    <h2 class="font-extrabold text-gray-800 text-xs md:text-sm">Rincian Pembayaran</h2>
                </div>

                @if($pesanan->pembayaran)
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-xs font-bold">
                        <div>
                            <span class="text-[9px] text-gray-400 font-black uppercase tracking-wider block">Total Tagihan</span>
                            <span class="text-base md:text-lg font-black text-gray-800 mt-1 block">Rp {{ number_format($pesanan->pembayaran->totalPembayaran, 0, ',', '.') }}</span>
                        </div>
                        <div>
                            <span class="text-[9px] text-gray-400 font-black uppercase tracking-wider block">Status Midtrans</span>
                            <div class="mt-1">
                                @if($pesanan->pembayaran->statusPembayaran === 'berhasil')
                                    <span class="bg-green-50 text-green-600 border border-green-200 px-3 py-1 rounded-full text-[9px] md:text-[10px] uppercase font-black tracking-wider inline-flex items-center gap-1">
                                        <i class="fa-solid fa-check"></i> BERHASIL
                                    </span>
                                @elseif($pesanan->pembayaran->statusPembayaran === 'pending')
                                    <span class="bg-amber-50 text-amber-600 border border-amber-200 px-3 py-1 rounded-full text-[9px] md:text-[10px] uppercase font-black tracking-wider inline-flex items-center gap-1">
                                        <i class="fa-solid fa-clock"></i> PENDING
                                    </span>
                                @else
                                    <span class="bg-red-50 text-red-600 border border-red-200 px-3 py-1 rounded-full text-[9px] md:text-[10px] uppercase font-black tracking-wider inline-flex items-center gap-1">
                                        <i class="fa-solid fa-xmark"></i> GAGAL
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="space-y-2 font-mono text-[9px] md:text-[10px] text-gray-400">
                            <div>
                                <span class="block font-sans uppercase font-black text-[9px]">Pilihan Metode Pembayaran</span>
                                <span class="text-gray-700 font-black text-xs">
                                    {{ $getFriendlyPaymentMethod($pesanan->pembayaran) }}
                                </span>
                            </div>

                        </div>
                    </div>
                @else
                    <p class="text-gray-400 text-xs font-semibold">Rincian pembayaran belum dibuat.</p>
                @endif
            </div>
        </div>

        <div class="lg:sticky lg:top-28 space-y-6" data-aos="fade-left" data-aos-delay="100">
            <div class="bg-white p-4 md:p-6 rounded-3xl shadow-md relative overflow-hidden">

                <div class="absolute top-0 right-0 w-20 h-20 bg-linear-to-bl from-green-50 to-transparent pointer-events-none rounded-bl-full"></div>

                <h2 class="font-extrabold text-gray-800 text-xs md:text-sm mb-4 pb-3 border-b border-gray-100 uppercase tracking-wider">Status</h2>

                <div class="mb-6 bg-gray-50 border border-gray-100 p-4 rounded-2xl flex items-center justify-between">
                    <span class="text-[10px] md:text-xs text-gray-400 font-black uppercase tracking-wider">Status Saat Ini</span>
                    <div>
                        @if($pesanan->status_pesanan === 'pending')
                            <span class="bg-amber-50 text-amber-600 border border-amber-200 px-3 py-1 rounded-xl text-[10px] md:text-xs font-black uppercase">Belum Bayar</span>
                        @elseif($pesanan->status_pesanan === 'diproses')
                            <span class="bg-blue-50 text-blue-600 border border-blue-200 px-3 py-1 rounded-xl text-[10px] md:text-xs font-black uppercase">Dikemas</span>
                        @elseif($pesanan->status_pesanan === 'dikirim')
                            <span class="bg-purple-50 text-purple-600 border border-purple-200 px-3 py-1 rounded-xl text-[10px] md:text-xs font-black uppercase">Dikirim</span>
                        @elseif($pesanan->status_pesanan === 'selesai')
                            <span class="bg-green-50 text-green-600 border border-green-200 px-3 py-1 rounded-xl text-[10px] md:text-xs font-black uppercase">Selesai</span>
                        @else
                            <span class="bg-red-50 text-red-600 border border-red-200 px-3 py-1 rounded-xl text-[10px] md:text-xs font-black uppercase">Dibatalkan</span>
                        @endif
                    </div>
                </div>

                <div class="space-y-4">
                    @if($pesanan->status_pesanan === 'pending')

                        <div class="space-y-3">
                            <p class="text-[10px] md:text-[11px] text-gray-400 font-bold leading-normal">Pembayaran belum terkonfirmasi oleh Midtrans. Jika pelanggan telah membayar via jalur alternatif, Anda dapat mengonfirmasi pembayaran secara manual.</p>

                            <form action="{{ route('admin.pesanan.action', $pesanan->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="action" value="proses">
                                <button type="submit" class="w-full bg-[#0f8629] hover:bg-[#0c6b20] text-white py-3 rounded-2xl font-black text-xs flex items-center justify-center gap-2 shadow-sm transition">
                                    <i class="fa-solid fa-credit-card"></i> Konfirmasi Bayar & Proses
                                </button>
                            </form>

                            <form action="{{ route('admin.pesanan.action', $pesanan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan transaksi ini? Stok produk akan dikembalikan.')">
                                @csrf
                                <input type="hidden" name="action" value="batal">
                                <button type="submit" class="w-full border border-red-200 text-red-600 hover:bg-red-50 py-3 rounded-2xl font-black text-xs flex items-center justify-center gap-2 transition">
                                    <i class="fa-solid fa-ban"></i> Batalkan Pesanan
                                </button>
                            </form>
                        </div>

                    @elseif($pesanan->status_pesanan === 'diproses')

                        <div class="space-y-4">
                            @php
                                $isAmbil = str_contains(strtolower($pesanan->deskripsi), 'ambil');
                            @endphp

                            @if($isAmbil)
                                <p class="text-[10px] md:text-[11px] text-gray-400 font-bold leading-normal">Pesanan ini menggunakan opsi <strong>Ambil di Tempat</strong>. Klik tombol di bawah ini untuk menandai pesanan siap diambil.</p>

                                <form id="formKirimPesanan" action="{{ route('admin.pesanan.action', $pesanan->id) }}" method="POST" class="space-y-3">
                                    @csrf
                                    <input type="hidden" name="action" value="kirim">
                                    <button type="submit" id="btnKirimPesanan" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-2xl font-black text-xs flex items-center justify-center gap-2 shadow-sm transition cursor-pointer">
                                        <i class="fa-solid fa-warehouse"></i> Siapkan untuk Diambil
                                    </button>
                                </form>
                            @else
                                <p class="text-[10px] md:text-[11px] text-gray-400 font-bold leading-normal">Pesanan sedang dikemas. Klik tombol di bawah ini untuk memproses pengiriman dan membuat nomor resi otomatis dari Biteship Sandbox.</p>

                                <form id="formKirimPesanan" action="{{ route('admin.pesanan.action', $pesanan->id) }}" method="POST" class="space-y-3">
                                    @csrf
                                    <input type="hidden" name="action" value="kirim">
                                    <button type="submit" id="btnKirimPesanan" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-2xl font-black text-xs flex items-center justify-center gap-2 shadow-sm transition cursor-pointer">
                                        <i class="fa-solid fa-paper-plane"></i> Lanjutkan ke pengiriman
                                    </button>
                                </form>
                            @endif


                            <div class="border-t border-gray-100 my-4"></div>

                            <form action="{{ route('admin.pesanan.action', $pesanan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan transaksi ini? Stok produk akan dikembalikan.')">
                                @csrf
                                <input type="hidden" name="action" value="batal">
                                <button type="submit" class="w-full border border-red-200 text-red-600 hover:bg-red-50 py-3 rounded-2xl font-black text-xs flex items-center justify-center gap-2 transition">
                                    <i class="fa-solid fa-ban"></i> Batalkan Pesanan
                                </button>
                            </form>
                        </div>

                    @elseif($pesanan->status_pesanan === 'dikirim')

                        <div class="text-center py-6 bg-purple-50 border border-purple-100 rounded-2xl">
                            <i class="fa-solid fa-truck text-purple-500 text-3xl mb-2"></i>
                            <h4 class="font-extrabold text-purple-800 text-[10px] md:text-xs uppercase tracking-wider">Proses Pengambilan / Pengiriman</h4>
                            <p class="text-[9px] md:text-[10px] text-purple-700 mt-1 leading-normal px-4 font-bold">Pesanan dalam proses. Pantau status pengiriman</p>
                        </div>

                    @elseif($pesanan->status_pesanan === 'selesai')

                        <div class="text-center py-6 bg-green-50 border border-green-100 rounded-2xl" data-order-finished="true">
                            <i class="fa-solid fa-circle-check text-green-500 text-3xl mb-2"></i>
                            <h4 class="font-extrabold text-green-800 text-[10px] md:text-xs uppercase tracking-wider">Transaksi Selesai</h4>
                            <p class="text-[9px] md:text-[10px] text-green-700 mt-1 leading-normal px-4 font-bold">Pesanan telah sampai di tangan pelanggan dan transaksi ditutup.</p>
                        </div>

                    @elseif($pesanan->status_pesanan === 'dibatalkan')

                        <div class="text-center py-6 bg-red-50 border border-red-100 rounded-2xl" data-order-finished="true">
                            <i class="fa-solid fa-circle-xmark text-red-500 text-3xl mb-2"></i>
                            <h4 class="font-extrabold text-red-800 text-[10px] md:text-xs uppercase tracking-wider">Transaksi Dibatalkan</h4>
                            <p class="text-[9px] md:text-[10px] text-red-700 mt-1 leading-normal px-4 font-bold">Pesanan telah dibatalkan dan stok barang telah dikembalikan.</p>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (window.Echo) {
            window.Echo.channel('order.{{ $pesanan->id }}')
                .listen('.OrderStatusUpdated', (e) => {
                    console.log('Order status updated via Reverb:', e);
                    updateOrderContent();
                });
        }

        @if(in_array($pesanan->status_pesanan, ['pending', 'diproses', 'dikirim']))
        const pollInterval = setInterval(function() {
            updateOrderContent(function(isFinished) {
                if (isFinished) {
                    clearInterval(pollInterval);
                }
            });
        }, 500);
        @endif

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
    });

    document.addEventListener('submit', function(e) {
        if (e.target && e.target.id === 'formKirimPesanan') {
            const btn = document.getElementById('btnKirimPesanan');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin mr-2"></i> Menghubungkan Biteship...';
                btn.className = "w-full bg-slate-300 text-slate-500 py-3 rounded-2xl font-black text-xs flex items-center justify-center gap-2 cursor-wait transition";
            }
        }
    });
</script>
@endsection
