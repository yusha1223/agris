@extends('layouts.admin')

@section('title', 'Kemitraan Admin - AGRIS')

@section('content')
<div class="w-full pt-2 pb-10">
    <div class="mb-8" data-aos="fade-up">
        <h1 class="text-xl md:text-2xl font-bold text-gray-800">Manajemen Kemitraan</h1>
        <p class="text-gray-500 text-sm">Verifikasi data pengajuan agen baru.</p>
    </div>

    <div class="hidden md:block bg-white rounded-4xl border border-gray-100 overflow-hidden shadow-sm" data-aos="fade-up" data-aos-delay="100">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-gray-400">Agen</th>
                    <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-gray-400">Status</th>
                    <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-gray-400">Tanggal</th>
                    <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-gray-400 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($kemitraans as $item)
                <tr class="hover:bg-gray-50/50 transition-all">
                    <td class="px-8 py-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gray-100 overflow-hidden shrink-0">
                                <img src="{{ $item->user->fotoProfil ? asset($item->user->fotoProfil) : 'https://ui-avatars.com/api/?name='.urlencode($item->user->namaLengkap) }}" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <p class="font-bold text-gray-800">{{ $item->user->namaLengkap }}</p>
                                <p class="text-[10px] text-gray-400 font-medium">{{ $item->user->noTelp }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-6">
                        @php
                            $badgeClass = match($item->statusPengajuan) {
                                'Aktif' => 'bg-green-100 text-green-600',
                                'Ditolak' => 'bg-red-100 text-red-600',
                                'Menunggu Verifikasi MOU' => 'bg-blue-100 text-blue-600',
                                default => 'bg-orange-100 text-orange-600'
                            };
                        @endphp
                        <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase {{ $badgeClass }}">
                            {{ $item->statusPengajuan }}
                        </span>
                    </td>
                    <td class="px-8 py-6 text-sm text-gray-500">
                        {{ $item->tanggalPengajuan->format('d M Y') }}
                    </td>
                    <td class="px-8 py-6 text-right">
                        <a href="{{ route('admin.kemitraan.show', $item->id) }}" class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-gray-100 text-gray-600 hover:bg-[#58CC02] hover:text-white transition-all">
                            <i class="fa-solid fa-chevron-right"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="grid grid-cols-1 gap-2 md:hidden" data-aos="fade-up" data-aos-delay="100">
        @foreach($kemitraans as $item)
        <div class="bg-white p-5 rounded-3xl border border-gray-100 shadow-sm">
            <div class="flex justify-between items-start mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-gray-100 overflow-hidden">
                        <img src="{{ $item->user->fotoProfil ? asset($item->user->fotoProfil) : 'https://ui-avatars.com/api/?name='.urlencode($item->user->namaLengkap) }}" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <p class="font-bold text-gray-800">{{ $item->user->namaLengkap }}</p>
                        <p class="text-xs text-gray-400">{{ $item->user->noTelp }}</p>
                    </div>
                </div>
                <a href="{{ route('admin.kemitraan.show', $item->id) }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-50 text-gray-400">
                    <i class="fa-solid fa-chevron-right"></i>
                </a>
            </div>

            <div class="flex justify-between items-center pt-4 border-t border-gray-50">
                <div>
                    <p class="text-[10px] uppercase tracking-widest text-gray-400 font-black mb-1">Status</p>
                    @php
                        $badgeClass = match($item->statusPengajuan) {
                            'Aktif' => 'bg-green-100 text-green-600',
                            'Ditolak' => 'bg-red-100 text-red-600',
                            'Menunggu Verifikasi MOU' => 'bg-blue-100 text-blue-600',
                            default => 'bg-orange-100 text-orange-600'
                        };
                    @endphp
                    <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase {{ $badgeClass }}">
                        {{ $item->statusPengajuan }}
                    </span>
                </div>
                <div class="text-right">
                    <p class="text-[10px] uppercase tracking-widest text-gray-400 font-black mb-1">Tanggal</p>
                    <p class="text-xs font-bold text-gray-600">{{ $item->tanggalPengajuan->format('d M Y') }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (window.Echo) {
            window.Echo.channel('kemitraan-status')
                .listen('.KemitraanUpdated', (e) => {
                    window.location.reload();
                });
        }
    });
</script>
@endsection
