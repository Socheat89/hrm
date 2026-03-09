@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => '']) }}
         style="padding:.85rem 1.1rem;border-radius:12px;background:rgba(34,197,94,.1);border:1.5px solid rgba(34,197,94,.25);color:#4ade80;font-size:.85rem;font-weight:600;display:flex;align-items:center;gap:.6rem;margin-bottom:1rem">
        <i class="fa-solid fa-circle-check" style="color:#22c55e"></i>
        {{ $status }}
    </div>
@endif
