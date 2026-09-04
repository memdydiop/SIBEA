<?php

use App\Actions\Audit\LogAuditAction;
use App\Actions\Cms\CreateServiceAction;
use App\Actions\Cms\UpdateServiceAction;
use App\Models\Expertise;
use App\Models\Service;
use Flux\Flux;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

new #[Title('Services')] class extends Component {
    use WithPagination;

    public ?int $expertise_id = null;
    public string $slug = '';
    public string $title = '';
    public ?string $excerpt = null;
    public ?string $content = null;
    public ?string $icon = null;
    public ?string $meta_title = null;
    public ?string $meta_description = null;
    public int $order = 0;
    public bool $is_active = true;

    public ?int $editingId = null;
    public bool $showModal = false;
    public string $search = '';

    public function mount(): void
    {
        Gate::authorize('viewAny', Service::class);
    }

    #[Computed]
    public function services()
    {
        return Service::with('expertise')
            ->when($this->search, fn ($q) => $q->where('title', 'ilike', "%{$this->search}%"))
            ->orderBy('order')
            ->paginate(15);
    }

    #[Computed]
    public function expertises()
    {
        return Expertise::active()->get();
    }

    public function openCreate(): void
    {
        Gate::authorize('create', Service::class);
        $this->reset(['slug', 'title', 'excerpt', 'content', 'icon', 'editingId']);
        $this->expertise_id = $this->expertises->first()?->id;
        $this->order = 0;
        $this->is_active = true;
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $s = Service::findOrFail($id);
        Gate::authorize('update', $s);
        $this->editingId = $s->id;
        $this->expertise_id = $s->expertise_id;
        $this->slug = $s->slug;
        $this->title = $s->title;
        $this->excerpt = $s->excerpt;
        $this->content = $s->content;
        $this->icon = $s->icon;
        $this->order = $s->order;
        $this->is_active = $s->is_active;
        $this->showModal = true;
    }

    public function save(CreateServiceAction $create, UpdateServiceAction $update, LogAuditAction $audit): void
    {
        $data = [
            'expertise_id' => $this->expertise_id,
            'slug' => $this->slug ?: null,
            'title' => $this->title,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'icon' => $this->icon,
            'order' => $this->order,
            'is_active' => $this->is_active,
        ];

        if ($this->editingId) {
            $s = Service::findOrFail($this->editingId);
            Gate::authorize('update', $s);
            $old = $s->toArray();
            $updated = $update($s, $data);
            $audit('SERVICE_UPDATED', $updated, $old, $updated->toArray());
            Flux::toast(variant: 'success', text: __('Service mis à jour.'));
        } else {
            Gate::authorize('create', Service::class);
            $s = $create($data);
            $audit('SERVICE_CREATED', $s, null, $s->toArray());
            Flux::toast(variant: 'success', text: __('Service créé.'));
        }

        $this->showModal = false;
        $this->reset(['editingId']);
        unset($this->services);
    }

    public function delete(int $id, LogAuditAction $audit): void
    {
        $s = Service::findOrFail($id);
        Gate::authorize('delete', $s);
        $old = $s->toArray();
        $s->delete();
        $audit('SERVICE_DELETED', Service::class, $old, null);
        Flux::toast(variant: 'success', text: __('Service supprimé.'));
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->reset(['editingId']);
    }
}; ?>

<section class="w-full">
    <flux:heading size="xl" level="1">{{ __('Services') }}</flux:heading>
    <flux:subheading class="mb-6">{{ __('Services liés aux expertises') }}</flux:subheading>

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
        <flux:input wire:model.live.debounce.300ms="search" placeholder="{{ __('Rechercher...') }}" class="max-w-sm" />
        @can('create', App\Models\Service::class)
            <flux:button variant="primary" icon="plus" wire:click="openCreate">{{ __('Nouveau service') }}</flux:button>
        @endcan
    </div>

    <div class="border rounded-lg border-zinc-200 dark:border-zinc-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-zinc-50 dark:bg-zinc-800/50 text-zinc-500">
                    <tr>
                        <th class="text-left px-4 py-3">{{ __('Titre') }}</th>
                        <th class="text-left px-4 py-3">{{ __('Expertise') }}</th>
                        <th class="text-left px-4 py-3">{{ __('Slug') }}</th>
                        <th class="text-center px-4 py-3">{{ __('Actif') }}</th>
                        <th class="text-right px-4 py-3">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse($this->services as $s)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/30">
                            <td class="px-4 py-3 font-medium">{{ $s->title }}</td>
                            <td class="px-4 py-3">{{ $s->expertise?->title ?? '—' }}</td>
                            <td class="px-4 py-3"><flux:badge size="sm">{{ $s->slug }}</flux:badge></td>
                            <td class="px-4 py-3 text-center">@if($s->is_active)<flux:badge variant="success" size="sm">{{ __('Oui') }}</flux:badge>@else<flux:badge variant="danger" size="sm">{{ __('Non') }}</flux:badge>@endif</td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-1">
                                    @can('update', $s)<flux:button variant="ghost" size="sm" icon="pencil-square" wire:click="openEdit({{ $s->id }})" />@endcan
                                    @can('delete', $s)<flux:button variant="ghost" size="sm" icon="trash" wire:click="delete({{ $s->id }})" wire:confirm="{{ __('Supprimer ?') }}" class="text-red-500" />@endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-8 text-center text-zinc-500">{{ __('Aucun service.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t">{{ $this->services->links() }}</div>
    </div>

    <flux:modal wire:model="showModal" class="max-w-2xl" @close="closeModal">
        <form wire:submit="save" class="space-y-6">
            <flux:heading size="lg">{{ $editingId ? __('Modifier le service') : __('Nouveau service') }}</flux:heading>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <flux:select wire:model="expertise_id" :label="__('Expertise')">
                    <flux:select.option value="">{{ __('Aucune') }}</flux:select.option>
                    @foreach($this->expertises as $exp)
                        <flux:select.option value="{{ $exp->id }}">{{ $exp->title }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:input wire:model="order" type="number" :label="__('Ordre')" />
                <flux:input wire:model="title" :label="__('Titre')" required class="sm:col-span-2" />
                <flux:input wire:model="slug" :label="__('Slug (auto)')" placeholder="gros-oeuvre" />
                <flux:input wire:model="icon" :label="__('Icône')" />
                <flux:checkbox wire:model="is_active" :label="__('Actif')" />
                <div class="sm:col-span-2"><flux:input wire:model="meta_title" :label="__('Meta titre')" />
                <flux:input wire:model="meta_description" :label="__('Meta description')" />
                <flux:textarea wire:model="excerpt" :label="__('Extrait')" rows="2" /></div>
                <div class="sm:col-span-2"><flux:textarea wire:model="content" :label="__('Contenu')" rows="4" /></div>
            </div>
            <div class="flex justify-end gap-2">
                <flux:button variant="ghost" wire:click="closeModal" type="button">{{ __('Annuler') }}</flux:button>
                <flux:button variant="primary" type="submit">{{ $editingId ? __('Mettre à jour') : __('Créer') }}</flux:button>
            </div>
        </form>
    </flux:modal>
</section>
