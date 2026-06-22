@extends('layouts.agen')

@section('title', 'Daftar Produk - AGRIS')

@section('content')
<div class="max-w-7xl mx-auto pt-3 md:pt-5 pb-12 px-3 md:px-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4" data-aos="fade-up">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Daftar Produk</h1>
            <p class="text-gray-500 text-sm">Cari dan pilih produk berdasarkan kategori yang tersedia</p>
        </div>
    </div>

    <div class="bg-white p-4 md:p-5 rounded-2xl shadow-sm border border-gray-100 mb-8" data-aos="fade-up" data-aos-delay="100">
        <form action="{{ route('agen.produk.index') }}" method="GET" class="flex flex-col md:flex-row items-end gap-4">
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif
            <div class="w-full md:flex-1">
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1 ml-1">Jenis</label>
                <select name="jenis" class="w-full px-4 py-2.5 rounded-xl border border-gray-100 bg-gray-50 outline-none focus:ring-2 focus:ring-[#58CC02] text-sm cursor-pointer appearance-none">
                    <option value="">Semua Jenis</option>
                    @foreach($daftarJenis as $j)
                        <option value="{{ $j }}" {{ request('jenis') == $j ? 'selected' : '' }}>{{ strtoupper($j) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="w-full md:flex-1">
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1 ml-1">Mutu</label>
                <select name="mutu" class="w-full px-4 py-2.5 rounded-xl border border-gray-100 bg-gray-50 outline-none focus:ring-2 focus:ring-[#58CC02] text-sm cursor-pointer appearance-none">
                    <option value="">Semua Mutu</option>
                    @foreach($daftarMutu as $m)
                        <option value="{{ $m }}" {{ request('mutu') == $m ? 'selected' : '' }}>MUTU {{ strtoupper($m) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="w-full md:flex-1">
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1 ml-1">Isi Karung</label>
                <select name="karung" class="w-full px-4 py-2.5 rounded-xl border border-gray-100 bg-gray-50 outline-none focus:ring-2 focus:ring-[#58CC02] text-sm cursor-pointer appearance-none">
                    <option value="">Semua Ukuran</option>
                    @foreach($daftarKarung as $k)
                        <option value="{{ $k }}" {{ request('karung') == $k ? 'selected' : '' }}>{{ $k }} Kg</option>
                    @endforeach
                </select>
            </div>

            <div class="w-full md:w-auto">
                <button type="submit" class="w-full md:w-auto bg-gray-800 hover:bg-black text-white px-8 py-2.5 rounded-xl transition font-bold text-sm flex items-center justify-center shadow-sm">
                    <i class="fa-solid fa-filter mr-2"></i> Filter
                </button>
            </div>
        </form>
    </div>

    <div id="product-grid" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-2 md:gap-3">
        @forelse($produks as $item)
        <div id="product-card-{{ $item->id }}" class="group bg-white rounded-lg overflow-hidden border border-gray-100 shadow-sm hover:shadow-md transition flex flex-col h-full relative" data-aos="fade-up" data-aos-delay="{{ ($loop->iteration - 1) * 50 }}">
            <a href="{{ route('agen.produk.show', $item->id) }}" class="relative aspect-square bg-gray-50 flex items-center justify-center overflow-hidden">
                @if($item->fotoProduk)
                    <img src="{{ asset('storage/' . $item->fotoProduk) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" alt="{{ $item->namaProduk }}">
                @else
                    <div class="flex items-center justify-center h-full text-gray-200">
                        <i class="fa-solid fa-image text-4xl"></i>
                    </div>
                @endif
                <div id="out-of-stock-badge-{{ $item->id }}" class="absolute inset-0 bg-black/40 flex items-center justify-center z-10 {{ $item->stok <= 0 ? '' : 'hidden' }}">
                    <span class="bg-red-500 text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase">Stok Habis</span>
                </div>
            </a>

            <div class="p-2.5 flex flex-col grow">
                <div class="flex flex-wrap gap-1 mb-2">
                    <span class="text-[9px] font-bold uppercase text-gray-800 bg-gray-800/10 px-1.5 py-0.5 rounded">{{ $item->kategori->jenisKategori }}</span>
                    <span class="text-[9px] font-bold uppercase text-gray-800 bg-gray-800/10 px-1.5 py-0.5 rounded">{{ $item->kategori->karung }} Kg</span>
                    <span class="text-[9px] font-bold uppercase text-gray-800 bg-gray-800/10 px-1.5 py-0.5 rounded">{{ $item->kategori->mutu }}</span>
                </div>

                <a href="{{ route('agen.produk.show', $item->id) }}" class="grow">
                    <h3 class="text-gray-800 text-15 font-normal line-clamp-2 leading-snug mb-1 min-h-9.5">{{ $item->namaProduk }}</h3>
                    <p class="text-gray-900 font-bold text-base mb-0.5">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                </a>

                <div class="mt-auto">
                    <div class="flex items-center justify-between pt-2 border-t border-gray-100 flex-wrap gap-1 mb-3">
                        <div class="flex items-center gap-1 text-[11px] text-gray-500 truncate max-w-[70%]">
                            <div class="bg-violet-600 text-white rounded w-3.5 h-3.5 flex items-center justify-center text-[8px] shrink-0">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <span class="truncate font-medium text-gray-500">Tersedia</span>
                        </div>
                        <span id="product-stock-{{ $item->id }}" class="text-[10px] font-bold {{ $item->stok > 5 ? 'text-gray-500' : 'text-orange-500' }} uppercase tracking-tight shrink-0">Stok: {{ $item->stok }}</span>
                    </div>

                    <form class="add-to-cart-form">
                        @csrf
                        <input type="hidden" name="produkId" value="{{ $item->id }}">
                        <input type="hidden" name="jumlah" value="1">
                        <button id="product-btn-{{ $item->id }}" type="button" onclick="addToCart(this)" {{ $item->stok <= 0 ? 'disabled' : '' }} class="w-full {{ $item->stok <= 0 ? 'bg-gray-300 cursor-not-allowed' : 'bg-[#58CC02] hover:bg-[#46A302]' }} text-white py-2 rounded-xl transition font-bold text-xs flex items-center justify-center gap-2 shadow-sm">
                            <i class="fa-solid fa-cart-plus"></i> <span class="btn-text">{{ $item->stok <= 0 ? 'Habis' : 'Tambah Pesanan' }}</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-20 text-center bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
            <i class="fa-solid fa-box-open text-5xl text-gray-200 mb-4"></i>
            <p class="text-gray-400 font-bold uppercase text-sm tracking-widest">Produk tidak ditemukan.</p>
        </div>
        @endforelse
    </div>

    <div class="mt-10 px-4 md:px-0">
        {{ $produks->links() }}
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
    const isMitra = {{ auth()->user()->isActive == 1 ? 'true' : 'false' }};

    if (!isMitra) {
        openModal('modalAksesMitra');
        return;
    }

    let form = btn.closest('.add-to-cart-form');
    let formData = new FormData(form);

    fetch("{{ route('agen.produk.add-to-cart') }}", {
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

            if (prod.deleted_at) {
                const cardEl = document.getElementById(`product-card-${prod.id}`);
                if (cardEl) {
                    cardEl.remove();
                }
                return;
            }

            const cardEl = document.getElementById(`product-card-${prod.id}`);
            if (!cardEl) {
                fetch(window.location.href)
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newGrid = doc.getElementById('product-grid');
                        const oldGrid = document.getElementById('product-grid');
                        if (newGrid && oldGrid) {
                            oldGrid.innerHTML = newGrid.innerHTML;
                        }
                    })
                    .catch(err => console.error('Error fetching new product grid:', err));
                return;
            }

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
@endsection
