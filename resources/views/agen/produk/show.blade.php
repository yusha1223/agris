@extends('layouts.agen')

@section('title', $item->namaProduk . ' - Detail Produk')

@section('content')
<div class="max-w-7xl mx-auto pt-3 md:pt-5 pb-12 px-3 md:px-6">
    <div class="flex items-center gap-3 pb-5" data-aos="fade-up">
        <a href="{{ route('agen.produk.index') }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 transition shadow-sm">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1 class="text-xl font-bold text-gray-800">Detail Produk</h1>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up" data-aos-delay="100">
        <div class="grid grid-cols-1 md:grid-cols-2">
            <div class="p-6 md:p-10 bg-gray-50 flex items-center justify-center" data-aos="fade-right" data-aos-delay="200">
                <div class="relative w-auto rounded-2xl overflow-hidden shadow-lg bg-white">
                    @if($item->fotoProduk)
                        <img src="{{ asset('storage/' . $item->fotoProduk) }}" class="w-full h-full max-h-125 object-cover transition duration-500" alt="{{ $item->namaProduk }}">
                    @else
                        <div class="flex flex-col items-center justify-center aspect-square text-gray-300 p-16">
                            <i class="fa-solid fa-image text-8xl mb-4"></i>
                            <p class="font-bold">Foto tidak tersedia</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="p-8 md:p-12 flex flex-col" data-aos="fade-left" data-aos-delay="200">
                <div class="mb-6">
                    <h1 class="text-3xl md:text-4xl font-black text-gray-900 leading-tight mb-2">{{ $item->namaProduk }}</h1>
                    <p class="text-2xl font-bold text-[#58CC02]">
                        Rp {{ number_format($item->harga, 0, ',', '.') }}
                        <span class="text-sm text-gray-400 font-medium">/ Karung</span>
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-8">
                    <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100">
                        <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Jenis</p>
                        <p class="font-bold text-gray-800 uppercase">{{ $item->kategori->jenisKategori }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100">
                        <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Mutu Produk</p>
                        <p class="font-bold text-gray-800 uppercase">{{ strtoupper($item->kategori->mutu) }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100">
                        <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Ukuran Karung</p>
                        <p class="font-bold text-gray-800 uppercase">{{ $item->kategori->karung }} Kg</p>
                    </div>
                </div>

                <div class="mb-8">
                    <h4 class="font-black text-gray-800 uppercase text-xs tracking-wider mb-3">Deskripsi Produk</h4>
                    <div class="text-gray-600 leading-relaxed text-sm space-y-4">
                        {!! nl2br(e($item->deskripsi ?? 'Belum ada deskripsi untuk produk ini.')) !!}
                    </div>
                </div>

                <div class="mt-auto pt-8 border-t border-gray-100">
                    <div class="flex items-center justify-between gap-6 mb-4">
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase">Stok Tersedia</p>
                            <p id="detail-stock-count" class="text-lg font-black {{ $item->stok > 0 ? 'text-gray-800' : 'text-red-500' }}">
                                <span class="stock-val">{{ $item->stok }}</span> <span class="text-xs font-bold text-gray-400">Karung</span>
                            </p>
                        </div>

                        <div id="qty-control-container" class="flex items-center gap-2 bg-gray-50 rounded-2xl border border-gray-100 px-3 py-2 {{ $item->stok > 0 ? '' : 'hidden' }}">
                            <button type="button" id="btn-minus" class="w-8 h-8 rounded-xl bg-white border border-gray-200 text-gray-600 hover:bg-gray-100 transition font-bold text-base flex items-center justify-center shadow-sm">
                                <i class="fa-solid fa-minus text-xs"></i>
                            </button>
                            <input type="number" id="qty-input" value="1" min="1" max="{{ $item->stok }}" class="w-10 text-center font-black text-gray-800 text-sm bg-transparent outline-none">
                            <button type="button" id="btn-plus" class="w-8 h-8 rounded-xl bg-white border border-gray-200 text-gray-600 hover:bg-gray-100 transition font-bold text-base flex items-center justify-center shadow-sm">
                                <i class="fa-solid fa-plus text-xs"></i>
                            </button>
                        </div>
                    </div>

                    <form id="detail-cart-form">
                        @csrf
                        <input type="hidden" name="produkId" value="{{ $item->id }}">
                        <input type="hidden" name="jumlah" id="jumlah-input" value="1">

                        <button type="button" id="btn-add-cart" {{ $item->stok <= 0 ? 'disabled' : '' }} class="w-full {{ $item->stok <= 0 ? 'bg-gray-300 cursor-not-allowed' : 'bg-[#58CC02] hover:bg-[#46a302] shadow-lg shadow-[#58CC02]/20' }} text-white py-4 rounded-2xl transition-all font-black flex items-center justify-center gap-3">
                            <i class="fa-solid fa-cart-plus"></i>
                            {{ $item->stok <= 0 ? 'Stok Habis' : 'Tambah Pesanan' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<x-modal id="modalAksesMitra" title="Anda Belum Bermitra?" message="Anda harus menjadi mitra aktif untuk menambahkan produk ke keranjang." confirmText="Baik" cancelText="Batal" confirmId="btnConfirmMitra" cancelId="btnCancelMitra" />

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
    setTimeout(() => { alertDiv.style.opacity = '1'; alertDiv.style.transform = 'translateX(0)'; }, 100);
    setTimeout(() => {
        alertDiv.style.opacity = '0';
        alertDiv.style.transform = 'translateX(20px)';
        setTimeout(() => { alertDiv.remove(); }, 500);
    }, 4000);
}

const qtyInput = document.getElementById('qty-input');
const jumlahInput = document.getElementById('jumlah-input');
let maxStok = {{ $item->stok }};

function syncJumlah() { if (jumlahInput) jumlahInput.value = qtyInput.value; }

document.getElementById('btn-minus')?.addEventListener('click', () => {
    let val = parseInt(qtyInput.value);
    if (val > 1) { qtyInput.value = val - 1; syncJumlah(); }
});

document.getElementById('btn-plus')?.addEventListener('click', () => {
    let val = parseInt(qtyInput.value);
    if (val < maxStok) { qtyInput.value = val + 1; syncJumlah(); }
});

qtyInput?.addEventListener('input', () => {
    let val = parseInt(qtyInput.value);
    if (isNaN(val) || val < 1) qtyInput.value = 1;
    if (val > maxStok) qtyInput.value = maxStok;
    syncJumlah();
});

document.getElementById('btn-add-cart')?.addEventListener('click', function () {
    const isMitra = {{ auth()->user()->isActive == 1 ? 'true' : 'false' }};
    if (!isMitra) { openModal('modalAksesMitra'); return; }
    const form = document.getElementById('detail-cart-form');
    const formData = new FormData(form);
    fetch("{{ route('agen.produk.add-to-cart') }}", {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => {
        if (res.status === 403) { openModal('modalAksesMitra'); throw new Error('Unauthorized'); }
        return res.json();
    })
    .then(data => {
        if (data.cartCount !== undefined) updateCartBadge(data.cartCount);
        showNotification('Informasi', data.message, 'success');
    })
    .catch(err => {
        console.error('Error:', err);
        showNotification('Gagal', 'Terjadi kesalahan sistem', 'error');
    });
});

document.getElementById('btnConfirmMitra')?.addEventListener('click', () => closeModal('modalAksesMitra'));
document.getElementById('btnCancelMitra')?.addEventListener('click', () => closeModal('modalAksesMitra'));

if (window.Echo) {
    window.Echo.channel('produk-channel')
        .listen('.ProdukUpdated', (e) => {
            const prod = e.produk;
            if (prod.id == "{{ $item->id }}") {
                maxStok = parseInt(prod.stok);

                const stockEl = document.getElementById('detail-stock-count');
                if (stockEl) {
                    const valEl = stockEl.querySelector('.stock-val');
                    if (valEl) valEl.textContent = maxStok;

                    if (maxStok > 0) {
                        stockEl.className = "text-lg font-black text-gray-800";
                    } else {
                        stockEl.className = "text-lg font-black text-red-500";
                    }
                }

                if (qtyInput) {
                    qtyInput.max = maxStok;
                    if (parseInt(qtyInput.value) > maxStok) {
                        qtyInput.value = maxStok || 1;
                        syncJumlah();
                    }
                }

                const controlContainer = document.getElementById('qty-control-container');
                if (controlContainer) {
                    if (maxStok > 0) {
                        controlContainer.classList.remove('hidden');
                    } else {
                        controlContainer.classList.add('hidden');
                    }
                }

                const btnAddCart = document.getElementById('btn-add-cart');
                if (btnAddCart) {
                    if (maxStok <= 0) {
                        btnAddCart.disabled = true;
                        btnAddCart.className = "w-full bg-gray-300 cursor-not-allowed text-white py-4 rounded-2xl transition-all font-black flex items-center justify-center gap-3";
                        btnAddCart.innerHTML = '<i class="fa-solid fa-cart-plus"></i> Stok Habis';
                    } else {
                        btnAddCart.disabled = false;
                        btnAddCart.className = "w-full bg-[#58CC02] hover:bg-[#46a302] shadow-lg shadow-[#58CC02]/20 text-white py-4 rounded-2xl transition-all font-black flex items-center justify-center gap-3";
                        btnAddCart.innerHTML = '<i class="fa-solid fa-cart-plus"></i> Tambah Pesanan';
                    }
                }
            }
        });
}
</script>
@endsection
