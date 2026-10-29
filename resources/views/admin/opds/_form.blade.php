@php
    $isEdit = isset($opd) && $opd !== null;
    $isActive = old('is_active', $isEdit ? $opd->is_active : true);
@endphp

<form method="POST" action="{{ $action }}" class="space-y-6">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm sm:p-6">
        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label for="kode_opd" class="mb-1.5 block text-sm font-semibold text-slate-700">Kode OPD</label>
                <input
                    id="kode_opd"
                    name="kode_opd"
                    type="text"
                    value="{{ old('kode_opd', $isEdit ? $opd->kode_opd : '') }}"
                    required
                    class="block w-full rounded-base border border-default-medium bg-neutral-primary-soft px-3 py-2.5 text-sm text-heading shadow-xs transition placeholder:text-body focus:border-brand focus:ring-2 focus:ring-brand-soft focus:outline-none"
                    placeholder="Contoh: 1.01.01"
                >
                @error('kode_opd')
                    <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="singkatan" class="mb-1.5 block text-sm font-semibold text-slate-700">Singkatan</label>
                <input
                    id="singkatan"
                    name="singkatan"
                    type="text"
                    value="{{ old('singkatan', $isEdit ? $opd->singkatan : '') }}"
                    class="block w-full rounded-base border border-default-medium bg-neutral-primary-soft px-3 py-2.5 text-sm text-heading shadow-xs transition placeholder:text-body focus:border-brand focus:ring-2 focus:ring-brand-soft focus:outline-none"
                    placeholder="Contoh: Disdik"
                >
                @error('singkatan')
                    <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-2">
                <label for="nama_opd" class="mb-1.5 block text-sm font-semibold text-slate-700">Nama OPD</label>
                <input
                    id="nama_opd"
                    name="nama_opd"
                    type="text"
                    value="{{ old('nama_opd', $isEdit ? $opd->nama_opd : '') }}"
                    required
                    class="block w-full rounded-base border border-default-medium bg-neutral-primary-soft px-3 py-2.5 text-sm text-heading shadow-xs transition placeholder:text-body focus:border-brand focus:ring-2 focus:ring-brand-soft focus:outline-none"
                    placeholder="Contoh: Dinas Pendidikan"
                >
                @error('nama_opd')
                    <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="mb-1.5 block text-sm font-semibold text-slate-700">Email</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email', $isEdit ? $opd->email : '') }}"
                    class="block w-full rounded-base border border-default-medium bg-neutral-primary-soft px-3 py-2.5 text-sm text-heading shadow-xs transition placeholder:text-body focus:border-brand focus:ring-2 focus:ring-brand-soft focus:outline-none"
                    placeholder="opd@contoh.go.id"
                >
                @error('email')
                    <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="telepon" class="mb-1.5 block text-sm font-semibold text-slate-700">Telepon</label>
                <input
                    id="telepon"
                    name="telepon"
                    type="text"
                    value="{{ old('telepon', $isEdit ? $opd->telepon : '') }}"
                    class="block w-full rounded-base border border-default-medium bg-neutral-primary-soft px-3 py-2.5 text-sm text-heading shadow-xs transition placeholder:text-body focus:border-brand focus:ring-2 focus:ring-brand-soft focus:outline-none"
                    placeholder="Contoh: 021-1234567"
                >
                @error('telepon')
                    <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-2">
                <label for="alamat" class="mb-1.5 block text-sm font-semibold text-slate-700">Alamat</label>
                <textarea
                    id="alamat"
                    name="alamat"
                    rows="3"
                    class="block w-full rounded-base border border-default-medium bg-neutral-primary-soft px-3 py-2.5 text-sm text-heading shadow-xs transition placeholder:text-body focus:border-brand focus:ring-2 focus:ring-brand-soft focus:outline-none"
                    placeholder="Alamat kantor OPD"
                >{{ old('alamat', $isEdit ? $opd->alamat : '') }}</textarea>
                @error('alamat')
                    <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-2">
                <label for="deskripsi" class="mb-1.5 block text-sm font-semibold text-slate-700">Deskripsi</label>
                <textarea
                    id="deskripsi"
                    name="deskripsi"
                    rows="4"
                    class="block w-full rounded-base border border-default-medium bg-neutral-primary-soft px-3 py-2.5 text-sm text-heading shadow-xs transition placeholder:text-body focus:border-brand focus:ring-2 focus:ring-brand-soft focus:outline-none"
                    placeholder="Deskripsi singkat mengenai OPD"
                >{{ old('deskripsi', $isEdit ? $opd->deskripsi : '') }}</textarea>
                @error('deskripsi')
                    <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-2">
                <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 bg-slate-50/70 p-4">
                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        @checked($isActive)
                        class="mt-0.5 h-4 w-4 rounded-xs border border-default-medium bg-neutral-secondary-medium text-emerald-700 focus:ring-2 focus:ring-brand-soft"
                    >
                    <span>
                        <span class="block text-sm font-semibold text-slate-700">OPD aktif</span>
                        <span class="mt-0.5 block text-xs text-slate-500">Hanya OPD aktif yang dapat dipilih untuk akun pengguna baru.</span>
                    </span>
                </label>
            </div>
        </div>
    </div>

    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
        <a
            href="{{ route('admin.opds.index') }}"
            class="inline-flex items-center justify-center rounded-base border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
        >
            Batal
        </a>
        <button
            type="submit"
            class="inline-flex items-center justify-center gap-2 rounded-base bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800 focus:ring-4 focus:ring-emerald-200"
        >
            <x-icon name="check" :size="17" />
            {{ $isEdit ? 'Simpan perubahan' : 'Tambah OPD' }}
        </button>
    </div>
</form>
