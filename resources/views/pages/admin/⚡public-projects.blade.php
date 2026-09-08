<?php

use App\Actions\Audit\LogAuditAction;
use App\Actions\Cms\CreatePublicProjectAction;
use App\Actions\Cms\UpdatePublicProjectAction;
use App\Models\PublicProject;
use Flux\Flux;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

new #[Title('Réalisations')] class extends Component {
    use WithPagination;
    use WithFileUploads;

    public string $slug = '';
    public string $title = '';
    public string $category = 'Bâtiment';
    public ?string $client_name = null;
    public ?string $location = null;
    public ?int $year = null;
    public ?string $description = null;
    public bool $is_featured = false;
    public bool $is_published = true;
    public ?string $published_at = null;
    public ?string $cover_image = null;
    public ?string $meta_title = null;
    public ?string $meta_description = null;
    public $cover_image_upload = null;

    public ?int $editingId = null;
    public bool $showModal = false;
    public string $search = '';

    public function mount(): void
    {
        Gate::authorize('viewAny', PublicProject::class);
    }

    #[Computed]
    public function projects()
    {
        return PublicProject::when($this->search, fn($q) => $q->where('title', 'ilike', "%{$this->search}%")->orWhere('category', 'ilike', "%{$this->search}%"))->orderByDesc('year')->paginate(15);
    }

    public function openCreate(): void
    {
        Gate::authorize('create', PublicProject::class);
        $this->reset(['slug', 'title', 'client_name', 'location', 'description', 'cover_image', 'meta_title', 'meta_description', 'cover_image_upload', 'editingId']);
        $this->category = 'Bâtiment';
        $this->year = now()->year;
        $this->is_featured = false;
        $this->is_published = true;
        $this->published_at = now()->format('Y-m-d');
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $p = PublicProject::findOrFail($id);
        Gate::authorize('update', $p);
        $this->editingId = $p->id;
        $this->slug = $p->slug;
        $this->title = $p->title;
        $this->category = $p->category;
        $this->client_name = $p->client_name;
        $this->location = $p->location;
        $this->year = $p->year;
        $this->description = $p->description;
        $this->is_featured = $p->is_featured;
        $this->is_published = $p->is_published;
        $this->published_at = $p->published_at?->format('Y-m-d');
        $this->meta_title = $p->meta_title;
        $this->meta_description = $p->meta_description;
        $this->cover_image = $p->cover_image;
        $this->showModal = true;
    }

    public function save(CreatePublicProjectAction $create, UpdatePublicProjectAction $update, LogAuditAction $audit): void
    {
        if ($this->cover_image_upload) {
            Validator::make(['cover_image_upload' => $this->cover_image_upload], ['cover_image_upload' => ['image', 'mimes:jpeg,png,webp', 'max:2048', 'dimensions:max_width=4000,max_height=4000']])->validate();

            $path = $this->cover_image_upload->store('cms/projects', 'public');
            $this->cover_image = '/storage/' . $path;
        }

        if ($this->cover_image && str_starts_with($this->cover_image, 'cms/')) {
            $this->cover_image = '/storage/' . $this->cover_image;
        }

        $data = [
            'slug' => $this->slug ?: null,
            'title' => $this->title,
            'category' => $this->category,
            'client_name' => $this->client_name,
            'location' => $this->location,
            'year' => $this->year,
            'description' => $this->description,
            'is_featured' => $this->is_featured,
            'is_published' => $this->is_published,
            'published_at' => $this->published_at,
            'cover_image' => $this->cover_image,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
        ];

        if ($this->editingId) {
            $p = PublicProject::findOrFail($this->editingId);
            Gate::authorize('update', $p);
            $old = $p->toArray();
            $updated = $update($p, $data);
            $audit('PUBLIC_PROJECT_UPDATED', $updated, $old, $updated->toArray());
            Flux::toast(variant: 'success', text: __('Projet mis à jour.'));
        } else {
            Gate::authorize('create', PublicProject::class);
            $p = $create($data);
            $audit('PUBLIC_PROJECT_CREATED', $p, null, $p->toArray());
            Flux::toast(variant: 'success', text: __('Projet créé.'));
        }

        $this->showModal = false;
        $this->reset(['editingId']);
        unset($this->projects);
    }

    public function delete(int $id, LogAuditAction $audit): void
    {
        $p = PublicProject::findOrFail($id);
        Gate::authorize('delete', $p);
        $old = $p->toArray();
        $p->delete();
        $audit('PUBLIC_PROJECT_DELETED', PublicProject::class, $old, null);
        Flux::toast(variant: 'success', text: __('Projet supprimé.'));
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->reset(['editingId']);
    }
}; ?>

<section class="w-full">
    <flux:heading size="xl" level="1">{{ __('Réalisations publiques') }}</flux:heading>
    <flux:subheading class="mb-6">{{ __('Projets vitrine — publiés explicitement (CDC 9.2)') }}</flux:subheading>

    @php
        $totalProjects = \App\Models\PublicProject::count();
        $publishedProjects = \App\Models\PublicProject::where('is_published', true)->count();
    @endphp
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <x-stat-widget title="Total" :value="$totalProjects" suffix=" projets" icon="building-office-2"
            trendLabel="Tous projets" />
        <x-stat-widget title="Publiés" :value="$publishedProjects" :suffix="' / ' . $totalProjects" icon="check-circle" :trend="$totalProjects > 0 ? round(($publishedProjects / $totalProjects) * 100, 1) . '%' : '0%'"
            :trendUp="true" trendLabel="Visibles" />
        <x-stat-widget title="Vedettes" :value="\App\Models\PublicProject::where('is_featured', true)->count()" suffix=" vedettes" icon="star" trendLabel="Accueil" />
    </div>

    <flux:card class="!p-0 overflow-hidden">
        <x-card-header title="Réalisations" subtitle="Projets vitrine — publiés">
            <x-slot:actions>
                <!-- actions will be in toolbar -->
            </x-slot:actions>
        </x-card-header>

        <x-table-toolbar searchPlaceholder="Search project..." searchModel="search" statusFilter="statusFilter"
            :statusOptions="['All' => 'Status', 'published' => 'Publiés', 'draft' => 'Brouillons']" :perPageOptions="[5, 10, 15, 20]" :gridUrl="route('admin.public-projects')" :listUrl="route('admin.public-projects')" />


        <flux:table :paginate="$this->projects">
            <flux:table.columns>
                <flux:table.column>{{ __('Titre') }}</flux:table.column>
                <flux:table.column>{{ __('Catégorie') }}</flux:table.column>
                <flux:table.column align="center">{{ __('Année') }}</flux:table.column>
                <flux:table.column align="center">{{ __('Publié') }}</flux:table.column>
                <flux:table.column align="center">{{ __('Vedette') }}</flux:table.column>
                <flux:table.column align="end">{{ __('Actions') }}</flux:table.column>
            </flux:table.columns>
            <flux:table.rows>
                @forelse($this->projects as $p)
                    <flux:table.row class="hover:bg-zinc-50">
                        <flux:table.cell>{{ $p->title }}<div class="text-xs text-zinc-500">{{ $p->slug }}
                            </div>
                        </flux:table.cell>
                        <flux:table.cell>
                            <flux:badge size="sm">{{ $p->category }}</flux:badge>
                        </flux:table.cell>
                        <flux:table.cell align="center">{{ $p->year ?? '—' }}</flux:table.cell>
                        <flux:table.cell align="center">
                            @if ($p->is_published)
                                <flux:badge variant="success" size="sm">{{ __('Oui') }}</flux:badge>
                            @else
                                <flux:badge variant="zinc" size="sm">{{ __('Non') }}</flux:badge>
                            @endif
                        </flux:table.cell>
                        <flux:table.cell align="center">
                            @if ($p->is_featured)
                                <flux:badge variant="warning" size="sm">{{ __('Oui') }}</flux:badge>
                            @else
                                —
                            @endif
                        </flux:table.cell>
                        <flux:table.cell align="end">
                            <div class="flex justify-end gap-1">
                                @can('update', $p)
                                    <flux:button variant="ghost" size="sm" icon="pencil-square"
                                        wire:click="openEdit({{ $p->id }})" />
                                @endcan
                                @can('delete', $p)
                                    <flux:button variant="ghost" size="sm" icon="trash"
                                        wire:click="delete({{ $p->id }})" wire:confirm="{{ __('Supprimer ?') }}"
                                        class="text-red-500" />
                                @endcan
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell align="center" colspan="6">{{ __('Aucun projet.') }}</flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </flux:card>

    <flux:modal wire:model="showModal" class="max-w-2xl" @close="closeModal">
        <form wire:submit="save" class="space-y-6">
            <flux:heading size="lg">{{ $editingId ? __('Modifier le projet') : __('Nouveau projet') }}
            </flux:heading>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <flux:input wire:model="title" :label="__('Titre')" required class="sm:col-span-2" />
                <flux:input wire:model="slug" :label="__('Slug (auto)')" placeholder="residence-les-palmiers" />
                <flux:select wire:model="category" :label="__('Catégorie')" required>
                    <flux:select.option value="Bâtiment">Bâtiment</flux:select.option>
                    <flux:select.option value="Génie civil">Génie civil</flux:select.option>
                    <flux:select.option value="VRD">VRD</flux:select.option>
                    <flux:select.option value="Énergie">Énergie</flux:select.option>
                </flux:select>
                <flux:input wire:model="client_name" :label="__('Client')" />
                <flux:input wire:model="location" :label="__('Localisation')" />
                <flux:input wire:model="year" type="number" :label="__('Année')" />
                <flux:input type="file" wire:model="cover_image_upload" :label="__('Ou fichier image')"
                    accept="image/*" />
                <flux:input wire:model="meta_title" :label="__('Meta titre')" placeholder="SEO" />
                <flux:input wire:model="meta_description" :label="__('Meta description')" />
                <flux:input wire:model="cover_image" :label="__('Image couverture URL')" class="sm:col-span-2" />
                <div class="sm:col-span-2 -mt-2"><a href="{{ route('admin.media') }}" target="_blank"
                        class="text-xs text-zinc-500 underline hover:text-zinc-700">{{ __('Ouvrir la médiathèque') }}
                        →</a></div>
                <div class="sm:col-span-2">
                    <flux:textarea wire:model="description" :label="__('Description')" rows="3" />
                </div>
                <flux:input wire:model="published_at" type="date" :label="__('Date publication')" />
                <div class="flex flex-col gap-2">
                    <flux:checkbox wire:model="is_published" :label="__('Publié (CDC 9.2)')" />
                    <flux:checkbox wire:model="is_featured" :label="__('Vedette accueil')" />
                </div>
            </div>
            <div class="flex justify-end gap-2">
                <flux:button variant="ghost" wire:click="closeModal" type="button">{{ __('Annuler') }}
                </flux:button>
                <flux:button variant="primary" type="submit">
                    {{ $editingId ? __('Mettre à jour') : __('Créer') }}</flux:button>
            </div>
        </form>
    </flux:modal>
</section>
