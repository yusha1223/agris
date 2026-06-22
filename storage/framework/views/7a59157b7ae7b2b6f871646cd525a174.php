<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Admin'); ?></title>
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/icon.svg')); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script>
        window.currentUserId = "<?php echo e(Auth::id()); ?>";
        window.isAdmin = true;
    </script>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="bg-gray-100 font-sans antialiased">

<div id="progressBarContainer" class="hidden fixed top-0 left-0 w-full h-1 bg-gray-200 z-110">
    <div id="progressBar" class="h-full bg-[#58CC02] w-0 transition-all duration-300 ease-linear"></div>
</div>

<div class="fixed bottom-5 right-5 z-999999 space-y-3">
    <?php if(session('success')): ?>
        <div class="alert-info flex items-center w-full max-w-xs p-4 rounded-2xl shadow-xl border border-green-200 bg-green-50" role="alert">
            <div class="inline-flex items-center justify-center shrink-0 w-10 h-10 rounded-full bg-green-600 text-white">
                <i class="fa-solid fa-check text-sm"></i>
            </div>
            <div class="ms-3">
                <div class="text-sm font-bold text-green-800">Pesan</div>
                <div class="text-xs text-green-700 mt-0.5"><?php echo e(session('success')); ?></div>
            </div>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert-info flex items-center w-full max-w-xs p-4 rounded-2xl shadow-xl border border-red-200 bg-red-50" role="alert">
            <div class="inline-flex items-center justify-center shrink-0 w-10 h-10 rounded-full bg-red-600 text-white">
                <i class="fa-solid fa-xmark text-sm"></i>
            </div>
            <div class="ms-3">
                <div class="text-sm font-bold text-red-800">Gagal</div>
                <div class="text-xs text-red-700 mt-0.5"><?php echo e(session('error')); ?></div>
            </div>
        </div>
    <?php endif; ?>
</div>

<div class="<?php echo e(!Route::is('admin.profile') ? 'md:ml-64' : ''); ?> transition-all duration-300">
    <?php echo $__env->make('components.topbar-admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="pt-17 px-5 min-h-screen">
        <?php echo $__env->yieldContent('content'); ?>
    </main>
</div>

<?php echo $__env->yieldPushContent('modals'); ?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const forms = document.querySelectorAll('form');
        const progressContainer = document.getElementById('progressBarContainer');
        const progressBar = document.getElementById('progressBar');

        // forms progress bar
        forms.forEach(form => {
            if (form.id !== 'logoutFormReal') {
                form.addEventListener('submit', function () {
                    progressContainer.classList.remove('hidden');
                    let width = 0;
                    const interval = setInterval(() => {
                        if (width >= 90) {
                            clearInterval(interval);
                        } else {
                            width += 10;
                            progressBar.style.width = width + "%";
                        }
                    }, 100);
                });
            }
        });

        const alerts = document.querySelectorAll('.alert-info');
        alerts.forEach(alert => {
            alert.style.opacity = '0';
            alert.style.transform = 'translateX(20px)';
            alert.style.transition = "all 0.5s ease";

            setTimeout(() => {
                alert.style.opacity = '1';
                alert.style.transform = 'translateX(0)';
            }, 100);

            setTimeout(() => {
                alert.style.opacity = '0';
                alert.style.transform = 'translateX(20px)';
                setTimeout(() => { alert.remove(); }, 500);
            }, 4000);
        });
    });
</script>
</body>
</html>
<?php /**PATH D:\project\Agris\resources\views/layouts/admin.blade.php ENDPATH**/ ?>