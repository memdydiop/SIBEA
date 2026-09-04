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

    public function mount(): void
    {
        Gate::authorize('viewAny', Expertise::class);
    }

    #[Computed]
    public function expertises()
    {
        return Expertise::when($this->search, fn ($q) => $q->where('title', 'ilike', "%{$this->search}%")->orWhere('slug', 'ilike', "%{$this->search}%"))
            ->orderBy('order')
            ->paginate(15);
    }

    public function openCreate(): void
    {
        Gate::authorize('create', Expertise::class);
        $this->reset(['slug', 'title', 'excerpt', 'content', 'icon', 'cover_image', 'cover_image_upload', 'editingId']);
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
        $this->meta_title = $expertise->meta_title;
        $this->meta_description = $expertise->meta_description;
        $this->cover_image = $exp->cover_image;
        $this->order = $exp->order;
        $this->is_active = $exp->is_active;
        $this->showModal = true;
    }

    public function save(CreateExpertiseAction $create, UpdateExpertiseAction $update, LogAuditAction $audit): void
    {
        if ($this->cover_image_upload) {
            $this->meta_title = $expertise->meta_title;
        $this->meta_description = $expertise->meta_description;
        $this->cover_image = $this->cover_image_upload->store('cms/expertises', 'public');
        }

        $data = [
            'slug' => $this->slug ?: null,
            'title' => $this->title,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'icon' => $this->icon,
            'cover_image' => $this->cover_image ? (str_starts_with($this->cover_image, 'cms/') ? '/storage/'.$this->cover_image : $this->cover_image) : null,
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
    <flux:subheading class="mb-6">{{ __('Gestion CMS vitrine — expertises SIBEA (Bâtiment, VRD, Énergie...)') }}</flux:subheading>

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
        <flux:input wire:model.live.debounce.300ms="search" placeholder="{{ __('Rechercher titre ou slug...') }}" class="max-w-sm" />
        @can('create', App\Models\Expertise::class)
            <flux:button variant="primary" icon="plus" wire:click="openCreate">{{ __('Nouvelle expertise') }}</flux:button>
        @endcan
    </div>

    <div class="border rounded-lg border-zinc-200 dark:border-zinc-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-zinc-50 dark:bg-zinc-800/50 text-zinc-500">
                    <tr>
                        <th class="text-left px-4 py-3">{{ __('Ordre') }}</th>
                        <th class="text-left px-4 py-3">{{ __('Titre') }}</th>
                        <th class="text-left px-4 py-3">{{ __('Slug') }}</th>
                        <th class="text-center px-4 py-3">{{ __('Actif') }}</th>
                        <th class="text-right px-4 py-3">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse($this->expertises as $exp)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/30">
                            <td class="px-4 py-3">{{ $exp->order }}</td>
                            <td class="px-4 py-3 font-medium">{{ $exp->title }}</td>
                            <td class="px-4 py-3"><flux:badge size="sm">{{ $exp->slug }}</flux:badge></td>
                            <td class="px-4 py-3 text-center">@if($exp->is_active)<flux:badge variant="success" size="sm">{{ __('Oui') }}</flux:badge>@else<flux:badge variant="danger" size="sm">{{ __('Non') }}</flux:badge>@endif</td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-1">
                                    @can('update', $exp)<flux:button variant="ghost" size="sm" icon="pencil-square" wire:click="openEdit({{ $exp->id }})" />@endcan
                                    @can('delete', $exp)<flux:button variant="ghost" size="sm" icon="trash" wire:click="delete({{ $exp->id }})" wire:confirm="{{ __('Supprimer ?') }}" class="text-red-500" />@endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-8 text-center text-zinc-500">{{ __('Aucune expertise.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t">{{ $this->expertises->links() }}</div>
    </div>

    <flux:modal wire:model="showModal" class="max-w-2xl" @close="closeModal">
        <form wire:submit="save" class="space-y-6">
            <flux:heading size="lg">{{ $editingId ? __('Modifier l’expertise') : __('Nouvelle expertise') }}</flux:heading>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <flux:input wire:model="title" :label="__('Titre')" required />
                <flux:input wire:model="slug" :label="__('Slug (auto si vide)')" placeholder="genie-civil" />
                <flux:input wire:model="order" type="number" :label="__('Ordre')" />
                <flux:checkbox wire:model="is_active" :label="__('Actif')" />
                <flux:input wire:model="icon" :label="__('Icône')" placeholder="building-office" />
                <flux:input wire:model="meta_title" :label="__('Meta titre')" placeholder="SEO" />
                <flux:input wire:model="meta_description" :label="__('Meta description')" />
                <flux:input wire:model="cover_image" :label="__('Image URL')" />
                <flux:input type="file" wire:model="cover_image_upload" :label="__('Ou fichier image (max 2Mo)')" accept="image/*" />
                <div class="sm:col-span-2 -mt-2"><a href="{{ route('admin.media') }}" target="_blank" class="text-xs text-zinc-500 underline hover:text-zinc-700">{{ __('Ouvrir la médiathèque') }} →</a></div>
                <div class="sm:col-span-2"><flux:textarea wire:model="excerpt" :label="__('Extrait')" rows="2" /></div>
                <div class="sm:col-span-2"><flux:textarea wire:model="content" :label="__('Contenu')" rows="4" /></div>
            </div>
            <div class="flex justify-end gap-2">
                <flux:button variant="ghost" wire:click="closeModal" type="button">{{ __('Annuler') }}</flux:button>
                <flux:button variant="primary" type="submit">{{ $editingId ? __('Mettre à jour') : __('Créer') }}</flux:button>
            </div>
        </form>
    </flux:modal>
</section>
