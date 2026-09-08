<?php

use App\Actions\Audit\LogAuditAction;
use App\Models\Media;
use Flux\Flux;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

new #[Title('Médiathèque')] class extends Component
{
 use WithFileUploads;
 use WithPagination;

 public string $search = '';

 public string $collectionFilter = '';

 public string $collection = 'cms';

 public $file = null;

 public ?string $alt = null;

 public int $order = 0;

 public function mount(): void
 {
 Gate::authorize('viewAny', Media::class);
 }

 public function updatedSearch(): void
 {
 $this->resetPage();
 }

 public function updatedCollectionFilter(): void
 {
 $this->resetPage();
 }

 #[Computed]
 public function medias()
 {
 return Media::when($this->search, fn ($q) => $q->where('file_name', 'ilike', "%{$this->search}%"))
 ->when($this->collectionFilter, fn ($q) => $q->where('collection', $this->collectionFilter))
 ->orderBy('order')
 ->orderByDesc('created_at')
 ->paginate(24);
 }

 public function upload(LogAuditAction $audit): void
 {
 Gate::authorize('create', Media::class);

 Validator::make([
 'collection' => $this->collection,
 'file' => $this->file,
 'alt' => $this->alt,
 'order' => $this->order,
 ], [
 'collection' => ['required', 'string', 'in:cms,hero,avatar,default'],
 'file' => ['required', 'file', 'image', 'mimes:jpeg,png,webp,jpg,svg', 'max:5120', 'dimensions:max_width=4000,max_height=4000'],
 'alt' => ['nullable', 'string', 'max:255'],
 'order' => ['required', 'integer', 'min:0', 'max:9999'],
 ])->validate();

 $originalName = $this->file->getClientOriginalName();
 $mime = $this->file->getMimeType();
 $size = $this->file->getSize();

 $path = $this->file->store("media/{$this->collection}", 'public');

 $media = Media::create([
 'collection' => $this->collection,
 'file_name' => $originalName,
 'file_path' => $path,
 'mime_type' => $mime,
 'size' => $size,
 'alt' => $this->alt,
 'order' => $this->order,
 ]);

 $audit('MEDIA_CREATED', $media, null, $media->toArray());

 Flux::toast(variant: 'success', text: __('Média ajouté.'));

 $this->reset(['file', 'alt']);
 $this->order = 0;
 unset($this->medias);
 }

 public function delete(int $id, LogAuditAction $audit): void
 {
 $media = Media::findOrFail($id);
 Gate::authorize('delete', $media);

 $old = $media->toArray();

 Storage::disk('public')->delete($media->file_path);

 $media->delete();

 $audit('MEDIA_DELETED', Media::class, $old, null);

 Flux::toast(variant: 'success', text: __('Média supprimé.'));

 unset($this->medias);
 }
}; ?>

<section class="w-full">
 <flux:heading size="xl" level="1">{{ __('Médiathèque') }}</flux:heading>
 <flux:subheading class="mb-6">{{ __('Bibliothèque centrale — collections cms / hero / avatar. Stockage public/media/{collection}. Utilisable dans Expertises, Actualités, Pages, Programmes, Réalisations via URL.') }}</flux:subheading>

 <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
 <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
 <flux:input wire:model.live.debounce.300ms="search" placeholder="{{ __('Rechercher par nom de fichier...') }}" class="max-w-sm" />
 <flux:select wire:model.live="collectionFilter" placeholder="{{ __('Toutes collections') }}" class="max-w-[200px]">
 <flux:select.option value="">{{ __('Toutes collections') }}</flux:select.option>
 <flux:select.option value="cms">cms</flux:select.option>
 <flux:select.option value="hero">hero</flux:select.option>
 <flux:select.option value="avatar">avatar</flux:select.option>
 <flux:select.option value="default">default</flux:select.option>
 </flux:select>
 </div>
 <flux:badge>{{ $this->medias->total() }} {{ __('média(s)') }}</flux:badge>
 </div>

 @can('create', App\Models\Media::class)
 <form wire:submit="upload" class="mb-8 rounded-lg border border-zinc-200 overflow-hidden">
 <div class="bg-zinc-50 px-4 py-3 flex items-center justify-between">
 <div class="font-semibold text-sm">{{ __('Ajouter un média') }}</div>
 <flux:badge size="sm" variant="zinc">{{ __('public/media/{collection}') }}</flux:badge>
 </div>
 <div class="p-6 grid grid-cols-1 sm:grid-cols-4 gap-4">
 <flux:select wire:model="collection" :label="__('Collection')" required>
 <flux:select.option value="cms">cms</flux:select.option>
 <flux:select.option value="hero">hero</flux:select.option>
 <flux:select.option value="avatar">avatar</flux:select.option>
 <flux:select.option value="default">default</flux:select.option>
 </flux:select>
 <flux:input type="file" wire:model="file" :label="__('Fichier (jpeg,png,webp,svg max 5 Mo, 4000x4000)')" accept="image/jpeg,image/png,image/webp,image/svg+xml" required />
 <flux:input wire:model="alt" :label="__('Texte alternatif')" placeholder="{{ __('Description pour accessibilité') }}" />
 <flux:input wire:model="order" type="number" :label="__('Ordre')" min="0" max="9999" />
 </div>
 <div class="px-6 pb-6 flex justify-between items-center">
 <div class="text-xs text-zinc-500">{{ __('Stocké via Storage::disk(\'public\') → /storage/media/{collection}/...') }}</div>
 <flux:button variant="primary" type="submit" icon="plus">{{ __('Uploader') }}</flux:button>
 </div>
 </form>
 @endcan

 <div class="border rounded-lg border-zinc-200 overflow-hidden">
 @if($this->medias->count() > 0)
 <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 p-4">
 @foreach($this->medias as $media)
 <div class="rounded-lg border border-zinc-200 overflow-hidden bg-white flex flex-col">
 <div class="aspect-[16/10] bg-zinc-100 flex items-center justify-center overflow-hidden">
 @if($media->mime_type && str_starts_with($media->mime_type, 'image/'))
 <img src="{{ $media->url }}" alt="{{ $media->alt ?? $media->file_name }}" class="h-full w-full object-cover" loading="lazy">
 @else
 <flux:icon.photo class="w-10 h-10 text-zinc-400" />
 @endif
 </div>
 <div class="p-3 flex-1 flex flex-col gap-1">
 <div class="flex items-center justify-between gap-2">
 <div class="text-sm font-medium truncate" title="{{ $media->file_name }}">{{ $media->file_name }}</div>
 <flux:badge size="sm" variant="zinc">{{ $media->collection }}</flux:badge>
 </div>
 <div class="text-xs text-zinc-500 truncate" title="{{ $media->file_path }}">{{ $media->file_path }}</div>
 <div class="text-xs text-zinc-500">/storage/{{ $media->file_path }}</div>
 @if($media->alt)
 <div class="text-xs text-zinc-600 line-clamp-2">{{ __('Alt:') }} {{ $media->alt }}</div>
 @else
 <div class="text-xs text-zinc-400 italic">{{ __('Sans alt') }}</div>
 @endif
 <div class="flex items-center gap-2 text-xs text-zinc-500">
 <span>{{ $media->mime_type ?? '—' }}</span>
 <span>·</span>
 <span>
 @if($media->size)
 @if($media->size > 1048576)
 {{ number_format($media->size / 1048576, 2) }} Mo
 @elseif($media->size > 1024)
 {{ number_format($media->size / 1024, 1) }} Ko
 @else
 {{ $media->size }} o
 @endif
 @else
 —
 @endif
 </span>
 <span>·</span>
 <span>{{ __('Ordre') }} {{ $media->order }}</span>
 </div>
 <div class="mt-2 flex justify-end gap-1">
 <flux:button variant="ghost" size="sm" icon="clipboard-document" :href="$media->url" target="_blank" wire:navigate="false">{{ __('Voir') }}</flux:button>
 @can('delete', $media)
 <flux:button variant="ghost" size="sm" icon="trash" wire:click="delete({{ $media->id }})" wire:confirm="{{ __('Supprimer ce média ? Fichier supprimé du disque.') }}" class="text-red-500" />
 @endcan
 </div>
 </div>
 </div>
 @endforeach
 </div>
 <div class="p-4 border-t border-zinc-200">
 {{ $this->medias->links() }}
 </div>
 @else
 <div class="px-4 py-12 text-center text-zinc-500">{{ __('Aucun média. Uploadez un fichier ci-dessus.') }}</div>
 @endif
 </div>

 <div class="mt-6 text-xs text-zinc-500">
 {{ __('Astuce: copiez l’URL /storage/media/... pour l’utiliser dans les champs cover_image (Expertises, Actualités, Pages, Programmes, Réalisations) ou ouvrez') }}
 <a href="{{ route('admin.media') }}" class="underline hover:text-zinc-700">{{ __('la médiathèque') }}</a>.
 </div>
</section>
