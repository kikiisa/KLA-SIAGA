@extends('layouts.auth')

@section('title', 'Masuk  '.config('app.name'))

@section('content')
    <main class="grid min-h-screen lg:grid-cols-[1.05fr_1fr]">
        @include('partials.auth-story')
        <section class="flex flex-col px-6 py-10 sm:px-10 lg:px-14 xl:px-20">
            <div class="mb-10 lg:hidden">
                <x-logo />
            </div>

            <div class="mx-auto flex w-full max-w-md flex-1 flex-col justify-center">
                <div class="mb-8">
                    <span class="text-xs font-semibold uppercase tracking-[0.18em] text-emerald-700">Akses Sistem</span>
                    <h2 class="mt-3 text-3xl font-semibold tracking-tight text-slate-900">Selamat datang kembali</h2>
                    <p class="mt-2 text-sm leading-relaxed text-slate-500">
                        Masuk menggunakan kredensial yang telah diberikan oleh administrator.
                    </p>
                </div>

                @include('partials.alerts')

                <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="mb-2.5 block text-sm font-medium text-heading">Alamat email</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 grid w-11 place-items-center text-body">
                                <x-icon name="users" :size="18" />
                            </span>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                value="{{ old('email') }}"
                                placeholder="nama@siagakla.go.id"
                                required
                                autofocus
                                autocomplete="username"
                                @class([
                                    'block w-full rounded-base border bg-neutral-primary-soft py-2.5 pl-11 pr-3 text-sm text-heading shadow-xs transition placeholder:text-body focus:ring-2 focus:outline-none',
                                    'border-danger focus:border-danger focus:ring-danger-medium' => $errors->has('email'),
                                    'border-default-medium focus:border-brand focus:ring-brand-soft' => ! $errors->has('email'),
                                ])
                            >
                        </div>
                    </div>

                    <div>
                        <label for="password" class="mb-2.5 block text-sm font-medium text-heading">Kata sandi</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 grid w-11 place-items-center text-body">
                                <x-icon name="lock" :size="18" />
                            </span>
                            <input
                                id="password"
                                name="password"
                                type="password"
                                placeholder="Masukkan kata sandi"
                                required
                                autocomplete="current-password"
                                @class([
                                    'block w-full rounded-base border bg-neutral-primary-soft py-2.5 pl-11 pr-12 text-sm text-heading shadow-xs transition placeholder:text-body focus:ring-2 focus:outline-none',
                                    'border-danger focus:border-danger focus:ring-danger-medium' => $errors->has('password'),
                                    'border-default-medium focus:border-brand focus:ring-brand-soft' => ! $errors->has('password'),
                                ])
                            >
                            <button
                                type="button"
                                data-password-toggle="password"
                                aria-label="Tampilkan kata sandi"
                                class="absolute inset-y-0 right-0 grid w-11 place-items-center text-body transition hover:text-heading focus:outline-none"
                            >
                                <x-icon name="eye" :size="18" />
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center">
                            <input
                                id="remember"
                                type="checkbox"
                                name="remember"
                                value="1"
                                @checked(old('remember'))
                                class="h-4 w-4 rounded-xs border border-default-medium bg-neutral-secondary-medium focus:ring-2 focus:ring-brand-soft"
                            >
                            <label for="remember" class="ms-2 text-sm font-medium text-heading">Ingat saya</label>
                        </div>

                        <a href="mailto:admin@siagakla.go.id?subject=Lupa%20Kata%20Sandi" class="text-sm font-medium text-fg-brand hover:underline">
                            Lupa kata sandi?
                        </a>
                    </div>

                    <button
                        type="submit"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-base border border-transparent bg-brand px-5 py-2.5 text-sm font-medium leading-5 text-white shadow-xs transition hover:bg-brand-strong focus:outline-none focus:ring-4 focus:ring-brand-medium"
                    >
                        Masuk
                        <x-icon name="arrow" :size="18" />
                    </button>

                    <div class="flex items-center justify-center gap-2 text-xs text-body-subtle">
                        <x-icon name="lock" :size="15" />
                        Akses dilindungi dan seluruh aktivitas tercatat.
                    </div>
                </form>
            </div>

            <div class="mx-auto mt-8 flex w-full max-w-md flex-wrap items-center justify-center gap-1.5 text-sm text-body">
                <x-icon name="help" :size="17" />
                Mengalami kendala?
                <a href="mailto:admin@siagakla.go.id" class="font-medium text-fg-brand hover:underline">
                    Hubungi administrator
                </a>
            </div>
        </section>
    </main>
@endsection
