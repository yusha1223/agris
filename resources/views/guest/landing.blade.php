@extends('layouts.app')

@section('title', 'AGRIS - PT Surya Kencana Agrifarm')

@section('content')

@php
    $services = [
        [
            'icon' => 'fa-seedling',
            'title' => 'Distribusi Benih',
            'desc' => 'Penyaluran benih padi dan jagung unggul dengan logistik terintegrasi.'
        ],
        [
            'icon' => 'fa-microscope',
            'title' => 'Uji Laboratorium',
            'desc' => 'Produk melalui uji kelayakan ketat di laboratorium bersertifikasi.'
        ],
        [
            'icon' => 'fa-handshake',
            'title' => 'Kemitraan Tani',
            'desc' => 'Kerjasama strategis untuk meningkatkan kualitas hasil panen.'
        ]
    ];

    $faqs = [
        [
            'q' => 'Apa itu AGRIS?',
            'a' => 'AGRIS adalah platform penyedia benih padi dan jagung tersertifikasi dari PT Surya Kencana Agrifarm Sejahtera.'
        ],
        [
            'q' => 'Apakah benih sudah bersertifikat?',
            'a' => 'Ya, seluruh benih yang kami distribusikan telah lolos uji BPSB dan memiliki daya tumbuh tinggi.'
        ],
        [
            'q' => 'Bagaimana cara menjadi mitra AGRIS?',
            'a' => 'Anda dapat mendaftar melalui tombol "Mulai Sekarang" atau menghubungi admin kami.'
        ],
        [
            'q' => 'Di mana lokasi gudang distribusi?',
            'a' => 'Gudang utama kami terletak di Jawa Timur, melayani pengiriman ke seluruh Indonesia.'
        ]
    ];
@endphp

<x-navbar/>

<section id="home" class="relative min-h-screen shrink-0 flex items-center overflow-hidden pt-20 md:pt-24">
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('images/about.svg') }}" class="w-full h-full object-cover" alt="Background">
        <div class="absolute inset-0 bg-black/50 bg-opacity-40 backdrop-blur-sm"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-16 w-full">
        <div class="flex flex-col md:flex-row items-center justify-between gap-12">
            <div class="max-w-2xl text-white order-2 md:order-1 text-center md:text-left" data-aos="fade-up" data-aos-delay="300">
                <h1 class="text-4xl md:text-6xl font-bold leading-tight mb-6">
                    Pusat Penyedia <br class="hidden md:block">
                    <span class="text-green-500">Suplai Benih</span> Unggul
                </h1>
                <p class="text-base md:text-lg opacity-90 mb-10 leading-relaxed max-w-xl mx-auto md:mx-0">
                    Platform digital PT Surya Kencana Agrifarm Sejahtera untuk memenuhi kebutuhan pertanian Anda dengan sistem terpercaya dan hasil panen maksimal.
                </p>
                <div class="flex flex-col sm:flex-row justify-center md:justify-start gap-4">
                    <a href="{{ route('login') }}" class="px-8 py-4 bg-green-700 text-white font-bold rounded-2xl shadow-xl hover:bg-green-600 transition duration-300 text-center">
                        Mulai Sekarang
                    </a>
                    <a href="{{ route('about') }}" class="px-8 py-4 border-2 border-white border-opacity-30 backdrop-blur-sm text-white rounded-2xl font-bold hover:bg-white/50 transition pb-10 duration-300 text-center">
                        Tentang Kami
                    </a>
                </div>
            </div>
            <div class="flex justify-center order-1 md:order-2" data-aos="flip-left" data-aos-delay="300">
                <img src="{{ asset('images/icon.svg') }}" class="w-48 md:w-80 h-auto drop-shadow-2xl" alt="Icon AGRIS">
            </div>
        </div>
    </div>
</section>

<section id="about" class="relative py-24 bg-gray-100 px-6">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center gap-12 md:gap-20">
        <div class="w-full md:w-1/2 relative">
            <div class="relative z-10">
                <img src="{{ asset('images/about.svg') }}" class="rounded-3xl shadow-2xl w-full object-cover h-87.5 md:h-125" data-aos="fade-up" >
                <div class="absolute -bottom-6 -right-6 bg-gray-100 p-6 rounded-3xl shadow-xl border-2 border-gray-200 hidden lg:block" data-aos="flip-down">
                    <p class="text-slate-900 font-extrabold text-4xl">10+</p>
                    <p class="text-gray-400 text-xs font-black uppercase tracking-widest">Terpercaya</p>
                </div>
            </div>
        </div>

        <div class="w-full md:w-1/2" data-aos="fade-up">
            <span class="text-green-600 font-bold tracking-widest uppercase text-xs mb-4 block">Tentang AGRIS</span>
            <h2 class="text-3xl md:text-5xl font-bold text-slate-900 mb-6 leading-tight">Dedikasi Kami untuk Kemajuan Petani Indonesia</h2>
            <p class="text-gray-600 leading-relaxed text-base md:text-lg mb-8">
                AGRIS lahir dari semangat untuk memberikan solusi hulu pertanian. Kami memahami bahwa benih berkualitas adalah fondasi utama keberhasilan panen.
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="flex items-start gap-3 border-2 border-gray-200 p-4 rounded-2xl bg-gray-50" data-aos="fade-up" data-aos-delay="100">
                    <div class="mt-1 shrink-0 w-5 h-5 rounded-full bg-green-500 flex items-center justify-center">
                        <i class="fa-solid fa-check text-[8px] text-white"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 text-sm md:text-base">Seleksi Benih Unggul</h4>
                        <p class="text-xs text-gray-500">Daya tumbuh di atas 95%.</p>
                    </div>
                </div>
                <div class="flex items-start gap-3 border-2 border-gray-200 p-4 rounded-2xl bg-gray-50" data-aos="fade-up" data-aos-delay="200">
                    <div class="mt-1 shrink-0 w-5 h-5 rounded-full bg-green-500 flex items-center justify-center">
                        <i class="fa-solid fa-check text-[8px] text-white"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 text-sm md:text-base">Distribusi Nasional</h4>
                        <p class="text-xs text-gray-500">Logistik menjangkau pelosok.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="services" class="relative py-24 bg-gray-200 px-6">
    <div class="max-w-7xl mx-auto w-full">
        <div class="text-center max-w-2xl mx-auto mb-16" data-aos="fade-up">
            <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">Layanan Utama Kami</h2>
            <p class="text-gray-500 text-sm md:text-base">Mendukung ekosistem pertanian secara profesional.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($services as $service)
            <div class="group bg-white p-8 md:p-10 rounded-3xl shadow-sm hover:shadow-xl transition-all duration-300 border-2 border-gray-200" data-aos="zoom-in" data-aos-delay="{{ $loop->index * 100 }}">
                <div class="w-16 h-16 bg-green-50 rounded-2xl flex items-center justify-center text-green-600 text-2xl mb-8 group-hover:bg-green-600 group-hover:text-white transition-all duration-300">
                    <i class="fa-solid {{ $service['icon'] }}"></i>
                </div>
                <h3 class="font-bold text-xl text-slate-900 mb-4">{{ $service['title'] }}</h3>
                <p class="text-gray-500 text-sm leading-relaxed">{{ $service['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section id="faq" class="relative py-24 bg-gray-100 px-6">
    <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-16 lg:gap-20 w-full">
        <div class="w-full lg:w-1/3" data-aos="fade-up">
            <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-6">FAQ</h2>
            <p class="text-gray-500 mb-8 text-sm md:text-base">Kami merangkum hal yang sering ditanyakan oleh mitra kami.</p>
            <div class="p-6 sm:p-8 bg-slate-900 rounded-3xl text-white w-full max-w-md mx-auto lg:mx-0">
                <p class="font-bold mb-2 text-green-400">Butuh bantuan lain?</p>
                <p class="text-xs opacity-70 mb-5">Tim support kami siap membantu anda.</p>
            </div>
        </div>

        <div class="w-full lg:w-2/3 space-y-4" data-aos="fade-up">
            @foreach($faqs as $faq)
            <div class="faq-item group">
                <button class="faq-btn w-full flex items-center justify-between p-6 bg-gray-50 rounded-2xl border border-gray-200 hover:border-green-500 hover:border-opacity-30 transition-all duration-300 text-left">
                    <span class="font-bold text-slate-900 text-sm md:text-base">{{ $faq['q'] }}</span>
                    <div class="shrink-0 w-10 h-10 rounded-xl bg-white flex items-center justify-center text-slate-900 group-hover:bg-green-600 group-hover:text-white transition-all">
                        <i class="fa-solid fa-chevron-down text-xs transition-transform duration-300"></i>
                    </div>
                </button>
                <div class="faq-content hidden px-7 py-5 text-gray-500 text-sm leading-relaxed bg-gray-50 bg-opacity-50 rounded-b-2xl border-x border-b border-gray-100">
                    {{ $faq['a'] }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<x-footer/>

@endsection

@push('scripts')
<script>
    document.querySelectorAll(".faq-btn").forEach(btn => {
        btn.addEventListener("click", () => {
            const content = btn.nextElementSibling;
            const icon = btn.querySelector('.fa-chevron-down');

            const isHidden = content.classList.toggle("hidden");

            if(!isHidden) {
                icon.style.transform = "rotate(180deg)";
                btn.classList.add('shadow-lg', 'bg-white');
            } else {
                icon.style.transform = "rotate(0deg)";
                btn.classList.remove('shadow-lg', 'bg-white');
            }
        });
    });
</script>
@endpush
