@extends('layouts.app')

@section('title', 'Edit Periode Evaluasi — '.config('app.name'))

@section('content')
    <div class="mb-6">
        <p class="text-xs font-medium text-slate-500">Konfigurasi Evaluasi / Periode Evaluasi</p>
        <h1 class="mt-1 text-2xl font-semibold tracking-tight text-slate-900">Edit Periode Evaluasi</h1>
        <p class="mt-1 text-sm text-slate-500">Perbarui informasi dan jadwal pengumpulan periode.</p>
    </div>

    @include('partials.alerts')

    @include('admin.periods._form', [
        'action' => route('admin.periods.update', $period),
        'method' => 'PUT',
        'period' => $period,
    ])
@endsection
