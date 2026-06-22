@extends('layouts.agen')

@section('content')
<div class="max-w-7xl mx-auto pt-5 pb-12 px-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-6" data-aos="fade-right">
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4 uppercase text-xs">Alamat Pengiriman</h3>
                <div class="p-4 bg-gray-50 rounded-xl">
                    <p class="font-bold text-gray-800">{{ $user->namaLengkap }}</p>
                    <p class="text-sm text-gray-600 mt-2">{{ $user->detailAlamat }}</p>
                    <p class="text-xs font-bold text-[#58CC02] mt-2 italic">Kecamatan: {{ $user->desa->kecamatan->namaKecamatan ?? 'Tidak diketahui' }}</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4 uppercase text-xs">Metode Pengambilan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="p-4 border rounded-xl cursor-pointer hover:border-[#58CC02] transition">
                        <input type="radio" name="delivery_type" value="ambil" checked onchange="handleDeliveryChange('ambil')">
                        <span class="ml-2 text-sm font-bold">Ambil di Gudang</span>
                    </label>
                    <label class="p-4 border rounded-xl cursor-pointer hover:border-[#58CC02] transition">
                        <input type="radio" name="delivery_type" value="kirim" onchange="handleDeliveryChange('kirim')">
                        <span class="ml-2 text-sm font-bold">Kirim ke Lokasi</span>
                    </label>
                </div>

                <div id="section-kurir" class="hidden mt-6 pt-6 border-t border-dashed border-gray-200">
                    <div id="kurir-list" class="space-y-3 text-sm"></div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1" data-aos="fade-left">
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 sticky top-5">
                <h3 class="font-bold text-gray-800 mb-4 uppercase text-xs">Ringkasan Pembayaran</h3>
                <div class="space-y-3 mb-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Subtotal Barang</span>
                        <span class="font-bold">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Ongkos Kirim</span>
                        <span id="text-ongkir" class="font-bold text-[#58CC02]">Rp 0</span>
                    </div>
                </div>
                <div class="pt-4 border-t flex justify-between items-center">
                    <span class="font-bold">Total Bayar</span>
                    <span id="text-total" data-base="{{ $totalHarga }}" class="text-xl font-black text-[#58CC02]">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                </div>
                <button id="btn-bayar" class="w-full bg-[#58CC02] hover:bg-[#46A302] text-white font-bold py-4 rounded-xl mt-6 shadow-lg transition">Bayar Sekarang</button>
            </div>
        </div>
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key', env('MIDTRANS_CLIENT_KEY')) }}"></script>
<script>
    const itemsBiteship = [
        @foreach($items as $item)
        { name: "{{ $item->produk->namaProduk }}", quantity: {{ $item->jumlah }}, value: {{ $item->produk->harga }}, weight: {{ $item->produk->kategori->karung * 1000 }} },
        @endforeach
    ];

    function handleDeliveryChange(type) {
        if(type === 'kirim') {
            document.getElementById('section-kurir').classList.remove('hidden');
            getRates();
        } else {
            document.getElementById('section-kurir').classList.add('hidden');
            applyOngkir(0);
        }
    }

    function getRates() {
        const list = document.getElementById('kurir-list');
        list.innerHTML = '<div class="animate-pulse py-4">Mencari kurir terbaik...</div>';

        fetch("{{ route('agen.checkout.ongkir') }}", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': "{{ csrf_token() }}", 'Accept': 'application/json' },
            body: JSON.stringify({ berat: {{ $totalBeratGram }}, items: itemsBiteship })
        })
        .then(res => res.json())
        .then(data => {
            if(data.pricing) {
                let html = '';
                data.pricing.forEach(p => {
                    const price = p.price;
                    const originalPrice = p.original_price || p.list_price;
                    let priceHtml = `<div class="font-bold text-sm">Rp ${price.toLocaleString('id-ID')}</div>`;
                    if (originalPrice && originalPrice > price) {
                        priceHtml = `
                            <div class="text-right">
                                <p class="text-[10px] text-gray-400 line-through">Rp ${originalPrice.toLocaleString('id-ID')}</p>
                                <p class="font-bold text-sm text-green-600">Rp ${price.toLocaleString('id-ID')}</p>
                            </div>`;
                    }
                    html += `
                        <label class="flex items-center p-4 border border-gray-100 rounded-xl cursor-pointer hover:bg-gray-50 transition">
                            <input type="radio" name="courier" value="${price}" onchange="applyOngkir(${price})">
                            <div class="ml-4 flex-1">
                                <p class="text-xs font-bold uppercase">${p.courier_name} - ${p.courier_service_name}</p>
                                <p class="text-[10px] text-gray-400 font-bold italic">Estimasi: ${p.duration}</p>
                            </div>
                            ${priceHtml}
                        </label>`;
                });
                list.innerHTML = html;
            } else {
                list.innerHTML = '<p class="text-red-500 text-xs font-bold">' + (data.error || 'Gagal memuat kurir.') + '</p>';
            }
        })
        .catch(() => {
            list.innerHTML = '<p class="text-red-500 text-xs font-bold">Terjadi kesalahan pada server.</p>';
        });
    }

    function applyOngkir(val) {
        const base = parseInt(document.getElementById('text-total').dataset.base);
        document.getElementById('text-ongkir').innerText = 'Rp ' + val.toLocaleString('id-ID');
        document.getElementById('text-total').innerText = 'Rp ' + (base + val).toLocaleString('id-ID');
    }

    document.getElementById('btn-bayar').onclick = function() {
        const total = parseInt(document.getElementById('text-total').innerText.replace(/\D/g, ''));
        this.disabled = true;
        this.innerText = 'Memproses...';

        fetch("{{ route('agen.checkout.bayar') }}", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
            body: JSON.stringify({ total_bayar: total })
        })
        .then(res => res.json())
        .then(data => {
            snap.pay(data.token, {
                onSuccess: function() { location.href = "/agen/transaksi"; },
                onClose: function() {
                    document.getElementById('btn-bayar').disabled = false;
                    document.getElementById('btn-bayar').innerText = 'Bayar Sekarang';
                }
            });
        });
    };
</script>
@endsection
