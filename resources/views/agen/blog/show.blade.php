@extends('layouts.agen')

@section('title', 'Detail Blog - AGRIS')

@section('content')
<div class="max-w-5xl mx-auto pt-3 md:pt-5 pb-12 px-1">
    <div class="mb-6 flex items-center gap-4" data-aos="fade-up">
        <a href="{{ route('agen.blog.index') }}" class="w-10 h-10 rounded-full border border-gray-200 flex items-center justify-center text-gray-400 bg-white shadow-sm transition-all">
            <i class="fa-solid fa-arrow-left text-sm"></i>
        </a>
        <h2 class="text-xl font-bold text-gray-800">Detail Blog</h2>
    </div>

    <div class="bg-white rounded-4xl border border-gray-100 shadow-sm overflow-hidden" data-aos="fade-up" data-aos-delay="100">
        @if($blog->fotoBlog)
            <div class="w-full h-64 md:h-100 overflow-hidden" data-aos="fade-down" data-aos-delay="200">
                <img src="{{ storage_url($blog->fotoBlog) }}" class="w-full h-full object-cover">
            </div>
        @endif

        <div class="p-6 md:p-10 lg:p-12">
            <div class="flex items-center gap-3 mb-8">
                <div class="w-10 h-10 rounded-full border border-gray-100 overflow-hidden shadow-sm">
                    <img src="{{ $blog->user && $blog->user->fotoProfil ? storage_url($blog->user->fotoProfil) : 'https://ui-avatars.com/api/?name='.urlencode(($blog->user->username ?? $blog->user->email) ?? 'Admin') }}" class="h-full w-full object-cover rounded-full">
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Tanggal Upload</p>
                    <p class="text-sm font-bold text-gray-900 uppercase tracking-tight">{{ $blog->tanggalBlog->format('d F Y') }}</p>
                </div>
            </div>

            <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold text-gray-900 leading-tight mb-8">{{ $blog->judulBlog }}</h1>

            <div class="prose prose-sm md:prose-base prose-green max-w-none text-gray-600 leading-relaxed text-justify hyphens-auto" style="text-align: justify;">
                @php
                    $urlPattern = '/(https?:\/\/[^\s]+)/';
                    $contentWithLinks = preg_replace(
                        $urlPattern,
                        '<a href="$1" target="_blank" class="text-[#58CC02] hover:underline font-bold transition-all">$1</a>',
                        e($blog->isiBlog)
                    );
                @endphp
                {!! nl2br($contentWithLinks) !!}
            </div>
        </div>
    </div>
</div>
@endsection
