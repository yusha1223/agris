@extends('layouts.agen')

@section('title', 'Upload MOU Kemitraan - AGRIS')

@section('content')
<div class="max-w-4xl mx-auto pt-3 md:pt-5 pb-12 px-3 md:px-6">
    @if($kemitraan)
    @php
        $steps = [
            'Menunggu Upload MOU' => 1,
            'Menunggu Verifikasi MOU' => 2,
            'Aktif' => 3
        ];
        $currentStep = $steps[$kemitraan->statusPengajuan] ?? 1;
        $isFailed = $kemitraan->statusPengajuan == 'Ditolak';
    @endphp

    <div class="bg-white rounded-3xl border border-gray-100 p-8 shadow-sm mb-12" data-aos="fade-up">
        <div class="relative flex items-center justify-between mb-12">
            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-1 bg-gray-100 z-0"></div>
            <div class="absolute left-0 top-1/2 -translate-y-1/2 h-1 {{ $isFailed ? 'bg-red-500' : 'bg-[#58CC02]' }} z-0" style="width: {{ $isFailed ? '100' : ($currentStep - 1) * 50 }}%"></div>

            @foreach(['Upload MOU', 'Verifikasi', 'Selesai'] as $index => $label)
            <div class="relative z-10 flex flex-col items-center">
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold {{ $currentStep > $index ? 'bg-[#58CC02] text-white' : ($currentStep == $index + 1 ? 'bg-white border-4 border-[#58CC02] text-[#58CC02]' : 'bg-white border-4 border-gray-100 text-gray-300') }}">
                    @if($currentStep > $index + 1)
                        <i class="fa-solid fa-check"></i>
                    @elseif($isFailed && $index + 1 >= $currentStep)
                        <i class="fa-solid fa-xmark text-red-500"></i>
                    @else
                        {{ $index + 1 }}
                    @endif
                </div>
                <span class="absolute top-12 text-[10px] font-bold uppercase tracking-wider whitespace-nowrap {{ $currentStep >= $index + 1 ? 'text-[#58CC02]' : 'text-gray-400' }}">{{ $label }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="mb-8 text-center" data-aos="fade-up" data-aos-delay="100">
        <h1 class="text-2xl font-bold text-gray-800">Lengkapi Dokumen Kemitraan</h1>
        <p class="text-gray-500">Unggah file MOU dalam format PDF untuk diverifikasi oleh admin.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="md:col-span-1" data-aos="fade-right" data-aos-delay="150">
            <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm mb-6">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2 text-sm uppercase tracking-wider">
                    <i class="fa-solid fa-list-check text-[#58CC02]"></i> Ketentuan
                </h3>
                <ul class="space-y-3 text-xs text-gray-500">
                    <li class="flex gap-2">
                        <i class="fa-solid fa-circle-check text-green-500 mt-0.5"></i>
                        <span>File wajib PDF (Maks. 10MB).</span>
                    </li>
                    <li class="flex gap-2">
                        <i class="fa-solid fa-circle-check text-green-500 mt-0.5"></i>
                        <span>Gunakan template yang telah disediakan.</span>
                    </li>
                </ul>
                <div class="mt-6 pt-6 border-t border-gray-50">
                    <a href="https://docs.google.com/document/d/1l4LvWVaSuImZOfhnV_KPClyWzXpBd3Qy9Ask56TmjCI/export?format=docx" download class="flex items-center justify-center gap-2 w-full py-3 bg-gray-50 text-gray-600 text-xs font-bold rounded-xl border border-gray-100 hover:bg-gray-100 transition-all">
                        <i class="fa-solid fa-download"></i> Download Template
                    </a>
                </div>
            </div>
        </div>

        <div class="md:col-span-2" data-aos="fade-left" data-aos-delay="150">
            <form id="formUploadMou" action="{{ route('kemitraan.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-3xl border border-gray-100 p-8 shadow-sm">
                @csrf
                <div id="dropzone" class="border-2 border-dashed border-gray-200 rounded-3xl p-10 text-center hover:border-[#58CC02] transition-all group relative">
                    <input type="file" name="fileKemitraan" id="fileKemitraan" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" accept="application/pdf" required>

                    <div id="uploadPlaceholder" class="relative z-10 pointer-events-none">
                        <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-green-50 transition-colors">
                            <i class="fa-solid fa-file-pdf text-2xl text-gray-400 group-hover:text-[#58CC02]"></i>
                        </div>
                        <p class="text-gray-600 font-bold mb-1">Pilih file PDF</p>
                        <p class="text-gray-400 text-xs">Klik atau seret file ke sini</p>
                    </div>

                    <div id="previewContainer" class="hidden relative z-30">
                        <div class="flex items-center justify-between bg-green-50 p-4 rounded-2xl border border-green-100 mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center text-[#58CC02] shadow-sm">
                                    <i class="fa-solid fa-file-lines text-xl"></i>
                                </div>
                                <div class="text-left">
                                    <p id="previewName" class="text-sm font-bold text-gray-800 truncate max-w-37.5">document.pdf</p>
                                    <p id="previewSize" class="text-[10px] text-gray-400">0 KB</p>
                                </div>
                            </div>
                            <button type="button" id="btnRemoveFile" class="text-gray-400 hover:text-red-500 transition-colors cursor-pointer border-none bg-transparent">
                                <i class="fa-solid fa-circle-xmark text-lg"></i>
                            </button>
                        </div>
                        <button type="button" id="btnViewPdf" class="inline-flex items-center gap-2 text-[#58CC02] text-xs font-bold hover:underline bg-transparent cursor-pointer border-none">
                            <i class="fa-solid fa-eye"></i> Lihat Isi Dokumen (Preview)
                        </button>
                    </div>
                </div>

                <div id="errorMessage" class="hidden mt-4 p-4 bg-red-50 border border-red-100 rounded-2xl items-center gap-3 text-red-600 text-sm">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span>Format file tidak didukung. Mohon unggah file dalam format PDF.</span>
                </div>

                <div id="actionContainer" class="hidden mt-8">
                    <button type="button" onclick="openModal('uploadModal')" class="w-full py-4 bg-[#58CC02] text-white font-black rounded-2xl shadow-lg shadow-green-100 uppercase tracking-widest hover:opacity-90 transition-all cursor-pointer border-none">
                        Kirim
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<x-modal id="uploadModal" title="Konfirmasi" message="Yakin untuk mengajukan kemitraan?" confirmText="Iya" cancelText="Batal" confirmId="btnFinalSubmit" cancelId="btnCancelUpload" />

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('fileKemitraan');
        const uploadPlaceholder = document.getElementById('uploadPlaceholder');
        const previewContainer = document.getElementById('previewContainer');
        const actionContainer = document.getElementById('actionContainer');
        const errorMessage = document.getElementById('errorMessage');
        const previewName = document.getElementById('previewName');
        const previewSize = document.getElementById('previewSize');
        const btnViewPdf = document.getElementById('btnViewPdf');
        const btnRemove = document.getElementById('btnRemoveFile');
        const form = document.getElementById('formUploadMou');

        let pdfUrl = null;

        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const file = this.files[0];

                if (file.type !== 'application/pdf') {
                    errorMessage.classList.remove('hidden');
                    this.value = '';
                    return;
                }

                errorMessage.classList.add('hidden');
                previewName.innerText = file.name;
                previewSize.innerText = (file.size / 1024).toFixed(1) + ' KB';

                if (pdfUrl) URL.revokeObjectURL(pdfUrl);
                pdfUrl = URL.createObjectURL(file);

                uploadPlaceholder.classList.add('hidden');
                previewContainer.classList.remove('hidden');
                actionContainer.classList.remove('hidden');
                fileInput.classList.add('hidden');
            }
        });

        btnViewPdf.addEventListener('click', function(e) {
            e.preventDefault();
            if (pdfUrl) window.open(pdfUrl, '_blank');
        });

        btnRemove.addEventListener('click', function(e) {
            e.preventDefault();
            fileInput.value = '';
            if (pdfUrl) {
                URL.revokeObjectURL(pdfUrl);
                pdfUrl = null;
            }
            errorMessage.classList.add('hidden');
            uploadPlaceholder.classList.remove('hidden');
            previewContainer.classList.add('hidden');
            actionContainer.classList.add('hidden');
            fileInput.classList.remove('hidden');
        });

        document.getElementById('btnFinalSubmit').addEventListener('click', function() {
            this.disabled = true;
            form.submit();
        });

        document.getElementById('btnCancelUpload').addEventListener('click', () => {
            closeModal('uploadModal');
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
