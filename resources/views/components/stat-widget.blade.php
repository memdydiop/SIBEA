@props([
    'title' => 'Leads Generated',
    'value' => '48,20',
    'suffix' => 'k',
    'icon' => 'users',
    'trend' => null,
    'trendUp' => true,
    'trendLabel' => null,
    'chartId' => null,
])

<flux:card class="">
    <x-card-body padding>
        <div class="grid grid-cols-2 gap-4 items-center">
            <div>
                <flux:heading level="5" class="text-xs font-medium uppercase tracking-wide text-zinc-500 truncate" title="{{ $title }}">
                    {{ $title }}
                </flux:heading>
                <div class="flex items-center gap-2 my-3">
                    <div class="h-10 w-10 shrink-0 rounded-full bg-zinc-100 border border-zinc-200 flex items-center justify-center">
                        <flux:icon :icon="$icon" class="size-5 text-zinc-500" />
                    </div>
                    <flux:heading level="3" size="lg" class="font-bold text-zinc-900 leading-none">
                        <span>{{ $value }}</span> 
                        <span class="text-zinc-500 font-semibold">{{ $suffix }}</span>
                    </flux:heading>
                </div>
                @if($trend !== null)
                    <p class="mb-0 text-xs text-zinc-500 flex items-center gap-1.5">
                        <span class="inline-flex items-center gap-1 font-medium {{ $trendUp ? 'text-emerald-600' : 'text-red-600' }}">
                            {{ $trend }}
                            <flux:icon :icon="$trendUp ? 'arrow-trending-up' : 'arrow-trending-down'" class="size-3.5" />
                        </span>
                        @if($trendLabel)<span class="text-zinc-500">{{ $trendLabel }}</span>@endif
                    </p>
                @elseif($trendLabel)
                    <p class="mb-0 text-xs text-zinc-500">{{ $trendLabel }}</p>
                @endif
            </div>
            <div class="text-end">
                @if($chartId)
                    <div id="{{ $chartId }}" class="min-h-[50px] -mr-2"></div>
                @else
                    <div class="min-h-[50px] flex items-center justify-end text-zinc-300">
                        <flux:icon.chart-bar class="size-12 opacity-40" />
                    </div>
                @endif
                {{ $chart ?? '' }}
            </div>
        </div>
    </x-card-body>
</flux:card>
