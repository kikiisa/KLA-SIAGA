@extends('layouts.app')

@section('title', 'Tambah Periode Evaluasi — '.config('app.name'))

@section('content')
    <div class="mb-6">
        <p class="text-xs font-medium text-slate-500">Konfigurasi Evaluasi / Periode Evaluasi</p>
        <h1 class="mt-1 text-2xl font-semibold tracking-tight text-slate-900">Tambah Periode Evaluasi</h1>
        <p class="mt-1 text-sm text-slate-500">Periode baru dibuat dengan status Draft sebelum diaktifkan.</p>
    </div>

    @include('partials.alerts')

    @include('admin.periods._form', [
        'action' => route('admin.periods.store'),
        'method' => 'POST',
        'period' => null,
    ])
@endsection
