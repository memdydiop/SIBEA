<?php

use App\Actions\Audit\LogAuditAction;
use App\Actions\Cms\CreateMenuAction;
use App\Actions\Cms\UpdateMenuAction;
use App\Models\Menu;
use Flux\Flux;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

new #[Title('Menus')] class extends Component {
    use WithPagination;

    public string $name = '';
    public string $slug = '';
    public string $location = 'header';

    public ?int $editingId = null;
    public bool $showModal = false;
    public string $search = '';

    public function mount(): void
    {
        Gate::authorize('viewAny', Menu::class);
    }

    #[Computed]
    public function menus()
    {
        return Menu::when($this->search, fn ($q) => $q->where('name', 'ilike', "%{$this->search}%")->orWhere('slug', 'ilike', "%{$this->search}%"))
            ->orderBy('name')
            ->paginate(15);
    }

    public function openCreate(): void
    {
        Gate::authorize('create', Menu::class);
        $this->reset(['name', 'slug', 'editingId']);
        $this->location = 'header';
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $menu = Menu::findOrFail($id);
        Gate::authorize('update', $menu);
        $this->editingId = $menu->id;
        $this->name = $menu->name;
        $this->slug = $menu->slug;
        $this->location = $menu->location;
        $this->showModal = true;
    }

    public function save(CreateMenuAction $create, UpdateMenuAction $update, LogAuditAction $audit): void
    {
        $data = [
            'name' => $this->name,
            'slug' => $this->slug ?: null,
            'location' => $this->location,
        ];

        if ($this->editingId) {
            $menu = Menu::findOrFail($this->editingId);
            Gate::authorize('update', $menu);
            $old = $menu->toArray();
            $updated = $update($menu, $data);
            $audit('MENU_UPDATED', $updated, $old, $updated->toArray());
            Flux::toast(variant: 'success', text: __('Menu mis à jour.'));
        } else {
            Gate::authorize('create', Menu::class);
            $menu = $create($data);
            $audit('MENU_CREATED', $menu, null, $menu->toArray());
            Flux::toast(variant: 'success', text: __('Menu créé.'));
        }

        $this->showModal = false;
        $this->reset(['editingId']);
        unset($this->menus);
    }

    public function delete(int $id, LogAuditAction $audit): void
    {
        $menu = Menu::findOrFail($id);
        Gate::authorize('delete', $menu);
        $old = $menu->toArray();
        $menu->delete();
        $audit('MENU_DELETED', Menu::class, $old, null);
        Flux::toast(variant: 'success', text: __('Menu supprimé.'));
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->reset(['editingId']);
    }
}; ?>

<section class="w-full">
    <flux:heading size="xl" level="1">{{ __('Menus') }}</flux:heading>
    <flux:subheading class="mb-6">{{ __('Gestion CMS vitrine — menus navigation header/footer') }}</flux:subheading>

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
        <flux:input wire:model.live.debounce.300ms="search" placeholder="{{ __('Rechercher nom ou slug...') }}" class="max-w-sm" />
        @can('create', App\Models\Menu::class)
            <flux:button variant="primary" icon="plus" wire:click="openCreate">{{ __('Nouveau menu') }}</flux:button>
        @endcan
    </div>

    <div class="border rounded-lg border-zinc-200 dark:border-zinc-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-zinc-50 dark:bg-zinc-800/50 text-zinc-500">
                    <tr>
                        <th class="text-left px-4 py-3">{{ __('Nom') }}</th>
                        <th class="text-left px-4 py-3">{{ __('Slug') }}</th>
                        <th class="text-left px-4 py-3">{{ __('Emplacement') }}</th>
                        <th class="text-right px-4 py-3">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse($this->menus as $menu)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/30">
                            <td class="px-4 py-3 font-medium">{{ $menu->name }}</td>
                            <td class="px-4 py-3"><flux:badge size="sm">{{ $menu->slug }}</flux:badge></td>
                            <td class="px-4 py-3"><flux:badge size="sm">{{ $menu->location }}</flux:badge></td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-1">
                                    @can('update', $menu)<flux:button variant="ghost" size="sm" icon="pencil-square" wire:click="openEdit({{ $menu->id }})" />@endcan
                                    @can('delete', $menu)<flux:button variant="ghost" size="sm" icon="trash" wire:click="delete({{ $menu->id }})" wire:confirm="{{ __('Supprimer ?') }}" class="text-red-500" />@endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-8 text-center text-zinc-500">{{ __('Aucun menu.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t">{{ $this->menus->links() }}</div>
    </div>

    <flux:modal wire:model="showModal" class="max-w-xl" @close="closeModal">
        <form wire:submit="save" class="space-y-6">
            <flux:heading size="lg">{{ $editingId ? __('Modifier le menu') : __('Nouveau menu') }}</flux:heading>
            <div class="grid grid-cols-1 gap-4">
                <flux:input wire:model="name" :label="__('Nom')" required placeholder="Header" />
                <flux:input wire:model="slug" :label="__('Slug (auto si vide)')" placeholder="header" />
                <flux:select wire:model="location" :label="__('Emplacement')">
                    <flux:select.option value="header">{{ __('Header') }}</flux:select.option>
                    <flux:select.option value="footer">{{ __('Footer') }}</flux:select.option>
                    <flux:select.option value="sidebar">{{ __('Sidebar') }}</flux:select.option>
                </flux:select>
            </div>
            <div class="flex justify-end gap-2">
                <flux:button variant="ghost" wire:click="closeModal" type="button">{{ __('Annuler') }}</flux:button>
                <flux:button variant="primary" type="submit">{{ $editingId ? __('Mettre à jour') : __('Créer') }}</flux:button>
            </div>
        </form>
    </flux:modal>
</section>
