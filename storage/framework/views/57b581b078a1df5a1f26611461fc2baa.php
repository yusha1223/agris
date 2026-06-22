<?php $__env->startSection('title', 'Tambah Produk Baru - AGRIS'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-5xl mx-auto pt-4 pb-12">
    <div class="flex items-center gap-4 mb-6 px-4 md:px-0" data-aos="fade-up">
        <h1 class="text-xl font-bold text-gray-800">Tambah Data Produk</h1>
    </div>

    <form action="<?php echo e(route('admin.produk.store')); ?>" method="POST" enctype="multipart/form-data" id="formProduk">
        <?php echo csrf_field(); ?>
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden mx-4 md:mx-0 shadow-sm" data-aos="fade-up" data-aos-delay="100">
            <div class="flex flex-col lg:flex-row">
                <div class="lg:w-1/3 bg-gray-50 p-8 border-b lg:border-b-0 lg:border-r border-gray-200">
                    <div class="flex flex-col items-center">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Foto Produk</span>
                        <div class="relative cursor-pointer group">
                            <div id="imageContainer" class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                'w-44 h-44 rounded-xl overflow-hidden bg-white border-2 border-dashed flex items-center justify-center transition-colors',
                                'border-red-500' => $errors->has('fotoProduk'),
                                'border-gray-300' => !$errors->has('fotoProduk'),
                            ]); ?>">
                                <img id="previewImg" src="#" class="w-full h-full object-cover hidden">
                                <div id="placeholderIcon" class="text-center text-gray-300 group-hover:text-gray-400">
                                    <i class="fa-solid fa-camera text-3xl mb-1"></i>
                                    <p class="text-[10px] font-medium">Klik untuk upload</p>
                                </div>
                            </div>
                            <input type="file" name="fotoProduk" id="fotoInput" accept=".jpg,.jpeg,.png"
                                class="absolute inset-0 opacity-0 cursor-pointer"
                                onchange="previewImage(this)">
                        </div>
                        <p class="text-[10px] text-gray-400 mt-2 font-medium text-center">Format: JPG, JPEG, PNG (Maks. 10MB)</p>
                        <div id="clientError" class="hidden text-red-500 text-[11px] mt-1 font-semibold italic text-center"></div>
                        <?php $__errorArgs = ['fotoProduk'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-[11px] mt-1 font-semibold italic text-center"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="lg:w-2/3 p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Varietas Benih</label>
                            <input type="text" name="namaProduk" value="<?php echo e(old('namaProduk')); ?>"
                                class="<?php echo \Illuminate\Support\Arr::toCssClasses([ 'w-full px-4 py-3 rounded-xl border outline-none transition focus:ring-1 focus:ring-[#58CC02] focus:border-[#58CC02]', 'border-red-500' => $errors->has('namaProduk'), 'border-gray-300' => !$errors->has('namaProduk'), ]); ?>"
                                placeholder="Contoh: Padi Inpari 32">
                            <?php $__errorArgs = ['namaProduk'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-500 text-xs mt-1 font-medium italic"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="md:col-span-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Jenis Komoditas</label>
                            <select name="jenis" id="selectJenis" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-[#58CC02] outline-none bg-white appearance-none">
                                <option value="">-- Pilih Jenis --</option>
                                <?php $__currentLoopData = $kategoris->unique('jenisKategori'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($k->jenisKategori); ?>" <?php echo e(old('jenis') == $k->jenisKategori ? 'selected' : ''); ?>>
                                        <?php echo e(strtoupper($k->jenisKategori)); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['jenis'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-500 text-xs mt-1 font-medium italic"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="md:col-span-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Standar Mutu</label>
                            <select name="mutu" id="selectMutu" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-[#58CC02] outline-none bg-white appearance-none">
                                <option value="">-- Pilih Mutu --</option>
                                <?php $__currentLoopData = $kategoris->unique('mutu'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($k->mutu); ?>" <?php echo e(old('mutu') == $k->mutu ? 'selected' : ''); ?>>
                                        <?php echo e(strtoupper($k->mutu)); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['mutu'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-500 text-xs mt-1 font-medium italic"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="md:col-span-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Berat Karung (Kg)</label>
                            <input type="number" name="karung" step="0.01" min="0" value="<?php echo e(old('karung')); ?>"
                                class="<?php echo \Illuminate\Support\Arr::toCssClasses([ 'w-full px-4 py-3 rounded-xl border outline-none transition focus:border-[#58CC02]', 'border-red-500' => $errors->has('karung'), 'border-gray-300' => !$errors->has('karung'), ]); ?>"
                                placeholder="0.00">
                            <?php $__errorArgs = ['karung'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-500 text-xs mt-1 font-medium italic"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="md:col-span-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Stok (Karung)</label>
                            <input type="number" name="stok" step="1" min="0" value="<?php echo e(old('stok')); ?>"
                                class="<?php echo \Illuminate\Support\Arr::toCssClasses([ 'w-full px-4 py-3 rounded-xl border outline-none transition focus:border-[#58CC02]', 'border-red-500' => $errors->has('stok'), 'border-gray-300' => !$errors->has('stok'), ]); ?>"
                                placeholder="0">
                            <?php $__errorArgs = ['stok'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-500 text-xs mt-1 font-medium italic"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Harga per Karung (Rp)</label>
                            <input type="text" id="hargaVisual"
                                class="<?php echo \Illuminate\Support\Arr::toCssClasses([ 'w-full px-4 py-3 rounded-xl border outline-none font-bold text-gray-800 transition focus:border-[#58CC02]', 'border-red-500' => $errors->has('harga'), 'border-gray-300' => !$errors->has('harga'), ]); ?>"
                                placeholder="0" oninput="formatRupiah(this)">
                            <input type="number" name="harga" id="hargaAsli" value="<?php echo e(old('harga')); ?>" class="hidden">
                            <?php $__errorArgs = ['harga'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-500 text-xs mt-1 font-medium italic"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Keterangan</label>
                            <textarea name="deskripsi" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-300 outline-none resize-none focus:border-[#58CC02]" placeholder="Tambahkan catatan produk..."><?php echo e(old('deskripsi')); ?></textarea>
                        </div>
                    </div>

                    <div class="mt-8 flex gap-3">
                        <button type="button" onclick="openModal('modalKonfirmasiProduk')" class="flex-2 bg-[#58CC02] text-white py-3.5 rounded-xl font-bold active:bg-[#46a302] transition shadow-sm">
                            Tambah Produk
                        </button>
                        <a href="<?php echo e(route('admin.produk.index')); ?>" class="flex-1 bg-gray-100 text-center text-gray-600 py-3.5 rounded-xl font-bold hover:bg-gray-200 transition">
                            Batal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<?php if (isset($component)) { $__componentOriginal9f64f32e90b9102968f2bc548315018c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f64f32e90b9102968f2bc548315018c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modal','data' => ['id' => 'modalKonfirmasiProduk','title' => 'Konfirmasi','message' => 'Apakah anda yakin ingin menambah produk ini?','confirmText' => 'Iya','cancelText' => 'Batal','confirmId' => 'btnSubmitForm','cancelId' => 'btnCloseModal']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'modalKonfirmasiProduk','title' => 'Konfirmasi','message' => 'Apakah anda yakin ingin menambah produk ini?','confirmText' => 'Iya','cancelText' => 'Batal','confirmId' => 'btnSubmitForm','cancelId' => 'btnCloseModal']); ?>
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
    function previewImage(input) {
        const container = document.getElementById('imageContainer');
        const preview = document.getElementById('previewImg');
        const icon = document.getElementById('placeholderIcon');
        const errorDiv = document.getElementById('clientError');

        if (input.files && input.files[0]) {
            const file = input.files[0];
            if (file.size > 10 * 1024 * 1024) {
                errorDiv.textContent = "Ukuran file terlalu besar!";
                errorDiv.classList.remove('hidden');
                input.value = "";
                return;
            }
            errorDiv.classList.add('hidden');
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                icon.classList.add('hidden');
                container.classList.remove('border-dashed');
                container.classList.add('border-solid', 'border-[#58CC02]');
            }
            reader.readAsDataURL(file);
        }
    }

    function formatRupiah(el) {
        let val = el.value.replace(/[^0-9]/g, '');
        document.getElementById('hargaAsli').value = val;
        el.value = val ? new Intl.NumberFormat('id-ID').format(val) : '';
    }

    document.getElementById('btnSubmitForm').addEventListener('click', () => {
        document.getElementById('formProduk').submit();
    });

    document.getElementById('btnCloseModal').addEventListener('click', () => {
        closeModal('modalKonfirmasiProduk');
    });

    window.onload = function() {
        const hargaAsli = document.getElementById('hargaAsli').value;
        if (hargaAsli) {
            const visual = document.getElementById('hargaVisual');
            visual.value = new Intl.NumberFormat('id-ID').format(hargaAsli);
        }
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\project\Agris\resources\views/admin/produk/create.blade.php ENDPATH**/ ?>