@extends('layouts.agen')

@section('title', 'Keranjang - AGRIS')

@section('content')
<div class="max-w-7xl mx-auto pt-5 pb-12 px-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4" data-aos="fade-up">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Keranjang</h1>
            <p class="text-gray-500 text-sm">Gunakan keranjang Anda</p>
        </div>
    </div>

    @if($keranjangs->isEmpty())
    <div class="py-24 text-center bg-white rounded-2xl border border-gray-100 shadow-sm" data-aos="zoom-in">
        <i class="fa-solid fa-cart-shopping text-5xl text-gray-200 mb-4"></i>
        <p class="text-gray-400 font-bold uppercase text-sm tracking-widest mb-4">Keranjang masih kosong.</p>
        <a href="{{ route('agen.produk.index') }}" class="inline-block bg-[#58CC02] text-white px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-[#46A302] transition">
            Mulai Belanja
        </a>
    </div>
    @else
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden" data-aos="fade-up" data-aos-delay="100">
        <div class="hidden md:block w-full overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/60">
                        <th class="w-10 px-4 py-3"></th>
                        <th class="w-28 px-5 py-3"></th>
                        <th class="px-5 py-3 text-left text-[11px] font-bold text-gray-400 uppercase tracking-wide">Nama Produk</th>
                        <th class="px-5 py-3 text-left text-[11px] font-bold text-gray-400 uppercase tracking-wide">Jenis</th>
                        <th class="px-5 py-3 text-left text-[11px] font-bold text-gray-400 uppercase tracking-wide">Mutu</th>
                        <th class="px-5 py-3 text-left text-[11px] font-bold text-gray-400 uppercase tracking-wide">Berat</th>
                        <th class="px-5 py-3 text-center text-[11px] font-bold text-gray-400 uppercase tracking-wide">Jumlah</th>
                        <th class="px-5 py-3 text-left text-[11px] font-bold text-gray-400 uppercase tracking-wide">Harga</th>
                        <th class="px-5 py-3 text-right text-[11px] font-bold text-gray-400 uppercase tracking-wide pr-5">Subtotal</th>
                        <th class="w-12 px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody id="keranjang-list-desktop">
                    @foreach($keranjangs as $item)
                    <tr class="keranjang-item border-b border-gray-100 last:border-b-0 hover:bg-gray-50/40 transition-colors"
                        data-id="{{ $item->id }}"
                        data-harga="{{ $item->produk->harga }}"
                        data-karung="{{ $item->produk->kategori->karung }}">
                        <td class="px-4 py-4 text-center">
                            <input type="checkbox"
                                class="item-checkbox w-5 h-5 rounded accent-[#58CC02] cursor-pointer"
                                onchange="syncCheckbox(this)"
                                data-id="{{ $item->id }}">
                        </td>
                        <td class="px-5 py-4">
                            <div class="w-20 h-20 rounded-xl overflow-hidden bg-gray-50 border border-gray-100 flex items-center justify-center">
                                @if($item->produk->fotoProduk)
                                    <img src="{{ asset('storage/' . $item->produk->fotoProduk) }}" class="w-full h-full object-cover">
                                @else
                                    <i class="fa-solid fa-image text-xl text-gray-200"></i>
                                @endif
                            </div>
                        </td>
                        <td class="px-5 py-4"><p class="font-semibold text-gray-800 text-sm">{{ $item->produk->namaProduk }}</p></td>
                        <td class="px-5 py-4"><p class="text-sm text-gray-600">{{ $item->produk->kategori->jenisKategori }}</p></td>
                        <td class="px-5 py-4"><p class="text-sm text-gray-600">{{ $item->produk->kategori->mutu }}</p></td>
                        <td class="px-5 py-4"><p class="text-sm text-gray-600">{{ $item->produk->kategori->karung }} Kg</p></td>
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="triggerKurang('{{ $item->id }}')" class="w-8 h-8 rounded-full bg-red-500 hover:bg-red-600 text-white flex items-center justify-center transition text-xs shrink-0"><i class="fa-solid fa-minus"></i></button>
                                <input type="number" min="1" value="{{ $item->jumlah }}" class="jumlah-val w-16 text-center border border-gray-200 focus:border-[#58CC02] focus:ring-2 focus:ring-[#58CC02]/20 text-sm font-bold rounded-xl py-1.5 px-2 mx-1 focus:outline-none" onchange="triggerUpdateInput(this, '{{ $item->id }}')">
                                <button onclick="tambahJumlah('{{ $item->id }}')" class="w-8 h-8 rounded-full bg-[#58CC02] hover:bg-[#46A302] text-white flex items-center justify-center transition text-xs shrink-0"><i class="fa-solid fa-plus"></i></button>
                            </div>
                        </td>
                        <td class="px-5 py-4"><p class="text-sm font-bold text-gray-800 whitespace-nowrap">Rp {{ number_format($item->produk->harga, 0, ',', '.') }}</p></td>
                        <td class="px-5 py-4 text-right pr-5"><p class="subtotal-val text-sm font-bold text-gray-900 whitespace-nowrap">Rp {{ number_format($item->produk->harga * $item->jumlah, 0, ',', '.') }}</p></td>
                        <td class="px-6 py-4"><button onclick="triggerHapus('{{ $item->id }}')" class="w-9 h-9 rounded-xl bg-red-500 hover:bg-red-600 text-white flex items-center justify-center transition shadow-sm"><i class="fa-solid fa-trash text-xs"></i></button></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="md:hidden space-y-4 p-4 bg-gray-50/50">
            @foreach($keranjangs as $item)
            <div class="keranjang-item p-4 flex items-start gap-3.5 bg-white rounded-2xl border border-gray-100 shadow-xs"
                 data-id="{{ $item->id }}"
                 data-harga="{{ $item->produk->harga }}"
                 data-karung="{{ $item->produk->kategori->karung }}">
                <input type="checkbox"
                    class="item-checkbox w-5 h-5 rounded accent-[#58CC02] cursor-pointer shrink-0 mt-1.5"
                    onchange="syncCheckbox(this)"
                    data-id="{{ $item->id }}">
                <div class="w-14 h-14 rounded-xl overflow-hidden bg-gray-50 border border-gray-100 shrink-0">
                    @if($item->produk->fotoProduk)
                        <img src="{{ asset('storage/' . $item->produk->fotoProduk) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center"><i class="fa-solid fa-image text-lg text-gray-200"></i></div>
                    @endif
                </div>
                <div class="flex-1 min-w-0 flex flex-col gap-1">
                    <div>
                        <p class="font-extrabold text-gray-800 text-sm leading-snug">{{ $item->produk->namaProduk }}</p>
                        <p class="text-[10px] text-gray-400 font-bold uppercase mt-0.5 tracking-wider">{{ $item->produk->kategori->jenisKategori }} • {{ $item->produk->kategori->karung }} Kg</p>
                    </div>
                    <div class="flex items-center justify-between mt-1">
                        <span class="text-xs text-gray-400 font-bold">Rp {{ number_format($item->produk->harga, 0, ',', '.') }}</span>
                        <span class="font-extrabold text-[#0f8629] text-sm subtotal-val">Rp {{ number_format($item->produk->harga * $item->jumlah, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center justify-between border-t border-gray-100 pt-2.5 mt-1.5">
                        <button onclick="triggerHapus('{{ $item->id }}')" class="text-slate-400 hover:text-red-500 text-xs flex items-center gap-1.5 transition-colors">
                            <i class="fa-solid fa-trash text-[10px]"></i> <span class="text-[10px] font-extrabold uppercase tracking-wide">Hapus</span>
                        </button>
                        <div class="flex items-center gap-1 bg-gray-50 border border-gray-100 rounded-lg p-0.5">
                            <button onclick="triggerKurang('{{ $item->id }}')" class="w-6.5 h-6.5 bg-white border border-gray-200 rounded-md text-xs font-bold hover:bg-gray-100 flex items-center justify-center shadow-2xs transition-colors">-</button>
                            <input type="number" min="1" value="{{ $item->jumlah }}" class="jumlah-val w-10 text-center border-none bg-transparent focus:ring-0 text-xs font-extrabold p-0" onchange="triggerUpdateInput(this, '{{ $item->id }}')">
                            <button onclick="tambahJumlah('{{ $item->id }}')" class="w-6.5 h-6.5 bg-[#58CC02] text-white rounded-md text-xs font-bold hover:bg-[#46A302] flex items-center justify-center shadow-2xs transition-colors">+</button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="px-6 py-5 border-t border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4" data-aos="fade-up" data-aos-delay="200">
            <div class="flex flex-col sm:flex-row gap-4 sm:items-end">
                <div>
                    <p class="text-gray-500 text-sm font-semibold mb-0.5">Total Harga :</p>
                    <p class="text-xl font-bold text-gray-900" id="total-harga">Rp 0</p>
                </div>
                <div class="sm:border-l sm:border-gray-200 sm:pl-4">
                    <p class="text-gray-500 text-sm font-semibold mb-0.5">Total Berat :</p>
                    <p class="text-xl font-bold" id="total-berat-display">
                        <span id="total-berat-val">0</span>
                        <span class="text-sm font-bold text-gray-400">/ 500 Kg</span>
                    </p>
                </div>
            </div>
            <button type="button" onclick="triggerCheckout()" class="w-full sm:w-auto bg-[#58CC02] hover:bg-[#46A302] text-white px-12 py-3 rounded-xl font-bold text-base transition shadow-md text-center">
                Checkout Pesanan
            </button>
        </div>
    </div>
    @endif
</div>

<x-modal id="modalHapusKeranjang" title="Konfirmasi Hapus" message="Apakah anda yakin ingin menghapus produk ini dari keranjang?" confirmText="Hapus" cancelText="Batal" confirmId="btnConfirmHapus" cancelId="btnCloseHapus" />

<x-modal id="modalKonfirmasiCheckout" title="Konfirmasi Checkout" message="Yakin ingin melanjutkan checkout pesanan yang dipilih?" confirmText="Iya" cancelText="Batal" confirmId="btnSubmitCheckout" cancelId="btnCloseCheckout" />

<x-modal id="modalBeratKurang" title="Berat Tidak Mencukupi" message="Minimal total berat untuk checkout adalah 500 Kg. Silakan tambah produk atau pilih lebih banyak item." confirmText="Oke" cancelText="Batal" confirmId="btnCloseBeratKurang" cancelId="btnCloseBeratKurang2" />

<x-modal id="modalStokKurang" title="Stok Tidak Mencukupi" message="Jumlah yang dimasukkan melebihi stok yang tersedia." confirmText="Oke" cancelText="Batal" confirmId="btnCloseStokKurang" cancelId="btnCloseStokKurang2" />

<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
let idYangDihapus = null;

function syncCheckbox(cb) {
    const id = cb.dataset.id;
    const checked = cb.checked;
    document.querySelectorAll(`.item-checkbox[data-id="${id}"]`).forEach(el => {
        el.checked = checked;
    });
    hitungTotal();
}

function hitungTotal() {
    let total = 0;
    let beratTotal = 0;
    const seen = new Set();
    document.querySelectorAll('.keranjang-item').forEach(row => {
        const id = row.dataset.id;
        if (seen.has(id)) return;
        seen.add(id);
        const cb = row.querySelector('.item-checkbox');
        if (cb && cb.checked) {
            const harga = parseInt(row.dataset.harga) || 0;
            const karung = parseInt(row.dataset.karung) || 0;
            const inputEl = row.querySelector('.jumlah-val');
            const jumlah = inputEl ? (parseInt(inputEl.value) || parseInt(inputEl.textContent) || 0) : 0;
            total += (harga * jumlah);
            beratTotal += (karung * jumlah);
        }
    });
    document.getElementById('total-harga').textContent = 'Rp ' + total.toLocaleString('id-ID');
    document.getElementById('total-berat-val').textContent = beratTotal.toLocaleString('id-ID');

    const beratEl = document.getElementById('total-berat-display');
    if (beratTotal >= 500) {
        beratEl.classList.remove('text-red-500', 'text-orange-500');
        beratEl.classList.add('text-gray-900');
    } else if (beratTotal > 0) {
        beratEl.classList.remove('text-gray-900', 'text-red-500');
        beratEl.classList.add('text-orange-500');
    } else {
        beratEl.classList.remove('text-orange-500', 'text-red-500');
        beratEl.classList.add('text-gray-900');
    }
}

function updateRow(id, jumlah, subtotal, cartCount) {
    const subtotalAngka = parseInt(String(subtotal).replace(/\D/g, '')) || 0;
    document.querySelectorAll(`.keranjang-item[data-id="${id}"]`).forEach(row => {
        row.querySelectorAll('.jumlah-val').forEach(el => {
            if (el.tagName === 'INPUT') {
                el.value = jumlah;
            } else {
                el.textContent = jumlah;
            }
        });
        row.querySelectorAll('.subtotal-val').forEach(el => el.textContent = 'Rp ' + subtotalAngka.toLocaleString('id-ID'));
    });
    if (cartCount !== undefined) updateCartBadge(cartCount);
    hitungTotal();
}

function removeRow(id, cartCount) {
    document.querySelectorAll(`.keranjang-item[data-id="${id}"]`).forEach(row => {
        row.style.cssText = 'opacity:0;overflow:hidden;max-height:200px;transition:all 0.4s ease';
        setTimeout(() => {
            row.style.maxHeight = '0';
            row.style.padding = '0';
        }, 50);
        setTimeout(() => {
            row.remove();
            hitungTotal();
            if (cartCount !== undefined) updateCartBadge(cartCount);
            if (!document.querySelector('.keranjang-item')) location.reload();
        }, 450);
    });
}

function hapusItem(id) {
    fetch(`/agen/keranjang/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        removeRow(id, data.cartCount);
    });
}

function triggerHapus(id) {
    idYangDihapus = id;
    openModal('modalHapusKeranjang');
}

function tambahJumlah(id) {
    fetch(`/agen/keranjang/tambah/${id}`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(async r => {
        const data = await r.json();
        if (r.ok) {
            if (data.jumlah !== undefined) updateRow(id, data.jumlah, data.subtotal, data.cartCount);
        } else {
            const modal = document.getElementById('modalStokKurang');
            if (modal) {
                const msgEl = modal.querySelector('p');
                if (msgEl) {
                    msgEl.textContent = data.message || 'Jumlah sudah mencapai batas stok.';
                }
            }
            openModal('modalStokKurang');
            if (data.jumlah !== undefined) updateRow(id, data.jumlah, data.subtotal, data.cartCount);
        }
    })
    .catch(err => {
        console.error(err);
        const modal = document.getElementById('modalStokKurang');
        if (modal) {
            const msgEl = modal.querySelector('p');
            if (msgEl) {
                msgEl.textContent = 'Terjadi kesalahan jaringan.';
            }
        }
        openModal('modalStokKurang');
    });
}

function kurangJumlah(id) {
    fetch(`/agen/keranjang/kurang/${id}`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.jumlah !== undefined) updateRow(id, data.jumlah, data.subtotal, data.cartCount);
    });
}

function triggerKurang(id) {
    const row = document.querySelector(`.keranjang-item[data-id="${id}"]`);
    const inputEl = row.querySelector('.jumlah-val');
    const jumlah = inputEl ? (parseInt(inputEl.value) || parseInt(inputEl.textContent) || 0) : 0;
    if (jumlah <= 1) {
        idYangDihapus = id;
        openModal('modalHapusKeranjang');
    } else {
        kurangJumlah(id);
    }
}

function triggerUpdateInput(input, id) {
    let val = parseInt(input.value) || 0;
    if (val <= 0) {
        idYangDihapus = id;
        openModal('modalHapusKeranjang');
        input.value = 1;
        return;
    }

    fetch(`/agen/keranjang/update/${id}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ jumlah: val })
    })
    .then(async r => {
        const data = await r.json();
        if (r.ok) {
            if (data.jumlah !== undefined) {
                updateRow(id, data.jumlah, data.subtotal, data.cartCount);
            }
        } else {
            const modal = document.getElementById('modalStokKurang');
            if (modal) {
                const msgEl = modal.querySelector('p');
                if (msgEl) {
                    msgEl.textContent = data.message || 'Gagal memperbarui jumlah.';
                }
            }
            openModal('modalStokKurang');
            if (data.jumlah !== undefined) {
                updateRow(id, data.jumlah, data.subtotal, data.cartCount);
            }
        }
    })
    .catch(err => {
        console.error(err);
        const modal = document.getElementById('modalStokKurang');
        if (modal) {
            const msgEl = modal.querySelector('p');
            if (msgEl) {
                msgEl.textContent = 'Terjadi kesalahan jaringan.';
            }
        }
        openModal('modalStokKurang');
    });
}

function triggerCheckout() {
    const selectedIds = [];
    const seen = new Set();
    let beratTotal = 0;
    document.querySelectorAll('.keranjang-item').forEach(row => {
        const id = row.dataset.id;
        if (seen.has(id)) return;
        seen.add(id);
        const cb = row.querySelector('.item-checkbox');
        if (cb && cb.checked) {
            selectedIds.push(id);
            const karung = parseInt(row.dataset.karung) || 0;
            const inputEl = row.querySelector('.jumlah-val');
            const jumlah = inputEl ? (parseInt(inputEl.value) || parseInt(inputEl.textContent) || 0) : 0;
            beratTotal += (karung * jumlah);
        }
    });

    if (selectedIds.length === 0 || beratTotal < 500) {
        openModal('modalBeratKurang');
        return;
    }

    openModal('modalKonfirmasiCheckout');
}

document.getElementById('btnConfirmHapus').addEventListener('click', () => {
    if (idYangDihapus) {
        hapusItem(idYangDihapus);
        closeModal('modalHapusKeranjang');
        idYangDihapus = null;
    }
});

document.getElementById('btnCloseHapus').addEventListener('click', () => closeModal('modalHapusKeranjang'));

document.getElementById('btnSubmitCheckout').addEventListener('click', () => {
    closeModal('modalKonfirmasiCheckout');
    const selectedIds = [];
    const seen = new Set();
    document.querySelectorAll('.keranjang-item').forEach(row => {
        const id = row.dataset.id;
        if (seen.has(id)) return;
        seen.add(id);
        const cb = row.querySelector('.item-checkbox');
        if (cb && cb.checked) selectedIds.push(id);
    });
    if (selectedIds.length > 0) {
        window.location.href = `/agen/checkout?items=${selectedIds.join(',')}`;
    }
});

document.getElementById('btnCloseCheckout').addEventListener('click', () => closeModal('modalKonfirmasiCheckout'));
document.getElementById('btnCloseBeratKurang').addEventListener('click', () => closeModal('modalBeratKurang'));
document.getElementById('btnCloseBeratKurang2').addEventListener('click', () => closeModal('modalBeratKurang'));
document.getElementById('btnCloseStokKurang').addEventListener('click', () => closeModal('modalStokKurang'));
document.getElementById('btnCloseStokKurang2').addEventListener('click', () => closeModal('modalStokKurang'));
</script>
@endsection
