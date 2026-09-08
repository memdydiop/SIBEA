@props([
    'title' => null,
    'subtitle' => null,
])

<div {{ $attributes->class('flex flex-wrap items-center justify-between gap-3 px-4 py-2.5 bg-white border-b border-zinc-200/70 border-dashed') }}>
    <div class="flex-1 min-w-0">
        @if($title)
            <h3 class="text-sm font-semibold text-zinc-900 leading-tight">{{ $title }}</h3>
        @endif
        @if($subtitle)
            <p class="mt-1 text-xs text-zinc-500">{{ $subtitle }}</p>
        @endif
        {{ $slot }}
    </div>
    @isset($actions)
        <div class="flex items-center gap-2 shrink-0">
            {{ $actions }}
        </div>
    @endisset
</div>
