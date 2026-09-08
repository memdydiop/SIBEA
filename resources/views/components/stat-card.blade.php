@props([
    'label' => null,
    'value' => null,
    'hint' => null,
    'icon' => null,
    'variant' => 'default', // default, success, warning, danger, accent
])

@php
    $valueColor = match ($variant) {
        'success' => 'text-emerald-600',
        'warning' => 'text-amber-600',
        'danger' => 'text-red-600',
        'accent' => 'text-[var(--color-accent)]',
        default => 'text-zinc-900',
    };
@endphp

<flux:card>

    <x-card-body padding>
        @if ($label)
            <div class="flex items-center gap-1.5 text-xs font-medium uppercase tracking-wide text-zinc-500">
                @if ($icon)
                    <flux:icon :icon="$icon" class="size-3.5 text-zinc-400" />
                @endif
                <span>{{ $label }}</span>
            </div>
        @endif

        <div class="mt-2 flex items-baseline gap-2">
            @if ($value !== null)
                <span class="text-2xl font-semibold {{ $valueColor }}">{{ $value }}</span>
            @endif
            @if (trim($slot) !== '')
                <span class="text-sm text-zinc-500">{{ $slot }}</span>
            @endif
        </div>

        @if ($hint)
            <div class="mt-1 text-xs text-zinc-400">{{ $hint }}</div>
        @endif
    </x-card-body>

</flux:card>
