@if (session('success') || session('error'))
    <div class="toast-wrap" role="status" aria-live="polite">
        @if (session('success'))
            <div class="alert alert-success"><x-icon name="check" style="width:18px;height:18px" /> <span>{{ session('success') }}</span></div>
        @endif
        @if (session('error'))
            <div class="alert alert-error"><x-icon name="info" style="width:18px;height:18px" /> <span>{{ session('error') }}</span></div>
        @endif
    </div>
@endif
