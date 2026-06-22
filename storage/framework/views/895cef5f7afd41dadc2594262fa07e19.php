<?php $__env->startSection('title', 'Register - AGRIS'); ?>

<?php $__env->startSection('content'); ?>

<div class="flex min-h-screen w-full">

    <div class="hidden md:flex w-1/2 bg-[#0f8629] flex-col justify-between p-12">
        <div>
            <img src="<?php echo e(asset('images/icon.svg')); ?>" class="w-20">
        </div>

        <div class="flex justify-center items-center flex-1">
            <img src="<?php echo e(asset('images/plant.svg')); ?>" class="w-50">
        </div>

        <div class="text-white text-center text-lg font-medium leading-relaxed mb-6">
            Masuk dan Temukan <br> Keseimbangan Alam Dalam Setiap Tanam
        </div>
    </div>

    <div class="w-full md:w-1/2 flex items-center justify-center relative py-10 overflow-y-auto bg-gray-100 text-sm">

        <a href="<?php echo e(route('landing')); ?>" class="absolute top-6 left-6 text-gray-600 hover:text-[#0f8629] transition">
            <i class="fa-solid fa-arrow-left text-xl"></i>
        </a>

        <div class="w-full max-w-md px-8">

            <h2 class="text-2xl font-bold text-gray-700 mb-6 text-center">
                Daftar Akun AGRIS
            </h2>

            <div id="progressBarContainer" class="hidden fixed top-0 left-0 w-full h-1 bg-gray-200 z-50">
                <div id="progressBar" class="h-full bg-[#0f8629] w-0 transition-all duration-500 ease-linear"></div>
            </div>

            <form method="POST" action="<?php echo e(route('register')); ?>">
                <?php echo csrf_field(); ?>

                <div class="mb-3">
                    <input type="text" name="namaLengkap" value="<?php echo e(old('namaLengkap')); ?>" placeholder="Nama Lengkap" class="w-full bg-transparent border-b border-gray-400 focus:border-[#0f8629] focus:outline-none py-2">
                    <?php $__errorArgs = ['namaLengkap'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="mb-3">
                    <input type="text" name="noTelp" value="<?php echo e(old('noTelp')); ?>" placeholder="Nomor Telepon" class="w-full bg-transparent border-b border-gray-400 focus:border-[#0f8629] focus:outline-none py-2">
                    <?php $__errorArgs = ['noTelp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="mb-3">
                    <input type="email" name="email" value="<?php echo e(old('email')); ?>" placeholder="Email" class="w-full bg-transparent border-b border-gray-400 focus:border-[#0f8629] focus:outline-none py-2">
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="mb-3 relative">
                    <input type="password" name="password" id="password" placeholder="Password" class="w-full bg-transparent border-b border-gray-400 focus:border-[#0f8629] focus:outline-none py-2 pr-10">
                    <span class="toggle-password absolute right-2 top-2 cursor-pointer text-gray-500 hover:text-[#0f8629]" data-target="#password">
                        <i class="fa-solid fa-eye"></i>
                    </span>
                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="mb-3 relative">
                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Konfirmasi Password" class="w-full bg-transparent border-b border-gray-400 focus:border-[#0f8629] focus:outline-none py-2 pr-10">
                    <span class="toggle-password absolute right-2 top-2 cursor-pointer text-gray-500 hover:text-[#0f8629]" data-target="#password_confirmation">
                        <i class="fa-solid fa-eye"></i>
                    </span>
                </div>

                <button type="submit" class="w-full bg-[#0f8629] hover:bg-green-600 text-white py-2 rounded-full cursor-pointer text-lg transition">
                    Daftar
                </button>

            </form>

            <div class="flex items-center gap-2 my-4">
                <div class="flex-1 h-px bg-gray-400"></div>
                <span class="text-sm text-gray-600">atau</span>
                <div class="flex-1 h-px bg-gray-400"></div>
            </div>

            <a href="<?php echo e(route('google.login')); ?>" class="w-full flex items-center justify-center border border-gray-500 rounded-full py-3 hover:bg-gray-200 transition">
                <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-5 h-5 mr-2">
                Login dengan Google
            </a>

            <div class="text-center text-sm mt-6">
                Sudah punya akun?
                <a href="<?php echo e(route('login')); ?>" class="text-[#0f8629] font-semibold hover:underline">
                    Login
                </a>
            </div>

        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\project\Agris\resources\views/auth/register.blade.php ENDPATH**/ ?>