<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'id' => 'confirmModal',
    'title' => 'Konfirmasi',
    'message' => 'Apakah Anda yakin?',
    'confirmText' => 'Iya',
    'cancelText' => 'Batal',
    'confirmId' => 'submitForm',
    'cancelId' => 'closeModal'
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'id' => 'confirmModal',
    'title' => 'Konfirmasi',
    'message' => 'Apakah Anda yakin?',
    'confirmText' => 'Iya',
    'cancelText' => 'Batal',
    'confirmId' => 'submitForm',
    'cancelId' => 'closeModal'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div id="<?php echo e($id); ?>" class="fixed inset-0 z-300 hidden items-center justify-center p-4 overflow-x-hidden overflow-y-auto outline-none focus:outline-none modal-container">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity duration-300 modal-overlay" data-modal-id="<?php echo e($id); ?>"></div>

    <div id="content-<?php echo e($id); ?>" class="relative bg-white rounded-[2.5rem] p-8 md:p-10 max-w-sm w-full shadow-2xl text-center border border-gray-100 transition-all duration-300 transform scale-95 opacity-0">
        <div class="w-20 h-20 bg-green-100 text-[#58CC02] rounded-full flex items-center justify-center mx-auto mb-6 text-4xl shadow-sm">
            <i class="fa-solid fa-question"></i>
        </div>

        <h3 class="text-2xl font-black text-gray-800 mb-2"><?php echo e($title); ?></h3>
        <p class="text-gray-500 font-medium mb-8 leading-relaxed">
            <?php echo e($message); ?>

        </p>

        <div class="flex gap-3">
            <button type="button" id="<?php echo e($cancelId); ?>" class="flex-1 py-4 bg-red-500 hover:bg-red-400 text-white font-bold rounded-2xl transition-all active:scale-95 shadow-md">
                <?php echo e($cancelText); ?>

            </button>

            <button type="button" id="<?php echo e($confirmId); ?>" class="flex-1 py-4 bg-[#58CC02] hover:bg-[#4fb802] text-white font-bold rounded-2xl transition-all shadow-lg shadow-green-100 active:scale-95 btn-confirm-modal">
                <?php echo e($confirmText); ?>

            </button>
        </div>
    </div>
</div>

<?php if (! $__env->hasRenderedOnce('2ea2a983-47cd-49dc-8c4e-dfd1822edac3')): $__env->markAsRenderedOnce('2ea2a983-47cd-49dc-8c4e-dfd1822edac3'); ?>
<script>
    window.openModal = function(id) {
        const modal = document.getElementById(id);
        const content = document.getElementById('content-' + id);

        if (!modal || !content) return;

        const confirmBtn = modal.querySelector('.btn-confirm-modal');
        if (confirmBtn) {
            if (confirmBtn.disabled || confirmBtn.style.pointerEvents === 'none') {
                confirmBtn.disabled = false;
                confirmBtn.style.pointerEvents = '';
                confirmBtn.classList.remove('cursor-wait');
                confirmBtn.classList.add('hover:bg-[#4fb802]', 'active:scale-95');
                if (confirmBtn.hasAttribute('data-original-text')) {
                    confirmBtn.innerHTML = confirmBtn.getAttribute('data-original-text');
                }
            } else {
                confirmBtn.setAttribute('data-original-text', confirmBtn.innerHTML);
            }
        }

        document.body.style.overflow = 'hidden';
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    window.closeModal = function(id) {
        const modal = document.getElementById(id);
        const content = document.getElementById('content-' + id);

        if (!modal || !content) return;

        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');

        setTimeout(() => {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }, 300);
    }

    document.addEventListener('DOMContentLoaded', () => {
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('modal-overlay')) {
                const modalId = e.target.getAttribute('data-modal-id');
                window.closeModal(modalId);
            }

            const confirmBtn = e.target.closest('.btn-confirm-modal');
            if (confirmBtn) {
                if (confirmBtn.style.pointerEvents === 'none' || confirmBtn.disabled) {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    return;
                }

                const btnText = confirmBtn.textContent.trim().toLowerCase();
                const triggerTexts = ['iya', 'ya', 'hapus', 'lengkapi', 'setuju', 'proses', 'tambah', 'simpan', 'update', 'kirim'];
                if (triggerTexts.includes(btnText)) {
                    if (!confirmBtn.hasAttribute('data-original-text')) {
                        confirmBtn.setAttribute('data-original-text', confirmBtn.innerHTML);
                    }

                    confirmBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin mr-2"></i> Memproses';
                    confirmBtn.classList.add('cursor-wait');
                    confirmBtn.classList.remove('hover:bg-[#4fb802]', 'active:scale-95');
                    confirmBtn.style.pointerEvents = 'none';
                    setTimeout(() => {
                        confirmBtn.disabled = true;
                    }, 10);
                }
            }
        });
    });
</script>
<?php endif; ?>
<?php /**PATH D:\project\Agris\resources\views/components/modal.blade.php ENDPATH**/ ?>