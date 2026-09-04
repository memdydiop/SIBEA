<?php

use App\Models\AuditLog;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

new #[Title('Journal d’audit')] class extends Component {
    use WithPagination;

    public string $search = '';
    public string $actionFilter = '';

    public function mount(): void
    {
        Gate::authorize('viewAny', AuditLog::class);
    }

    #[Computed]
    public function logs()
    {
        return AuditLog::with(['user'])
            ->when($this->search, fn ($q) => $q->where('action', 'ilike', "%{$this->search}%")->orWhere('auditable_type', 'ilike', "%{$this->search}%"))
            ->when($this->actionFilter, fn ($q) => $q->where('action', $this->actionFilter))
            ->latest()
            ->paginate(20);
    }

    #[Computed]
    public function actions()
    {
        return AuditLog::select('action')->distinct()->orderBy('action')->pluck('action');
    }
}; ?>

<section class="w-full">
    <flux:heading size="xl" level="1">{{ __('Journal d’audit & Traçabilité') }}</flux:heading>
    <flux:subheading class="mb-6">{{ __('CDC 40 — USER_CREATED, ROLE_ASSIGNED, DEPARTMENT_*, etc. avec IP et valeurs avant/après') }}</flux:subheading>

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center mb-6">
        <flux:input wire:model.live.debounce.300ms="search" placeholder="{{ __('Rechercher action ou modèle...') }}" class="max-w-sm" />
        <flux:select wire:model.live="actionFilter" placeholder="{{ __('Filtrer par action') }}" class="max-w-xs">
            <flux:select.option value="">{{ __('Toutes les actions') }}</flux:select.option>
            @foreach($this->actions as $act)
                <flux:select.option value="{{ $act }}">{{ $act }}</flux:select.option>
            @endforeach
        </flux:select>
    </div>

    <div class="border rounded-lg border-zinc-200 dark:border-zinc-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-zinc-50 dark:bg-zinc-800/50 text-zinc-500">
                    <tr>
                        <th class="text-left px-4 py-3">{{ __('Date') }}</th>
                        <th class="text-left px-4 py-3">{{ __('Acteur') }}</th>
                        <th class="text-left px-4 py-3">{{ __('Action') }}</th>
                        <th class="text-left px-4 py-3">{{ __('Objet') }}</th>
                        <th class="text-left px-4 py-3">{{ __('IP') }}</th>
                        <th class="text-left px-4 py-3">{{ __('Valeurs') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse($this->logs as $log)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/30">
                            <td class="px-4 py-3 whitespace-nowrap text-xs">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3">{{ $log->user?->name ?? '—' }}<div class="text-xs text-zinc-400">{{ $log->user?->email }}</div></td>
                            <td class="px-4 py-3"><flux:badge size="sm">{{ $log->action }}</flux:badge></td>
                            <td class="px-4 py-3">
                                <div class="text-xs">{{ Str::afterLast($log->auditable_type ?? '', '\\') }} #{{ $log->auditable_id ?? '—' }}</div>
                                <div class="text-xs text-zinc-400 truncate max-w-[200px]">{{ $log->auditable_type }}</div>
                            </td>
                            <td class="px-4 py-3 text-xs">{{ $log->ip_address ?? '—' }}</td>
                            <td class="px-4 py-3 text-xs max-w-[260px]">
                                @if($log->old_values)<div class="text-red-500 truncate">{{ json_encode($log->old_values, JSON_UNESCAPED_UNICODE) }}</div>@endif
                                @if($log->new_values)<div class="text-green-600 truncate">{{ json_encode($log->new_values, JSON_UNESCAPED_UNICODE) }}</div>@endif
                                @if(!$log->old_values && !$log->new_values)<span class="text-zinc-400">—</span>@endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-8 text-center text-zinc-500">{{ __('Aucun événement d’audit.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t">{{ $this->logs->links() }}</div>
    </div>
</section>
