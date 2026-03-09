@props(['messages'])

@if ($messages)
    @foreach ((array) $messages as $message)
        <p class="auth-error" style="margin-top:.4rem">
            <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
        </p>
    @endforeach
@endif
