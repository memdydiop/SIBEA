<?php

use App\Actions\Audit\LogAuditAction;
use App\Actions\Cms\CreateMenuItemAction;
use App\Actions\Cms\UpdateMenuItemAction;
use App\Models\Menu;
use App\Models\MenuItem;
use Flux\Flux;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

new #[Title('Éléments de menu')] class extends Component {
    use WithPagination;

    public ?int $menu_id = null;
    public ?int $parent_id = null;
    public string $label = '';
    public string $url = '';
    public string $target = '_self';
    public int $order = 0;
    public bool $is_active = true;

    public ?int $editingId = null;
    public bool $showModal = false;
    public string $search = '';
    public ?int $filterMenuId = null;

    public function mount(): void
    {
        Gate::authorize('viewAny', MenuItem::class);
    }

    #[Computed]
    public function menus()
    {
        return Menu::orderBy('name')->get();
    }

    #[Computed]
    public function parentItems()
    {
        if (! $this->menu_id) {
            return collect();
        }

        return MenuItem::where('menu_id', $this->menu_id)
            ->when($this->editingId, fn ($q) => $q->where('id', '!=', $this->editingId))
            ->orderBy('label')
            ->get();
    }

    #[Computed]
    public function menuItems()
    {
        return MenuItem::with(['menu', 'parent'])
            ->when($this->filterMenuId, fn ($q) => $q->where('menu_id', $this->filterMenuId))
            ->when($this->search, fn ($q) => $q->where('label', 'ilike', "%{$this->search}%")->orWhere('url', 'ilike', "%{$this->search}%"))
            ->orderBy('order')
            ->paginate(15);
    }

    public function openCreate(): void
    {
        Gate::authorize('create', MenuItem::class);
        $this->reset(['label', 'url', 'parent_id', 'editingId']);
        $this->menu_id = $this->filterMenuId ?? $this->menus->first()?->id;
        $this->target = '_self';
        $this->order = 0;
        $this->is_active = true;
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $item = MenuItem::findOrFail($id);
        Gate::authorize('update', $item);
        $this->editingId = $item->id;
        $this->menu_id = $item->menu_id;
        $this->parent_id = $item->parent_id;
        $this->label = $item->label;
        $this->url = $item->url;
        $this->target = $item->target;
        $this->order = $item->order;
        $this->is_active = $item->is_active;
        $this->showModal = true;
    }

    public function save(CreateMenuItemAction $create, UpdateMenuItemAction $update, LogAuditAction $audit): void
    {
        $data = [
            'menu_id' => $this->menu_id,
            'parent_id' => $this->parent_id,
            'label' => $this->label,
            'url' => $this->url,
            'target' => $this->target,
            'order' => $this->order,
            'is_active' => $this->is_active,
        ];

        if ($this->editingId) {
            $item = MenuItem::findOrFail($this->editingId);
            Gate::authorize('update', $item);
            $old = $item->toArray();
            $updated = $update($item, $data);
            $audit('MENU_ITEM_UPDATED', $updated, $old, $updated->toArray());
            Flux::toast(variant: 'success', text: __('Élément mis à jour.'));
        } else {
            Gate::authorize('create', MenuItem::class);
            $item = $create($data);
            $audit('MENU_ITEM_CREATED', $item, null, $item->toArray());
            Flux::toast(variant: 'success', text: __('Élément créé.'));
        }

        $this->showModal = false;
        $this->reset(['editingId']);
        unset($this->menuItems);
    }

    public function delete(int $id, LogAuditAction $audit): void
    {
        $item = MenuItem::findOrFail($id);
        Gate::authorize('delete', $item);
        $old = $item->toArray();
        $item->delete();
        $audit('MENU_ITEM_DELETED', MenuItem::class, $old, null);
        Flux::toast(variant: 'success', text: __('Élément supprimé.'));
    }

    public function moveUp(int $id): void
    {
        $item = MenuItem::findOrFail($id);
        Gate::authorize('update', $item);
        $prev = MenuItem::where('menu_id', $item->menu_id)->where('order', '<', $item->order)->orderByDesc('order')->first();
        if ($prev) {
            [$item->order, $prev->order] = [$prev->order, $item->order];
            $item->save();
            $prev->save();
            unset($this->menuItems);
        }
    }

    public function moveDown(int $id): void
    {
        $item = MenuItem::findOrFail($id);
        Gate::authorize('update', $item);
        $next = MenuItem::where('menu_id', $item->menu_id)->where('order', '>', $item->order)->orderBy('order')->first();
        if ($next) {
            [$item->order, $next->order] = [$next->order, $item->order];
            $item->save();
            $next->save();
            unset($this->menuItems);
        }
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->reset(['editingId']);
    }

    public function updatingFilterMenuId(): void
    {
        $this->resetPage();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }
}; ?>

<section class="w-full">
    <flux:heading size="xl" level="1">{{ __('Éléments de menu') }}</flux:heading>
    <flux:subheading class="mb-6">{{ __('Gestion des liens de navigation — header, footer et menus personnalisés') }}</flux:subheading>

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
        <div class="flex flex-col sm:flex-row gap-2 w-full max-w-xl">
            <flux:select wire:model.live="filterMenuId" placeholder="{{ __('Filtrer par menu') }}" class="sm:w-48">
                <flux:select.option value="">{{ __('Tous les menus') }}</flux:select.option>
                @foreach($this->menus as $menu)
                    <flux:select.option value="{{ $menu->id }}">{{ $menu->name }} ({{ $menu->slug }})</flux:select.option>
                @endforeach
            </flux:select>
            <flux:input wire:model.live.debounce.300ms="search" placeholder="{{ __('Rechercher label ou URL...') }}" class="flex-1" />
        </div>
        @can('create', App\Models\MenuItem::class)
            <flux:button variant="primary" icon="plus" wire:click="openCreate">{{ __('Nouvel élément') }}</flux:button>
        @endcan
    </div>

    <div class="border rounded-lg border-zinc-200 dark:border-zinc-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-zinc-50 dark:bg-zinc-800/50 text-zinc-500">
                    <tr>
                        <th class="text-left px-4 py-3">{{ __('Ordre') }}</th>
                        <th class="text-left px-4 py-3">{{ __('Label') }}</th>
                        <th class="text-left px-4 py-3">{{ __('Menu') }}</th>
                        <th class="text-left px-4 py-3">{{ __('URL') }}</th>
                        <th class="text-left px-4 py-3">{{ __('Parent') }}</th>
                        <th class="text-center px-4 py-3">{{ __('Cible') }}</th>
                        <th class="text-center px-4 py-3">{{ __('Actif') }}</th>
                        <th class="text-right px-4 py-3">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse($this->menuItems as $item)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/30">
                            <td class="px-4 py-3">{{ $item->order }}</td>
                            <td class="px-4 py-3 font-medium">{{ $item->label }}</td>
                            <td class="px-4 py-3"><flux:badge size="sm">{{ $item->menu?->name ?? '—' }}</flux:badge></td>
                            <td class="px-4 py-3 text-zinc-600">{{ $item->url }}</td>
                            <td class="px-4 py-3">{{ $item->parent?->label ?? '—' }}</td>
                            <td class="px-4 py-3 text-center"><flux:badge size="sm">{{ $item->target }}</flux:badge></td>
                            <td class="px-4 py-3 text-center">@if($item->is_active)<flux:badge variant="success" size="sm">{{ __('Oui') }}</flux:badge>@else<flux:badge variant="danger" size="sm">{{ __('Non') }}</flux:badge>@endif</td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-1">
                                    @can('update', $item)
                                        <flux:button variant="ghost" size="sm" icon="arrow-up" wire:click="moveUp({{ $item->id }})" />
                                        <flux:button variant="ghost" size="sm" icon="arrow-down" wire:click="moveDown({{ $item->id }})" />
                                        <flux:button variant="ghost" size="sm" icon="pencil-square" wire:click="openEdit({{ $item->id }})" />
                                    @endcan
                                    @can('delete', $item)<flux:button variant="ghost" size="sm" icon="trash" wire:click="delete({{ $item->id }})" wire:confirm="{{ __('Supprimer ?') }}" class="text-red-500" />@endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="px-4 py-8 text-center text-zinc-500">{{ __('Aucun élément.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t">{{ $this->menuItems->links() }}</div>
    </div>

    <flux:modal wire:model="showModal" class="max-w-2xl" @close="closeModal">
        <form wire:submit="save" class="space-y-6">
            <flux:heading size="lg">{{ $editingId ? __('Modifier l’élément') : __('Nouvel élément') }}</flux:heading>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <flux:select wire:model="menu_id" :label="__('Menu')" required>
                    @foreach($this->menus as $menu)
                        <flux:select.option value="{{ $menu->id }}">{{ $menu->name }} ({{ $menu->slug }})</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:select wire:model="parent_id" :label="__('Parent (optionnel)')">
                    <flux:select.option value="">{{ __('Aucun — élément racine') }}</flux:select.option>
                    @foreach($this->parentItems as $parent)
                        <flux:select.option value="{{ $parent->id }}">{{ $parent->label }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:input wire:model="label" :label="__('Label')" required placeholder="Accueil" />
                <flux:input wire:model="url" :label="__('URL')" required placeholder="/expertises" />
                <flux:select wire:model="target" :label="__('Cible')">
                    <flux:select.option value="_self">{{ __('Même onglet (_self)') }}</flux:select.option>
                    <flux:select.option value="_blank">{{ __('Nouvel onglet (_blank)') }}</flux:select.option>
                </flux:select>
                <flux:input wire:model="order" type="number" :label="__('Ordre')" />
                <div class="flex items-end pb-2">
                    <flux:checkbox wire:model="is_active" :label="__('Actif')" />
                </div>
            </div>
            <div class="flex justify-end gap-2">
                <flux:button variant="ghost" wire:click="closeModal" type="button">{{ __('Annuler') }}</flux:button>
                <flux:button variant="primary" type="submit">{{ $editingId ? __('Mettre à jour') : __('Créer') }}</flux:button>
            </div>
        </form>
    </flux:modal>
</section>
