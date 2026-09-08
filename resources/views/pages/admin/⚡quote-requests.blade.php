<?php

use App\Actions\Audit\LogAuditAction;
use App\Enums\QuoteRequestStatus;
use App\Models\QuoteRequest;
use App\Models\User;
use Flux\Flux;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

new #[Title('Demandes de devis')] class extends Component {
 use WithPagination;

 public string $search = '';
 public string $statusFilter = '';
 public ?int $editingId = null;
 public string $status = '';
 public ?string $internal_notes = null;
 public ?int $assigned_to = null;
 public bool $showModal = false;

 public function mount(): void
 {
 Gate::authorize('viewAny', QuoteRequest::class);
 }

 #[Computed]
 public function quoteRequests()
 {
 return QuoteRequest::with('assignedUser')
 ->when($this->search, fn ($q) => $q->where('reference', 'ilike', "%{$this->search}%")->orWhere('email', 'ilike', "%{$this->search}%")->orWhere('first_name', 'ilike', "%{$this->search}%"))
 ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
 ->orderByDesc('created_at')
 ->paginate(15);
 }

 #[Computed]
 public function users()
 {
 return User::orderBy('name')->limit(50)->get();
 }

 public function openEdit(int $id): void
 {
 $qr = QuoteRequest::findOrFail($id);
 Gate::authorize('update', $qr);
 $this->editingId = $qr->id;
 $this->status = $qr->status->value;
 $this->internal_notes = $qr->internal_notes;
 $this->assigned_to = $qr->assigned_to;
 $this->showModal = true;
 }

 public function save(LogAuditAction $audit): void
 {
 $qr = QuoteRequest::findOrFail($this->editingId);
 Gate::authorize('update', $qr);
 $old = $qr->toArray();

 $qr->update([
 'status' => $this->status,
 'internal_notes' => $this->internal_notes,
 'assigned_to' => $this->assigned_to,
 ]);

 $audit('QUOTE_REQUEST_UPDATED', $qr, $old, $qr->toArray());
 Flux::toast(variant: 'success', text: __('Demande mise à jour.'));
 $this->showModal = false;
 $this->reset(['editingId']);
 unset($this->quoteRequests);
 }

 public function delete(int $id, LogAuditAction $audit): void
 {
 $qr = QuoteRequest::findOrFail($id);
 Gate::authorize('delete', $qr);
 $old = $qr->toArray();
 $qr->delete();
 $audit('QUOTE_REQUEST_DELETED', QuoteRequest::class, $old, null);
 Flux::toast(variant: 'success', text: __('Demande supprimée.'));
 }

 public function closeModal(): void
 {
 $this->showModal = false;
 $this->reset(['editingId']);
 }
}; ?>

<section class="w-full">
 <flux:heading size="xl" level="1">{{ __('Demandes de devis') }}</flux:heading>
 <flux:subheading class="mb-6">{{ __('CRM — flux prospect → devis (CDC 10) — statuts : Nouveau → Archivé') }}</flux:subheading>

 <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
 <div class="flex gap-2">
 <flux:input wire:model.live.debounce.300ms="search" placeholder="{{ __('Rechercher référence, email...') }}" class="max-w-sm" />
 <flux:select wire:model.live="statusFilter" class="max-w-[180px]">
 <flux:select.option value="">{{ __('Tous statuts') }}</flux:select.option>
 @foreach(\App\Enums\QuoteRequestStatus::cases() as $st)
 <flux:select.option value="{{ $st->value }}">{{ $st->label() }}</flux:select.option>
 @endforeach
 </flux:select>
 </div>
 <flux:text class="text-xs text-zinc-500">{{ __('Création via site vitrine /devis') }}</flux:text>
 </div>

 <flux:table :paginate="$this->quoteRequests" container:class="rounded-xl border border-zinc-200 bg-white shadow-sm overflow-hidden">
 <flux:table.columns>
 <flux:table.column>{{ __('Réf') }}</flux:table.column>
 <flux:table.column>{{ __('Demandeur') }}</flux:table.column>
 <flux:table.column>{{ __('Service') }}</flux:table.column>
 <flux:table.column>{{ __('Assigné') }}</flux:table.column>
 <flux:table.column align="center">{{ __('Statut') }}</flux:table.column>
 <flux:table.column align="end">{{ __('Actions') }}</flux:table.column>
 </flux:table.columns>
 <flux:table.rows>
 @forelse($this->quoteRequests as $qr)
 <flux:table.row class="hover:bg-zinc-50">
 <flux:table.cell><flux:badge size="sm">{{ $qr->reference }}</flux:badge><div class="text-xs text-zinc-500">{{ $qr->created_at->format('d/m/Y') }}</div></flux:table.cell>
 <flux:table.cell><div class="font-medium">{{ $qr->full_name }}</div><div class="text-xs text-zinc-500">{{ $qr->email }} · {{ $qr->phone }}</div></flux:table.cell>
 <flux:table.cell>{{ $qr->service_type }}</flux:table.cell>
 <flux:table.cell>{{ $qr->assignedUser?->name ?? '—' }}</flux:table.cell>
 <flux:table.cell align="center"><flux:badge size="sm">{{ $qr->status->label() }}</flux:badge></flux:table.cell>
 <flux:table.cell align="end">
 <div class="flex justify-end gap-1">
 @can('update', $qr)<flux:button variant="ghost" size="sm" icon="pencil-square" wire:click="openEdit({{ $qr->id }})" />@endcan
 @can('delete', $qr)<flux:button variant="ghost" size="sm" icon="trash" wire:click="delete({{ $qr->id }})" wire:confirm="{{ __('Supprimer ?') }}" class="text-red-500" />@endcan
 </div>
 </flux:table.cell>
 </flux:table.row>
 @empty
 <flux:table.row><flux:table.cell align="center" colspan="6">{{ __('Aucune demande.') }}</flux:table.cell></flux:table.row>
 @endforelse
 </flux:table.rows>
 </flux:table>
 </div>
 <div class="p-4 border-t">{{ $this->quoteRequests->links() }}</div>
 </div>

 <flux:modal wire:model="showModal" class="max-w-lg" @close="closeModal">
 <form wire:submit="save" class="space-y-6">
 <flux:heading size="lg">{{ __('Traiter la demande') }}</flux:heading>
 <flux:select wire:model="status" :label="__('Statut')" required>
 @foreach(\App\Enums\QuoteRequestStatus::cases() as $st)
 <flux:select.option value="{{ $st->value }}">{{ $st->label() }}</flux:select.option>
 @endforeach
 </flux:select>
 <flux:select wire:model="assigned_to" :label="__('Assigné à')">
 <flux:select.option value="">{{ __('Aucun') }}</flux:select.option>
 @foreach($this->users as $u)
 <flux:select.option value="{{ $u->id }}">{{ $u->name }}</flux:select.option>
 @endforeach
 </flux:select>
 <flux:textarea wire:model="internal_notes" :label="__('Notes internes')" rows="3" />
 <div class="flex justify-end gap-2">
 <flux:button variant="ghost" wire:click="closeModal" type="button">{{ __('Annuler') }}</flux:button>
 <flux:button variant="primary" type="submit">{{ __('Enregistrer') }}</flux:button>
 </div>
 </form>
 </flux:modal>
</section>
