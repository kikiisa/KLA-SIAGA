@extends('layouts.app')

@section('title', 'Tambah Pengguna — '.config('app.name'))

@section('content')
    <div class="mb-6">
        <p class="text-xs font-medium text-slate-500">Administrasi / Manajemen Pengguna</p>
        <h1 class="mt-1 text-2xl font-semibold tracking-tight text-slate-900">Tambah pengguna</h1>
        <p class="mt-1 text-sm text-slate-500">Buat akun baru dan tentukan role serta OPD-nya.</p>
    </div>

    @include('partials.alerts')

    @include('admin.users._form', [
        'action' => route('admin.users.store'),
        'method' => 'POST',
        'user' => null,
    ])
@endsection
