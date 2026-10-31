@php
    $isEdit = isset($period) && $period !== null;
@endphp

<form method="POST" action="{{ $action }}" class="space-y-6">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm sm:p-6">
        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label for="code" class="mb-1.5 block text-sm font-semibold text-slate-700">Kode periode</label>
                <input
                    id="code"
                    name="code"
                    type="text"
                    value="{{ old('code', $isEdit ? $period->code : '') }}"
                    required
                    class="block w-full rounded-base border border-default-medium bg-neutral-primary-soft px-3 py-2.5 text-sm text-heading shadow-xs transition placeholder:text-body focus:border-brand focus:ring-2 focus:ring-brand-soft focus:outline-none"
                    placeholder="Contoh: KLA-2026"
                >
                @error('code')
                    <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="year" class="mb-1.5 block text-sm font-semibold text-slate-700">Tahun evaluasi</label>
                <input
                    id="year"
                    name="year"
                    type="number"
                    min="2000"
                    max="2100"
                    value="{{ old('year', $isEdit ? $period->year : date('Y')) }}"
                    required
                    class="block w-full rounded-base border border-default-medium bg-neutral-primary-soft px-3 py-2.5 text-sm text-heading shadow-xs transition placeholder:text-body focus:border-brand focus:ring-2 focus:ring-brand-soft focus:outline-none"
                >
                @error('year')
                    <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-2">
                <label for="name" class="mb-1.5 block text-sm font-semibold text-slate-700">Nama periode</label>
                <input
                    id="name"
                    name="name"
                    type="text"
                    value="{{ old('name', $isEdit ? $period->name : '') }}"
                    required
                    class="block w-full rounded-base border border-default-medium bg-neutral-primary-soft px-3 py-2.5 text-sm text-heading shadow-xs transition placeholder:text-body focus:border-brand focus:ring-2 focus:ring-brand-soft focus:outline-none"
                    placeholder="Contoh: Evaluasi Pemenuhan Indikator KLA Tahun {{ date('Y') }}"
                >
                @error('name')
                    <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="start_date" class="mb-1.5 block text-sm font-semibold text-slate-700">Tanggal mulai</label>
                <input
                    id="start_date"
                    name="start_date"
                    type="date"
                    value="{{ old('start_date', $isEdit ? $period->start_date->format('Y-m-d') : '') }}"
                    required
                    class="block w-full rounded-base border border-default-medium bg-neutral-primary-soft px-3 py-2.5 text-sm text-heading shadow-xs transition placeholder:text-body focus:border-brand focus:ring-2 focus:ring-brand-soft focus:outline-none"
                >
                @error('start_date')
                    <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="end_date" class="mb-1.5 block text-sm font-semibold text-slate-700">Batas pengumpulan</label>
                <input
                    id="end_date"
                    name="end_date"
                    type="date"
                    value="{{ old('end_date', $isEdit ? $period->end_date->format('Y-m-d') : '') }}"
                    required
                    class="block w-full rounded-base border border-default-medium bg-neutral-primary-soft px-3 py-2.5 text-sm text-heading shadow-xs transition placeholder:text-body focus:border-brand focus:ring-2 focus:ring-brand-soft focus:outline-none"
                >
                @error('end_date')
                    <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-2">
                <label for="description" class="mb-1.5 block text-sm font-semibold text-slate-700">Deskripsi</label>
                <textarea
                    id="description"
                    name="description"
                    rows="4"
                    class="block w-full rounded-base border border-default-medium bg-neutral-primary-soft px-3 py-2.5 text-sm text-heading shadow-xs transition placeholder:text-body focus:border-brand focus:ring-2 focus:ring-brand-soft focus:outline-none"
                    placeholder="Deskripsi singkat mengenai periode evaluasi"
                >{{ old('description', $isEdit ? $period->description : '') }}</textarea>
                @error('description')
                    <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            @if ($isEdit)
                <div class="sm:col-span-2 rounded-xl border border-slate-200 bg-slate-50/70 p-4">
                    <p class="text-sm font-semibold text-slate-700">Status saat ini</p>
                    <p class="mt-1 text-sm text-slate-500">
                        {{ $period->statusLabel() }} — pengelolaan status dilakukan melalui tindakan pada daftar periode
                        (aktifkan, tutup, atau arsipkan) dengan urutan Draft → Aktif → Ditutup → Diarsipkan.
                    </p>
                </div>
            @endif
        </div>
    </div>

    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
        <a
            href="{{ route('admin.periods.index') }}"
            class="inline-flex items-center justify-center rounded-base border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
        >
            Batal
        </a>
        <button
            type="submit"
            class="inline-flex items-center justify-center gap-2 rounded-base bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800 focus:ring-4 focus:ring-emerald-200"
        >
            <x-icon name="check" :size="17" />
            {{ $isEdit ? 'Simpan perubahan' : 'Tambah periode' }}
        </button>
    </div>
</form>
