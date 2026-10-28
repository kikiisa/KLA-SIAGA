@php
    $user = auth()->user();
    $name = $user?->name ?? 'Andi Kurniawan';
    $initials = collect(explode(' ', $name))
        ->filter()
        ->take(2)
        ->map(fn (string $part): string => mb_substr($part, 0, 1))
        ->implode('');
@endphp

<header class="sticky top-0 z-30 border-b border-slate-200/80 bg-white/90 backdrop-blur">
    <div class="flex h-16 items-center gap-3 px-4 sm:px-6 lg:px-8">
        <button
            type="button"
            data-sidebar-open
            aria-label="Buka menu"
            class="grid h-10 w-10 shrink-0 place-items-center rounded-xl text-slate-500 transition hover:bg-slate-100 hover:text-slate-800 lg:hidden"
        >
            <x-icon name="menu" :size="20" />
        </button>

        <div class="relative hidden max-w-md flex-1 sm:block">
            <span class="pointer-events-none absolute inset-y-0 left-0 grid w-11 place-items-center text-body">
                <x-icon name="search" :size="18" />
            </span>
            <input
                type="search"
                placeholder="Cari indikator, OPD, atau dokumen..."
                class="block w-full rounded-base border border-default-medium bg-neutral-secondary-medium py-2.5 pl-11 pr-3 text-sm text-heading shadow-xs transition placeholder:text-body focus:border-brand focus:ring-2 focus:ring-brand-soft focus:outline-none"
            >
        </div>

        <div class="ml-auto flex items-center gap-2 sm:gap-3">
            <button
                type="button"
                aria-label="Notifikasi"
                class="relative grid h-10 w-10 place-items-center rounded-xl text-slate-500 transition hover:bg-slate-100 hover:text-slate-800"
            >
                <x-icon name="bell" :size="20" />
                <span class="absolute right-2.5 top-2.5 h-2 w-2 rounded-full bg-rose-500 ring-2 ring-white"></span>
            </button>

            <div class="hidden h-8 w-px bg-slate-200 sm:block"></div>

            <button type="button" class="flex items-center gap-2.5 rounded-xl px-1.5 py-1.5 text-left transition hover:bg-slate-100">
                <span class="grid h-9 w-9 place-items-center rounded-full bg-emerald-100 text-xs font-bold text-emerald-700">
                    {{ $initials }}
                </span>
                <span class="hidden leading-tight sm:block">
                    <span class="block text-sm font-semibold text-slate-800">{{ $name }}</span>
                    <span class="block text-[11px] text-slate-500">Admin KLA</span>
                </span>
                <span class="hidden text-slate-400 sm:block">
                    <x-icon name="chevron" :size="15" />
                </span>
            </button>
        </div>
    </div>
</header>
