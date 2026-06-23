@extends('layouts.app')

@section('title', $blog->judulBlog . ' - AGRIS')

@section('content')
<x-navbar/>

<section class="relative pt-25 pb-24 bg-white px-6">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center gap-2 mb-5" data-aos="fade-up">
            <div class="flex justify-between">
                <a href="{{ route('guest.blog.index') }}" class="w-12 h-12 rounded-full border border-gray-200 flex items-center justify-center text-gray-400 hover:text-[#58CC02] bg-white shadow-sm transition-all">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
            </div>
            <span class="font-bold text-2xl items-center pl-3">Detail Blog</span>
        </div>

        <div class="bg-white rounded-[48px] border border-gray-100 shadow-sm overflow-hidden" data-aos="fade-up" data-aos-delay="100">
            @if($blog->fotoBlog)
                <div class="w-full aspect-video max-h-112.5 overflow-hidden bg-gray-50">
                    <img src="{{ storage_url($blog->fotoBlog) }}" class="w-full h-full object-cover">
                </div>
            @endif

            <div class="p-12">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-12 h-12 rounded-full border border-gray-100 bg-gray-50 shadow-sm overflow-hidden shrink-0">
                        <img src="{{ $blog->user && $blog->user->fotoProfil ? storage_url($blog->user->fotoProfil) : 'https://ui-avatars.com/api/?name='.urlencode(($blog->user->username ?? $blog->user->email) ?? 'Admin') }}" class="h-full w-full object-cover rounded-full">
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900">{{ $blog->user->name ?? $blog->user->username ?? 'Admin' }}</h4>
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">{{ $blog->tanggalBlog->format('d F Y') }}</p>
                    </div>
                </div>

                <h1 class="text-4xl font-bold text-gray-900 leading-tight mb-8">{{ $blog->judulBlog }}</h1>

                <div class="prose prose-lg prose-green max-w-none text-gray-600 leading-relaxed text-justify hyphens-auto" style="text-align: justify;">
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
</section>

<x-footer/>
@endsection
