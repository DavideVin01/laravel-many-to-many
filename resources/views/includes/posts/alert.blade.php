@if (session('message'))
    <div class="mt-3 alert alert-{{ session('type') }} ?? 'info'">
        {{ session('message') }}
    </div>

    @endif