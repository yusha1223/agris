@extends('layouts.admin')

@section('title', 'Blog Admin - AGRIS')

@section('content')
<div class="w-full pt-2 pb-10">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4" data-aos="fade-up">
        <div>
            <h1 class="text-xl md:text-2xl font-bold text-gray-800">Manajemen Blog</h1>
            <p class="text-gray-500 text-sm">Buat dan bagikan cerita Anda</p>
        </div>
        <a href="{{ route('admin.blog.create') }}" class="w-full md:w-auto bg-[#58CC02] hover:bg-[#46A302] text-white px-5 md:px-6 py-2.5 md:py-3 rounded-2xl font-bold transition-all flex items-center justify-center gap-2 shadow-sm text-xs md:text-sm">
            <i class="fa-solid fa-plus"></i> Buat Blog
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($blogs as $blog)
        <div class="group bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 flex flex-col h-full" data-aos="zoom-in" data-aos-delay="{{ ($loop->iteration - 1) * 100 }}">
            <div class="relative aspect-video w-full overflow-hidden bg-gray-50 flex items-center justify-center">
                @if($blog->fotoBlog)
                    <img src="{{ storage_url($blog->fotoBlog) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="{{ $blog->judulBlog }}">
                @else
                    <div class="flex items-center justify-center h-full text-gray-200">
                        <i class="fa-solid fa-image text-4xl"></i>
                    </div>
                @endif
                <a href="{{ route('admin.blog.show', $blog->id) }}" class="absolute inset-0 z-10"></a>
            </div>

            <div class="p-5 flex flex-col grow">
                <a href="{{ route('admin.blog.show', $blog->id) }}">
                    <h3 class="text-base md:text-lg font-bold text-gray-800 mb-2 line-clamp-2 leading-snug hover:text-[#58CC02] transition-colors">
                        {{ $blog->judulBlog }}
                    </h3>
                </a>

                <div class="text-gray-500 text-xs leading-relaxed line-clamp-2 mb-6 grow">
                    {{ strip_tags($blog->isiBlog) }}
                </div>

                <div class="pt-4 flex items-center justify-between border-t border-gray-50 mt-auto">
                    <div class="flex items-center gap-2">
                        <div class="h-8 w-8 overflow-hidden rounded-full border-2 border-white shadow-sm bg-gray-100 shrink-0">
                            <img src="{{ $blog->user && $blog->user->fotoProfil ? storage_url($blog->user->fotoProfil) : 'https://ui-avatars.com/api/?name='.urlencode(($blog->user->username ?? $blog->user->email) ?? 'Admin') }}" class="h-full w-full object-cover">
                        </div>
                        <div class="min-w-0">
                            <p class="text-[9px] font-black uppercase tracking-widest text-gray-400 mb-0.5">Tanggal</p>
                            <p class="text-xs font-bold text-gray-770 truncate">{{ $blog->tanggalBlog->format('d M Y') }}</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.blog.show', $blog->id) }}" class="w-8 h-8 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-[#58CC02] group-hover:text-white transition-all duration-300">
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-16 text-center bg-gray-50 rounded-4xl border-2 border-dashed border-gray-200">
            <div class="mb-3">
                <i class="fa-solid fa-box-open text-4xl text-gray-200"></i>
            </div>
            <p class="text-gray-400 text-sm font-bold">Belum ada Blog yang dibuat.</p>
        </div>
        @endforelse
    </div>

    <div class="mt-10">
        {{ $blogs->links() }}
    </div>
</div>
@endsection
