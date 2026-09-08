@props([
    'align' => 'between', // between, end, start, center
])

@php
$alignClass = match($align) {
    'end' => 'justify-end',
    'start' => 'justify-start',
    'center' => 'justify-center',
    default => 'justify-between',
};
@endphp

<div {{ $attributes->class("flex flex-wrap items-center gap-3 p-4 bg-zinc-50/50 border-t border-zinc-200 $alignClass") }}>
    {{ $slot }}
</div>
