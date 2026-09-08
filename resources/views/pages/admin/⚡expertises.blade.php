<?php

use App\Actions\Audit\LogAuditAction;
use App\Actions\Cms\CreateExpertiseAction;
use App\Actions\Cms\UpdateExpertiseAction;
use App\Models\Expertise;
use Flux\Flux;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

new #[Title('Expertises')] class extends Component {
    use WithPagination;
    use WithFileUploads;

    public string $slug = '';
    public string $title = '';
    public ?string $excerpt = null;
    public ?string $content = null;
    public ?string $icon = null;
    public ?string $cover_image = null;
    public ?string $meta_title = null;
    public ?string $meta_description = null;
    public $cover_image_upload = null;
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
        Gate::authorize('viewAny', Expertise::class);
    }

    #[Computed]
    public function expertises()
    {
        return Expertise::withCount('services')
            ->when($this->search, fn($q) => $q->where('title', 'ilike', "%{$this->search}%")->orWhere('slug', 'ilike', "%{$this->search}%"))
            ->when($this->statusFilter === 'active', fn($q) => $q->where('is_active', true))
            ->when($this->statusFilter === 'inactive', fn($q) => $q->where('is_active', false))
            ->tap(fn($q) => $this->sortBy ? $q->orderBy($this->sortBy, $this->sortDirection) : $q)
            ->paginate(15);
    }

    public function openCreate(): void
    {
        Gate::authorize('create', Expertise::class);
        $this->reset(['slug', 'title', 'excerpt', 'content', 'icon', 'cover_image', 'meta_title', 'meta_description', 'cover_image_upload', 'editingId']);
        $this->order = 0;
        $this->is_active = true;
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $exp = Expertise::findOrFail($id);
        Gate::authorize('update', $exp);
        $this->editingId = $exp->id;
        $this->slug = $exp->slug;
        $this->title = $exp->title;
        $this->excerpt = $exp->excerpt;
        $this->content = $exp->content;
        $this->icon = $exp->icon;
        $this->meta_title = $exp->meta_title;
        $this->meta_description = $exp->meta_description;
        $this->cover_image = $exp->cover_image;
        $this->order = $exp->order;
        $this->is_active = $exp->is_active;
        $this->showModal = true;
    }

    public function save(CreateExpertiseAction $create, UpdateExpertiseAction $update, LogAuditAction $audit): void
    {
        if ($this->cover_image_upload) {
            $this->cover_image = $this->cover_image_upload->store('cms/expertises', 'public');
        }

        $data = [
            'slug' => $this->slug ?: null,
            'title' => $this->title,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'icon' => $this->icon,
            'cover_image' => $this->cover_image ? (str_starts_with($this->cover_image, 'cms/') ? '/storage/' . $this->cover_image : $this->cover_image) : null,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'order' => $this->order,
            'is_active' => $this->is_active,
        ];

        if ($this->editingId) {
            $exp = Expertise::findOrFail($this->editingId);
            Gate::authorize('update', $exp);
            $old = $exp->toArray();
            $updated = $update($exp, $data);
            $audit('EXPERTISE_UPDATED', $updated, $old, $updated->toArray());
            Flux::toast(variant: 'success', text: __('Expertise mise à jour.'));
        } else {
            Gate::authorize('create', Expertise::class);
            $exp = $create($data);
            $audit('EXPERTISE_CREATED', $exp, null, $exp->toArray());
            Flux::toast(variant: 'success', text: __('Expertise créée.'));
        }

        $this->showModal = false;
        $this->reset(['editingId']);
        unset($this->expertises);
    }

    public function delete(int $id, LogAuditAction $audit): void
    {
        $exp = Expertise::findOrFail($id);
        Gate::authorize('delete', $exp);
        $old = $exp->toArray();
        $exp->delete();
        $audit('EXPERTISE_DELETED', Expertise::class, $old, null);
        Flux::toast(variant: 'success', text: __('Expertise supprimée.'));
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->reset(['editingId']);
    }
}; ?>

<section class="w-full">
    <flux:heading size="xl" level="1">{{ __('Expertises') }}</flux:heading>
    <flux:subheading class="mb-6">{{ __('Gestion CMS vitrine — expertises SIBEA (Bâtiment, VRD, Énergie...)') }}
    </flux:subheading>
    {{-- Stats --}}
    @php
        $totalExpertises = \App\Models\Expertise::count();
        $activeExpertises = \App\Models\Expertise::where('is_active', true)->count();
        $totalServices = \App\Models\Service::count();
    @endphp
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <x-stat-widget title="Total" :value="$totalExpertises" suffix=" expertises" icon="academic-cap"
            trendLabel="Toutes expertises" />
        <x-stat-widget title="Actives" :value="$activeExpertises" :suffix="' / ' . $totalExpertises" icon="check-circle" :trend="$totalExpertises > 0 ? round(($activeExpertises / $totalExpertises) * 100, 1) . '%' : '0%'"
            :trendUp="true" trendLabel="Visibles vitrine" />
        <x-stat-widget title="Services" :value="$totalServices" suffix=" services" icon="wrench-screwdriver"
            trendLabel="Tous services" />
    </div>

    <flux:card class="!p-0 overflow-hidden">
        <x-card-header title="Expertises" subtitle="Gestion CMS — expertises SIBEA">
            <x-slot:actions>
                @can('create', App\Models\Expertise::class)
                    <flux:button variant="primary" size="sm" icon="plus" wire:click="openCreate">
                        {{ __('Nouvelle expertise') }}</flux:button>
                @endcan
            </x-slot:actions>
        </x-card-header>

        <x-table-toolbar searchPlaceholder="Search expertise..." searchModel="search" statusFilter="statusFilter"
            :statusOptions="['All' => 'Status', 'active' => 'Actifs', 'inactive' => 'Inactifs']" :perPageOptions="[5, 10, 15, 20]" :gridUrl="route('admin.expertises')" :listUrl="route('admin.expertises')" />
        <flux:table :paginate="$this->expertises">
            <flux:table.columns>
                <flux:table.column sortable :sorted="$sortBy === 'order'" :direction="$sortDirection"
                    wire:click="sort('order')">{{ __('Ordre') }}</flux:table.column>
                <flux:table.column sortable :sorted="$sortBy === 'title'" :direction="$sortDirection"
                    wire:click="sort('title')">{{ __('Titre') }}</flux:table.column>
                <flux:table.column sortable :sorted="$sortBy === 'slug'" :direction="$sortDirection"
                    wire:click="sort('slug')">{{ __('Slug') }}</flux:table.column>
                <flux:table.column align="center" sortable :sorted="$sortBy === 'is_active'" :direction="$sortDirection"
                    wire:click="sort('is_active')">{{ __('Actif') }}</flux:table.column>
                <flux:table.column align="end">{{ __('Actions') }}</flux:table.column>
            </flux:table.columns>
            <flux:table.rows>
                @forelse($this->expertises as $exp)
                    <flux:table.row class="hover:bg-zinc-50">
                        <flux:table.cell>{{ $exp->order }}</flux:table.cell>
                        <flux:table.cell>{{ $exp->title }}</flux:table.cell>
                        <flux:table.cell>
                            <flux:badge size="sm">{{ $exp->slug }}</flux:badge>
                        </flux:table.cell>
                        <flux:table.cell align="center">
                            @if ($exp->is_active)
                                <flux:badge variant="success" size="sm">{{ __('Oui') }}</flux:badge>
                            @else
                                <flux:badge variant="danger" size="sm">{{ __('Non') }}</flux:badge>
                            @endif
                        </flux:table.cell>
                        <flux:table.cell align="end">
                            <div class="flex justify-end gap-1">
                                @can('update', $exp)
                                    <flux:button variant="ghost" size="sm" icon="pencil-square"
                                        wire:click="openEdit({{ $exp->id }})" />
                                @endcan
                                @can('delete', $exp)
                                    <flux:button variant="ghost" size="sm" icon="trash"
                                        wire:click="delete({{ $exp->id }})" wire:confirm="{{ __('Supprimer ?') }}"
                                        class="text-red-500" />
                                @endcan
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell align="center" colspan="5">{{ __('Aucune expertise.') }}</flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </flux:card>


    <flux:modal wire:model="showModal" class="max-w-2xl" @close="closeModal">
        <form wire:submit="save" class="space-y-6">
            <flux:heading size="lg">{{ $editingId ? __('Modifier l’expertise') : __('Nouvelle expertise') }}
            </flux:heading>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <flux:input wire:model="title" :label="__('Titre')" required />
                <flux:input wire:model="slug" :label="__('Slug (auto si vide)')" placeholder="genie-civil" />
                <flux:input wire:model="order" type="number" :label="__('Ordre')" />
                <flux:checkbox wire:model="is_active" :label="__('Actif')" />
                <flux:input wire:model="icon" :label="__('Icône')" placeholder="building-office" />
                <flux:input wire:model="meta_title" :label="__('Meta titre')" placeholder="SEO" />
                <flux:input wire:model="meta_description" :label="__('Meta description')" />
                <flux:input wire:model="cover_image" :label="__('Image URL')" />
                <flux:input type="file" wire:model="cover_image_upload" :label="__('Ou fichier image (max 2Mo)')"
                    accept="image/*" />
                <div class="sm:col-span-2 -mt-2"><a href="{{ route('admin.media') }}" target="_blank"
                        class="text-xs text-zinc-500 underline hover:text-zinc-700">{{ __('Ouvrir la médiathèque') }}
                        →</a></div>
                <div class="sm:col-span-2">
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
