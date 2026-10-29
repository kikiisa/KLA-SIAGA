@extends('layouts.app')

@section('title', 'Edit OPD — '.config('app.name'))

@section('content')
    <div class="mb-6">
        <p class="text-xs font-medium text-slate-500">Administrasi / Manajemen OPD</p>
        <h1 class="mt-1 text-2xl font-semibold tracking-tight text-slate-900">Edit OPD</h1>
        <p class="mt-1 text-sm text-slate-500">Perbarui data dan status OPD.</p>
    </div>

    @include('partials.alerts')

    @include('admin.opds._form', [
        'action' => route('admin.opds.update', $opd),
        'method' => 'PUT',
        'opd' => $opd,
    ])
@endsection
