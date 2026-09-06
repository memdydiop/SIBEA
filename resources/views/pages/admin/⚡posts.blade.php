<?php

use App\Actions\Audit\LogAuditAction;
use App\Actions\Cms\CreatePostAction;
use App\Actions\Cms\UpdatePostAction;
use App\Models\Post;
use Flux\Flux;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

new #[Title('Actualités')] class extends Component {
    use WithPagination;
    use WithFileUploads;

    public string $slug = '';
    public string $title = '';
    public ?string $category = null;
    public ?string $excerpt = null;
    public ?string $content = null;
    public ?string $cover_image = null;
    public ?string $meta_title = null;
    public ?string $meta_description = null;
    public $cover_image_upload = null;
    public bool $is_published = false;
    public ?string $published_at = null;

    public ?int $editingId = null;
    public bool $showModal = false;
    public string $search = '';

    public function mount(): void
    {
        Gate::authorize('viewAny', Post::class);
    }

    #[Computed]
    public function posts()
    {
        return Post::when($this->search, fn ($q) => $q->where('title', 'ilike', "%{$this->search}%"))
            ->orderByDesc('published_at')
            ->paginate(15);
    }

    public function openCreate(): void
    {
        Gate::authorize('create', Post::class);
        $this->reset(['slug', 'title', 'category', 'excerpt', 'content', 'cover_image', 'meta_title', 'meta_description', 'cover_image_upload', 'editingId']);
        $this->is_published = false;
        $this->published_at = now()->format('Y-m-d');
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $p = Post::findOrFail($id);
        Gate::authorize('update', $p);
        $this->editingId = $p->id;
        $this->slug = $p->slug;
        $this->title = $p->title;
        $this->category = $p->category;
        $this->excerpt = $p->excerpt;
        $this->content = $p->content;
        $this->meta_title = $p->meta_title;
        $this->meta_description = $p->meta_description;
        $this->cover_image = $p->cover_image;
        $this->is_published = $p->is_published;
        $this->published_at = $p->published_at?->format('Y-m-d');
        $this->showModal = true;
    }

    public function save(CreatePostAction $create, UpdatePostAction $update, LogAuditAction $audit): void
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
            'category' => $this->category,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'cover_image' => $this->cover_image,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'is_published' => $this->is_published,
            'published_at' => $this->published_at,
        ];

        if ($this->editingId) {
            $p = Post::findOrFail($this->editingId);
            Gate::authorize('update', $p);
            $old = $p->toArray();
            $updated = $update($p, $data);
            $audit('POST_UPDATED', $updated, $old, $updated->toArray());
            Flux::toast(variant: 'success', text: __('Actualité mise à jour.'));
        } else {
            Gate::authorize('create', Post::class);
            $p = $create($data);
            $audit('POST_CREATED', $p, null, $p->toArray());
            Flux::toast(variant: 'success', text: __('Actualité créée.'));
        }

        $this->showModal = false;
        $this->reset(['editingId']);
        unset($this->posts);
    }

    public function delete(int $id, LogAuditAction $audit): void
    {
        $p = Post::findOrFail($id);
        Gate::authorize('delete', $p);
        $old = $p->toArray();
        $p->delete();
        $audit('POST_DELETED', Post::class, $old, null);
        Flux::toast(variant: 'success', text: __('Actualité supprimée.'));
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->reset(['editingId']);
    }
}; ?>

<section class="w-full">
    <flux:heading size="xl" level="1">{{ __('Actualités') }}</flux:heading>
    <flux:subheading class="mb-6">{{ __('Publications vitrine — brouillon / publié (CDC 9.1)') }}</flux:subheading>

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
        <flux:input wire:model.live.debounce.300ms="search" placeholder="{{ __('Rechercher...') }}" class="max-w-sm" />
        @can('create', App\Models\Post::class)
            <flux:button variant="primary" icon="plus" wire:click="openCreate">{{ __('Nouvelle actualité') }}</flux:button>
        @endcan
    </div>

    <div class="border rounded-lg border-zinc-200 dark:border-zinc-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-zinc-50 dark:bg-zinc-800/50 text-zinc-500">
                    <tr>
                        <th class="text-left px-4 py-3">{{ __('Titre') }}</th>
                        <th class="text-left px-4 py-3">{{ __('Catégorie') }}</th>
                        <th class="text-left px-4 py-3">{{ __('Slug') }}</th>
                        <th class="text-center px-4 py-3">{{ __('Publié') }}</th>
                        <th class="text-right px-4 py-3">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse($this->posts as $p)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/30">
                            <td class="px-4 py-3 font-medium">{{ $p->title }}</td>
                            <td class="px-4 py-3">{{ $p->category ?? '—' }}</td>
                            <td class="px-4 py-3"><flux:badge size="sm">{{ $p->slug }}</flux:badge></td>
                            <td class="px-4 py-3 text-center">@if($p->is_published)<flux:badge variant="success" size="sm">{{ __('Oui') }}</flux:badge>@else<flux:badge variant="zinc" size="sm">{{ __('Non') }}</flux:badge>@endif</td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-1">
                                    @can('update', $p)<flux:button variant="ghost" size="sm" icon="pencil-square" wire:click="openEdit({{ $p->id }})" />@endcan
                                    @can('delete', $p)<flux:button variant="ghost" size="sm" icon="trash" wire:click="delete({{ $p->id }})" wire:confirm="{{ __('Supprimer ?') }}" class="text-red-500" />@endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-8 text-center text-zinc-500">{{ __('Aucune actualité.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t">{{ $this->posts->links() }}</div>
    </div>

    <flux:modal wire:model="showModal" class="max-w-2xl" @close="closeModal">
        <form wire:submit="save" class="space-y-6">
            <flux:heading size="lg">{{ $editingId ? __('Modifier l’actualité') : __('Nouvelle actualité') }}</flux:heading>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <flux:input wire:model="title" :label="__('Titre')" required class="sm:col-span-2" />
                <flux:input wire:model="slug" :label="__('Slug (auto)')" />
                <flux:input wire:model="category" :label="__('Catégorie')" placeholder="Actualité" />
                <flux:input type="file" wire:model="cover_image_upload" :label="__('Ou fichier image')" accept="image/*" />
                <flux:input wire:model="meta_title" :label="__('Meta titre')" placeholder="SEO" />
                <flux:input wire:model="meta_description" :label="__('Meta description')" />
                <flux:input wire:model="cover_image" :label="__('Image URL')" />
                <div class="sm:col-span-2 -mt-2"><a href="{{ route('admin.media') }}" target="_blank" class="text-xs text-zinc-500 underline hover:text-zinc-700">{{ __('Ouvrir la médiathèque') }} →</a> <span class="text-xs text-zinc-400">{{ __('(copier /storage/media/...)') }}</span></div>
                <flux:input wire:model="published_at" type="date" :label="__('Date publication')" />
                <flux:checkbox wire:model="is_published" :label="__('Publié')" />
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
