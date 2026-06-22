<?php $__env->startSection('title', 'Detail Blog - AGRIS'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-5xl mx-auto pt-5 pb-12 px-1">
    <div class="mb-6 flex items-center gap-4" data-aos="fade-up">
        <a href="<?php echo e(route('agen.blog.index')); ?>" class="w-10 h-10 rounded-full border border-gray-200 flex items-center justify-center text-gray-400 bg-white shadow-sm transition-all">
            <i class="fa-solid fa-arrow-left text-sm"></i>
        </a>
        <h2 class="text-xl font-bold text-gray-800">Detail Blog</h2>
    </div>

    <div class="bg-white rounded-4xl border border-gray-100 shadow-sm overflow-hidden" data-aos="fade-up" data-aos-delay="100">
        <?php if($blog->fotoBlog): ?>
            <div class="w-full h-64 md:h-100 overflow-hidden" data-aos="fade-down" data-aos-delay="200">
                <img src="<?php echo e(asset('storage/' . $blog->fotoBlog)); ?>" class="w-full h-full object-cover">
            </div>
        <?php endif; ?>

        <div class="p-6 md:p-10 lg:p-12">
            <div class="flex items-center gap-3 mb-8">
                <div class="w-10 h-10 rounded-full border border-gray-100 overflow-hidden shadow-sm">
                    <img src="<?php echo e($blog->user && $blog->user->fotoProfil ? asset($blog->user->fotoProfil) : 'https://ui-avatars.com/api/?name='.urlencode(($blog->user->username ?? $blog->user->email) ?? 'Admin')); ?>" class="h-full w-full object-cover rounded-full">
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Tanggal Upload</p>
                    <p class="text-sm font-bold text-gray-900 uppercase tracking-tight"><?php echo e($blog->tanggalBlog->format('d F Y')); ?></p>
                </div>
            </div>

            <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold text-gray-900 leading-tight mb-8"><?php echo e($blog->judulBlog); ?></h1>

            <div class="prose prose-sm md:prose-base prose-green max-w-none text-gray-600 leading-relaxed">
                <?php
                    $urlPattern = '/(https?:\/\/[^\s]+)/';
                    $contentWithLinks = preg_replace(
                        $urlPattern,
                        '<a href="$1" target="_blank" class="text-[#58CC02] hover:underline font-bold transition-all">$1</a>',
                        e($blog->isiBlog)
                    );
                ?>
                <?php echo nl2br($contentWithLinks); ?>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.agen', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\project\Agris\resources\views/agen/blog/show.blade.php ENDPATH**/ ?>