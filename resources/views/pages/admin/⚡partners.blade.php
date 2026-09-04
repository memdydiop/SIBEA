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

    <div class="border rounded-lg border-zinc-200 dark:border-zinc-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-zinc-50 dark:bg-zinc-800/50 text-zinc-500">
                    <tr>
                        <th class="text-left px-4 py-3">{{ __('Nom') }}</th>
                        <th class="text-left px-4 py-3">{{ __('Site') }}</th>
                        <th class="text-center px-4 py-3">{{ __('Ordre') }}</th>
                        <th class="text-center px-4 py-3">{{ __('Actif') }}</th>
                        <th class="text-right px-4 py-3">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse($this->partners as $p)
                        <tr>
                            <td class="px-4 py-3 font-medium">{{ $p->name }}</td>
                            <td class="px-4 py-3 text-xs text-zinc-500">{{ $p->website_url ?? '—' }}</td>
                            <td class="px-4 py-3 text-center">{{ $p->order }}</td>
                            <td class="px-4 py-3 text-center">@if($p->is_active)<flux:badge variant="success" size="sm">{{ __('Oui') }}</flux:badge>@else<flux:badge variant="zinc" size="sm">{{ __('Non') }}</flux:badge>@endif</td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-1">
                                    @can('update', $p)<flux:button variant="ghost" size="sm" icon="pencil-square" wire:click="openEdit({{ $p->id }})" />@endcan
                                    @can('delete', $p)<flux:button variant="ghost" size="sm" icon="trash" wire:click="delete({{ $p->id }})" wire:confirm="{{ __('Supprimer ?') }}" class="text-red-500" />@endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-8 text-center text-zinc-500">{{ __('Aucun partenaire.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
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
