@php
    $isEdit = isset($user) && $user !== null;
    $roleLabels = [
        'admin' => 'Admin',
        'pic' => 'PIC',
        'operator_opd' => 'Operator OPD',
    ];
    $selectedRole = old('role', $isEdit ? $user->roles->first()?->name : '');
    $selectedOpd = old('opd_id', $isEdit ? $user->opd_id : '');
    $isActive = old('is_active', $isEdit ? $user->is_active : true);
@endphp

<form method="POST" action="{{ $action }}" class="space-y-6">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm sm:p-6">
        <div class="grid gap-5 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label for="name" class="mb-1.5 block text-sm font-semibold text-slate-700">Nama lengkap</label>
                <input
                    id="name"
                    name="name"
                    type="text"
                    value="{{ old('name', $isEdit ? $user->name : '') }}"
                    required
                    autocomplete="name"
                    class="block w-full rounded-base border border-default-medium bg-neutral-primary-soft px-3 py-2.5 text-sm text-heading shadow-xs transition placeholder:text-body focus:border-brand focus:ring-2 focus:ring-brand-soft focus:outline-none"
                    placeholder="Contoh: Andi Kurniawan"
                >
                @error('name')
                    <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-2">
                <label for="email" class="mb-1.5 block text-sm font-semibold text-slate-700">Alamat email</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email', $isEdit ? $user->email : '') }}"
                    required
                    autocomplete="email"
                    class="block w-full rounded-base border border-default-medium bg-neutral-primary-soft px-3 py-2.5 text-sm text-heading shadow-xs transition placeholder:text-body focus:border-brand focus:ring-2 focus:ring-brand-soft focus:outline-none"
                    placeholder="nama@opd.go.id"
                >
                @error('email')
                    <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="role" class="mb-1.5 block text-sm font-semibold text-slate-700">Role</label>
                <select
                    id="role"
                    name="role"
                    required
                    class="block w-full rounded-base border border-default-medium bg-neutral-primary-soft px-3 py-2.5 text-sm text-heading shadow-xs transition focus:border-brand focus:ring-2 focus:ring-brand-soft focus:outline-none"
                >
                    <option value="" disabled @selected($selectedRole === '')>Pilih role</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}" @selected($selectedRole === $role->name)>
                            {{ $roleLabels[$role->name] ?? $role->name }}
                        </option>
                    @endforeach
                </select>
                @error('role')
                    <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="opd_id" class="mb-1.5 block text-sm font-semibold text-slate-700">OPD</label>
                <select
                    id="opd_id"
                    name="opd_id"
                    class="block w-full rounded-base border border-default-medium bg-neutral-primary-soft px-3 py-2.5 text-sm text-heading shadow-xs transition focus:border-brand focus:ring-2 focus:ring-brand-soft focus:outline-none"
                >
                    <option value="">Tidak terhubung OPD</option>
                    @foreach ($opds as $opd)
                        <option value="{{ $opd->id }}" @selected((string) $selectedOpd === (string) $opd->id)>
                            {{ $opd->singkatan ? $opd->singkatan.' — ' : '' }}{{ $opd->nama_opd }}
                        </option>
                    @endforeach
                </select>
                <p class="mt-1.5 text-xs text-slate-500">Kosongkan untuk akun admin pusat atau PIC.</p>
                @error('opd_id')
                    <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="mb-1.5 block text-sm font-semibold text-slate-700">
                    Kata sandi
                    @if ($isEdit)
                        <span class="font-normal text-slate-400">(opsional)</span>
                    @endif
                </label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    @required(! $isEdit)
                    autocomplete="new-password"
                    class="block w-full rounded-base border border-default-medium bg-neutral-primary-soft px-3 py-2.5 text-sm text-heading shadow-xs transition placeholder:text-body focus:border-brand focus:ring-2 focus:ring-brand-soft focus:outline-none"
                    placeholder="{{ $isEdit ? 'Biarkan kosong jika tidak diubah' : 'Minimal 8 karakter' }}"
                >
                @error('password')
                    <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="mb-1.5 block text-sm font-semibold text-slate-700">Konfirmasi kata sandi</label>
                <input
                    id="password_confirmation"
                    name="password_confirmation"
                    type="password"
                    @required(! $isEdit)
                    autocomplete="new-password"
                    class="block w-full rounded-base border border-default-medium bg-neutral-primary-soft px-3 py-2.5 text-sm text-heading shadow-xs transition placeholder:text-body focus:border-brand focus:ring-2 focus:ring-brand-soft focus:outline-none"
                    placeholder="Ulangi kata sandi"
                >
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
                        <span class="block text-sm font-semibold text-slate-700">Akun aktif</span>
                        <span class="mt-0.5 block text-xs text-slate-500">Pengguna hanya dapat masuk jika akun ini aktif.</span>
                    </span>
                </label>
            </div>
        </div>
    </div>

    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
        <a
            href="{{ route('admin.users.index') }}"
            class="inline-flex items-center justify-center rounded-base border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
        >
            Batal
        </a>
        <button
            type="submit"
            class="inline-flex items-center justify-center gap-2 rounded-base bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800 focus:ring-4 focus:ring-emerald-200"
        >
            <x-icon name="check" :size="17" />
            {{ $isEdit ? 'Simpan perubahan' : 'Tambah pengguna' }}
        </button>
    </div>
</form>
