@props(['compact' => false, 'dark' => false])

<div {{ $attributes->merge(['class' => 'flex items-center gap-3']) }}>
    <span @class([
        'grid shrink-0 place-items-center rounded-xl shadow-sm',
        'h-9 w-9' => $compact,
        'h-11 w-11' => ! $compact,
        'bg-emerald-500/15 text-emerald-300 ring-1 ring-inset ring-white/15' => $dark,
        'bg-emerald-600 text-white' => ! $dark,
    ])>
        <x-icon name="logo" :size="$compact ? 20 : 24" />
    </span>

    <span class="flex flex-col leading-none">
        <span @class([
            'font-bold tracking-tight',
            'text-base' => $compact,
            'text-lg' => ! $compact,
            'text-white' => $dark,
            'text-slate-900' => ! $dark,
        ])>
            <span @class(['text-emerald-300' => $dark, 'text-emerald-700' => ! $dark])>SIAGA</span> KLA
        </span>
        <small @class([
            'mt-1 text-[10px] font-semibold uppercase tracking-[0.18em]',
            'text-emerald-100/60' => $dark,
            'text-slate-500' => ! $dark,
        ])>Sistem Monitoring KLA</small>
    </span>
</div>
