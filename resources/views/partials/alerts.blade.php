@if (session('status'))
    <div class="mb-6 flex items-center gap-2 rounded-base border border-success-medium bg-success-soft p-4 text-sm text-fg-success" role="alert">
        <x-icon name="check" :size="17" />
        <span>{{ session('status') }}</span>
    </div>
@endif

@if ($errors->any())
    <div class="mb-6 flex items-center gap-2 rounded-base border border-danger-medium bg-danger-soft p-4 text-sm text-fg-danger" role="alert">
        <x-icon name="warning" :size="17" />
        <span>{{ $errors->first() }}</span>
    </div>
@endif
