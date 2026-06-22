@php
    $isChatRoute = Route::currentRouteName() === 'agen.chat.index' || request()->is('*chat*');
    $cartCount = auth()->check()
        ? \App\Models\Keranjang::where('userId', auth()->id())->distinct('produkId')->count('produkId')
        : 0;
@endphp

<nav class="{{ $isChatRoute ? 'relative' : 'fixed top-0' }} w-full z-55 shadow-md border-b border-white/10 transition-all duration-300">
    <div class="bg-[#0f8629] py-3 px-4 md:px-6">
        <div class="max-w-7xl mx-auto flex justify-between items-center gap-4">
            <a href="{{ route('agen.produk.index') }}" class="flex items-center gap-2 shrink-0">
                <img src="{{ asset('images/icon.svg') }}" class="w-12 h-auto" alt="Logo AGRIS">
                <span class="text-2xl font-bold text-white uppercase tracking-tight">AGRIS</span>
            </a>

            <div class="flex-1 max-w-xl hidden md:block px-4">
                <form action="{{ route('agen.produk.index') }}" method="GET" class="relative flex items-center bg-green-600/40 rounded-full p-1 border border-white/10 group">
                    <input type="text" name="search" value="{{ request()->routeIs('agen.produk.*') ? request('search') : '' }}" placeholder="Cari Produk...." class="w-full bg-white rounded-full py-2 px-5 text-sm text-gray-700 focus:outline-none placeholder-gray-400 transition-all">
                    <button type="submit" class="px-4 text-white hover:scale-110 transition-transform">
                        <i class="fa-solid fa-magnifying-glass text-base"></i>
                    </button>
                </form>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                <a href="{{ route('agen.keranjang.index') }}" class="flex items-center justify-center w-10 h-10 rounded-full bg-green-600/50 text-white hover:bg-white/20 transition-all relative">
                    <i class="fa-solid fa-cart-shopping text-lg"></i>
                    <div id="cart-notification-dot"
                        class="absolute -top-1 -right-1 min-w-4.5 h-4.5 bg-red-500 border-2 border-[#0f8629] rounded-full text-white text-[9px] font-black flex items-center justify-center px-0.5 transition-all {{ $cartCount > 0 ? '' : 'hidden' }}">
                        {{ $cartCount > 9 ? '9+' : $cartCount }}
                    </div>
                </a>

                <a href="{{ route('agen.chat.index') }}" class="relative flex items-center justify-center w-10 h-10 rounded-full bg-green-600/50 text-white hover:bg-white/20 transition-all">
                    <i class="fa-solid fa-comments text-lg"></i>
                    <span id="chat-notification-dot" class="{{ $unread_messages_count > 0 ? '' : 'hidden' }} absolute -top-1 -right-1 flex h-4 w-4">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-4 w-4 bg-red-600 border-2 border-white"></span>
                    </span>
                </a>

                <div class="relative hidden md:block">
                    <button id="dropdownBtn" type="button" class="group flex items-center gap-3 rounded-full bg-green-600/50 p-1 pr-4 transition-all hover:bg-white/20 focus:outline-none">
                        <div class="h-9 w-9 overflow-hidden rounded-full border-2 border-white pointer-events-none">
                            <img src="{{ auth()->user()->fotoProfil ? asset(auth()->user()->fotoProfil) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->namaLengkap) }}"
                                class="h-full w-full object-cover">
                        </div>
                        <div class="flex items-center gap-2 pointer-events-none text-white text-left">
                            <div class="flex flex-col leading-tight">
                                <span class="text-sm font-bold">{{ auth()->user()->username }}</span>
                                <span class="text-xs font-bold">Profil</span>
                            </div>
                            <i class="fa-solid fa-chevron-down text-[10px] transition-transform duration-300" id="dropdownArrow"></i>
                        </div>
                    </button>

                    <div id="dropdownMenu" class="hidden absolute right-0 mt-3 w-52 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-60 opacity-0 scale-95 transition-all duration-200 origin-top-right">
                        <a href="{{ route('agen.profile') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition font-bold">
                            <i class="fa-regular fa-id-card mr-3 text-[#0f8629] text-lg"></i> Profil Saya
                        </a>
                        <div class="mx-4 border-t border-gray-100 my-1"></div>
                        <button type="button" id="logoutBtnTrigger" class="w-full flex items-center px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition font-bold text-left">
                            <i class="fa-solid fa-right-from-bracket mr-3 text-lg"></i> Logout
                        </button>
                    </div>
                </div>

                <button id="hamburger" class="md:hidden p-2 text-white hover:bg-white/10 rounded-lg transition">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="bg-[#19a201] hidden md:block border-t border-white/5 shadow-inner">
        <div class="max-w-7xl mx-auto px-6 text-white/95 text-sm font-bold tracking-wide uppercase">
            <div class="flex justify-center items-center gap-10 h-11">
                @php $navs =
                [['agen.blog.*', 'Blog', route('agen.blog.index')],
                ['agen.produk.*', 'Produk', route('agen.produk.index')],
                ['agen.pesanan.*', 'Transaksi', route('agen.pesanan.index')],
                ['kemitraan.*', 'Kemitraan', route('kemitraan.index')]];
                @endphp
                @foreach($navs as $nav)
                    <a href="{{ $nav[2] }}" class="hover:text-white transition-all py-1 border-b-2 {{ $nav[0] && Route::is($nav[0]) ? 'border-white' : 'border-transparent' }} hover:border-white">
                        {{ $nav[1] }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <div id="mobileMenu" class="hidden md:hidden bg-white border-t border-gray-100 shadow-2xl absolute w-full left-0 max-h-[calc(100vh)] overflow-y-auto opacity-0 -translate-y-2 transition-all duration-200">
        <div class="px-6 py-6 space-y-4">
            <div class="flex items-center gap-4 p-4 bg-gray-100 rounded-2xl border border-gray-100">
                <div class="h-14 w-14 overflow-hidden rounded-full border-2 border-[#0f8629]">
                    <img src="{{ auth()->user()->fotoProfil ? asset(auth()->user()->fotoProfil) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->username) }}" class="h-full w-full object-cover">
                </div>
                <div>
                    <h4 class="font-bold text-gray-900 text-lg">{{ auth()->user()->username }}</h4>
                    <a href="{{ route('agen.profile') }}" class="text-sm font-bold text-[#0f8629] hover:underline">Profil</a>
                </div>
            </div>

            <form action="{{ route('agen.produk.index') }}" method="GET" class="flex items-center gap-2 bg-gray-100 rounded-xl px-4 py-2.5 border border-gray-200">
                <i class="fa-solid fa-magnifying-glass text-gray-400 text-sm"></i>
                <input type="text" name="search" value="{{ request()->routeIs('agen.produk.*') ? request('search') : '' }}" placeholder="Cari Produk...." class="flex-1 bg-transparent text-sm text-gray-700 focus:outline-none placeholder-gray-400">
            </form>

            <div class="grid grid-cols-1 gap-2">
                <a href="{{ route('agen.blog.index') }}" class="flex items-center py-3 px-4 rounded-xl {{ Route::is('agen.blog.*') ? 'bg-green-50 text-[#0f8629]' : 'text-gray-700 hover:bg-gray-50' }} font-bold">
                    <i class="fa-solid fa-newspaper mr-3 w-5 text-lg"></i> Blog
                </a>
                <a href="{{ route('agen.produk.index') }}" class="flex items-center py-3 px-4 rounded-xl {{ Route::is('agen.produk.*') ? 'bg-green-50 text-[#0f8629]' : 'text-gray-700 hover:bg-gray-50' }} font-bold">
                    <i class="fa-solid fa-box mr-3 w-5 text-lg"></i> Produk
                </a>
                <a href="{{ route('agen.pesanan.index') }}" class="flex items-center py-3 px-4 rounded-xl {{ Route::is('agen.pesanan.*') ? 'bg-green-50 text-[#0f8629]' : 'text-gray-700 hover:bg-gray-50' }} font-bold">
                    <i class="fa-solid fa-receipt mr-3 w-5 text-lg"></i> Transaksi
                </a>
                <a href="{{ route('kemitraan.index') }}" class="flex items-center py-3 px-4 rounded-xl {{ Route::is('kemitraan.*') ? 'bg-green-50 text-[#0f8629]' : 'text-gray-700 hover:bg-gray-50' }} font-bold">
                    <i class="fa-solid fa-handshake mr-3 w-5 text-lg"></i> Kemitraan
                </a>
                <a href="{{ route('agen.keranjang.index') }}" class="flex items-center py-3 px-4 rounded-xl {{ Route::is('agen.keranjang.*') ? 'bg-green-50 text-[#0f8629]' : 'text-gray-700 hover:bg-gray-50' }} font-bold relative">
                    <i class="fa-solid fa-cart-shopping mr-3 w-5 text-lg"></i> Keranjang
                    <div id="cart-notification-dot-mobile"
                        class="ml-auto min-w-5 h-5 bg-red-500 rounded-full text-white text-[9px] font-black flex items-center justify-center px-1 {{ $cartCount > 0 ? '' : 'hidden' }}">
                        {{ $cartCount > 9 ? '9+' : $cartCount }}
                    </div>
                </a>
                <a href="{{ route('agen.chat.index') }}" class="flex items-center py-3 px-4 rounded-xl {{ Route::is('agen.chat.index') ? 'bg-green-50 text-[#0f8629]' : 'text-gray-700 hover:bg-gray-50' }} font-bold relative">
                    <i class="fa-solid fa-comments mr-3 w-5 text-lg"></i> Chat
                    <div id="chat-notification-dot-mobile" class="{{ $unread_messages_count > 0 ? '' : 'hidden' }} ml-auto w-2.5 h-2.5 bg-red-500 rounded-full"></div>
                </a>
                <div class="my-2 border-t border-gray-100"></div>
                <button type="button" class="logoutMobileBtn w-full flex items-center py-4 px-4 rounded-xl hover:bg-red-50 font-bold text-red-500 transition-all text-left">
                    <i class="fa-solid fa-right-from-bracket mr-3 w-5 text-lg"></i> Logout
                </button>
            </div>
        </div>
    </div>
</nav>

@if(!$isChatRoute)
    <div class="h-20 md:h-28"></div>
@endif

<x-modal id="logoutModal" title="Konfirmasi Logout" message="Apakah Anda yakin ingin keluar?" confirmText="Iya" cancelText="Batal" confirmId="confirmLogoutBtn" cancelId="closeLogoutBtn" />

<form id="logoutFormReal" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const el = {
        hamburger: document.getElementById('hamburger'),
        mobileMenu: document.getElementById('mobileMenu'),
        dropdownBtn: document.getElementById('dropdownBtn'),
        dropdownMenu: document.getElementById('dropdownMenu'),
        dropdownArrow: document.getElementById('dropdownArrow'),
        chatDot: document.getElementById('chat-notification-dot'),
        chatDotMobile: document.getElementById('chat-notification-dot-mobile'),
        cartDot: document.getElementById('cart-notification-dot'),
        cartDotMobile: document.getElementById('cart-notification-dot-mobile')
    };

    const animateToggle = (target, show) => {
        if (show) {
            target.classList.remove('hidden');
            setTimeout(() => target.classList.remove('opacity-0', 'scale-95', '-translate-y-2'), 10);
        } else {
            target.classList.add('opacity-0', 'scale-95', '-translate-y-2');
            setTimeout(() => target.classList.add('hidden'), 200);
        }
    };

    el.dropdownBtn?.addEventListener('click', (e) => {
        e.stopPropagation();
        const isHidden = el.dropdownMenu.classList.contains('hidden');
        if (isHidden) animateToggle(el.mobileMenu, false);
        animateToggle(el.dropdownMenu, isHidden);
        if (el.dropdownArrow) el.dropdownArrow.style.transform = isHidden ? 'rotate(180deg)' : 'rotate(0deg)';
    });

    el.hamburger?.addEventListener('click', (e) => {
        e.stopPropagation();
        const isHidden = el.mobileMenu.classList.contains('hidden');
        if (isHidden) animateToggle(el.dropdownMenu, false);
        animateToggle(el.mobileMenu, isHidden);
    });

    document.addEventListener('click', (e) => {
        if (!e.target.closest('#dropdownBtn') && !e.target.closest('#dropdownMenu')) {
            animateToggle(el.dropdownMenu, false);
            if (el.dropdownArrow) el.dropdownArrow.style.transform = 'rotate(0deg)';
        }
        if (!e.target.closest('#hamburger') && !e.target.closest('#mobileMenu')) animateToggle(el.mobileMenu, false);
    });

    if (window.Echo) {
        window.Echo.private(`chat.${@js(auth()->id())}`).listen('.MessageSent', () => {
            if (!window.location.href.includes('chat')) {
                el.chatDot?.classList.remove('hidden');
                el.chatDotMobile?.classList.remove('hidden');
            }
        });
    }

    const logout = () => openModal('logoutModal');
    document.getElementById('logoutBtnTrigger')?.addEventListener('click', logout);
    document.querySelector('.logoutMobileBtn')?.addEventListener('click', logout);
    document.getElementById('confirmLogoutBtn')?.addEventListener('click', () => document.getElementById('logoutFormReal')?.submit());
    document.getElementById('closeLogoutBtn')?.addEventListener('click', () => closeModal('logoutModal'));
});

function updateCartBadge(count) {
    const dot = document.getElementById('cart-notification-dot');
    const dotMobile = document.getElementById('cart-notification-dot-mobile');
    const label = count > 9 ? '9+' : count;
    [dot, dotMobile].forEach(el => {
        if (!el) return;
        if (count > 0) {
            el.classList.remove('hidden');
            el.textContent = label;
        } else {
            el.classList.add('hidden');
            el.textContent = '';
        }
    });
}
</script>
