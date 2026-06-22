<?php $__env->startSection('title', 'Register - AGRIS'); ?>

<?php $__env->startSection('content'); ?>

<div class="min-h-screen flex items-center justify-center bg-gray-100">

    <div class="bg-white p-8 rounded-lg shadow w-full max-w-md">

        <div class="mb-4">
            <a href="<?php echo e(route('register')); ?>" class="inline-flex items-center gap-2 text-gray-600 hover:text-[#58CC02] transition text-sm">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
        </div>

        <h2 class="text-2xl font-bold text-center mb-6">
            Verifikasi OTP
        </h2>

        <?php if(session('error')): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

        <?php if(session('success')): ?>
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('otp.verify')); ?>">
            <?php echo csrf_field(); ?>

            <input type="text"
                name="otp"
                maxlength="6"
                placeholder="Masukkan 6 digit OTP"
                class="w-full border p-3 text-center text-xl tracking-widest rounded mb-4">

            <button class="w-full bg-[#58CC02] text-white py-3 cursor-pointer rounded">
                Verifikasi
            </button>
        </form>

        <form method="POST" action="<?php echo e(route('otp.resend')); ?>" class="mt-4 text-center">
            <?php echo csrf_field(); ?>
            <button class="text-sm text-gray-600 cursor-pointer hover:underline">
                Kirim Ulang OTP
            </button>
        </form>

    </div>

</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\project\Agris\resources\views/auth/otp.blade.php ENDPATH**/ ?>