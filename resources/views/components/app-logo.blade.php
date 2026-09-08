@props([
    'sidebar' => false,
])

@php
$siteLogo = \App\Models\SiteSetting::get('site_logo');
$logoUrl = $siteLogo ?: null;
@endphp

@if ($sidebar)
    <flux:sidebar.brand  {{ $attributes }}>
        <x-slot name="logo"
            class="flex h-14 w-auto max-w-36 items-center justify-center overflow-hidden {{ $logoUrl ? 'bg-white/10' : 'aspect-square size-8 bg-accent-content text-accent-foreground' }}">
            @if ($logoUrl)
                <img src="{{ $logoUrl }}" alt="{{ config('app.name') }}" class="h-14 rounded w-auto max-w-32 object-contain in-data-flux-sidebar-collapsed-desktop:max-w-10 in-data-flux-sidebar-collapsed-desktop:h-6" />
            @else
                <x-app-logo-icon class="size-5 fill-current text-white" />
            @endif
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand :name="config('app.name', 'Laravel')" {{ $attributes }}>
        <x-slot name="logo"
            class="flex h-14 w-auto max-w-36 items-center justify-center rounded-md overflow-hidden {{ $logoUrl ? 'bg-white px-2 py-1' : 'aspect-square size-8 bg-accent-content text-accent-foreground' }}">
            @if ($logoUrl)
                <img src="{{ $logoUrl }}" alt="{{ config('app.name') }}" class="h-14 w-auto max-w-32 object-contain" />
            @else
                <x-app-logo-icon class="size-5 fill-current text-white" />
            @endif
        </x-slot>
    </flux:brand>
@endif
