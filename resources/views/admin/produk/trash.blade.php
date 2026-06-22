@extends('layouts.admin')

@section('title', 'Stok Kosong - AGRIS')

@section('content')
<div class="max-w-7xl mx-auto pt-3 pb-10">
    <div class="flex items-center gap-4 mb-8" data-aos="fade-up">
        <a href="{{ route('admin.produk.index') }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 transition shadow-sm shrink-0">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-xl md:text-2xl font-extrabold text-gray-800">Stok Kosong</h1>
            <p class="text-gray-500 text-sm">Daftar benih kosong</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl md:rounded-3xl shadow-sm border border-gray-100 overflow-hidden" data-aos="fade-up" data-aos-delay="100">
        <div class="hidden md:block">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 text-gray-500 text-[10px] font-bold uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Informasi Produk</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Mutu</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($produks as $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl bg-gray-100 overflow-hidden shrink-0">
                                    @if($item->fotoProduk)
                                        <img src="{{ asset('storage/' . $item->fotoProduk) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="flex items-center justify-center h-full text-gray-300 text-[8px]">NO IMG</div>
                                    @endif
                                </div>
                                <div>
                                    <span class="font-bold text-gray-800 block">{{ $item->namaProduk }}</span>
                                    <span class="text-xs text-red-500 font-bold">Stok: {{ $item->stok }} Karung</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 uppercase text-xs font-bold text-gray-500">{{ $item->kategori->jenisKategori }}</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full bg-red-50 text-red-600 uppercase text-[10px] font-bold">
                                {{ $item->kategori->mutu }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-400 text-xs italic">Dihapus {{ $item->deleted_at->diffForHumans() }}</td>
                        <td class="px-6 py-3 text-right">
                            <a href="{{ route('admin.produk.edit', $item->id) }}" class="inline-block bg-blue-50 text-blue-600 px-4 py-2 rounded-xl text-xs font-bold hover:bg-blue-600 hover:text-white transition">
                                Edit
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-20 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fa-solid fa-circle-check text-5xl text-[#58CC02]/20 mb-4"></i>
                                <p class="text-gray-400 font-medium">Tidak Ada Stok Benih yang Habis</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="md:hidden divide-y divide-gray-100">
            @forelse($produks as $item)
            <div class="p-4 flex flex-col gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-gray-100 overflow-hidden shrink-0">
                        @if($item->fotoProduk)
                            <img src="{{ asset('storage/' . $item->fotoProduk) }}" class="w-full h-full object-cover">
                        @else
                            <div class="flex items-center justify-center h-full text-gray-300 text-[10px]">NO IMG</div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <span class="font-bold text-gray-800 block text-sm">{{ $item->namaProduk }}</span>
                        <div class="flex gap-2 mt-1">
                            <span class="text-[9px] font-bold uppercase text-gray-500 bg-gray-100 px-2 py-0.5 rounded">
                                {{ $item->kategori->jenisKategori }}
                            </span>
                            <span class="text-[9px] font-bold uppercase text-red-600 bg-red-50 px-2 py-0.5 rounded">
                                {{ $item->kategori->mutu }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between bg-gray-50 p-3 rounded-xl">
                    <div>
                        <p class="text-[10px] text-gray-400 uppercase font-bold">Status</p>
                        <p class="text-xs text-red-500 font-bold">Stok {{ $item->stok }} · <span class="text-gray-400 font-normal italic">{{ $item->deleted_at->diffForHumans() }}</span></p>
                    </div>
                    <a href="{{ route('admin.produk.edit', $item->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-xs font-bold shadow-sm">
                        Edit
                    </a>
                </div>
            </div>
            @empty
            <div class="py-16 text-center px-4">
                <i class="fa-solid fa-circle-check text-5xl text-[#58CC02]/20 mb-4"></i>
                <p class="text-gray-400 text-sm font-medium">Tidak Ada Stok Benih yang Habis</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
