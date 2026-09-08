@props([
    'searchPlaceholder' => 'Search project name...',
    'searchModel' => 'search',
    'statusFilter' => null, // wire:model name for status
    'statusOptions' => [
        'All' => 'Status',
        'In Progress' => 'In Progress',
        'Pending Review' => 'Pending Review',
        'Overdue' => 'Overdue',
        'In Review' => 'In Review',
        'Completed' => 'Completed',
        'Scheduled' => 'Scheduled',
        'On Hold' => 'On Hold',
        'Pending' => 'Pending',
    ],
    'deadlineFilter' => null,
    'deadlineOptions' => [
        'All' => 'Deadline',
        'Today' => 'Today',
        'Last 7 Days' => 'Last 7 Days',
        'Last 30 Days' => 'Last 30 Days',
        'This Year' => 'This Year',
    ],
    'perPageModel' => null,
    'perPageOptions' => [5, 8, 10, 15, 20],
    'perPage' => 15,
    'showDelete' => false,
    'deleteLabel' => 'Delete',
])

<div
    {{ $attributes->class('flex flex-wrap items-center justify-between gap-3 px-4 py-2 bg-white border-b border-zinc-200') }}>
    {{-- Left: Search + Delete --}}
    <div class="flex items-center gap-2">
        <div class="relative">
            <flux:icon.magnifying-glass
                class="absolute left-2.5 top-1/2 -translate-y-1/2 size-4 text-zinc-400 pointer-events-none" />
            <flux:input size="sm" :placeholder="$searchPlaceholder" wire:model.live.debounce.300ms="{{ $searchModel }}"
                class="pl-8 w-64 border rounded" />
        </div>

        @if ($showDelete)
            <flux:button variant="danger" size="sm" class="hidden" data-table-delete-selected>
                {{ $deleteLabel }}
            </flux:button>
        @endif
        {{ $slot }}
    </div>

    {{-- Center/Right: Filters + PerPage + View Toggle --}}
    <div class="flex flex-wrap items-center gap-2 ml-auto">
        <span class="hidden sm:inline text-xs font-semibold text-zinc-500 mr-1">{{ __('Filter By:') }}</span>

        @if ($statusFilter !== null)
            <div class="relative">
                <flux:select size="sm" wire:model.live="{{ $statusFilter }}" class="min-w-40 pl-8">
                    @foreach ($statusOptions as $value => $label)
                        <flux:select.option value="{{ $value }}">{{ $label }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:icon.tag
                    class="absolute left-2.5 top-1/2 -translate-y-1/2 size-4 text-zinc-400 pointer-events-none" />
            </div>
        @endif

        @if ($deadlineFilter !== null)
            <div class="relative">
                <flux:select size="sm" wire:model.live="{{ $deadlineFilter }}" class="min-w-40 pl-8">
                    @foreach ($deadlineOptions as $value => $label)
                        <flux:select.option value="{{ $value }}">{{ $label }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:icon.calendar
                    class="absolute left-2.5 top-1/2 -translate-y-1/2 size-4 text-zinc-400 pointer-events-none" />
            </div>
        @endif

        @if ($perPageModel !== null)
            <flux:select size="sm" wire:model.live="{{ $perPageModel }}" class="w-20">
                @foreach ($perPageOptions as $opt)
                    <flux:select.option value="{{ $opt }}">{{ $opt }}</flux:select.option>
                @endforeach
            </flux:select>
        @elseif(!empty($perPageOptions))
            <flux:select size="sm" value="{{ $perPage }}" data-table-set-rows-per-page class="w-20">
                @foreach ($perPageOptions as $opt)
                    <flux:select.option value="{{ $opt }}">{{ $opt }}</flux:select.option>
                @endforeach
            </flux:select>
        @endif

        @if (isset($actions))
            <div class="flex items-center gap-2">
                {{ $actions }}
            </div>
        @endif
    </div>
</div>
