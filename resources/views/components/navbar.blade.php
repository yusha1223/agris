<nav id="navbar" class="fixed w-full z-50 transition-all duration-500 px-6 bg-[#0f8629]/60 backdrop-blur-lg">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center h-17">
            <a href="{{ route('landing') }}" class="flex items-center gap-3 text-2xl font-bold text-white tracking-tight">
                <img src="{{ asset('images/icon.svg') }}" class="w-10 h-auto drop-shadow-md" alt="Logo AGRIS">
                <span>AGRIS</span>
            </a>

            <div class="hidden md:flex items-center gap-10 font-semibold text-white">
                <a href="{{ route('landing') }}" class="relative group py-2 transition-colors duration-300">
                    Beranda
                    <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-[#10b981] transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="{{ route('about') }}" class="relative group py-2 transition-colors duration-300">
                    Tentang
                    <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-[#10b981] transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="{{ route('guest.blog.index') }}" class="relative group py-2 transition-colors duration-300">
                    Blog
                    <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-[#10b981] transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="{{ route('contact') }}" class="relative group py-2 transition-colors duration-300">
                    Kontak
                    <span class="absolute left-0 -bottom-1 w-0 h-0.5 bg-[#10b981] transition-all duration-300 group-hover:w-full"></span>
                </a>
            </div>

            <div class="hidden md:flex items-center gap-3">
                <a href="{{ route('register') }}" class="px-6 py-2 rounded-xl text-white font-bold border-2 hover:bg-white/50 border-white/20">
                    Daftar
                </a>
                <a href="{{ route('login') }}" class="px-6 py-2.5 rounded-xl bg-[#0eb833] hover:bg-[#0ed038] text-white font-bold shadow-lg shadow-green-900/20">
                    Login
                </a>
            </div>

            <div class="md:hidden flex items-center">
                <button id="menu-btn" class="text-white focus:outline-none transition-all duration-300">
                    <i id="menu-icon" class="fas fa-bars text-2xl"></i>
                </button>
            </div>
        </div>
    </div>

    <div id="mobile-menu" class="hidden md:hidden bg-[#0B2B26] border-t border-white/10 absolute w-full left-0">
        <div class="px-6 pt-6 pb-12 space-y-5 font-medium text-gray-300">
            <a href="{{ route('landing') }}" class="block hover:text-white transition border-b border-white/5 pb-3">Beranda</a>
            <a href="{{ route('about') }}" class="block hover:text-white transition border-b border-white/5 pb-3">Tentang</a>
            <a href="{{ route('guest.blog.index') }}" class="block hover:text-white transition border-b border-white/5 pb-3">Blog</a>
            <a href="{{ route('contact') }}" class="block hover:text-white transition border-b border-white/5 pb-3">Kontak</a>
            <div class="pt-6 grid grid-cols-2 gap-4">
                <a href="{{ route('login') }}" class="flex items-center justify-center py-3 rounded-xl border border-white/20 text-white font-bold hover:bg-white/5">
                    Login
                </a>
                <a href="{{ route('register') }}" class="flex items-center justify-center py-3 rounded-xl bg-[#58CC02] text-white font-bold shadow-lg">
                    Daftar
                </a>
            </div>
        </div>
    </div>
</nav>

<script>
    function initNavbar() {
        const menuBtn = document.getElementById('menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        const menuIcon = document.getElementById('menu-icon');
        const navbar = document.getElementById('navbar');

        if (menuBtn && mobileMenu && menuIcon) {
            menuBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
                const isHidden = mobileMenu.classList.contains('hidden');
                menuIcon.classList.toggle('fa-bars', isHidden);
                menuIcon.classList.toggle('fa-times', !isHidden);
            });
        }

        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.replace('bg-[#0f8629]/60', 'bg-[#0f8629]');
            } else {
                navbar.classList.replace('bg-[#0f8629]', 'bg-[#0f8629]/60');
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initNavbar);
    } else {
        initNavbar();
    }
</script>
