@extends('layouts.app')

@section('title', 'Role & Permission — '.config('app.name'))

@section('content')
    @php
        $roleLabels = [
            'admin' => 'Admin',
            'pic' => 'PIC',
            'operator_opd' => 'Operator OPD',
        ];
        $permissionGroups = $permissions->groupBy(
            fn ($permission): string => str($permission->name)->before('.')->toString()
        );
    @endphp

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xs font-medium text-slate-500">Administrasi</p>
            <h1 class="mt-1 text-2xl font-semibold tracking-tight text-slate-900">Role & Permission</h1>
            <p class="mt-1 text-sm text-slate-500">Atur hak akses setiap role sesuai tugasnya.</p>
        </div>
    </div>

    @include('partials.alerts')

    <form method="POST" action="{{ route('admin.roles.update') }}" class="mt-6">
        @csrf
        @method('PUT')

        <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-[11px] font-semibold uppercase tracking-[0.12em] text-slate-500">
                        <tr>
                            <th scope="col" class="sticky left-0 z-10 min-w-64 bg-slate-50 px-5 py-3">Permission</th>
                            @foreach ($roles as $role)
                                <th scope="col" class="min-w-36 px-4 py-3 text-center">
                                    {{ $roleLabels[$role->name] ?? $role->name }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($permissionGroups as $group => $groupPermissions)
                            <tr class="bg-slate-50/80">
                                <th
                                    scope="colgroup"
                                    colspan="{{ $roles->count() + 1 }}"
                                    class="sticky left-0 px-5 py-2 text-left text-[11px] font-bold uppercase tracking-[0.14em] text-slate-500"
                                >
                                    {{ str($group)->headline() }}
                                </th>
                            </tr>

                            @foreach ($groupPermissions as $permission)
                                <tr class="transition hover:bg-slate-50/70">
                                    <td class="sticky left-0 z-10 bg-white px-5 py-3 font-medium text-slate-700">
                                        {{ $permission->name }}
                                    </td>
                                    @foreach ($roles as $role)
                                        <td class="px-4 py-3 text-center">
                                            <input
                                                type="checkbox"
                                                name="permissions[{{ $role->name }}][]"
                                                value="{{ $permission->name }}"
                                                @checked($role->permissions->contains('name', $permission->name))
                                                aria-label="{{ $permission->name }} untuk {{ $roleLabels[$role->name] ?? $role->name }}"
                                                class="h-4 w-4 rounded-xs border border-default-medium bg-neutral-secondary-medium text-emerald-700 focus:ring-2 focus:ring-brand-soft"
                                            >
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="{{ $roles->count() + 1 }}" class="px-5 py-12 text-center text-sm text-slate-500">
                                    Belum ada permission. Jalankan seeder role dan permission terlebih dahulu.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-5 flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-xs text-slate-500">
                Perubahan berlaku untuk semua pengguna dengan role terkait.
            </p>
            <button
                type="submit"
                class="inline-flex items-center justify-center gap-2 rounded-base bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800 focus:ring-4 focus:ring-emerald-200"
            >
                <x-icon name="check" :size="17" />
                Simpan permission
            </button>
        </div>
    </form>
@endsection
