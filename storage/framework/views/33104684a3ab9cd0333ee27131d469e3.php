<header class="fixed top-0 right-0 left-0 bg-[#0f8629] h-16 z-50">
    <div class="flex items-center justify-between h-full px-6">
        <div class="flex items-center gap-4">
            <a href="<?php echo e(route('admin.produk.index')); ?>" class="flex items-center gap-2">
                <img src="<?php echo e(asset('images/icon.svg')); ?>" class="h-9 w-9 object-contain">
                <span class="text-2xl font-bold text-white uppercase tracking-wider">AGRIS</span>
            </a>
        </div>

        <div class="flex-1 max-w-xl px-7 hidden md:block">
            <form action="<?php echo e(route('admin.produk.index')); ?>" method="GET" class="relative flex items-center bg-green-600/70 rounded-full p-1 shadow-inner border border-white/10">
                <input type="text" name="search" value="<?php echo e(request()->routeIs('admin.produk.*') ? request('search') : ''); ?>" placeholder="Cari Produk...."
                    class="w-full bg-white/90 rounded-full py-2 px-5 text-sm text-gray-700 focus:outline-none border-none placeholder-gray-400">
                <button type="submit" class="px-4 text-white hover:scale-110 transition-transform">
                    <i class="fa-solid fa-magnifying-glass text-lg"></i>
                </button>
            </form>
        </div>

        <div class="flex items-center gap-2 md:gap-4">
            <a href="<?php echo e(route('admin.chat.index')); ?>" class="relative flex items-center justify-center w-10 h-10 rounded-full bg-green-600/70 text-white hover:bg-green-600/60 transition-all">
                <i class="fa-solid fa-comments text-lg"></i>
                <span id="chatBadge" class="<?php echo e($unread_messages_count > 0 ? '' : 'hidden'); ?> absolute -top-1 -right-1 flex h-4 w-4">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-4 w-4 bg-red-600 border-2 border-white"></span>
                </span>
            </a>

            <div class="relative hidden md:block">
                <button id="dropdownBtn" type="button"
                    class="flex items-center gap-3 rounded-full bg-green-600/70 p-1 pr-4 transition-all hover:bg-green-600/60 focus:outline-none">
                    <div class="h-9 w-9 overflow-hidden rounded-full border-2 border-white pointer-events-none">
                        <img src="<?php echo e(auth()->user()->fotoProfil ? asset(auth()->user()->fotoProfil) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->username)); ?>"
                            class="h-full w-full object-cover">
                    </div>
                    <div class="flex items-center gap-2 pointer-events-none text-white">
                        <div class="flex flex-col items-start leading-tight">
                            <span class="text-sm font-bold"><?php echo e(auth()->user()->username); ?></span>
                            <span class="text-xs font-bold">Profil</span>
                        </div>
                        <i class="fa-solid fa-chevron-down text-[10px] transition-transform duration-300"
                            id="dropdownArrow"></i>
                    </div>
                </button>

                <div id="dropdownMenu"
                    class="hidden absolute right-0 mt-3 w-52 bg-white rounded-2xl shadow-2xl border border-gray-100 py-2 z-60">
                    <a href="<?php echo e(route('admin.profile')); ?>"
                        class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition font-semibold">
                        <i class="fa-regular fa-id-card mr-3 text-[#0f8629] text-lg"></i> Profil Saya
                    </a>
                    <div class="mx-4 border-t border-gray-100 my-1"></div>
                    <button type="button" id="logoutBtnTrigger"
                        class="w-full flex items-center px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition font-bold text-left">
                        <i class="fa-solid fa-right-from-bracket mr-3 text-lg"></i> Logout
                    </button>
                </div>
            </div>

            <?php if(!request()->routeIs('admin.profile')): ?>
            <button id="hamburgerBtn" class="md:hidden p-2.5 text-white hover:bg-white/10 rounded-full transition-all">
                <i class="fa-solid fa-bars text-xl"></i>
            </button>
            <?php endif; ?>
        </div>
    </div>
</header>

<?php if(!request()->routeIs('admin.profile')): ?>
<aside id="sidebar" class="fixed top-0 right-0 md:left-0 md:right-auto z-45 w-64 h-screen transition-transform duration-300 ease-in-out translate-x-full md:translate-x-0 bg-[#0f8629]">
    <div class="h-full flex flex-col">
        <div class="h-16 flex items-center px-6 md:hidden">
            <button id="closeSidebarBtn" class="ml-auto text-white/70 hover:text-white p-2">
                <i class="fa-solid fa-xmark text-2xl"></i>
            </button>
        </div>

        <div class="h-16 hidden md:block"></div>

        <nav class="flex-1 px-4 space-y-1 mt-4 md:mt-2 overflow-y-auto">
            <?php
                $menus = [
                    ['name' => 'Produk', 'url' => route('admin.produk.index'), 'active' => 'admin/produk*', 'icon' => 'fa-seedling'],
                    ['name' => 'Kemitraan', 'url' => route('admin.kemitraan.index'), 'active' => 'admin/kemitraan*', 'icon' => 'fa-users'],
                    ['name' => 'Transaksi', 'url' => route('admin.pesanan.index'), 'active' => 'admin/pesanan*', 'icon' => 'fa-wallet'],
                    ['name' => 'Laporan', 'url' => route('admin.laporan.index'), 'active' => 'admin/laporan*', 'icon' => 'fa-file-lines'],
                    ['name' => 'Blog', 'url' => route('admin.blog.index'), 'active' => 'admin/blog*', 'icon' => 'fa-book-open'],
                ];
            ?>

            <?php $__currentLoopData = $menus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e($menu['url']); ?>" class="relative z-50 flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-200 <?php echo e(request()->is($menu['active']) ? 'bg-white/20 text-white shadow-sm' : 'text-white/70 hover:bg-white/10 hover:text-white'); ?>">
                    <i class="fa-solid <?php echo e($menu['icon']); ?> w-5 text-center"></i>
                    <span><?php echo e($menu['name']); ?></span>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </nav>

        <div class="p-4 border-t border-white/10 md:hidden relative z-50">
            <a href="<?php echo e(route('admin.profile')); ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-white/70 hover:bg-white/10 hover:text-white transition-all">
                <i class="fa-solid fa-user-gear w-5 text-center"></i>
                <span>Profil Saya</span>
            </a>
            <button type="button" onclick="openModal('logoutModal')" class="flex items-center w-full gap-3 px-4 py-3 mt-1 rounded-xl text-sm font-semibold text-red-200 hover:bg-red-500 hover:text-white transition-all text-left">
                <i class="fa-solid fa-right-from-bracket w-5 text-center"></i>
                <span>Log Out</span>
            </button>
        </div>
    </div>
</aside>

<div id="sidebarOverlay" class="fixed inset-0 z-40 hidden md:hidden bg-black/40 backdrop-blur-[2px]"></div>
<?php endif; ?>

<?php if (isset($component)) { $__componentOriginal9f64f32e90b9102968f2bc548315018c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f64f32e90b9102968f2bc548315018c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modal','data' => ['id' => 'logoutModal','title' => 'Konfirmasi','message' => 'Apakah Anda yakin ingin keluar?','confirmText' => 'Iya','cancelText' => 'Batal','confirmId' => 'confirmLogoutBtn','cancelId' => 'closeLogoutBtn']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'logoutModal','title' => 'Konfirmasi','message' => 'Apakah Anda yakin ingin keluar?','confirmText' => 'Iya','cancelText' => 'Batal','confirmId' => 'confirmLogoutBtn','cancelId' => 'closeLogoutBtn']); ?>
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

<form id="logoutFormReal" action="<?php echo e(route('logout')); ?>" method="POST" class="hidden">
    <?php echo csrf_field(); ?>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropBtn = document.getElementById('dropdownBtn');
        const dropMenu = document.getElementById('dropdownMenu');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const hamburgerBtn = document.getElementById('hamburgerBtn');
        const closeSidebarBtn = document.getElementById('closeSidebarBtn');
        const logoutTrigger = document.getElementById('logoutBtnTrigger');
        const chatBadge = document.getElementById('chatBadge');

        if (window.Echo) {
            window.Echo.private(`chat.${<?php echo \Illuminate\Support\Js::from(auth()->id())->toHtml() ?>}`)
                .listen('.MessageSent', (e) => {
                    if (chatBadge && !window.location.href.includes('admin/chat')) {
                        chatBadge.classList.remove('hidden');
                    }
                });
        }

        if (dropBtn && dropMenu) {
            dropBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                dropMenu.classList.toggle('hidden');
                document.getElementById('dropdownArrow')?.classList.toggle('rotate-180');
            });
            document.addEventListener('click', () => {
                dropMenu.classList.add('hidden');
                document.getElementById('dropdownArrow')?.classList.remove('rotate-180');
            });
        }

        if (logoutTrigger) {
            logoutTrigger.addEventListener('click', (e) => {
                e.preventDefault();
                if (typeof openModal === 'function') openModal('logoutModal');
            });
        }

        function toggleSidebar() {
            if (!sidebar || !overlay) return;
            const isHidden = sidebar.classList.contains('translate-x-full');
            if (isHidden) {
                sidebar.classList.remove('translate-x-full');
                overlay.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            } else {
                sidebar.classList.add('translate-x-full');
                overlay.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        }

        if (hamburgerBtn) {
            hamburgerBtn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                toggleSidebar();
            });
        }

        if (closeSidebarBtn) closeSidebarBtn.addEventListener('click', toggleSidebar);
        if (overlay) overlay.addEventListener('click', toggleSidebar);

        document.getElementById('confirmLogoutBtn')?.addEventListener('click', () => {
            document.getElementById('logoutFormReal').submit();
        });

        document.getElementById('closeLogoutBtn')?.addEventListener('click', () => {
            if (typeof closeModal === 'function') closeModal('logoutModal');
        });


    });
</script>
<?php /**PATH D:\project\Agris\resources\views/components/topbar-admin.blade.php ENDPATH**/ ?>