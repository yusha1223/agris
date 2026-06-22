<?php $__env->startSection('title', 'Detail Kemitraan - AGRIS'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-5xl mx-auto pt-4 pb-12 px-4">
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4" data-aos="fade-up">
        <div class="flex items-center gap-4">
            <a href="<?php echo e(route('admin.kemitraan.index')); ?>" class="w-10 h-10 shrink-0 flex items-center justify-center rounded-full bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 transition shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-xl md:text-2xl font-bold text-gray-800">Detail Kemitraan Agen</h1>
                <p class="text-gray-500 text-xs md:text-sm">Validasi dokumen MOU untuk mengaktifkan status mitra.</p>
            </div>
        </div>

        <?php if($kemitraan->statusPengajuan == 'Aktif'): ?>
        <form id="formHentikan" action="<?php echo e(route('admin.kemitraan.action', $kemitraan->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="action" value="hentikan">
            <button type="button" onclick="triggerModal('modalHentikan')" class="w-full md:w-auto px-6 py-2.5 bg-red-500 text-white font-bold rounded-xl border border-red-100 hover:bg-red-600 hover:text-white transition-all flex items-center justify-center gap-2 text-sm">
                Hapus Kemitraan
            </button>
        </form>
        <?php endif; ?>
    </div>

    <?php
        $steps = [
            'Menunggu Upload MOU' => 1,
            'Menunggu Verifikasi MOU' => 2,
            'Aktif' => 3
        ];
        $currentStep = $steps[$kemitraan->statusPengajuan] ?? 1;
        $isFailed = $kemitraan->statusPengajuan == 'Ditolak';
    ?>

    <div class="bg-white rounded-3xl border border-gray-100 p-6 md:p-8 shadow-sm mb-8" data-aos="fade-up" data-aos-delay="100">
        <div class="relative flex items-center justify-between mb-16 px-4">
            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-1 bg-gray-100 z-0"></div>
            <div class="absolute left-0 top-1/2 -translate-y-1/2 h-1 <?php echo e($isFailed ? 'bg-red-500' : 'bg-[#58CC02]'); ?> transition-all duration-500 z-0" style="width: <?php echo e($isFailed ? '100' : ($currentStep - 1) * 50); ?>%"></div>

            <?php $__currentLoopData = ['Upload MOU', 'Verifikasi', 'Selesai']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="relative z-10 flex flex-col items-center">
                <div class="w-8 h-8 md:w-10 md:h-10 rounded-full flex items-center justify-center font-bold transition-all <?php echo e($isFailed ? 'bg-red-500 text-white' : ($currentStep > $index ? 'bg-[#58CC02] text-white' : ($currentStep == $index + 1 ? 'bg-white border-4 border-[#58CC02] text-[#58CC02]' : 'bg-white border-4 border-gray-100 text-gray-300'))); ?>">
                    <?php if($isFailed): ?>
                        <i class="fa-solid fa-xmark text-[10px]"></i>
                    <?php elseif($currentStep > $index + 1): ?>
                        <i class="fa-solid fa-check text-[10px]"></i>
                    <?php else: ?>
                        <span class="text-[10px]"><?php echo e($index + 1); ?></span>
                    <?php endif; ?>
                </div>
                <span class="absolute top-10 md:top-12 text-[8px] md:text-[10px] font-black uppercase tracking-widest whitespace-nowrap <?php echo e($isFailed ? 'text-red-500' : ($currentStep >= $index + 1 ? 'text-[#58CC02]' : 'text-gray-400')); ?>"><?php echo e($label); ?></span>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 w-full">
            <div class="lg:col-span-2 space-y-6" data-aos="fade-right" data-aos-delay="200">
                <div>
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-4">Profil Agen</h3>
                    <div class="flex flex-col md:flex-row gap-6 p-6 bg-gray-50 rounded-3xl border border-gray-100">
                        <img src="<?php echo e($kemitraan->user->fotoProfil ? asset($kemitraan->user->fotoProfil) : 'https://ui-avatars.com/api/?name='.urlencode($kemitraan->user->namaLengkap).'&background=58CC02&color=fff'); ?>"
                            class="w-24 h-24 rounded-2xl object-cover shadow-sm self-center md:self-start"
                            alt="Foto Profil">
                        <div class="space-y-4 flex-1">
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase">Nama Lengkap</p>
                                <p class="font-bold text-gray-800 text-lg"><?php echo e($kemitraan->user->namaLengkap); ?></p>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase">Email</p>
                                    <p class="text-sm font-medium text-gray-700 break-all"><?php echo e($kemitraan->user->email); ?></p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase">No. Telp.</p>
                                    <p class="text-sm font-medium text-gray-700"><?php echo e($kemitraan->user->noTelp ?? '-'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-white border border-gray-100 rounded-3xl">
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-map-marked-alt text-[#58CC02]"></i> Alamat
                    </h4>

                    <div class="grid grid-cols-1 gap-y-4 mb-6">
                        <div class="py-3 px-4 rounded-2xl border border-gray-200 bg-gray-50/50 font-medium text-black text-sm leading-relaxed">
                            <?php if($kemitraan->user->desa): ?>
                                <?php echo e($kemitraan->user->detailAlamat); ?>,
                                Desa <?php echo e($kemitraan->user->desa->namaDesa); ?>,
                                Kec. <?php echo e($kemitraan->user->desa->kecamatan->namaKecamatan); ?>,
                                <?php echo e($kemitraan->user->desa->kecamatan->kabupaten->namaKabupaten); ?>,
                                Provinsi <?php echo e($kemitraan->user->desa->kecamatan->kabupaten->provinsi->namaProvinsi); ?>

                            <?php else: ?>
                                <span class="text-gray-400 italic">Alamat belum lengkap</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-50 flex justify-between items-center">
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Tanggal Pengajuan</p>
                            <p class="text-sm font-bold text-gray-800"><?php echo e($kemitraan->tanggalPengajuan->format('d F Y')); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6" data-aos="fade-left" data-aos-delay="200">
                <div>
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-4">Verifikasi Dokumen</h3>
                    <div class="bg-gray-50 p-6 rounded-3xl border border-gray-100">
                        <div class="p-6 bg-white rounded-2xl border border-gray-200 border-dashed text-center mb-6">
                            <?php if($kemitraan->fileKemitraan): ?>
                                <i class="fa-solid fa-file-pdf text-5xl text-red-500 mb-3"></i>
                                <p class="text-xs font-bold text-gray-800 mb-4 uppercase">File Dokumen MOU</p>
                                <button type="button" onclick="previewBase64Pdf('<?php echo e($kemitraan->fileKemitraan); ?>')" class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-gray-800 text-white text-xs font-bold rounded-xl hover:bg-black transition-all border-none cursor-pointer">
                                    <i class="fa-solid fa-eye mr-2"></i> Preview PDF
                                </button>
                            <?php else: ?>
                                <i class="fa-solid fa-file-circle-question text-5xl text-gray-200 mb-3"></i>
                                <p class="text-xs font-medium text-gray-400 italic">File tidak tersedia</p>
                            <?php endif; ?>
                        </div>

                        <div class="space-y-3">
                            <?php if($kemitraan->statusPengajuan == 'Menunggu Verifikasi MOU'): ?>
                                <form id="formAktifkan" action="<?php echo e(route('admin.kemitraan.verifyMou', $kemitraan->id)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="status" value="Aktif">
                                    <button type="button" onclick="triggerModal('modalAktifkan')" class="w-full py-3.5 bg-[#58CC02] text-white font-bold rounded-xl shadow-lg shadow-green-100 hover:bg-[#46a302] transition-all uppercase text-xs tracking-widest cursor-pointer border-none">
                                        Setujui Pengajuan
                                    </button>
                                </form>

                                <form id="formTolakMou" action="<?php echo e(route('admin.kemitraan.verifyMou', $kemitraan->id)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="status" value="Ditolak">
                                    <button type="button" onclick="triggerModal('modalTolakMou')" class="w-full py-3.5 bg-white text-red-600 border border-red-100 font-bold rounded-xl hover:bg-red-50 transition-all uppercase text-xs tracking-widest cursor-pointer">
                                        Tolak Pengajuan
                                    </button>
                                </form>
                            <?php endif; ?>

                            <?php if($kemitraan->statusPengajuan == 'Aktif'): ?>
                                <div class="p-4 bg-green-50 border border-green-100 rounded-2xl text-center">
                                    <p class="text-xs font-black text-[#58CC02] uppercase tracking-widest">Kemitraan Aktif</p>
                                </div>
                            <?php endif; ?>

                            <?php if($kemitraan->statusPengajuan == 'Ditolak'): ?>
                                <div class="p-4 bg-red-50 border border-red-100 rounded-2xl text-center">
                                    <p class="text-xs font-black text-red-600 uppercase tracking-widest">Kemitraan Ditolak</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if (isset($component)) { $__componentOriginal9f64f32e90b9102968f2bc548315018c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f64f32e90b9102968f2bc548315018c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modal','data' => ['id' => 'modalHentikan','message' => 'Apakah anda yakin ingin menghapus kerja sama Agen?','confirmText' => 'Iya','cancelText' => 'Batal','confirmId' => 'btnConfirmHentikan','cancelId' => 'btnCancelHentikan']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'modalHentikan','message' => 'Apakah anda yakin ingin menghapus kerja sama Agen?','confirmText' => 'Iya','cancelText' => 'Batal','confirmId' => 'btnConfirmHentikan','cancelId' => 'btnCancelHentikan']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9f64f32e90b9102968f2bc548315018c)): ?>
<?php $attributes = $__attributesOriginal9f64f32e90b9102968f2bc548315018c; ?>
<?php unset($__attributesOriginal9f64f32e90b9102968f2bc548315018c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9f64f32e90b9102968f2bc548315018c)): ?>
<?php $component = $__componentOriginal9f64f32e90b9102968f2bc548315018c; ?>
<?php unset($__componentOriginal9f64f32e90b9102968f2bc548315018c); ?>
<?php endif; ?>
<?php if (isset($component)) { $__componentOriginal9f64f32e90b9102968f2bc548315018c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f64f32e90b9102968f2bc548315018c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modal','data' => ['id' => 'modalAktifkan','message' => 'Yakin untuk menyutujui pengajuan Mitra?','confirmText' => 'Iya','cancelText' => 'Batal','confirmId' => 'btnConfirmAktif','cancelId' => 'btnCancelAktif']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'modalAktifkan','message' => 'Yakin untuk menyutujui pengajuan Mitra?','confirmText' => 'Iya','cancelText' => 'Batal','confirmId' => 'btnConfirmAktif','cancelId' => 'btnCancelAktif']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9f64f32e90b9102968f2bc548315018c)): ?>
<?php $attributes = $__attributesOriginal9f64f32e90b9102968f2bc548315018c; ?>
<?php unset($__attributesOriginal9f64f32e90b9102968f2bc548315018c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9f64f32e90b9102968f2bc548315018c)): ?>
<?php $component = $__componentOriginal9f64f32e90b9102968f2bc548315018c; ?>
<?php unset($__componentOriginal9f64f32e90b9102968f2bc548315018c); ?>
<?php endif; ?>
<?php if (isset($component)) { $__componentOriginal9f64f32e90b9102968f2bc548315018c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f64f32e90b9102968f2bc548315018c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modal','data' => ['id' => 'modalTolakMou','message' => 'Yakin ingin menolak pengajuan kemitraan ini?','confirmText' => 'Iya','cancelText' => 'Batal','confirmId' => 'btnConfirmTolakMou','cancelId' => 'btnCancelTolakMou']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'modalTolakMou','message' => 'Yakin ingin menolak pengajuan kemitraan ini?','confirmText' => 'Iya','cancelText' => 'Batal','confirmId' => 'btnConfirmTolakMou','cancelId' => 'btnCancelTolakMou']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9f64f32e90b9102968f2bc548315018c)): ?>
<?php $attributes = $__attributesOriginal9f64f32e90b9102968f2bc548315018c; ?>
<?php unset($__attributesOriginal9f64f32e90b9102968f2bc548315018c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9f64f32e90b9102968f2bc548315018c)): ?>
<?php $component = $__componentOriginal9f64f32e90b9102968f2bc548315018c; ?>
<?php unset($__componentOriginal9f64f32e90b9102968f2bc548315018c); ?>
<?php endif; ?>

<script>
    function previewBase64Pdf(base64String) {
        const byteCharacters = atob(base64String);
        const byteNumbers = new Array(byteCharacters.length);
        for (let i = 0; i < byteCharacters.length; i++) {
            byteNumbers[i] = byteCharacters.charCodeAt(i);
        }
        const byteArray = new Uint8Array(byteNumbers);
        const blob = new Blob([byteArray], {type: 'application/pdf'});
        const fileURL = URL.createObjectURL(blob);
        window.open(fileURL, '_blank');
    }

    function triggerModal(id) {
        if (typeof openModal === 'function') {
            openModal(id);
        } else {
            const modal = document.getElementById(id);
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeModalManual(id) {
        if (typeof closeModal === 'function') {
            closeModal(id);
        } else {
            const modal = document.getElementById(id);
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const setups = [
            { btn: 'btnConfirmHentikan', cancel: 'btnCancelHentikan', modal: 'modalHentikan', form: 'formHentikan' },
            { btn: 'btnConfirmAktif', cancel: 'btnCancelAktif', modal: 'modalAktifkan', form: 'formAktifkan' },
            { btn: 'btnConfirmTolakMou', cancel: 'btnCancelTolakMou', modal: 'modalTolakMou', form: 'formTolakMou' }
        ];

        setups.forEach(setup => {
            document.getElementById(setup.btn)?.addEventListener('click', () => {
                const form = document.getElementById(setup.form);
                if(form) form.submit();
            });
            document.getElementById(setup.cancel)?.addEventListener('click', () => closeModalManual(setup.modal));
        });

        if (window.Echo) {
            window.Echo.channel('kemitraan-status')
                .listen('.KemitraanUpdated', (e) => {
                    if (e.id == "<?php echo e($kemitraan->id); ?>") {
                        window.location.reload();
                    }
                });
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\project\Agris\resources\views/admin/kemitraan/show.blade.php ENDPATH**/ ?>