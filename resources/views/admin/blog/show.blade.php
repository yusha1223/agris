@extends('layouts.admin')

@section('title', 'Detail Blog - AGRIS')

@section('content')
<div class="max-w-4xl mx-auto pt-4 pb-12">
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4" data-aos="fade-up">
        <div class="flex items-center">
            <a href="{{ route('admin.blog.index') }}" class="w-10 h-10 rounded-full border border-gray-200 flex items-center justify-center text-gray-400 bg-white shadow-sm transition-all shrink-0">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </a>
            <span class="text-xl md:text-2xl pl-3 font-bold text-gray-800">Detail Blog</span>
        </div>

        <div class="flex gap-2 w-full sm:w-auto justify-end">
            <a href="{{ route('admin.blog.edit', $blog->id) }}" class="flex-1 sm:flex-none justify-center bg-blue-500 text-white px-4 py-2.5 rounded-xl font-bold flex items-center gap-2 hover:bg-blue-600 transition-all text-xs md:text-sm">
               <i class="fa-solid fa-pen-to-square text-xs"></i> Edit
            </a>
            <button type="button" onclick="openModal('modalHapus')" class="flex-1 sm:flex-none justify-center bg-red-500 text-white px-4 py-2.5 rounded-xl font-bold flex items-center gap-2 hover:bg-red-600 transition-all text-xs md:text-sm">
                <i class="fa-solid fa-trash text-xs"></i> Hapus
            </button>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden" data-aos="fade-up" data-aos-delay="100">
        @if($blog->fotoBlog)
            <div class="w-full aspect-video max-h-100 overflow-hidden bg-gray-50" data-aos="fade-down" data-aos-delay="200">
                <img src="{{ storage_url($blog->fotoBlog) }}" class="w-full h-full object-cover">
            </div>
        @endif

        <div class="p-6 md:p-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="h-8 w-8 overflow-hidden rounded-full border-2 border-white shadow-sm bg-gray-100 shrink-0">
                    <img src="{{ $blog->user && $blog->user->fotoProfil ? storage_url($blog->user->fotoProfil) : 'https://ui-avatars.com/api/?name='.urlencode(($blog->user->username ?? $blog->user->email) ?? 'Admin') }}" class="h-full w-full object-cover">
                </div>
                <div class="min-w-0">
                    <p class="text-[9px] font-black uppercase tracking-widest text-gray-400 mb-0.5">Tanggal Upload</p>
                    <p class="text-xs font-bold text-gray-700 truncate">{{ $blog->tanggalBlog->format('d M Y') }}</p>
                </div>
            </div>

            <h1 class="text-xl md:text-2xl font-bold text-gray-800 leading-snug mb-6">{{ $blog->judulBlog }}</h1>

            <div class="prose prose-sm md:prose-base max-w-none text-gray-600 leading-relaxed">
                @php
                    $urlPattern = '/(https?:\/\/[^\s]+)/';
                    $contentWithLinks = preg_replace(
                        $urlPattern,
                        '<a href="$1" target="_blank" class="text-blue-500 hover:underline font-bold transition-all">$1</a>',
                        e($blog->isiBlog)
                    );
                @endphp
                {!! nl2br($contentWithLinks) !!}
            </div>
        </div>
    </div>
</div>

<x-modal id="modalHapus" title="Hapus Blog?" message="Yakin ingin menghapus blog?" confirmText="Iya" cancelText="Batal" confirmId="btnConfirmDelete" cancelId="btnCancelDelete" />

<form id="delete-form" action="{{ route('admin.blog.destroy', $blog->id) }}" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

<script>
    document.getElementById('btnConfirmDelete').addEventListener('click', function() {
        this.disabled = true;
        document.getElementById('delete-form').submit();
    });

    document.getElementById('btnCancelDelete').addEventListener('click', function() {
        closeModal('modalHapus');
    });
</script>
@endsection
