@if (session('status'))
    <div
        data-sweet-alert
        data-icon="success"
        data-title="Berhasil"
        data-text="{{ session('status') }}"
    ></div>
@endif

@if ($errors->any())
    <div
        data-sweet-alert
        data-icon="error"
        data-title="Terjadi kesalahan"
        data-text="{{ $errors->first() }}"
    ></div>
@endif
