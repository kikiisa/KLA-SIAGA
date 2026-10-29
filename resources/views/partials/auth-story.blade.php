<section class="relative hidden overflow-hidden bg-emerald-950 px-12 py-12 lg:flex lg:flex-col xl:px-20 xl:py-16">
    <div class="pointer-events-none absolute -left-24 top-1/3 h-96 w-96 rounded-full bg-emerald-500/20 blur-3xl"></div>
    <div class="pointer-events-none absolute -right-20 -top-24 h-80 w-80 rounded-full bg-teal-400/10 blur-3xl"></div>
    <div class="pointer-events-none absolute bottom-0 left-1/4 h-72 w-72 rounded-full bg-lime-300/10 blur-3xl"></div>

    <div class="relative z-10 flex h-full flex-col">
        <x-logo dark />

        <div class="my-auto max-w-xl py-16">
            <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-emerald-100">
                Platform Evaluasi KLA Terintegrasi
            </span>

            <h1 class="mt-7 text-4xl font-semibold leading-[1.08] tracking-tight text-white xl:text-5xl">
                Wujudkan Kabupaten/Kota
                <em class="not-italic text-emerald-300">Layak Anak</em>
                bersama.
            </h1>

            <p class="mt-6 max-w-lg text-base leading-relaxed text-emerald-100/75">
                Kelola indikator, bukti dukung, dan proses verifikasi dalam satu sistem yang transparan dan terukur.
            </p>

            <div class="mt-9 flex flex-wrap gap-3">
                @foreach (['Satu indikator', 'Satu bukti', 'Tepat waktu'] as $principle)
                    <span class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm text-emerald-50">
                        <span class="grid h-5 w-5 place-items-center rounded-full bg-emerald-400/20 text-emerald-300">
                            <x-icon name="check" :size="13" />
                        </span>
                        {{ $principle }}
                    </span>
                @endforeach
            </div>
        </div>

        <p class="text-sm text-emerald-100/50">Dikelola oleh Pemerintah Kabupaten Gorontalo</p>
    </div>
</section>
