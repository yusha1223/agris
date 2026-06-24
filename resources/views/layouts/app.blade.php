<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AGRIS - Platform Agroindustri Terpercaya')</title>
    <meta name="description" content="@yield('meta_description', 'AGRIS adalah platform agroindustri modern yang menghubungkan produsen pertanian dengan konsumen secara langsung untuk mewujudkan keadilan pangan dan kemakmuran bersama.')">
    <meta name="keywords" content="@yield('meta_keywords', 'agris, agroindustri, pertanian, pasar tani, hasil bumi, pertanian digital, petani indonesia, beli hasil tani')">
    <meta name="author" content="AGRIS">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="@yield('canonical_url', request()->url())">
    <meta name="google-site-verification" content="googleab47259f04d99b96" />

    <meta property="og:type" content="website">
    <meta property="og:url" content="@yield('canonical_url', request()->url())">
    <meta property="og:title" content="@yield('title', 'AGRIS - Platform Agroindustri Terpercaya')">
    <meta property="og:description" content="@yield('meta_description', 'AGRIS adalah platform agroindustri modern yang menghubungkan produsen pertanian dengan konsumen secara langsung untuk mewujudkan keadilan pangan dan kemakmuran bersama.')">
    <meta property="og:image" content="@yield('meta_image', asset('images/icon.svg'))">
    <meta property="og:site_name" content="AGRIS">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="@yield('canonical_url', request()->url())">
    <meta property="twitter:title" content="@yield('title', 'AGRIS - Platform Agroindustri Terpercaya')">
    <meta property="twitter:description" content="@yield('meta_description', 'AGRIS adalah platform agroindustri modern yang menghubungkan produsen pertanian dengan konsumen secara langsung untuk mewujudkan keadilan pangan dan kemakmuran bersama.')">
    <meta property="twitter:image" content="@yield('meta_image', asset('images/icon.svg'))">

    <link rel="icon" type="image/png" href="{{ asset('images/icon.svg') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- JSON-LD Structured Data for SEO -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebSite",
      "name": "AGRIS",
      "url": "{{ url('/') }}",
      "potentialAction": {
        "@type": "SearchAction",
        "target": "{{ url('/blog') }}?search={search_term_string}",
        "query-input": "required name=search_term_string"
      }
    }
    </script>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "AGRIS",
      "url": "{{ url('/') }}",
      "logo": "{{ asset('images/icon.svg') }}",
      "contactPoint": {
        "@type": "ContactPoint",
        "telephone": "+628123456789",
        "contactType": "customer service",
        "areaServed": "ID",
        "availableLanguage": "Indonesian"
      }
    }
    </script>
</head>
<body class="bg-gray-700 text-gray-800 scroll-smooth font-sans antialiased">

<div id="progressBarContainer" class="hidden fixed top-0 left-0 w-full h-1 bg-gray-200 z-110">
    <div id="progressBar" class="h-full bg-[#58CC02] w-0 transition-all duration-300 ease-linear"></div>
</div>

<div class="fixed top-5 right-5 z-100 space-y-3">
    @if(session('success'))
        <div class="alert-info flex items-center w-full max-w-xs p-4 rounded-2xl shadow-xl border border-green-200 bg-green-50" role="alert">
            <div class="inline-flex items-center justify-center shrink-0 w-10 h-10 rounded-full bg-green-600 text-white">
                <i class="fa-solid fa-check text-sm"></i>
            </div>
            <div class="ms-3">
                <div class="text-sm font-bold text-green-800">Berhasil</div>
                <div class="text-xs text-green-700 mt-0.5">{{ session('success') }}</div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="alert-info flex items-center w-full max-w-xs p-4 rounded-2xl shadow-xl border border-red-200 bg-red-50" role="alert">
            <div class="inline-flex items-center justify-center shrink-0 w-10 h-10 rounded-full bg-red-600 text-white">
                <i class="fa-solid fa-xmark text-sm"></i>
            </div>
            <div class="ms-3">
                <div class="text-sm font-bold text-red-800">Gagal</div>
                <div class="text-xs text-red-700 mt-0.5">{{ session('error') }}</div>
            </div>
        </div>
    @endif
</div>

<div>
@yield('content')
</div>

@stack('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {
    const forms = document.querySelectorAll('form');
    const progressContainer = document.getElementById('progressBarContainer');
    const progressBar = document.getElementById('progressBar');

    forms.forEach(form => {
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
            }, 200);
        });
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
