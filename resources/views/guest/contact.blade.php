@extends('layouts.app')

@section('title', 'Kontak Kami - PT Surya Kencana Agrifarm')

@section('content')
<x-navbar />

<section class="relative pt-32 pb-20 px-6 overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('images/hero.svg') }}" class="w-full h-full object-cover" alt="Background">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-[2px]"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto text-center" data-aos="fade-up">
        <span class="text-[#58CC02] font-bold tracking-widest uppercase text-sm mb-4 block">
            Kontak
        </span>
        <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-6 leading-tight">
            Kontak Kami
        </h1>
        <p class="text-gray-200 max-w-2xl mx-auto text-lg leading-relaxed">
           Informasi melalui kontak kami
        </p>
    </div>
</section>

<section class="pt-20 pb-20 bg-gray-100 px-6">
    <div class="max-w-7xl mx-auto">
        <div class="grid md:grid-cols-1 gap-16 items-center">
            <div data-aos="fade-up">
                <h1 class="text-4xl font-bold text-gray-800 mb-6">Hubungi Kami</h1>
                <p class="text-gray-500 mb-10 text-lg">Ada pertanyaan atau ingin bekerja sama sebagai mitra? Tim kami siap membantu Anda dengan sepenuh hati.</p>

                <div class="space-y-6">
                    <div class="flex items-start gap-4 sm:gap-6 p-4 sm:p-6 rounded-3xl border-2 border-gray-300 hover:border-[#58CC02]/30 hover:bg-[#58CC02]/5 transition-all duration-300" data-aos="fade-up" data-aos-delay="100">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 bg-[#58CC02]/10 rounded-2xl flex items-center justify-center text-[#58CC02] shrink-0">
                            <i class="fa-solid fa-location-dot text-xl sm:text-2xl"></i>
                        </div>
                        <div class="min-w-0">
                            <h4 class="font-bold text-gray-800 text-base sm:text-lg">Kantor Pusat</h4>
                            <p class="text-gray-500 capitalize break-words">
                                @if($admin && (!empty($admin->detailAlamat) || !empty($admin->desa->namaDesa)))
                                    {{ !empty($admin->detailAlamat) ? strtolower($admin->detailAlamat) . ',' : '' }}
                                    {{ !empty($admin->desa->namaDesa) ? strtolower($admin->desa->namaDesa) . ',' : '' }}
                                    {{ !empty($admin->desa->kecamatan->namaKecamatan) ? strtolower($admin->desa->kecamatan->namaKecamatan) . ',' : '' }}
                                    {{ !empty($admin->desa->kecamatan->kabupaten->namaKabupaten) ? strtolower($admin->desa->kecamatan->kabupaten->namaKabupaten) : '' }}
                                @else
                                    Jl. Raya Pertanian No. 123, Kabupaten Jember, Jawa Timur, Indonesia.
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 sm:gap-6 p-4 sm:p-6 rounded-3xl border-2 border-gray-300 hover:border-[#58CC02]/30 hover:bg-[#58CC02]/5 transition-all duration-300" data-aos="fade-up" data-aos-delay="200">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 bg-[#58CC02]/10 rounded-2xl flex items-center justify-center text-[#58CC02] shrink-0">
                            <i class="fa-solid fa-phone text-xl sm:text-2xl"></i>
                        </div>
                        <div class="min-w-0">
                            <h4 class="font-bold text-gray-800 text-base sm:text-lg">Telepon & WhatsApp</h4>
                            <p class="text-gray-500 break-words">{{ $admin->noTelp ?? '+62 812-3456-7890' }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 sm:gap-6 p-4 sm:p-6 rounded-3xl border-2 border-gray-300 hover:border-[#58CC02]/30 hover:bg-[#58CC02]/5 transition-all duration-300" data-aos="fade-up" data-aos-delay="300">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 bg-[#58CC02]/10 rounded-2xl flex items-center justify-center text-[#58CC02] shrink-0">
                            <i class="fa-solid fa-envelope text-xl sm:text-2xl"></i>
                        </div>
                        <div class="min-w-0">
                            <h4 class="font-bold text-gray-800 text-base sm:text-lg">Email Resmi</h4>
                            <p class="text-gray-500 break-words">{{ $admin->email ?? 'agrisagroindustri@agris.co.id' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<x-footer />
@endsection
