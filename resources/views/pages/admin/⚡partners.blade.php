<?php

use App\Actions\Audit\LogAuditAction;
use App\Models\Partner;
use Flux\Flux;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

new #[Title('Partenaires')] class extends Component {
 use WithPagination;

 public string $name = '';
 public ?string $logo_url = null;
 public ?string $website_url = null;
 public int $order = 0;
 public bool $is_active = true;

 public ?int $editingId = null;
 public bool $showModal = false;
 public string $search = '';

 public function mount(): void
 {
 Gate::authorize('viewAny', Partner::class);
 }

 #[Computed]
 public function partners()
 {
 return Partner::when($this->search, fn ($q) => $q->where('name', 'ilike', "%{$this->search}%"))
 ->orderBy('order')
 ->paginate(15);
 }

 public function openCreate(): void
 {
 Gate::authorize('create', Partner::class);
 $this->reset(['name', 'logo_url', 'website_url', 'editingId']);
 $this->order = 0;
 $this->is_active = true;
 $this->showModal = true;
 }

 public function openEdit(int $id): void
 {
 $p = Partner::findOrFail($id);
 Gate::authorize('update', $p);
 $this->editingId = $p->id;
 $this->name = $p->name;
 $this->logo_url = $p->logo_url;
 $this->website_url = $p->website_url;
 $this->order = $p->order;
 $this->is_active = $p->is_active;
 $this->showModal = true;
 }

 public function save(LogAuditAction $audit): void
 {
 $data = Validator::make([
 'name' => $this->name,
 'logo_url' => $this->logo_url,
 'website_url' => $this->website_url,
 'order' => $this->order,
 'is_active' => $this->is_active,
 ], [
 'name' => ['required', 'string', 'max:150'],
 'logo_url' => ['nullable', 'string', 'max:255', 'url'],
 'website_url' => ['nullable', 'string', 'max:255', 'url'],
 'order' => ['nullable', 'integer'],
 'is_active' => ['boolean'],
 ])->validate();

 if ($this->editingId) {
 $p = Partner::findOrFail($this->editingId);
 Gate::authorize('update', $p);
 $old = $p->toArray();
 $p->update($data);
 $audit('PARTNER_UPDATED', $p, $old, $p->toArray());
 Flux::toast(variant: 'success', text: __('Partenaire mis à jour.'));
 } else {
 Gate::authorize('create', Partner::class);
 $p = Partner::create($data);
 $audit('PARTNER_CREATED', $p, null, $p->toArray());
 Flux::toast(variant: 'success', text: __('Partenaire créé.'));
 }

 $this->showModal = false;
 $this->reset(['editingId']);
 unset($this->partners);
 }

 public function delete(int $id, LogAuditAction $audit): void
 {
 $p = Partner::findOrFail($id);
 Gate::authorize('delete', $p);
 $old = $p->toArray();
 $p->delete();
 $audit('PARTNER_DELETED', Partner::class, $old, null);
 Flux::toast(variant: 'success', text: __('Partenaire supprimé.'));
 }

 public function closeModal(): void
 {
 $this->showModal = false;
 $this->reset(['editingId']);
 }
}; ?>

<section class="w-full">
 <flux:heading size="xl" level="1">{{ __('Partenaires') }}</flux:heading>
 <flux:subheading class="mb-6">{{ __('Logos et références vitrine') }}</flux:subheading>

 <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
 <flux:input wire:model.live.debounce.300ms="search" placeholder="{{ __('Rechercher...') }}" class="max-w-sm" />
 @can('create', App\Models\Partner::class)
 <flux:button variant="primary" icon="plus" wire:click="openCreate">{{ __('Nouveau partenaire') }}</flux:button>
 @endcan
 </div>

 <flux:table :paginate="$this->partners" container:class="rounded-xl border border-zinc-200 bg-white shadow-sm overflow-hidden">
 <flux:table.columns>
 <flux:table.column>{{ __('Nom') }}</flux:table.column>
 <flux:table.column>{{ __('Site') }}</flux:table.column>
 <flux:table.column align="center">{{ __('Ordre') }}</flux:table.column>
 <flux:table.column align="center">{{ __('Actif') }}</flux:table.column>
 <flux:table.column align="end">{{ __('Actions') }}</flux:table.column>
 </flux:table.columns>
 <flux:table.rows>
 @forelse($this->partners as $p)
 <flux:table.row>
 <flux:table.cell>{{ $p->name }}</flux:table.cell>
 <flux:table.cell>{{ $p->website_url ?? '—' }}</flux:table.cell>
 <flux:table.cell align="center">{{ $p->order }}</flux:table.cell>
 <flux:table.cell align="center">@if($p->is_active)<flux:badge variant="success" size="sm">{{ __('Oui') }}</flux:badge>@else<flux:badge variant="zinc" size="sm">{{ __('Non') }}</flux:badge>@endif</flux:table.cell>
 <flux:table.cell align="end">
 <div class="flex justify-end gap-1">
 @can('update', $p)<flux:button variant="ghost" size="sm" icon="pencil-square" wire:click="openEdit({{ $p->id }})" />@endcan
 @can('delete', $p)<flux:button variant="ghost" size="sm" icon="trash" wire:click="delete({{ $p->id }})" wire:confirm="{{ __('Supprimer ?') }}" class="text-red-500" />@endcan
 </div>
 </flux:table.cell>
 </flux:table.row>
 @empty
 <flux:table.row><flux:table.cell align="center" colspan="5">{{ __('Aucun partenaire.') }}</flux:table.cell></flux:table.row>
 @endforelse
 </flux:table.rows>
 </flux:table>
 </div>
 <div class="p-4 border-t">{{ $this->partners->links() }}</div>
 </div>

 <flux:modal wire:model="showModal" class="max-w-lg" @close="closeModal">
 <form wire:submit="save" class="space-y-6">
 <flux:heading size="lg">{{ $editingId ? __('Modifier') : __('Nouveau partenaire') }}</flux:heading>
 <flux:input wire:model="name" :label="__('Nom')" required />
 <flux:input wire:model="website_url" :label="__('Site web')" placeholder="https://" />
 <flux:input wire:model="logo_url" :label="__('Logo URL')" />
 <flux:input wire:model="order" type="number" :label="__('Ordre')" />
 <flux:checkbox wire:model="is_active" :label="__('Actif')" />
 <div class="flex justify-end gap-2">
 <flux:button variant="ghost" wire:click="closeModal" type="button">{{ __('Annuler') }}</flux:button>
 <flux:button variant="primary" type="submit">{{ $editingId ? __('Mettre à jour') : __('Créer') }}</flux:button>
 </div>
 </form>
 </flux:modal>
</section>
