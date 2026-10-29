@extends('layouts.app')

@section('title', 'Dashboard — '.config('app.name'))

@section('content')
    @php
        $user = auth()->user();
        $firstName = explode(' ', $user?->name ?? 'Andi Kurniawan')[0];
        $today = now()->locale('id')->translatedFormat('l, j F Y');

        $verificationBreakdown = [
            ['label' => 'Terverifikasi', 'value' => 108, 'color' => '#10b981'],
            ['label' => 'Menunggu verifikasi', 'value' => 24, 'color' => '#0ea5e9'],
            ['label' => 'Perlu revisi', 'value' => 16, 'color' => '#f59e0b'],
            ['label' => 'Belum diunggah', 'value' => 12, 'color' => '#cbd5e1'],
        ];
        $totalDocuments = array_sum(array_column($verificationBreakdown, 'value'));
        $verifiedPercentage = (int) round(($verificationBreakdown[0]['value'] / $totalDocuments) * 100);

        $stats = [
            ['icon' => 'document', 'label' => 'Total indikator', 'value' => '32', 'note' => 'Dalam 5 cluster aktif', 'tone' => 'teal'],
            ['icon' => 'clock', 'label' => 'Menunggu verifikasi', 'value' => '12', 'note' => '4 dokumen baru hari ini', 'tone' => 'blue'],
            ['icon' => 'warning', 'label' => 'Perlu revisi', 'value' => '8', 'note' => '3 mendekati tenggat', 'tone' => 'amber'],
            ['icon' => 'check', 'label' => 'Terverifikasi', 'value' => $verifiedPercentage.'%', 'note' => 'Naik 12% bulan ini', 'tone' => 'green'],
        ];
        $statTones = [
            'teal' => 'bg-teal-50 text-teal-600 ring-teal-100',
            'blue' => 'bg-sky-50 text-sky-600 ring-sky-100',
            'amber' => 'bg-amber-50 text-amber-600 ring-amber-100',
            'green' => 'bg-emerald-50 text-emerald-600 ring-emerald-100',
        ];

        $clusters = [
            ['name' => 'Hak Sipil dan Kebebasan', 'value' => 86, 'color' => '#10b981'],
            ['name' => 'Lingkungan Keluarga & Pengasuhan', 'value' => 74, 'color' => '#0ea5e9'],
            ['name' => 'Kesehatan Dasar & Kesejahteraan', 'value' => 68, 'color' => '#0ea5e9'],
            ['name' => 'Pendidikan & Kegiatan Budaya', 'value' => 55, 'color' => '#f59e0b'],
            ['name' => 'Perlindungan Khusus', 'value' => 42, 'color' => '#f59e0b'],
        ];

        $verificationRows = [
            ['name' => 'Dokumen Kebijakan Pemenuhan Hak Identitas Anak', 'opd' => 'Disdukcapil', 'cluster' => 'Hak Sipil & Kebebasan', 'time' => '10 menit lalu', 'tone' => 'blue'],
            ['name' => 'Laporan Data Pelayanan Anak Tahun 2026', 'opd' => 'Dinas Kesehatan', 'cluster' => 'Kesehatan Dasar', 'time' => '35 menit lalu', 'tone' => 'blue'],
            ['name' => 'SK Tim Pelaksana Kabupaten Layak Anak', 'opd' => 'DP3A', 'cluster' => 'Kelembagaan', 'time' => '1 jam lalu', 'tone' => 'amber'],
        ];
        $fileTones = [
            'blue' => 'bg-sky-50 text-sky-600 ring-sky-100',
            'amber' => 'bg-amber-50 text-amber-600 ring-amber-100',
        ];
    @endphp

    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <p class="text-xs font-medium text-slate-500">{{ $today }}</p>
            <h1 class="mt-1 text-2xl font-semibold tracking-tight text-slate-900">
                Selamat datang, {{ $firstName }}
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Pantau progres evaluasi dan tindak lanjuti dokumen hari ini.
            </p>
        </div>

        <div class="flex items-center gap-3 self-start rounded-base border border-default-medium bg-neutral-primary-soft px-3 py-2 shadow-xs">
            <label for="period" class="text-[10px] font-semibold uppercase tracking-[0.16em] text-body-subtle">Periode aktif</label>
            <select
                id="period"
                class="block rounded-base border border-default-medium bg-neutral-secondary-medium px-3 py-2 text-sm font-semibold text-heading shadow-xs focus:border-brand focus:ring-2 focus:ring-brand-soft focus:outline-none"
            >
                <option value="2026">Evaluasi KLA 2026</option>
                <option value="2025">Evaluasi KLA 2025</option>
            </select>
        </div>
    </div>

    <section class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($stats as $stat)
            <article class="flex items-start gap-4 rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl ring-1 ring-inset {{ $statTones[$stat['tone']] }}">
                    <x-icon :name="$stat['icon']" :size="20" />
                </span>
                <div class="min-w-0">
                    <p class="text-xs font-medium text-slate-500">{{ $stat['label'] }}</p>
                    <strong class="mt-1 block text-2xl font-semibold tracking-tight text-slate-900">{{ $stat['value'] }}</strong>
                    <small class="mt-0.5 block truncate text-[11px] text-slate-400">{{ $stat['note'] }}</small>
                </div>
            </article>
        @endforeach
    </section>

    <section class="mt-4 grid gap-4 lg:grid-cols-3">
        <article class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm lg:col-span-2">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <h2 class="text-base font-semibold text-slate-900">Progres Periode 2026</h2>
                    <p class="mt-0.5 text-xs text-slate-500">Rekap pemenuhan subdokumen wajib</p>
                </div>
                <a href="#" class="inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1.5 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-50">
                    Lihat laporan
                    <x-icon name="arrow" :size="14" />
                </a>
            </div>

            <div class="mt-6 flex flex-col items-center gap-8 sm:flex-row sm:items-center sm:justify-around">
                <div class="relative h-40 w-40 shrink-0">
                    <canvas
                        id="verification-chart"
                        data-chart="doughnut"
                        data-labels='@json(array_column($verificationBreakdown, 'label'), JSON_HEX_APOS)'
                        data-values='@json(array_column($verificationBreakdown, 'value'), JSON_HEX_APOS)'
                        data-colors='@json(array_column($verificationBreakdown, 'color'), JSON_HEX_APOS)'
                        class="h-full w-full"
                        role="img"
                        aria-label="Rekap status verifikasi dokumen"
                    ></canvas>
                    <div class="pointer-events-none absolute inset-0 grid place-items-center text-center">
                        <div>
                            <strong class="block text-2xl font-semibold tracking-tight text-slate-900">{{ $verifiedPercentage }}%</strong>
                            <span class="text-[11px] text-slate-500">Terverifikasi</span>
                        </div>
                    </div>
                </div>

                <div class="w-full max-w-xs space-y-3">
                    @foreach ($verificationBreakdown as $legend)
                        <div class="flex items-center gap-2.5 text-sm">
                            <span class="h-2.5 w-2.5 shrink-0 rounded-full" style="background-color: {{ $legend['color'] }}"></span>
                            <span class="text-slate-500">
                                <b class="font-semibold text-slate-800">{{ $legend['value'] }}</b>
                                {{ $legend['label'] }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </article>

        <article class="flex flex-col rounded-2xl border border-emerald-900/10 bg-gradient-to-br from-emerald-800 to-teal-900 p-5 text-white shadow-sm">
            <span class="grid h-10 w-10 place-items-center rounded-xl bg-white/10 text-emerald-100 ring-1 ring-inset ring-white/15">
                <x-icon name="calendar" :size="20" />
            </span>
            <span class="mt-5 text-[10px] font-semibold uppercase tracking-[0.18em] text-emerald-200/80">Batas pengumpulan</span>
            <h2 class="mt-1 text-3xl font-semibold tracking-tight">20 hari lagi</h2>
            <p class="mt-1 text-sm text-emerald-100/70">30 November 2026</p>

            <div class="mt-auto pt-6">
                <div class="h-1.5 overflow-hidden rounded-full bg-white/15">
                    <span class="block h-full w-[70%] rounded-full bg-emerald-300"></span>
                </div>
                <small class="mt-2 block text-[11px] text-emerald-100/60">70% periode telah berjalan</small>
            </div>
        </article>

        <article class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <h2 class="text-base font-semibold text-slate-900">Progres per cluster</h2>
                    <p class="mt-0.5 text-xs text-slate-500">Berdasarkan dokumen terverifikasi</p>
                </div>
                <button type="button" aria-label="Opsi lain" class="rounded-lg px-2 py-1 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600">
                    &bull;&bull;&bull;
                </button>
            </div>

            <div class="mt-5 h-64">
                <canvas
                    id="cluster-progress-chart"
                    data-chart="horizontal-bar"
                    data-labels='@json(array_column($clusters, 'name'), JSON_HEX_APOS)'
                    data-values='@json(array_column($clusters, 'value'), JSON_HEX_APOS)'
                    data-colors='@json(array_column($clusters, 'color'), JSON_HEX_APOS)'
                    class="h-full w-full"
                    role="img"
                    aria-label="Progres verifikasi per cluster"
                ></canvas>
            </div>
        </article>

        <article class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm lg:col-span-2">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <h2 class="text-base font-semibold text-slate-900">Menunggu verifikasi</h2>
                    <p class="mt-0.5 text-xs text-slate-500">Dokumen terbaru yang perlu diperiksa</p>
                </div>
                <a href="#" class="inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1.5 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-50">
                    Lihat semua
                    <x-icon name="arrow" :size="14" />
                </a>
            </div>

            <div class="mt-4 divide-y divide-slate-100">
                @foreach ($verificationRows as $row)
                    <div class="flex items-center gap-3 py-3 first:pt-0 last:pb-0">
                        <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl ring-1 ring-inset {{ $fileTones[$row['tone']] }}">
                            <x-icon name="document" :size="18" />
                        </span>

                        <div class="min-w-0 flex-1">
                            <b class="block truncate text-sm font-semibold text-slate-800">{{ $row['name'] }}</b>
                            <span class="mt-0.5 flex flex-wrap items-center gap-x-2 gap-y-0.5 text-[11px] text-slate-500">
                                {{ $row['opd'] }}
                                <i class="h-1 w-1 rounded-full bg-slate-300"></i>
                                {{ $row['cluster'] }}
                            </span>
                        </div>

                        <time class="hidden shrink-0 text-[11px] text-slate-400 sm:block">{{ $row['time'] }}</time>

                        <a
                            href="#"
                            aria-label="Buka dokumen"
                            class="grid h-8 w-8 shrink-0 place-items-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"
                        >
                            <x-icon name="chevron" :size="16" />
                        </a>
                    </div>
                @endforeach
            </div>
        </article>
    </section>
@endsection
