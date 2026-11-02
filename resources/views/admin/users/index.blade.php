@extends('layouts.admin')

@section('title', 'Pengguna | Admin Bumi Mandiri')
@section('page_title', 'Pengguna')

@section('content')
<x-admin.page-header
    eyebrow="Sistem"
    title="Pengguna"
    description="Kelola akun admin dan pelanggan yang terdaftar di Bumi Mandiri.">
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
        <x-icon name="plus" :size="15" />
        Tambah pengguna
    </a>
</x-admin.page-header>

<div class="admin-card overflow-hidden">
    @if($users->isEmpty())
        <x-empty-state icon="users" title="Belum ada pengguna" description="Tambahkan akun pertama untuk mulai mengelola akses." class="py-16">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">Tambah pengguna</a>
        </x-empty-state>
    @else
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Pengguna</th>
                        <th>Kontak</th>
                        <th>Peran</th>
                        <th>Terdaftar</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <x-admin.avatar :name="$user->name" />
                                    <div class="min-w-0">
                                        <p class="truncate font-semibold text-charcoal/85">{{ $user->name }}</p>
                                        <p class="truncate text-xs text-charcoal/70">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="whitespace-nowrap text-charcoal/70">{{ $user->phone ?: 'Belum diisi' }}</td>
                            <td>
                                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-[11px] font-semibold ring-1 ring-inset {{ $user->role?->name === 'admin' ? 'bg-forest/10 text-forest ring-forest/20' : 'bg-sand-light text-charcoal/70 ring-charcoal/10' }}">
                                    {{ $user->role?->name === 'admin' ? 'Admin' : 'Pelanggan' }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap text-charcoal/70">{{ $user->created_at->translatedFormat('d M Y') }}</td>
                            <td>
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="admin-action admin-action-edit" title="Edit pengguna">
                                        <x-icon name="edit" :size="14" />
                                        Edit
                                    </a>
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Hapus pengguna ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="admin-action admin-action-delete" title="Hapus pengguna">
                                                <x-icon name="trash" :size="14" />
                                                Hapus
                                            </button>
                                        </form>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1.5 text-xs text-charcoal/70" title="Akun yang sedang digunakan">Saat ini</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@if($users->hasPages())
    <div class="pagination-wrap mt-5">{{ $users->links() }}</div>
@endif
@endsection
