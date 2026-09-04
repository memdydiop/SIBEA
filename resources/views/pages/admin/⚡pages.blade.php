<?php

use App\Actions\Audit\LogAuditAction;
use App\Actions\Cms\CreatePageAction;
use App\Actions\Cms\UpdatePageAction;
use App\Models\Page;
use Flux\Flux;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

new #[Title('Pages')] class extends Component {
    use WithPagination;
    use WithFileUploads;

    public string $slug = '';
    public string $title = '';
    public ?string $meta_title = null;
    public ?string $meta_description = null;
    public ?string $excerpt = null;
    public ?string $content = null;
    public ?string $cover_image = null;
    public $cover_image_upload = null;
    public bool $is_published = false;
    public bool $is_archived = false;
    public ?string $published_at = null;
    public int $order = 0;

    public ?int $editingId = null;
    public bool $showModal = false;
    public string $search = '';

    public function mount(): void
    {
        Gate::authorize('viewAny', Page::class);
    }

    #[Computed]
    public function pages()
    {
        return Page::when($this->search, fn ($q) => $q->where('title', 'ilike', "%{$this->search}%")->orWhere('slug', 'ilike', "%{$this->search}%"))
            ->orderBy('order')
            ->paginate(15);
    }

    public function openCreate(): void
    {
        Gate::authorize('create', Page::class);
        $this->reset(['slug', 'title', 'meta_title', 'meta_description', 'excerpt', 'content', 'cover_image', 'cover_image_upload', 'editingId']);
        $this->order = 0;
        $this->is_published = false;
        $this->is_archived = false;
        $this->published_at = now()->format('Y-m-d');
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $page = Page::findOrFail($id);
        Gate::authorize('update', $page);
        $this->editingId = $page->id;
        $this->slug = $page->slug;
        $this->title = $page->title;
        $this->meta_title = $page->meta_title;
        $this->meta_description = $page->meta_description;
        $this->excerpt = $page->excerpt;
        $this->content = $page->content;
        $this->cover_image = $page->cover_image;
        $this->is_published = $page->is_published;
        $this->published_at = $page->published_at?->format('Y-m-d');
        $this->order = $page->order;
        $this->showModal = true;
    }

    public function save(CreatePageAction $create, UpdatePageAction $update, LogAuditAction $audit): void
    {
        if ($this->cover_image_upload) {
            Validator::make(
                ['cover_image_upload' => $this->cover_image_upload],
                ['cover_image_upload' => ['image', 'mimes:jpeg,png,webp', 'max:2048', 'dimensions:max_width=4000,max_height=4000']],
            )->validate();
            $this->cover_image = $this->cover_image_upload->store('cms', 'public');
        }

        if ($this->cover_image && str_starts_with($this->cover_image, 'cms/')) {
            $this->cover_image = '/storage/'.$this->cover_image;
        }

        $data = [
            'slug' => $this->slug ?: null,
            'title' => $this->title,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'cover_image' => $this->cover_image,
            'is_published' => $this->is_published,
            'is_archived' => $this->is_archived,
            'published_at' => $this->published_at,
            'order' => $this->order,
        ];

        if ($this->editingId) {
            $page = Page::findOrFail($this->editingId);
            Gate::authorize('update', $page);
            $old = $page->toArray();
            $updated = $update($page, $data);
            $audit('PAGE_UPDATED', $updated, $old, $updated->toArray());
            Flux::toast(variant: 'success', text: __('Page mise à jour.'));
        } else {
            Gate::authorize('create', Page::class);
            $page = $create($data);
            $audit('PAGE_CREATED', $page, null, $page->toArray());
            Flux::toast(variant: 'success', text: __('Page créée.'));
        }

        $this->showModal = false;
        $this->reset(['editingId']);
        unset($this->pages);
    }

    public function delete(int $id, LogAuditAction $audit): void
    {
        $page = Page::findOrFail($id);
        Gate::authorize('delete', $page);
        $old = $page->toArray();
        $page->delete();
        $audit('PAGE_DELETED', Page::class, $old, null);
        Flux::toast(variant: 'success', text: __('Page supprimée.'));
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->reset(['editingId']);
    }
}; ?>

<section class="w-full">
    <flux:heading size="xl" level="1">{{ __('Pages CMS') }}</flux:heading>
    <flux:subheading class="mb-6">{{ __('Gestion des pages statiques — contenus vitrine SIBEA') }}</flux:subheading>

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
        <flux:input wire:model.live.debounce.300ms="search" placeholder="{{ __('Rechercher titre ou slug...') }}" class="max-w-sm" />
        @can('create', App\Models\Page::class)
            <flux:button variant="primary" icon="plus" wire:click="openCreate">{{ __('Nouvelle page') }}</flux:button>
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
                        <th class="text-center px-4 py-3">{{ __('Publié') }}</th>
                        <th class="text-center px-4 py-3">{{ __('Archivé') }}</th>
                        <th class="text-right px-4 py-3">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse($this->pages as $page)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/30">
                            <td class="px-4 py-3">{{ $page->order }}</td>
                            <td class="px-4 py-3 font-medium">{{ $page->title }}</td>
                            <td class="px-4 py-3"><flux:badge size="sm">{{ $page->slug }}</flux:badge></td>
                            <td class="px-4 py-3 text-center">@if($page->is_published)<flux:badge variant="success" size="sm">{{ __('Oui') }}</flux:badge>@else<flux:badge variant="zinc" size="sm">{{ __('Non') }}</flux:badge>@endif</td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-1">
                                    @can('update', $page)<flux:button variant="ghost" size="sm" icon="pencil-square" wire:click="openEdit({{ $page->id }})" />@endcan
                                    @can('delete', $page)<flux:button variant="ghost" size="sm" icon="trash" wire:click="delete({{ $page->id }})" wire:confirm="{{ __('Supprimer ?') }}" class="text-red-500" />@endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-8 text-center text-zinc-500">{{ __('Aucune page.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t">{{ $this->pages->links() }}</div>
    </div>

    <flux:modal wire:model="showModal" class="max-w-2xl" @close="closeModal">
        <form wire:submit="save" class="space-y-6">
            <flux:heading size="lg">{{ $editingId ? __('Modifier la page') : __('Nouvelle page') }}</flux:heading>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <flux:input wire:model="title" :label="__('Titre')" required class="sm:col-span-2" />
                <flux:input wire:model="slug" :label="__('Slug (auto si vide)')" placeholder="histoire" />
                <flux:input wire:model="order" type="number" :label="__('Ordre')" />
                <flux:input wire:model="meta_title" :label="__('Meta title')" placeholder="SEO title" class="sm:col-span-2" />
                <div class="sm:col-span-2"><flux:textarea wire:model="meta_description" :label="__('Meta description')" rows="2" /></div>
                <flux:input type="file" wire:model="cover_image_upload" :label="__('Ou fichier image')" accept="image/*" />
                <flux:input wire:model="cover_image" :label="__('Image couverture URL')" class="sm:col-span-2" />
                <div class="sm:col-span-2 -mt-2"><a href="{{ route('admin.media') }}" target="_blank" class="text-xs text-zinc-500 underline hover:text-zinc-700">{{ __('Ouvrir la médiathèque') }} →</a></div>
                <flux:input wire:model="published_at" type="date" :label="__('Date publication')" />
                <div class="flex flex-col gap-2 justify-end">
                    <flux:checkbox wire:model="is_archived" :label="__('Archivé')" />
                <flux:checkbox wire:model="is_published" :label="__('Publié')" />
                </div>
                <div class="sm:col-span-2"><flux:textarea wire:model="excerpt" :label="__('Extrait')" rows="2" /></div>
                <div class="sm:col-span-2"><flux:textarea wire:model="content" :label="__('Contenu')" rows="5" /></div>
            </div>
            <div class="flex justify-end gap-2">
                <flux:button variant="ghost" wire:click="closeModal" type="button">{{ __('Annuler') }}</flux:button>
                <flux:button variant="primary" type="submit">{{ $editingId ? __('Mettre à jour') : __('Créer') }}</flux:button>
            </div>
        </form>
    </flux:modal>
</section>
