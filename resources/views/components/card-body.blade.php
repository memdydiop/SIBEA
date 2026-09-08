@props([
    'padding' => false, // Paces tables use p-0, content uses p-4
])

<div {{ $attributes->class($padding ? 'p-4 bg-white' : 'p-0 bg-white overflow-hidden') }}>
    {{ $slot }}
</div>
