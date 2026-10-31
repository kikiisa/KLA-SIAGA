@php
    $navItems = [
        ['icon' => 'grid', 'label' => 'Dashboard', 'route' => 'dashboard', 'active' => 'dashboard'],
        ['icon' => 'calendar', 'label' => 'Periode Evaluasi', 'route' => 'admin.periods.index', 'active' => 'admin.periods.*', 'permission' => 'period.view'],
        ['icon' => 'building', 'label' => 'Manajemen OPD', 'route' => 'admin.opds.index', 'active' => 'admin.opds.*', 'permission' => 'opd.view'],
        ['icon' => 'document', 'label' => 'Indikator & Dokumen'],
        ['icon' => 'shield', 'label' => 'Verifikasi', 'badge' => '12'],
        ['icon' => 'chart', 'label' => 'Laporan & Progres'],
        ['icon' => 'users', 'label' => 'Manajemen Pengguna', 'route' => 'admin.users.index', 'active' => 'admin.users.*', 'permission' => 'user.view'],
        ['icon' => 'lock', 'label' => 'Role & Permission', 'route' => 'admin.roles.index', 'active' => 'admin.roles.*', 'permission' => 'role.view'],
    ];

    $can = function (string $permission): bool {
        if (! auth()->check()) {
            return false;
        }

        try {
            return auth()->user()->can($permission);
        } catch (\Throwable) {
            return false;
        }
    };
@endphp

<aside
    data-sidebar
    class="fixed inset-y-0 left-0 z-50 flex w-72 -translate-x-full flex-col bg-emerald-950 text-emerald-100 transition-transform duration-300 lg:sticky lg:top-0 lg:h-screen lg:translate-x-0"
>
    <div class="flex items-center justify-between px-5 py-5">
        <x-logo compact dark />
        <button
            type="button"
            data-sidebar-close
            aria-label="Tutup menu"
            class="grid h-9 w-9 place-items-center rounded-lg text-xl text-emerald-100/70 transition hover:bg-white/10 hover:text-white lg:hidden"
        >
            &times;
        </button>
    </div>

    <p class="px-6 pb-2 pt-2 text-[10px] font-semibold uppercase tracking-[0.2em] text-emerald-100/40">
        Menu administrasi
    </p>

    <div class="relative min-h-0 flex-1">
        <div class="pointer-events-none absolute inset-x-0 top-0 z-10 h-5 bg-gradient-to-b from-emerald-950 to-transparent"></div>

        <nav class="sidebar-scroll h-full space-y-1 overflow-y-auto overscroll-contain px-3 py-1">
            @foreach ($navItems as $item)
                @php
                    $isVisible = ! isset($item['permission']) || $can($item['permission']);
                    $isActive = isset($item['active']) && request()->routeIs($item['active']);
                @endphp

                @if ($isVisible)
                    <a
                        href="{{ isset($item['route']) ? route($item['route']) : '#' }}"
                        @class([
                            'group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition',
                            'bg-white/10 text-white shadow-sm ring-1 ring-inset ring-white/10' => $isActive,
                            'text-emerald-100/65 hover:bg-white/5 hover:text-white' => ! $isActive,
                        ])
                    >
                        <span @class([
                            'grid h-8 w-8 shrink-0 place-items-center rounded-lg transition',
                            'bg-emerald-400/15 text-emerald-300' => $isActive,
                            'text-emerald-100/60 group-hover:text-emerald-200' => ! $isActive,
                        ])>
                            <x-icon :name="$item['icon']" :size="18" />
                        </span>
                        <span class="flex-1">{{ $item['label'] }}</span>
                        @isset($item['badge'])
                            <span class="rounded-full bg-emerald-400/15 px-2 py-0.5 text-[11px] font-semibold text-emerald-200">
                                {{ $item['badge'] }}
                            </span>
                        @endisset
                    </a>
                @endif
            @endforeach
        </nav>

        <div class="pointer-events-none absolute inset-x-0 bottom-0 z-10 h-5 bg-gradient-to-t from-emerald-950 to-transparent"></div>
    </div>

    <div class="space-y-3 px-3 pb-4 pt-4">
        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
            <span class="grid h-9 w-9 place-items-center rounded-xl bg-emerald-400/15 text-emerald-300">
                <x-icon name="help" :size="18" />
            </span>
            <strong class="mt-3 block text-sm font-semibold text-white">Butuh bantuan?</strong>
            <p class="mt-1 text-xs leading-relaxed text-emerald-100/55">
                Pelajari panduan penggunaan SIAGA KLA.
            </p>
            <a href="mailto:admin@siagakla.go.id" class="mt-3 inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-300 transition hover:text-emerald-200">
                Pusat Bantuan
                <x-icon name="arrow" :size="14" />
            </a>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button
                type="submit"
                class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-emerald-100/60 transition hover:bg-white/5 hover:text-white"
            >
                <x-icon name="logout" :size="18" />
                Keluar
            </button>
        </form>
    </div>
</aside>
