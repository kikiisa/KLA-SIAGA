@extends('layouts.app')

@section('title', 'Edit Pengguna — '.config('app.name'))

@section('content')
    <div class="mb-6">
        <p class="text-xs font-medium text-slate-500">Administrasi / Manajemen Pengguna</p>
        <h1 class="mt-1 text-2xl font-semibold tracking-tight text-slate-900">Edit pengguna</h1>
        <p class="mt-1 text-sm text-slate-500">Perbarui data akun, role, dan status aktif pengguna.</p>
    </div>

    @include('partials.alerts')

    @include('admin.users._form', [
        'action' => route('admin.users.update', $user),
        'method' => 'PUT',
        'user' => $user,
    ])
@endsection
