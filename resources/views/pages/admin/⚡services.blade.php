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
    public string $sortBy = 'order';
    public string $sortDirection = 'asc';
    public string $statusFilter = '';

    public function sort(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function mount(): void
    {
        Gate::authorize('viewAny', Service::class);
    }

    #[Computed]
    public function services()
    {
        return Service::with('expertise')
            ->when($this->search, fn($q) => $q->where('title', 'ilike', "%{$this->search}%"))
            ->when($this->statusFilter === 'active', fn($q) => $q->where('is_active', true))
            ->when($this->statusFilter === 'inactive', fn($q) => $q->where('is_active', false))
            ->tap(fn($q) => $this->sortBy ? $q->orderBy($this->sortBy, $this->sortDirection) : $q)
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
        $this->reset(['slug', 'title', 'excerpt', 'content', 'icon', 'meta_title', 'meta_description', 'editingId']);
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
        $this->meta_title = $s->meta_title;
        $this->meta_description = $s->meta_description;
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
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
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
    @php
        $totalServices = \App\Models\Service::count();
        $activeServices = \App\Models\Service::where('is_active', true)->count();
        $totalExpertises = \App\Models\Expertise::count();
    @endphp
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <x-stat-widget title="Total" :value="$totalServices" suffix=" services" icon="wrench-screwdriver"
            trendLabel="Tous services" />
        <x-stat-widget title="Actifs" :value="$activeServices" :suffix="' / ' . $totalServices" icon="check-circle" :trend="$totalServices > 0 ? round(($activeServices / $totalServices) * 100, 1) . '%' : '0%'"
            :trendUp="true" trendLabel="Visibles vitrine" />
        <x-stat-widget title="Expertises" :value="$totalExpertises" suffix=" expertises" icon="academic-cap"
            trendLabel="Liées" />
    </div>

    <flux:card class="!p-0 overflow-hidden">
        <x-card-header title="Services" subtitle="Services liés aux expertises">
            <x-slot:actions>
                @can('create', App\Models\Service::class)
                    <flux:button variant="primary" size="sm" icon="plus" wire:click="openCreate">
                        {{ __('Nouveau service') }}</flux:button>
                @endcan
            </x-slot:actions>
        </x-card-header>

        <x-table-toolbar searchPlaceholder="Search service..." searchModel="search" statusFilter="statusFilter"
            :statusOptions="['All' => 'Status', 'active' => 'Actifs', 'inactive' => 'Inactifs']" :perPageOptions="[5, 10, 15, 20]" :gridUrl="route('admin.services')" :listUrl="route('admin.services')" />

        <flux:table :paginate="$this->services">
            <flux:table.columns>
                <flux:table.column sortable :sorted="$sortBy === 'title'" :direction="$sortDirection"
                    wire:click="sort('title')">{{ __('Titre') }}</flux:table.column>
                <flux:table.column sortable :sorted="$sortBy === 'expertise_id'" :direction="$sortDirection"
                    wire:click="sort('expertise_id')">{{ __('Expertise') }}</flux:table.column>
                <flux:table.column sortable :sorted="$sortBy === 'slug'" :direction="$sortDirection"
                    wire:click="sort('slug')">{{ __('Slug') }}</flux:table.column>
                <flux:table.column align="center" sortable :sorted="$sortBy === 'is_active'" :direction="$sortDirection"
                    wire:click="sort('is_active')">{{ __('Actif') }}
                </flux:table.column>
                <flux:table.column align="end">{{ __('Actions') }}</flux:table.column>
            </flux:table.columns>
            <flux:table.rows>
                @forelse($this->services as $s)
                    <flux:table.row class="hover:bg-zinc-50">
                        <flux:table.cell>{{ $s->title }}</flux:table.cell>
                        <flux:table.cell>{{ $s->expertise?->title ?? '—' }}</flux:table.cell>
                        <flux:table.cell>
                            <flux:badge size="sm">{{ $s->slug }}</flux:badge>
                        </flux:table.cell>
                        <flux:table.cell align="center">
                            @if ($s->is_active)
                                <flux:badge variant="success" size="sm">{{ __('Oui') }}</flux:badge>
                            @else
                                <flux:badge variant="danger" size="sm">{{ __('Non') }}</flux:badge>
                            @endif
                        </flux:table.cell>
                        <flux:table.cell align="end">
                            <div class="flex justify-end gap-1">
                                @can('update', $s)
                                    <flux:button variant="ghost" size="sm" icon="pencil-square"
                                        wire:click="openEdit({{ $s->id }})" />
                                @endcan
                                @can('delete', $s)
                                    <flux:button variant="ghost" size="sm" icon="trash"
                                        wire:click="delete({{ $s->id }})" wire:confirm="{{ __('Supprimer ?') }}"
                                        class="text-red-500" />
                                @endcan
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell align="center" colspan="5">{{ __('Aucun service.') }}</flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </flux:card>

    <flux:modal wire:model="showModal" class="max-w-2xl" @close="closeModal">
        <form wire:submit="save" class="space-y-6">
            <flux:heading size="lg">{{ $editingId ? __('Modifier le service') : __('Nouveau service') }}
            </flux:heading>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <flux:select wire:model="expertise_id" :label="__('Expertise')">
                    <flux:select.option value="">{{ __('Aucune') }}</flux:select.option>
                    @foreach ($this->expertises as $exp)
                        <flux:select.option value="{{ $exp->id }}">{{ $exp->title }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:input wire:model="order" type="number" :label="__('Ordre')" />
                <flux:input wire:model="title" :label="__('Titre')" required class="sm:col-span-2" />
                <flux:input wire:model="slug" :label="__('Slug (auto)')" placeholder="gros-oeuvre" />
                <flux:input wire:model="icon" :label="__('Icône')" />
                <flux:checkbox wire:model="is_active" :label="__('Actif')" />
                <div class="sm:col-span-2">
                    <flux:input wire:model="meta_title" :label="__('Meta titre')" />
                    <flux:input wire:model="meta_description" :label="__('Meta description')" />
                    <flux:textarea wire:model="excerpt" :label="__('Extrait')" rows="2" />
                </div>
                <div class="sm:col-span-2">
                    <flux:textarea wire:model="content" :label="__('Contenu')" rows="4" />
                </div>
            </div>
            <div class="flex justify-end gap-2">
                <flux:button variant="ghost" wire:click="closeModal" type="button">{{ __('Annuler') }}</flux:button>
                <flux:button variant="primary" type="submit">{{ $editingId ? __('Mettre à jour') : __('Créer') }}
                </flux:button>
            </div>
        </form>
    </flux:modal>
</section>
