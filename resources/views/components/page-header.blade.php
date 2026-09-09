@props([
    'title',
    'subtitle' => null,
    'size' => 'xl',
    'level' => 1,
])

<div {{ $attributes }}>
    <flux:heading :size="$size" :level="$level">{{ $title }}</flux:heading>
    @if($subtitle)
        <flux:subheading class="mb-6">{{ $subtitle }}</flux:subheading>
    @endif
    {{ $slot }}
</div>
