@extends('layouts.admin')

@section('title', 'Manajemen Produk - AGRIS')

@section('content')
<div class="max-w-7xl mx-auto pt-2 pb-10">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4" data-aos="fade-up">
        <div>
            <h1 class="text-xl md:text-2xl font-bold text-gray-800">Daftar Produk</h1>
            <p class="text-gray-500 text-sm">Kelola stok berdasarkan inputan kategori admin</p>
        </div>
        <div class="flex gap-2 sm:gap-3">
            <a href="{{ route('admin.produk.trash') }}" class="flex-1 md:flex-none justify-center bg-white shadow hover:bg-gray-200 text-gray-600 px-4 py-2.5 rounded-xl transition font-bold text-xs md:text-sm flex items-center">
                Stok Habis
            </a>
            <a href="{{ route('admin.produk.create') }}" class="flex-1 md:flex-none justify-center bg-[#58CC02] hover:bg-[#46a302] text-white px-5 py-2.5 rounded-xl transition shadow-md font-bold text-xs md:text-sm flex items-center">
                <i class="fa-solid fa-plus mr-2"></i> Tambah
            </a>
        </div>
    </div>

    <div class="bg-white p-4 md:p-5 rounded-xl shadow-sm border border-gray-100 mb-8" data-aos="fade-up" data-aos-delay="100">
        <form action="{{ route('admin.produk.index') }}" method="GET" class="flex flex-col md:flex-row items-end gap-4">
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

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-2 md:gap-3">
        @forelse($produks as $item)
        <a href="{{ route('admin.produk.show', $item->id) }}" class="group" data-aos="zoom-in" data-aos-delay="{{ ($loop->iteration - 1) * 50 }}">
            <div class="bg-white rounded-lg overflow-hidden border border-gray-100 shadow-sm hover:shadow-md transition flex flex-col h-full relative">
                <div class="relative aspect-square bg-gray-50 flex items-center justify-center overflow-hidden">
                    @if($item->fotoProduk)
                        <img src="{{ storage_url($item->fotoProduk) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" alt="{{ $item->namaProduk }}">
                    @else
                        <div class="flex items-center justify-center h-full text-gray-200">
                            <i class="fa-solid fa-image text-4xl"></i>
                        </div>
                    @endif

                    @if($item->stok <= 0)
                        <div class="absolute inset-0 bg-black/40 flex items-center justify-center z-10">
                            <span class="bg-red-500 text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase">Stok Habis</span>
                        </div>
                    @endif
                </div>

                <div class="p-2.5 flex flex-col grow">
                    <div class="flex flex-wrap gap-1 mb-2">
                        <span class="text-[9px] font-bold uppercase text-gray-800 bg-gray-800/10 px-1.5 py-0.5 rounded">
                            {{ $item->kategori->jenisKategori }}
                        </span>
                        <span class="text-[9px] font-bold uppercase text-gray-800 bg-gray-800/10 px-1.5 py-0.5 rounded">
                            {{ $item->kategori->karung }} Kg
                        </span>
                        <span class="text-[9px] font-bold uppercase text-gray-800 bg-gray-800/10 px-1.5 py-0.5 rounded">
                            {{ $item->kategori->mutu }}
                        </span>
                    </div>

                    <h3 class="text-gray-800 text-15 font-normal line-clamp-2 leading-snug mb-1 min-h-9.5">
                        {{ $item->namaProduk }}
                    </h3>

                    <p class="text-gray-900 font-bold text-base mb-0.5">
                        Rp {{ number_format($item->harga, 0, ',', '.') }}
                    </p>

                    <div class="mt-auto">

                     <div class="flex items-center justify-between pt-2 border-t border-gray-100 flex-wrap gap-1">
                            <div class="flex items-center gap-1 text-[11px] text-gray-500 truncate max-w-[70%]">
                                <div class="bg-violet-600 text-white rounded w-3.5 h-3.5 flex items-center justify-center text-[8px] shrink-0">
                                    <i class="fa-solid fa-check"></i>
                                </div>
                                <span class="truncate font-medium text-gray-500">Tersedia</span>
                            </div>
                            <span class="text-[10px] font-bold {{ $item->stok > 5 ? 'text-gray-500' : 'text-orange-500' }} uppercase tracking-tight shrink-0">
                                Stok: {{ $item->stok }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </a>
        @empty
        <div class="col-span-full py-20 text-center bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
            <i class="fa-solid fa-box-open text-5xl text-gray-200 mb-4"></i>
            <p class="text-gray-400 font-bold uppercase text-sm tracking-widest">Data tidak ditemukan.</p>
        </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $produks->links() }}
    </div>
</div>
@endsection
