@extends('layouts.app')

@section('title', 'Manajemen OPD — '.config('app.name'))

@section('content')
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xs font-medium text-slate-500">Administrasi</p>
            <h1 class="mt-1 text-2xl font-semibold tracking-tight text-slate-900">Manajemen OPD</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola master data OPD yang tersedia pada akun pengguna.</p>
        </div>

        @can('opd.create')
            <a
                href="{{ route('admin.opds.create') }}"
                class="inline-flex items-center justify-center gap-2 rounded-base bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800 focus:ring-4 focus:ring-emerald-200"
            >
                <x-icon name="building" :size="17" />
                Tambah OPD
            </a>
        @endcan
    </div>

    @include('partials.alerts')

    <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-left text-[11px] font-semibold uppercase tracking-[0.12em] text-slate-500">
                    <tr>
                        <th scope="col" class="px-5 py-3">OPD</th>
                        <th scope="col" class="px-5 py-3">Kode</th>
                        <th scope="col" class="px-5 py-3">Kontak</th>
                        <th scope="col" class="px-5 py-3">Pengguna</th>
                        <th scope="col" class="px-5 py-3">Status</th>
                        <th scope="col" class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($opds as $opd)
                        <tr class="transition hover:bg-slate-50/70">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-emerald-100 text-emerald-700">
                                        <x-icon name="building" :size="17" />
                                    </span>
                                    <div class="min-w-0">
                                        <p class="truncate font-semibold text-slate-800">{{ $opd->nama_opd }}</p>
                                        <p class="truncate text-xs text-slate-500">{{ $opd->singkatan ?? 'Tanpa singkatan' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 font-mono text-xs text-slate-600">{{ $opd->kode_opd }}</td>
                            <td class="px-5 py-4 text-slate-600">
                                <p>{{ $opd->email ?? '—' }}</p>
                                <p class="text-xs text-slate-500">{{ $opd->telepon ?? '—' }}</p>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-sky-50 px-2.5 py-1 text-[11px] font-semibold text-sky-700 ring-1 ring-inset ring-sky-100">
                                    <x-icon name="users" :size="13" />
                                    {{ $opd->users_count }} pengguna
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <span @class([
                                    'inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[11px] font-semibold ring-1 ring-inset',
                                    'bg-emerald-50 text-emerald-700 ring-emerald-100' => $opd->is_active,
                                    'bg-slate-100 text-slate-600 ring-slate-200' => ! $opd->is_active,
                                ])>
                                    <span @class([
                                        'h-1.5 w-1.5 rounded-full',
                                        'bg-emerald-500' => $opd->is_active,
                                        'bg-slate-400' => ! $opd->is_active,
                                    ])></span>
                                    {{ $opd->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    @can('opd.update')
                                        <a
                                            href="{{ route('admin.opds.edit', $opd) }}"
                                            class="rounded-lg px-2.5 py-1.5 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-50"
                                        >
                                            Edit
                                        </a>
                                    @endcan

                                    @can('opd.delete')
                                        <form
                                            method="POST"
                                            action="{{ route('admin.opds.destroy', $opd) }}"
                                            onsubmit="return confirm('Hapus OPD ini?')"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="rounded-lg px-2.5 py-1.5 text-xs font-semibold text-rose-600 transition hover:bg-rose-50"
                                            >
                                                Hapus
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center">
                                <span class="mx-auto grid h-12 w-12 place-items-center rounded-2xl bg-slate-100 text-slate-400">
                                    <x-icon name="building" :size="22" />
                                </span>
                                <p class="mt-3 text-sm font-semibold text-slate-700">Belum ada OPD</p>
                                <p class="mt-1 text-xs text-slate-500">Tambahkan OPD pertama agar dapat dipilih pada akun pengguna.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($opds->hasPages())
        <div class="mt-5">
            {{ $opds->withQueryString()->links() }}
        </div>
    @endif
@endsection
