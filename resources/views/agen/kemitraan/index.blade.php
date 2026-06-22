@extends('layouts.agen')

@section('title', 'Status Kemitraan - AGRIS')

@section('content')
<div class="max-w-7xl mx-auto pt-3 md:pt-5 pb-12 px-3 md:px-6">
    <div class="mb-8" data-aos="fade-up">
        <h1 class="text-2xl font-bold text-gray-800">Status Kemitraan Agen</h1>
        <p class="text-gray-500 text-sm">Pantau sejauh mana proses pengajuan kemitraan Anda.</p>
    </div>

    @if(!$kemitraan || $kemitraan->statusPengajuan == 'Ditolak')
    <div class="bg-white rounded-3xl border border-gray-100 p-8 md:p-12 shadow-sm" data-aos="fade-up" data-aos-delay="100">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            <div class="text-center md:text-left" data-aos="fade-right" data-aos-delay="200">
                <div class="w-16 h-16 {{ !$kemitraan ? 'bg-green-50 text-[#58CC02]' : 'bg-red-50 text-red-500' }} rounded-2xl flex items-center justify-center mb-6 mx-auto md:mx-0">
                    <i class="fa-solid {{ !$kemitraan ? 'fa-handshake' : 'fa-circle-xmark' }} text-2xl"></i>
                </div>
                <h2 class="text-3xl font-extrabold text-gray-800 mb-4 leading-tight">
                    {{ !$kemitraan ? 'Buka Akses Produk Unggulan AGRIS' : 'Pengajuan Ditolak' }}
                </h2>
                <p class="text-gray-500 mb-8 leading-relaxed">
                    {{ !$kemitraan
                        ? 'Untuk menjaga kualitas distribusi, pembelian seluruh produk hanya dapat dilakukan oleh mitra resmi. Segera lengkapi kemitraan Anda untuk mulai bertransaksi.'
                        : 'Mohon maaf, pengajuan kemitraan Anda ditolak oleh admin karena dokumen MOU atau profil tidak memenuhi kriteria. Silakan ajukan ulang kembali.' }}
                </p>
                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="button" onclick="checkProfileAndRedirect('{{ route('kemitraan.create') }}')" class="inline-flex items-center justify-center px-8 py-4 {{ !$kemitraan ? 'bg-[#58CC02]' : 'bg-red-500' }} text-white font-bold rounded-2xl hover:opacity-90 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                        {{ !$kemitraan ? 'Ajukan Kerjasama' : 'Ajukan Ulang Sekarang' }}
                        <i class="fa-solid fa-arrow-right ml-2 text-sm"></i>
                    </button>
                </div>
            </div>

            <div class="bg-gray-50 rounded-4xl p-8 border border-gray-100" data-aos="fade-left" data-aos-delay="200">
                <h3 class="font-bold text-gray-800 mb-6 flex items-center gap-2">
                    <i class="fa-solid {{ !$kemitraan ? 'fa-gem text-yellow-500' : 'fa-circle-exclamation text-red-500' }}"></i>
                    {{ !$kemitraan ? 'Keunggulan Mitra Resmi' : 'Catatan Penolakan' }}
                </h3>
                <div class="space-y-5">
                    @if(!$kemitraan)
                        <div class="flex gap-4">
                            <div class="w-10 h-10 shrink-0 bg-white rounded-xl shadow-sm flex items-center justify-center text-[#58CC02]">
                                <i class="fa-solid fa-cart-shopping"></i>
                            </div>
                            <div>
                                <p class="font-bold text-gray-800 text-sm">Akses Eksklusif Produk</p>
                                <p class="text-xs text-gray-500">Satu-satunya jalur legal untuk melakukan pembelian produk AGRIS.</p>
                            </div>
                        </div>
                    @else
                        <p class="text-xs text-gray-500 leading-relaxed">
                            Penolakan biasanya terjadi jika file PDF tidak terbaca, data tidak sesuai dengan identitas, atau tanda tangan MOU tidak lengkap. Pastikan Anda memeriksa kembali dokumen sebelum mengajukan ulang.
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @else
    @php
        $steps = [
            'Menunggu Upload MOU' => 1,
            'Menunggu Verifikasi MOU' => 2,
            'Aktif' => 3
        ];
        $currentStep = $steps[$kemitraan->statusPengajuan] ?? 1;
    @endphp

    <div class="bg-white rounded-3xl border border-gray-100 p-8 shadow-sm" data-aos="fade-up" data-aos-delay="100">
        <div class="relative flex items-center justify-between mb-16 px-4">
            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-1 bg-gray-100 z-0"></div>
            <div class="absolute left-0 top-1/2 -translate-y-1/2 h-1 bg-[#58CC02] transition-all duration-500 z-0" style="width: {{ ($currentStep - 1) * 50 }}%"></div>

            @foreach(['Upload MOU', 'Verifikasi', 'Selesai'] as $index => $label)
            <div class="relative z-10 flex flex-col items-center">
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold transition-all {{ $currentStep > $index ? 'bg-[#58CC02] text-white' : ($currentStep == $index + 1 ? 'bg-white border-4 border-[#58CC02] text-[#58CC02]' : 'bg-white border-4 border-gray-100 text-gray-300') }}">
                    @if($currentStep > $index + 1) <i class="fa-solid fa-check text-xs"></i> @else <span class="text-xs">{{ $index + 1 }}</span> @endif
                </div>
                <span class="absolute top-12 text-[10px] font-bold uppercase tracking-widest whitespace-nowrap {{ $currentStep >= $index + 1 ? 'text-[#58CC02]' : 'text-gray-400' }}">{{ $label }}</span>
            </div>
            @endforeach
        </div>

        <div class="mt-20 pt-8 border-t border-gray-50" data-aos="fade-up" data-aos-delay="200">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center p-6 bg-gray-50 rounded-3xl border border-gray-100">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Status Saat Ini</p>
                    <div class="flex items-center gap-3">
                        <h3 class="text-xl font-bold text-gray-800">{{ strtoupper($kemitraan->statusPengajuan) }}</h3>
                    </div>
                </div>

                <div class="flex flex-col gap-3">
                    @if($kemitraan->statusPengajuan == 'Menunggu Upload MOU')
                        <button type="button" id="btnTriggerFile" class="w-full py-4 bg-blue-600 text-white text-xs font-bold rounded-xl shadow-lg hover:bg-blue-700 transition-all uppercase tracking-widest">
                            <i class="fa-solid fa-file-arrow-up mr-2"></i> Unggah Dokumen
                        </button>
                    @elseif($kemitraan->statusPengajuan == 'Menunggu Verifikasi MOU')
                        <div class="py-4 bg-blue-50 text-blue-600 text-center rounded-xl font-bold text-xs uppercase tracking-widest">
                            Dokumen Dalam Proses Review
                        </div>
                    @elseif($kemitraan->statusPengajuan == 'Aktif')
                        <div class="py-4 bg-green-50 text-[#58CC02] text-center rounded-xl font-bold text-xs uppercase tracking-widest">
                            <i class="fa-solid fa-circle-check mr-2"></i> Kemitraan Aktif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<x-modal id="modalLengkapiProfil" title="Profil Belum Lengkap" message="Harap lengkapi profil dulu sebelum pengajuan" confirmText="Lengkapi" cancelText="Nanti Saja" confirmId="btnLengkapiSekarang" cancelId="btnTutupModalProfil" />
<form id="upload-form" action="{{ $kemitraan ? route('kemitraan.uploadMou', $kemitraan->id) : route('kemitraan.store') }}" method="POST" enctype="multipart/form-data" class="hidden">
    @csrf
    <input type="file" name="fileKemitraan" id="inputManualFile" accept=".pdf" onchange="this.form.submit()">
</form>

<script>
    function checkProfileAndRedirect(targetUrl) {
        const isComplete = @json(!empty(Auth::user()->namaLengkap) && !empty(Auth::user()->noTelp) && !empty(Auth::user()->desaId) && !empty(Auth::user()->detailAlamat));

        if (isComplete) {
            window.location.href = targetUrl;
        } else {
            openModal('modalLengkapiProfil');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        @if(session('modalIncomplete'))
            openModal('modalLengkapiProfil');
        @endif

        document.getElementById('btnLengkapiSekarang')?.addEventListener('click', function() {
            window.location.href = "{{ route('admin.profile') }}";
        });

        document.getElementById('btnTutupModalProfil')?.addEventListener('click', function() {
            closeModal('modalLengkapiProfil');
        });

        document.getElementById('btnTriggerFile')?.addEventListener('click', () => {
            document.getElementById('inputManualFile').click();
        });

        if (window.Echo) {
            window.Echo.channel('kemitraan-status')
                .listen('.KemitraanUpdated', (e) => {
                    if (e.id == "{{ $kemitraan->id ?? '' }}") {
                        window.location.reload();
                    }
                });
        }
    });
</script>
@endsection
