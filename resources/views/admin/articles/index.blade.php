@extends('layouts.admin')

@section('title', 'Artikel | Admin Bumi Mandiri')
@section('page_title', 'Artikel')

@section('content')
<x-admin.page-header
    eyebrow="Konten"
    title="Artikel"
    description="Tulis panduan, cerita, dan informasi yang membantu pengunjung merencanakan kunjungan.">
    <a href="{{ route('admin.articles.create') }}" class="btn btn-primary btn-sm">
        <x-icon name="plus" :size="15" />
        Tulis artikel
    </a>
</x-admin.page-header>

<div class="admin-card overflow-hidden">
    @if($articles->isEmpty())
        <x-empty-state icon="article" title="Belum ada artikel" description="Artikel pertama bisa menjadi panduan awal untuk calon pengunjung." class="py-16">
            <a href="{{ route('admin.articles.create') }}" class="btn btn-primary btn-sm">Tulis artikel</a>
        </x-empty-state>
    @else
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Artikel</th>
                        <th>Penulis</th>
                        <th>Status</th>
                        <th>Dibuat</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($articles as $article)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <img src="{{ $article->imageUrl() }}" alt=""
                                         class="h-11 w-16 shrink-0 rounded-lg object-cover" width="128" height="88" loading="lazy">
                                    <div class="min-w-0">
                                        <p class="max-w-[340px] truncate font-semibold text-charcoal/85">{{ $article->title }}</p>
                                        <p class="mt-0.5 text-xs text-charcoal/70">{{ $article->reading_time }} menit baca</p>
                                    </div>
                                </div>
                            </td>
                            <td class="whitespace-nowrap text-charcoal/70">{{ $article->author?->name ?? 'Tanpa penulis' }}</td>
                            <td><x-status-badge :status="$article->is_published ? 'published' : 'draft'" /></td>
                            <td class="whitespace-nowrap text-charcoal/70">{{ $article->created_at->translatedFormat('d M Y') }}</td>
                            <td>
                                <div class="flex items-center justify-end gap-1">
                                    @if($article->is_published)
                                        <a href="{{ route('articles.show', $article) }}" target="_blank" rel="noopener"
                                           class="admin-action text-charcoal/70 hover:bg-charcoal/5" title="Lihat di situs">
                                            <x-icon name="external-link" :size="14" />
                                            Lihat
                                        </a>
                                    @endif
                                    <a href="{{ route('admin.articles.edit', $article) }}" class="admin-action admin-action-edit">
                                        <x-icon name="edit" :size="14" />
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.articles.destroy', $article) }}" method="POST" class="inline" onsubmit="return confirm('Hapus artikel ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="admin-action admin-action-delete">
                                            <x-icon name="trash" :size="14" />
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@if($articles->hasPages())
    <div class="pagination-wrap mt-5">{{ $articles->links() }}</div>
@endif
@endsection
