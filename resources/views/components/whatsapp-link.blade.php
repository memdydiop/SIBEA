@props([
    'phone' => null,
    'label' => null,
    'variant' => 'pill', // pill | icon | float | inline
    'size' => null,
    'message' => null,
])

@php
    $waRaw = $phone ?? \App\Models\SiteSetting::get('contact_whatsapp', \App\Models\SiteSetting::get('contact_phone', '+2252722000000'));
    $waPhone = preg_replace('/\D+/', '', $waRaw);
    $waText = $message ?? __('Bonjour SIBEA, je souhaite un devis pour...');
    $waUrl = 'https://wa.me/'.$waPhone.'?text='.urlencode($waText);

    $base = match($variant) {
        'float' => 'fixed bottom-4 right-4 z-50 inline-flex h-14 w-14 items-center justify-center transition hover:scale-105 hover:drop-shadow-[0_12px_40px_rgba(0,0,0,0.22)] drop-shadow-[0_8px_30px_rgba(0,0,0,0.18)]',
        'icon' => 'inline-flex h-9 w-9 items-center justify-center transition hover:scale-105',
        'pill' => 'inline-flex items-center gap-1.5 rounded-full bg-[#25D366] px-2.5 py-1 text-xs font-semibold text-white hover:bg-[#128C7E] transition',
        'inline' => 'inline-flex items-center gap-1.5 text-[#25D366] hover:text-[#128C7E] transition font-medium',
        default => 'inline-flex items-center gap-1.5 rounded-full bg-[#25D366] px-3 py-1.5 text-xs font-semibold text-white hover:bg-[#128C7E] transition',
    };

    $iconSize = $size ?? match($variant) {
        'float' => 'h-14 w-14',
        'icon' => 'h-9 w-9',
        'pill' => 'h-4 w-4',
        default => 'h-4 w-4',
    };
@endphp

<a href="{{ $waUrl }}" target="_blank" rel="noopener" {{ $attributes->class($base) }} aria-label="WhatsApp{{ $label ? ' — '.$label : '' }}">
    <x-whatsapp-icon :size="$iconSize" />
    @if($label)
        <span>{{ $label }}</span>
    @endif
    {{ $slot }}
</a>
