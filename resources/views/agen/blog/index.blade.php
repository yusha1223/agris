@extends('layouts.agen')

@section('title', 'Blog - AGRIS')

@section('content')
<div class="max-w-7xl mx-auto pt-5 pb-12 px-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4" data-aos="fade-up">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Blog Agris</h1>
            <p class="text-gray-500 text-sm">Informasi Seputar Agris</p>
        </div>
    </div>

    <div id="blog-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
        @forelse($blogs as $blog)
        <div class="group relative bg-white rounded-3xl overflow-hidden shadow-sm flex flex-col border border-gray-100 hover:shadow-lg hover:shadow-gray-200/40 transition-all duration-300" data-aos="zoom-in" data-aos-delay="{{ ($loop->iteration - 1) * 100 }}">
            <a href="{{ route('agen.blog.show', $blog->id) }}" class="absolute inset-0 z-20" aria-label="Baca {{ $blog->judulBlog }}"></a>

            <div class="relative h-44 sm:h-48 md:h-52 w-full overflow-hidden">
                @if($blog->fotoBlog)
                    <img src="{{ asset('storage/' . $blog->fotoBlog) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                @else
                    <div class="w-full h-full bg-gray-50 flex items-center justify-center text-gray-300">
                        <i class="fa-solid fa-image text-3xl"></i>
                    </div>
                @endif
                <div class="absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            </div>

            <div class="p-5 flex flex-col flex-1">
                <h3 class="text-lg font-bold text-gray-900 mb-2 leading-tight group-hover:text-[#58CC02] transition-colors duration-300 line-clamp-2">
                    {{ $blog->judulBlog }}
                </h3>

                <div class="text-gray-500 text-xs leading-relaxed line-clamp-2 mb-6">
                    {{ Str::limit(strip_tags($blog->isiBlog), 100, '...') }}
                </div>

                <div class="mt-auto pt-4 flex items-center justify-between border-t border-gray-50">
                    <div class="flex items-center gap-3">
                        <div class="h-8 w-8 overflow-hidden rounded-full border border-gray-100 bg-gray-50 shadow-sm">
                            <img src="{{ $blog->user && $blog->user->fotoProfil ? asset($blog->user->fotoProfil) : 'https://ui-avatars.com/api/?name='.urlencode(($blog->user->username ?? $blog->user->email) ?? 'Admin') }}" class="h-full w-full object-cover">
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] font-bold text-gray-900 leading-none mb-1">Tanggal Upload</p>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider">{{ $blog->tanggalBlog->format('d M Y') }}</p>
                        </div>
                    </div>

                    <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-gray-300 group-hover:bg-[#58CC02] group-hover:text-white transition-all duration-300">
                        <i class="fa-solid fa-arrow-right text-[10px] transition-transform duration-300"></i>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-16 text-center bg-gray-50 rounded-4xl border-2 border-dashed border-gray-200 px-6" data-aos="zoom-in">
            <div class="mb-3">
                <i class="fa-solid fa-box-open text-3xl text-gray-200"></i>
            </div>
            <p class="text-gray-400 text-sm font-bold">Belum ada blog yang tersedia.</p>
        </div>
        @endforelse
    </div>

    <div class="mt-10 flex justify-center">
        {{ $blogs->links() }}
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    if (window.Echo) {
        window.Echo.channel('blog-channel')
            .listen('.BlogUpdated', (e) => {
                fetch(window.location.href)
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newGrid = doc.getElementById('blog-grid');
                        const oldGrid = document.getElementById('blog-grid');
                        if (newGrid && oldGrid) {
                            oldGrid.innerHTML = newGrid.innerHTML;
                        }
                    })
                    .catch(err => console.error('Error fetching blog grid:', err));
            });
    }
});
</script>
@endsection
