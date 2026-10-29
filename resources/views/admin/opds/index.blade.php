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

    <div class="mt-6 rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:p-5">
        <div class="datatable-scroll overflow-x-auto">
            <table
                class="min-w-full divide-y divide-slate-200 text-sm"
                data-datatable
                data-url="{{ route('admin.opds.data') }}"
                data-order-column="0"
                data-order-direction="asc"
            >
                <thead class="bg-slate-50 text-left text-[11px] font-semibold uppercase tracking-[0.12em] text-slate-500">
                    <tr>
                        <th scope="col" data-column="nama_opd" class="px-5 py-3">OPD</th>
                        <th scope="col" data-column="kode_opd" class="px-5 py-3">Kode</th>
                        <th scope="col" data-column="kontak" data-orderable="false" class="px-5 py-3">Kontak</th>
                        <th scope="col" data-column="users_count" data-searchable="false" class="px-5 py-3">Pengguna</th>
                        <th scope="col" data-column="status_label" data-orderable="false" class="px-5 py-3">Status</th>
                        <th scope="col" data-column="action" data-orderable="false" data-searchable="false" data-class-name="text-right" class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
@endsection
