@extends('layouts.agen')

@section('title', 'Checkout Pembayaran - AGRIS')

@section('content')
<script src="{{ config('services.midtrans.is_production', false) ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ $midtransClientKey }}"></script>

<div class="max-w-6xl mx-auto pt-6 pb-20 px-4 sm:px-6">

    <div class="mb-10 text-center sm:text-left flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
            <a href="{{ route('agen.keranjang.index') }}" class="inline-flex items-center gap-1.5 text-[10px] font-bold text-slate-400 hover:text-[#58CC02] transition-colors uppercase tracking-widest mb-3">
                <i class="fa-solid fa-arrow-left text-xs"></i> Kembali ke Keranjang
            </a>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Checkout Pembayaran</h1>
            <p class="text-slate-500 text-sm mt-1">Selesaikan pemesanan Anda dengan memilih opsi pengiriman dan metode pembayaran</p>
        </div>
    </div>

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-2xl mb-8 text-sm font-bold flex items-center gap-3 shadow-xs">
            <i class="fa-solid fa-triangle-exclamation text-lg"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white p-4 sm:p-6 md:p-8 rounded-3xl border border-slate-100 shadow-sm">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                    <i class="fa-solid fa-basket-shopping text-slate-400 text-lg"></i>
                    <h2 class="font-extrabold text-slate-800 text-sm">Daftar Produk</h2>
                </div>
                <div class="divide-y divide-slate-100">
                    @foreach($keranjangs as $item)
                        <div class="flex items-center gap-3 sm:gap-4 py-4 first:pt-0 last:pb-0">
                            <div class="w-12 h-12 sm:w-14 sm:h-14 bg-slate-50 rounded-xl overflow-hidden border border-slate-100 flex items-center justify-center p-1 shrink-0">
                                @if($item->produk->fotoProduk)
                                    <img src="{{ asset('storage/' . $item->produk->fotoProduk) }}" class="w-full h-full object-cover rounded-lg shadow-xs">
                                @else
                                    <i class="fa-solid fa-image text-lg text-slate-300"></i>
                                @endif
                            </div>
                            <div class="grow min-w-0">
                                <h4 class="font-extrabold text-slate-800 text-xs truncate">{{ $item->produk->namaProduk }}</h4>
                                <p class="text-[11px] text-slate-400 mt-1 font-bold">
                                    {{ $item->jumlah }} barang • Rp {{ number_format($item->produk->harga, 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="font-bold text-slate-800 text-sm">
                                    Rp {{ number_format($item->produk->harga * $item->jumlah, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white p-4 sm:p-6 md:p-8 rounded-3xl border border-slate-100 shadow-sm transition-all duration-300">
                <div class="flex items-center gap-3 mb-8 pb-4 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-2xl bg-[#58CC02]/10 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-truck-fast text-lg text-[#58CC02]"></i>
                    </div>
                    <div>
                        <h2 class="font-extrabold text-slate-800 text-base">Pilihan Pengiriman</h2>
                        <p class="text-xs text-slate-400">Pilih kurir ekspedisi pengiriman atau ambil langsung di gudang</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
                    <label for="type_kirim" class="relative flex flex-col p-4 sm:p-5 rounded-2xl border-2 border-[#58CC02] bg-[#58CC02]/5 cursor-pointer transition-all duration-300 shadow-xs hover:shadow-sm" id="card_type_kirim">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2">
                                <span class="w-8 h-8 rounded-xl bg-[#58CC02]/10 flex items-center justify-center text-[#58CC02]">
                                    <i class="fa-solid fa-truck-ramp-box"></i>
                                </span>
                                <span class="text-sm font-bold text-slate-800 uppercase tracking-wide">Kirim Lewat Kurir</span>
                            </div>
                            <input type="radio" name="delivery_type_selector" id="type_kirim" value="kirim" checked class="w-5 h-5 accent-[#58CC02] cursor-pointer">
                        </div>
                        <span class="text-xs text-slate-500 leading-relaxed font-medium">Paket dikirim langsung ke alamat terdaftar Anda menggunakan kurir ekspedisi terintegrasi.</span>
                    </label>

                    <label for="type_ambil" class="relative flex flex-col p-4 sm:p-5 rounded-2xl border-2 border-slate-100 cursor-pointer transition-all duration-300 shadow-xs hover:border-slate-300 hover:shadow-sm" id="card_type_ambil">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2">
                                <span class="w-8 h-8 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500">
                                    <i class="fa-solid fa-warehouse"></i>
                                </span>
                                <span class="text-sm font-bold text-slate-800 uppercase tracking-wide">Ambil di Tempat</span>
                            </div>
                            <input type="radio" name="delivery_type_selector" id="type_ambil" value="ambil" class="w-5 h-5 accent-[#58CC02] cursor-pointer">
                        </div>
                        <span class="text-xs text-slate-500 leading-relaxed font-medium">Ambil pesanan Anda secara mandiri di Gudang Utama AGRIS Jember. Tanpa biaya pengiriman.</span>
                    </label>
                </div>

                <div id="address_section" class="space-y-6">
                    <div class="bg-slate-50/50 p-4 sm:p-5 rounded-2xl border border-slate-100 flex items-start gap-3 sm:gap-4">
                        <div class="w-8 h-8 rounded-xl bg-slate-100 flex items-center justify-center text-[#58CC02] shrink-0 mt-0.5">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <div class="text-xs leading-relaxed">
                            <p class="font-extrabold text-slate-800 text-sm">{{ $user->namaLengkap }} <span class="text-slate-400 font-bold ml-2">• {{ $user->noTelp }}</span></p>
                            <p class="text-slate-500 mt-1.5">{{ $user->alamatLengkap }}</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Pilihan Layanan Ekspedisi</label>

                        <div id="shipping-loading" class="flex flex-col items-center justify-center py-10 text-slate-400 bg-slate-50/50 rounded-2xl border border-dashed border-slate-200">
                            <i class="fa-solid fa-circle-notch fa-spin text-3xl mb-3 text-[#58CC02]"></i>
                            <span class="text-xs font-bold">Menghubungkan Biteship API untuk memuat tarif terbaik...</span>
                        </div>

                        <div id="shipping-error" class="hidden p-4 bg-red-50 text-red-600 rounded-2xl text-xs font-bold flex items-center gap-2.5">
                            <i class="fa-solid fa-triangle-exclamation text-base"></i>
                            Gagal sinkronisasi data kurir Biteship. Pastikan sambungan internet stabil dan klik tombol "Coba Lagi".
                        </div>

                        <select id="shipping_service" class="hidden">
                            <option value="">-- Pilih Kurir --</option>
                        </select>

                        <div id="courier_cards_container" class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-3">
                        </div>
                    </div>
                </div>

                <div id="pickup_section" class="hidden bg-slate-50/50 p-4 sm:p-5 rounded-2xl border border-slate-100 flex items-start gap-3 sm:gap-4">
                    <div class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center text-[#58CC02] shrink-0 mt-0.5">
                        <i class="fa-solid fa-warehouse text-base"></i>
                    </div>
                    <div class="text-xs">
                        <p class="font-extrabold text-slate-800 text-sm">Gudang Utama AGRIS (Jember)</p>
                        <p class="text-[#58CC02] font-bold mt-1 uppercase tracking-wider">Operasional : Senin - Sabtu (08:00 - 17:00 WIB)</p>
                        <p class="text-slate-500 mt-2.5 leading-relaxed font-medium">
                            Kawasan Bisnis Agris, Jl. Manyar Gg. Kelapa, Puring, Slawu, Patrang, Jember, Jawa Timur
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-4 sm:p-6 md:p-8 rounded-3xl border border-slate-100 shadow-sm">
                <div class="flex items-center gap-3 mb-8 pb-4 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-2xl bg-[#58CC02]/10 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-credit-card text-base text-[#58CC02]"></i>
                    </div>
                    <div>
                        <h2 class="font-extrabold text-slate-800 text-base">Sistem Pembayaran</h2>
                        <p class="text-xs text-slate-400">Penyelesaian pembayaran instan yang terenkripsi dan aman</p>
                    </div>
                </div>

                <div class="relative overflow-hidden flex items-center p-4 sm:p-5 rounded-2xl border-2 border-[#58CC02] bg-linear-to-r from-green-50/20 to-emerald-50/10 cursor-default shadow-xs">
                    <div class="absolute -right-8 -bottom-8 w-24 h-24 text-green-200/20 pointer-events-none">
                        <i class="fa-solid fa-shield-halved text-7xl"></i>
                    </div>

                    <div class="grow flex items-center gap-3 sm:gap-4 relative z-10">
                        <div class="w-12 h-12 rounded-xl bg-white border border-slate-100 flex items-center justify-center shadow-xs shrink-0 font-extrabold text-[#0f8629]">
                            <i class="fa-solid fa-shield-halved text-2xl text-[#58CC02]"></i>
                        </div>
                        <div>
                            <span class="text-sm font-bold text-slate-800 block uppercase tracking-wide">Transfer</span>
                            <span class="text-[10px] text-slate-500 font-bold block mt-0.5">Virtual Account (Mandiri, BCA, BNI, BRI), QRIS, Kartu Kredit, Alfamart/Indomaret</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="sticky top-28 space-y-6">
            <div class="bg-white p-4 sm:p-6 md:p-8 rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-linear-to-bl from-[#58CC02]/10 to-transparent rounded-bl-full pointer-events-none"></div>

                <h2 class="font-bold text-slate-800 text-sm mb-6 pb-4 border-b border-slate-100 uppercase tracking-wider">Ringkasan Belanja</h2>

                <div class="space-y-4 text-xs pb-5 border-b border-slate-100">
                    <div class="flex justify-between text-slate-500 font-bold">
                        <span>Total Harga Barang</span>
                        <span class="text-slate-800 font-extrabold">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-slate-500 font-bold">
                        <span>Total Berat Barang</span>
                        <span class="text-slate-800 font-extrabold">{{ number_format($totalWeight, 0, ',', '.') }} Kg</span>
                    </div>
                    <div class="flex justify-between text-slate-500 font-bold">
                        <span>Biaya Pengiriman</span>
                        <span id="shipping-cost-display" class="text-slate-800 font-extrabold">Memuat Kurir...</span>
                    </div>
                </div>

                <div class="flex justify-between items-center my-6">
                    <div class="flex flex-col">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none">Total Pembayaran</span>
                        <span class="text-[10px] text-slate-400 mt-1 font-bold">Sudah termasuk PPN</span>
                    </div>
                    <span id="total-payment-display" class="text-2xl font-bold text-[#58CC02] tracking-tight">
                        Rp {{ number_format($totalPrice, 0, ',', '.') }}
                    </span>
                </div>

                <form id="formCheckout">
                    <input type="hidden" name="items" value="{{ request('items') }}">
                    <input type="hidden" name="alamat_pengiriman" value="{{ $user->alamatLengkap }}">
                    <input type="hidden" name="delivery_type" id="hidden_delivery_type" value="kirim">
                    <input type="hidden" name="courier_name" id="hidden_courier_name">
                    <input type="hidden" name="courier_service" id="hidden_courier_service">
                    <input type="hidden" name="shipping_cost" id="hidden_shipping_cost" value="0">

                    <button type="submit" id="btnSubmitOrder" disabled class="w-full bg-slate-100 text-slate-400 py-4 rounded-2xl font-bold text-sm flex items-center justify-center gap-2 cursor-not-allowed transition-all duration-300">
                        <i class="fa-solid fa-credit-card text-xs"></i> Bayar Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', async function() {
    const originAreaId = @js($originAreaId);
    const destinationAreaId = @js($destinationAreaId);
    const itemTotal = @js($totalPrice);
    const biteshipItems = [
        @foreach($keranjangs as $item)
        {
            name: @js($item->produk->namaProduk),
            description: "Produk AGRIS",
            value: @js((int) $item->produk->harga),
            quantity: @js((int) $item->jumlah),
            weight: @js((int) ($item->produk->kategori->karung))
        },
        @endforeach
    ];

    const loadingEl = document.getElementById('shipping-loading');
    const errorEl = document.getElementById('shipping-error');
    const cardsContainer = document.getElementById('courier_cards_container');

    const shippingDisplay = document.getElementById('shipping-cost-display');
    const totalDisplay = document.getElementById('total-payment-display');
    const btnSubmit = document.getElementById('btnSubmitOrder');

    const hiddenDeliveryType = document.getElementById('hidden_delivery_type');
    const hiddenCourier = document.getElementById('hidden_courier_name');
    const hiddenService = document.getElementById('hidden_courier_service');
    const hiddenCost = document.getElementById('hidden_shipping_cost');

    const addressSection = document.getElementById('address_section');
    const pickupSection = document.getElementById('pickup_section');

    const cardKirim = document.getElementById('card_type_kirim');
    const cardAmvil = document.getElementById('card_type_ambil');

    let availableRates = [];
    let selectedRateIndex = null;
    let savedSnapToken = null;
    let savedOrderId = null;

    async function loadShippingRates() {
        loadingEl.classList.remove('hidden');
        cardsContainer.classList.add('hidden');
        errorEl.classList.add('hidden');
        shippingDisplay.textContent = 'Memuat Kurir...';
        setButtonState(false);

        try {
            const response = await fetch("{{ route('agen.checkout.cek-ongkir') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    origin_area_id: originAreaId,
                    destination_area_id: destinationAreaId,
                    items: biteshipItems
                })
            });

            if (response.ok) {
                availableRates = await response.json();
                loadingEl.classList.add('hidden');
                cardsContainer.classList.remove('hidden');

                cardsContainer.innerHTML = '';
                availableRates.forEach((rate, index) => {
                    let badgeBg = 'bg-slate-500';
                    const nameLower = rate.courier_name.toLowerCase();
                    if (nameLower.includes('jne')) {
                        badgeBg = 'bg-blue-600';
                    } else if (nameLower.includes('j&t') || nameLower.includes('jnt')) {
                        badgeBg = 'bg-red-600';
                    } else if (nameLower.includes('sicepat')) {
                        badgeBg = 'bg-orange-500';
                    } else if (nameLower.includes('tiki')) {
                        badgeBg = 'bg-sky-600';
                    } else if (nameLower.includes('ninja')) {
                        badgeBg = 'bg-neutral-800';
                    } else if (nameLower.includes('anteraja')) {
                        badgeBg = 'bg-pink-600';
                    }

                    const card = document.createElement('div');
                    card.className = "courier-card relative flex flex-col p-4 sm:p-5 rounded-2xl border-2 border-slate-100 bg-white cursor-pointer transition-all duration-300 hover:border-slate-300 hover:-translate-y-0.5 hover:shadow-xs";
                    card.innerHTML = `
                        <div class="flex items-center justify-between mb-3">
                            <span class="px-2.5 py-0.5 rounded-lg text-[9px] font-bold tracking-wider text-white ${badgeBg} uppercase">${rate.courier_name}</span>
                            <span class="text-[10px] font-bold text-slate-400"><i class="fa-solid fa-clock mr-1"></i>${rate.duration}</span>
                        </div>
                        <div class="mt-1">
                            <span class="text-xs text-slate-500 font-extrabold block uppercase tracking-wider">${rate.courier_service_name}</span>
                            <span class="text-lg font-bold text-slate-800 block mt-1">Rp ${rate.price.toLocaleString('id-ID')}</span>
                        </div>
                        <div class="absolute bottom-4 right-4 hidden select-check-icon">
                            <div class="w-6 h-6 rounded-full bg-[#58CC02] flex items-center justify-center text-white text-xs shadow-sm">
                                <i class="fa-solid fa-check"></i>
                            </div>
                        </div>
                    `;

                    card.addEventListener('click', function() {
                        document.querySelectorAll('.courier-card').forEach(c => {
                            c.className = "courier-card relative flex flex-col p-4 sm:p-5 rounded-2xl border-2 border-slate-100 bg-white cursor-pointer transition-all duration-300 hover:border-slate-300 hover:-translate-y-0.5 hover:shadow-xs";
                            c.querySelector('.select-check-icon').classList.add('hidden');
                        });

                        card.className = "courier-card relative flex flex-col p-4 sm:p-5 rounded-2xl border-2 border-[#58CC02] bg-[#58CC02]/5 scale-[1.02] shadow-xs cursor-pointer transition-all duration-300";
                        card.querySelector('.select-check-icon').classList.remove('hidden');

                        selectedRateIndex = index;
                        const cost = rate.price;
                        const total = itemTotal + cost;

                        shippingDisplay.textContent = 'Rp ' + cost.toLocaleString('id-ID');
                        totalDisplay.textContent = 'Rp ' + total.toLocaleString('id-ID');
                        setButtonState(true);

                        hiddenCourier.value = rate.courier_name;
                        hiddenService.value = rate.courier_service_code;
                        hiddenCost.value = cost;

                        savedSnapToken = null;
                        savedOrderId = null;
                    });

                    cardsContainer.appendChild(card);
                });

                shippingDisplay.textContent = 'Pilih Kurir';
            } else {
                throw new Error('Gagal');
            }
        } catch (e) {
            console.error(e);
            loadingEl.classList.add('hidden');
            errorEl.classList.remove('hidden');
            shippingDisplay.textContent = 'Error';
        }
    }

    await loadShippingRates();

    document.querySelectorAll('input[name="delivery_type_selector"]').forEach(radio => {
        radio.addEventListener('change', async function() {
            const type = this.value;
            hiddenDeliveryType.value = type;

            savedSnapToken = null;
            savedOrderId = null;

            if (type === 'kirim') {
                cardKirim.className = "relative flex flex-col p-5 rounded-2xl border-2 border-[#58CC02] bg-[#58CC02]/5 cursor-pointer transition-all duration-300 shadow-xs hover:shadow-sm";
                cardAmvil.className = "relative flex flex-col p-5 rounded-2xl border-2 border-slate-100 cursor-pointer transition-all duration-300 hover:border-slate-300 hover:shadow-sm";

                addressSection.classList.remove('hidden');
                pickupSection.classList.add('hidden');
                selectedRateIndex = null;

                await loadShippingRates();
            } else {
                cardAmvil.className = "relative flex flex-col p-5 rounded-2xl border-2 border-[#58CC02] bg-[#58CC02]/5 cursor-pointer transition-all duration-300 shadow-xs hover:shadow-sm";
                cardKirim.className = "relative flex flex-col p-5 rounded-2xl border-2 border-slate-100 cursor-pointer transition-all duration-300 hover:border-slate-300 hover:shadow-sm";

                addressSection.classList.add('hidden');
                pickupSection.classList.remove('hidden');

                shippingDisplay.textContent = 'Rp 0';
                totalDisplay.textContent = 'Rp ' + itemTotal.toLocaleString('id-ID');

                hiddenCourier.value = "Ambil di Tempat";
                hiddenService.value = "Ambil Sendiri";
                hiddenCost.value = "0";

                setButtonState(true);
            }
        });
    });

    function setButtonState(enabled) {
        if (enabled) {
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = '<i class="fa-solid fa-credit-card text-xs"></i> Bayar Sekarang';
            btnSubmit.className = "w-full bg-[#58CC02] hover:bg-[#46A302] text-white py-4 rounded-2xl font-bold text-sm flex items-center justify-center gap-2 shadow-md transition duration-300 cursor-pointer hover:scale-[1.02] active:scale-95";
        } else {
            btnSubmit.disabled = true;
            btnSubmit.innerHTML = '<i class="fa-solid fa-credit-card text-xs"></i> Bayar Sekarang';
            btnSubmit.className = "w-full bg-slate-100 text-slate-400 py-4 rounded-2xl font-bold text-sm flex items-center justify-center gap-2 cursor-not-allowed transition duration-300";
        }
    }

    function showGeneralError(message) {
        const modalMsg = document.querySelector('#generalErrorModal p');
        if (modalMsg) {
            modalMsg.textContent = message;
        }
        window.openModal('generalErrorModal');
    }

    function showGeneralError(message) {
        const modalMsg = document.querySelector('#generalErrorModal p');
        if (modalMsg) {
            modalMsg.textContent = message;
        }
        window.openModal('generalErrorModal');
    }

    document.getElementById('btnCloseErrorModal').onclick = function() {
        window.closeModal('generalErrorModal');
    };
    document.getElementById('btnCloseErrorModal2').onclick = function() {
        window.closeModal('generalErrorModal');
    };

    const form = document.getElementById('formCheckout');
    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        btnSubmit.disabled = true;
        btnSubmit.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Memproses Pesanan...';
        btnSubmit.className = "w-full bg-slate-300 text-slate-500 py-4 rounded-2xl font-bold text-sm flex items-center justify-center gap-2 cursor-wait transition duration-300";

        if (savedSnapToken && savedOrderId) {
            if (savedSnapToken.startsWith('MOCK-SNAP-TOKEN-')) {
                window.location.href = `/agen/pesanan/${savedOrderId}`;
                return;
            }
            window.snap.pay(savedSnapToken, {
                onSuccess: function(result) {
                    window.location.href = `/agen/pesanan/${savedOrderId}?status=success`;
                },
                onPending: function(result) {
                    setButtonState(true);
                },
                onError: function(result) {
                    showGeneralError(result.status_message || 'Pembayaran gagal.');
                    setButtonState(true);
                },
                onClose: function() {
                    setButtonState(true);
                }
            });
            return;
        }

        const formData = {
            items: form.querySelector('[name="items"]').value,
            alamat_pengiriman: form.querySelector('[name="alamat_pengiriman"]').value,
            delivery_type: hiddenDeliveryType.value,
            courier_name: hiddenCourier.value,
            courier_service: hiddenService.value,
            shipping_cost: parseFloat(hiddenCost.value)
        };

        try {
            const response = await fetch("{{ route('agen.checkout.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(formData)
            });

            const data = await response.json();

            if (data.success) {
                savedSnapToken = data.snap_token;
                savedOrderId = data.order_id;

                if (savedSnapToken && savedSnapToken.startsWith('MOCK-SNAP-TOKEN-')) {
                    window.location.href = `/agen/pesanan/${savedOrderId}`;
                    return;
                }

                window.snap.pay(savedSnapToken, {
                    onSuccess: function(result) {
                        window.location.href = `/agen/pesanan/${savedOrderId}?status=success`;
                    },
                    onPending: function(result) {
                        setButtonState(true);
                    },
                    onError: function(result) {
                        showGeneralError(result.status_message || 'Pembayaran gagal.');
                        setButtonState(true);
                    },
                    onClose: function() {
                        setButtonState(true);
                    }
                });
            } else {
                showGeneralError(data.message || 'Gagal memproses pesanan.');
                setButtonState(true);
            }
        } catch (error) {
            console.error(error);
            showGeneralError('Terjadi kesalahan jaringan atau konfigurasi Midtrans key belum valid.');
            setButtonState(true);
        }
    });
});
</script>

<x-modal id="generalErrorModal"
         title="Kesalahan Sistem"
         message="Terjadi kesalahan jaringan atau konfigurasi Midtrans key belum valid."
         confirmText="Tutup"
         cancelText="Batal"
         confirmId="btnCloseErrorModal"
         cancelId="btnCloseErrorModal2" />
@endsection
