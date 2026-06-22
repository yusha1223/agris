<?php $__env->startSection('title', 'Edit Produk - AGRIS'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-5xl mx-auto pt-4 pb-12">
    <div class="flex items-center gap-4 mb-6 px-4 md:px-0" data-aos="fade-up">
        <div>
            <h1 class="text-xl font-bold text-gray-800">Edit Data Produk</h1>
            <p class="text-xs text-gray-500">Mengubah varietas <span class="text-[#58CC02] font-semibold"><?php echo e($produk->namaProduk); ?></span></p>
        </div>
    </div>

    <form action="<?php echo e(route('admin.produk.update', $produk->id)); ?>" method="POST" enctype="multipart/form-data" id="formProduk">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
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
                                <?php if($produk->fotoProduk): ?>
                                    <img id="previewImg" src="<?php echo e(asset('storage/' . $produk->fotoProduk)); ?>" class="w-full h-full object-cover">
                                    <div id="placeholderIcon" class="hidden text-center text-gray-300 group-hover:text-gray-400">
                                        <i class="fa-solid fa-camera text-3xl mb-1"></i>
                                        <p class="text-[10px] font-medium">Ganti Foto</p>
                                    </div>
                                <?php else: ?>
                                    <img id="previewImg" src="#" class="w-full h-full object-cover hidden">
                                    <div id="placeholderIcon" class="text-center text-gray-300 group-hover:text-gray-400">
                                        <i class="fa-solid fa-camera text-3xl mb-1"></i>
                                        <p class="text-[10px] font-medium">Klik untuk upload</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <input type="file" name="fotoProduk" id="fotoInput" accept=".jpg,.jpeg,.png"
                                class="absolute inset-0 opacity-0 cursor-pointer"
                                onchange="previewImage(this)">
                        </div>
                        <?php $__errorArgs = ['fotoProduk'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-[10px] text-red-500 mt-2 font-bold"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <p class="text-[10px] text-gray-400 mt-2 font-medium text-center">Format: JPG, JPEG, PNG (Maks. 10MB)</p>
                    </div>
                </div>

                <div class="lg:w-2/3 p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Varietas Benih</label>
                            <input type="text" name="namaProduk" value="<?php echo e(old('namaProduk', $produk->namaProduk)); ?>"
                                class="<?php echo \Illuminate\Support\Arr::toCssClasses([ 'w-full px-4 py-3 rounded-xl border outline-none transition focus:ring-1 focus:ring-[#58CC02] focus:border-[#58CC02]', 'border-red-500' => $errors->has('namaProduk'), 'border-gray-300' => !$errors->has('namaProduk'), ]); ?>">
                            <?php $__errorArgs = ['namaProduk'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-[10px] text-red-500 mt-1 font-bold"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="md:col-span-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Jenis Komoditas</label>
                            <select name="jenis" id="selectJenis" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['w-full px-4 py-3 rounded-xl border outline-none bg-white appearance-none', 'border-red-500' => $errors->has('jenis'), 'border-gray-300' => !$errors->has('jenis')]); ?>">
                                <option value="">-- Pilih Jenis --</option>
                                <?php $__currentLoopData = $kategoris->unique('jenisKategori'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($k->jenisKategori); ?>" <?php echo e((old('jenis', $produk->kategori->jenisKategori) == $k->jenisKategori) ? 'selected' : ''); ?>>
                                        <?php echo e(strtoupper($k->jenisKategori)); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['jenis'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-[10px] text-red-500 mt-1 font-bold"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="md:col-span-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Standar Mutu</label>
                            <select name="mutu" id="selectMutu" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['w-full px-4 py-3 rounded-xl border outline-none bg-white appearance-none', 'border-red-500' => $errors->has('mutu'), 'border-gray-300' => !$errors->has('mutu')]); ?>">
                                <option value="">-- Pilih Mutu --</option>
                            </select>
                            <?php $__errorArgs = ['mutu'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-[10px] text-red-500 mt-1 font-bold"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="md:col-span-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Berat Karung (Kg)</label>
                            <input type="number" name="karung" step="0.1" min="0" value="<?php echo e(old('karung', $produk->kategori->karung)); ?>"
                                class="<?php echo \Illuminate\Support\Arr::toCssClasses([ 'w-full px-4 py-3 rounded-xl border outline-none transition focus:border-[#58CC02]', 'border-red-500' => $errors->has('karung'), 'border-gray-300' => !$errors->has('karung'), ]); ?>">
                            <?php $__errorArgs = ['karung'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-[10px] text-red-500 mt-1 font-bold"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="md:col-span-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Stok (Karung)</label>
                            <input type="number" name="stok" step="1" min="0" value="<?php echo e(old('stok', $produk->stok)); ?>"
                                class="<?php echo \Illuminate\Support\Arr::toCssClasses([ 'w-full px-4 py-3 rounded-xl border outline-none transition focus:border-[#58CC02]', 'border-red-500' => $errors->has('stok'), 'border-gray-300' => !$errors->has('stok'), ]); ?>">
                            <?php $__errorArgs = ['stok'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-[10px] text-red-500 mt-1 font-bold"><?php echo e($message); ?></p>
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
                            <input type="number" name="harga" id="hargaAsli" value="<?php echo e(old('harga', (int)$produk->getAttributes()['harga'])); ?>" class="hidden">
                            <?php $__errorArgs = ['harga'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-[10px] text-red-500 mt-1 font-bold"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Keterangan</label>
                            <textarea name="deskripsi" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-300 outline-none resize-none focus:border-[#58CC02]"><?php echo e(old('deskripsi', $produk->deskripsi)); ?></textarea>
                            <?php $__errorArgs = ['deskripsi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-[10px] text-red-500 mt-1 font-bold"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="mt-8 flex gap-3">
                        <button type="button" onclick="openModal('modalUpdateProduk')" class="flex-2 bg-[#58CC02] text-white px-6 py-3.5 rounded-xl font-bold active:bg-[#46a302] transition shadow-sm">
                            Simpan
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modal','data' => ['id' => 'modalUpdateProduk','title' => 'Konfirmasi','message' => 'Apakah anda yakin ingin mengubah produk ini?','confirmText' => 'Iya','cancelText' => 'Batal','confirmId' => 'btnSubmitForm','cancelId' => 'btnCloseModal']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'modalUpdateProduk','title' => 'Konfirmasi','message' => 'Apakah anda yakin ingin mengubah produk ini?','confirmText' => 'Iya','cancelText' => 'Batal','confirmId' => 'btnSubmitForm','cancelId' => 'btnCloseModal']); ?>
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
    const dataKategori = <?php echo json_encode($kategoris, 15, 512) ?>;
    const selectJenis = document.getElementById('selectJenis');
    const selectMutu = document.getElementById('selectMutu');
    const hargaVisual = document.getElementById('hargaVisual');
    const hargaAsli = document.getElementById('hargaAsli');

    function populateDropdown(target, data, selectedValue = null) {
        data.forEach(item => {
            const opt = document.createElement('option');
            opt.value = item;
            opt.textContent = item.toString().toUpperCase();
            if (selectedValue && item.toString() === selectedValue.toString()) opt.selected = true;
            target.appendChild(opt);
        });
    }

    function resetDropdown(target, placeholder) {
        target.innerHTML = `<option value="">${placeholder}</option>`;
    }

    window.onload = function() {
        const initialJenis = "<?php echo e(old('jenis', $produk->kategori->jenisKategori)); ?>";
        const initialMutu = "<?php echo e(old('mutu', $produk->kategori->mutu)); ?>";
        if (initialJenis) {
            const filteredMutu = [...new Set(dataKategori.filter(k => k.jenisKategori === initialJenis).map(k => k.mutu))];
            populateDropdown(selectMutu, filteredMutu, initialMutu);
        }
        if (hargaAsli.value) {
            hargaVisual.value = new Intl.NumberFormat('id-ID').format(hargaAsli.value);
        }
    };

    selectJenis.addEventListener('change', function() {
        resetDropdown(selectMutu, '-- Pilih Mutu --');
        if (this.value) {
            const filteredMutu = [...new Set(dataKategori.filter(k => k.jenisKategori === this.value).map(k => k.mutu))];
            populateDropdown(selectMutu, filteredMutu);
        }
    });

    function formatRupiah(el) {
        let val = el.value.replace(/[^0-9]/g, '');
        hargaAsli.value = val;
        el.value = val ? new Intl.NumberFormat('id-ID').format(val) : '';
    }

    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                const preview = document.getElementById('previewImg');
                const icon = document.getElementById('placeholderIcon');
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                icon.classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    document.getElementById('btnSubmitForm').addEventListener('click', () => {
        document.getElementById('formProduk').submit();
    });

    document.getElementById('btnCloseModal').addEventListener('click', () => {
        closeModal('modalUpdateProduk');
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\project\Agris\resources\views/admin/produk/edit.blade.php ENDPATH**/ ?>