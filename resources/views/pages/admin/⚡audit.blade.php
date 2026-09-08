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

 <flux:table :paginate="$this->logs" container:class="rounded-xl border border-zinc-200 bg-white shadow-sm overflow-hidden">
 <flux:table.columns>
 <flux:table.column>{{ __('Date') }}</flux:table.column>
 <flux:table.column>{{ __('Acteur') }}</flux:table.column>
 <flux:table.column>{{ __('Action') }}</flux:table.column>
 <flux:table.column>{{ __('Objet') }}</flux:table.column>
 <flux:table.column>{{ __('IP') }}</flux:table.column>
 <flux:table.column>{{ __('Valeurs') }}</flux:table.column>
 </flux:table.columns>
 <flux:table.rows>
 @forelse($this->logs as $log)
 <flux:table.row class="hover:bg-zinc-50">
 <flux:table.cell>{{ $log->created_at->format('d/m/Y H:i') }}</flux:table.cell>
 <flux:table.cell>{{ $log->user?->name ?? '—' }}<div class="text-xs text-zinc-400">{{ $log->user?->email }}</div></flux:table.cell>
 <flux:table.cell><flux:badge size="sm">{{ $log->action }}</flux:badge></flux:table.cell>
 <flux:table.cell>
 <div class="text-xs">{{ Str::afterLast($log->auditable_type ?? '', '\\') }} #{{ $log->auditable_id ?? '—' }}</div>
 <div class="text-xs text-zinc-400 truncate max-w-[200px]">{{ $log->auditable_type }}</div>
 </flux:table.cell>
 <flux:table.cell>{{ $log->ip_address ?? '—' }}</flux:table.cell>
 <flux:table.cell>
 @if($log->old_values)<div class="text-red-500 truncate">{{ json_encode($log->old_values, JSON_UNESCAPED_UNICODE) }}</div>@endif
 @if($log->new_values)<div class="text-green-600 truncate">{{ json_encode($log->new_values, JSON_UNESCAPED_UNICODE) }}</div>@endif
 @if(!$log->old_values && !$log->new_values)<span class="text-zinc-400">—</span>@endif
 </flux:table.cell>
 </flux:table.row>
 @empty
 <flux:table.row><flux:table.cell align="center" colspan="6">{{ __('Aucun événement d’audit.') }}</flux:table.cell></flux:table.row>
 @endforelse
 </flux:table.rows>
 </flux:table>
 </div>
 <div class="p-4 border-t">{{ $this->logs->links() }}</div>
 </div>
</section>
