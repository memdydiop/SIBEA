<?php

use App\Actions\Audit\LogAuditAction;
use App\Data\HomepageData;
use App\Models\SiteSetting;
use Flux\Flux;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

new #[Title('Paramètres du site')] class extends Component {
 use WithFileUploads;

 public string $search = '';
 public string $key = '';
 public ?string $value = null;
 public string $group = 'general';
 public ?int $editingId = null;
 public bool $showModal = false;

 // Identité visuelle — logo
 public ?string $site_logo = null;
 public $site_logo_upload = null;

 public function mount(): void
 {
 Gate::authorize('viewAny', SiteSetting::class);
 $this->site_logo = SiteSetting::get('site_logo');
 }

 #[Computed]
 public function settings()
 {
 return SiteSetting::when($this->search, fn ($q) => $q->where('key', 'ilike', "%{$this->search}%"))
 ->whereNotIn('key', HomepageData::homepageKeys())
 ->orderBy('group')
 ->orderBy('key')
 ->get();
 }

 #[Computed]
 public function groupedSettings()
 {
 return $this->settings->groupBy('group');
 }

 public function openCreate(): void
 {
 Gate::authorize('create', SiteSetting::class);
 $this->reset(['key', 'value', 'editingId']);
 $this->group = 'general';
 $this->showModal = true;
 }

 public function openEdit(int $id): void
 {
 $s = SiteSetting::findOrFail($id);
 Gate::authorize('update', $s);
 $this->editingId = $s->id;
 $this->key = $s->key;
 $this->value = $s->value;
 $this->group = $s->group;
 $this->showModal = true;
 }

 public function save(LogAuditAction $audit): void
 {
 $data = Validator::make([
 'key' => $this->key,
 'value' => $this->value,
 'group' => $this->group,
 ], [
 'key' => ['required', 'string', 'max:100', 'regex:/^[a-z0-9_\.]+$/'],
 'value' => ['nullable', 'string'],
 'group' => ['required', 'string', 'max:50'],
 ])->validate();

 if ($this->editingId) {
 $s = SiteSetting::findOrFail($this->editingId);
 Gate::authorize('update', $s);
 $old = $s->toArray();
 $s->update($data);
 $audit('SITE_SETTING_UPDATED', $s, $old, $s->toArray());
 Flux::toast(variant: 'success', text: __('Paramètre mis à jour.'));
 } else {
 Gate::authorize('create', SiteSetting::class);
 $s = SiteSetting::create($data);
 $audit('SITE_SETTING_CREATED', $s, null, $s->toArray());
 Flux::toast(variant: 'success', text: __('Paramètre créé.'));
 }

 $this->showModal = false;
 $this->reset(['editingId']);
 unset($this->settings);
 }

 public function delete(int $id, LogAuditAction $audit): void
 {
 $s = SiteSetting::findOrFail($id);
 Gate::authorize('delete', $s);
 $old = $s->toArray();
 $s->delete();
 $audit('SITE_SETTING_DELETED', SiteSetting::class, $old, null);
 Flux::toast(variant: 'success', text: __('Paramètre supprimé.'));
 }

 public function saveLogo(LogAuditAction $audit): void
 {
 Gate::authorize('viewAny', SiteSetting::class);
 if ($this->site_logo_upload) {
 Validator::make(['site_logo_upload' => $this->site_logo_upload], ['site_logo_upload' => ['image', 'mimes:jpeg,png,webp,svg', 'max:2048', 'dimensions:max_width=2000,max_height=2000']])->validate();
 $path = $this->site_logo_upload->store('site/logos', 'public');
 $this->site_logo = '/storage/' . $path;
 }
 if ($this->site_logo && str_starts_with($this->site_logo, 'site/')) {
 $this->site_logo = '/storage/' . $this->site_logo;
 }
 $old = SiteSetting::get('site_logo');
 SiteSetting::set('site_logo', $this->site_logo ?: null, 'branding');
 $s = SiteSetting::where('key', 'site_logo')->first();
 $audit('SITE_LOGO_UPDATED', $s, ['value' => $old], ['value' => $this->site_logo]);
 Flux::toast(variant: 'success', text: __('Logo mis à jour.'));
 $this->site_logo_upload = null;
 unset($this->settings);
 }

 public function removeLogo(LogAuditAction $audit): void
 {
 Gate::authorize('viewAny', SiteSetting::class);
 $old = SiteSetting::get('site_logo');
 SiteSetting::set('site_logo', null, 'branding');
 $this->site_logo = null;
 $this->site_logo_upload = null;
 $s = SiteSetting::where('key', 'site_logo')->first();
 if ($s) {
 $s->delete();
 }
 $audit('SITE_LOGO_REMOVED', SiteSetting::class, ['value' => $old], null);
 Flux::toast(variant: 'success', text: __('Logo supprimé.'));
 unset($this->settings);
 }

 public function closeModal(): void
 {
 $this->showModal = false;
 $this->reset(['editingId']);
 }
}; ?>

<section class="w-full">
 <flux:heading size="xl" level="1">{{ __('Paramètres du site') }}</flux:heading>
 <flux:subheading class="mb-6">{{ __('Clés génériques (contact_*, menus, etc.). Les clés homepage (hero_*, site_*, stats_*, about_*, seo_*) sont gérées dans ') }}<a href="{{ route('admin.homepage') }}" class="underline hover:text-zinc-700">{{ __('Page d’accueil') }}</a>{{ __(' — masquées ici pour éviter la duplication.') }}</flux:subheading>
 <flux:callout variant="secondary" icon="information-circle" class="mb-6">{{ __('Homepage vitrine éditée via Page d’accueil (flux:card slideshow 3 slides). Ce tableau liste uniquement les clés hors vitrine.') }}</flux:callout>

 {{-- Identité visuelle — Logo --}}
 <div class="mb-6 rounded-xl border border-zinc-200 bg-white shadow-sm overflow-hidden">
 <div class="bg-zinc-50 px-4 py-3 flex items-center justify-between">
 <div>
 <div class="text-sm font-semibold">{{ __('Identité visuelle') }}</div>
 <div class="text-xs text-zinc-500">{{ __('Logo sidebar & header — affiché dans la sidebar Ynex #111c43') }}</div>
 </div>
 <a href="{{ route('admin.media') }}" target="_blank" class="text-xs underline text-zinc-500 hover:text-zinc-700">{{ __('Médiathèque') }} →</a>
 </div>
 <div class="p-4 flex flex-col sm:flex-row gap-6 items-start">
 <div class="flex-1 space-y-3">
 <flux:input wire:model="site_logo" :label="__('Logo URL (ex: /storage/site/logos/logo.png)')" placeholder="/storage/site/logos/logo.svg" />
 <flux:input type="file" wire:model="site_logo_upload" :label="__('Ou uploader (png, svg, webp, max 2Mo)')" accept="image/png,image/jpeg,image/webp,image/svg+xml" />
 @if($site_logo_upload)
 <div class="text-xs text-zinc-500">{{ __('Nouveau fichier sélectionné — sera enregistré dans site/logos') }}</div>
 @endif
 <div class="flex gap-2">
 <flux:button variant="primary" size="sm" icon="check" wire:click="saveLogo">{{ __('Enregistrer logo') }}</flux:button>
 @if($site_logo)
 <flux:button variant="ghost" size="sm" icon="trash" wire:click="removeLogo" wire:confirm="{{ __('Supprimer le logo ?') }}" class="text-red-500">{{ __('Supprimer') }}</flux:button>
 @endif
 </div>
 </div>
 <div class="w-full sm:w-64 shrink-0">
 <div class="text-xs font-medium text-zinc-500 mb-2">{{ __('Aperçu sidebar Ynex') }}</div>
  <div class="rounded-lg border border-white/10 bg-[#111c43] p-4 flex items-center gap-3">
  @if($site_logo)
  <img src="{{ $site_logo }}" alt="logo" class="h-8 w-auto max-w-32 object-contain rounded-md bg-white px-2 py-1">
  @else
  <div class="h-8 w-8 rounded-md bg-white/10 flex items-center justify-center shrink-0"><flux:icon.building-office class="size-5 text-white/70" /></div>
  @endif
  <div class="min-w-0">
  <div class="text-sm font-semibold text-white leading-none truncate">{{ config('app.name', 'SIBEA') }}</div>
  <div class="text-xs text-white/60">{{ __('Sidebar #111c43') }}</div>
  </div>
  </div>
  <div class="mt-2 text-xs text-zinc-500">{{ __('Recommandé: SVG/PNG rectangulaire ou carré, hauteur 28px, max 2Mo, fond transparent') }}</div>
 </div>
 </div>
 </div>

 <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
 <flux:input wire:model.live.debounce.300ms="search" placeholder="{{ __('Rechercher clé...') }}" class="max-w-sm" />
 @can('create', App\Models\SiteSetting::class)
 <flux:button variant="primary" icon="plus" wire:click="openCreate">{{ __('Nouveau paramètre') }}</flux:button>
 @endcan
 </div>

 @foreach($this->groupedSettings as $group => $items)
 <div class="mb-6 border rounded-lg border-zinc-200 overflow-hidden">
 <div class="bg-zinc-50 px-4 py-2 text-sm font-semibold">{{ $group }}</div>
 <div class="divide-y divide-zinc-200">
 @foreach($items as $s)
 <div class="flex items-center justify-between px-4 py-3">
 <div>
 <div class="font-medium text-sm">{{ $s->key }}</div>
 <div class="text-xs text-zinc-500 line-clamp-1">{{ $s->value ?? '—' }}</div>
 </div>
 <div class="flex gap-1">
 @can('update', $s)<flux:button variant="ghost" size="sm" icon="pencil-square" wire:click="openEdit({{ $s->id }})" />@endcan
 @can('delete', $s)<flux:button variant="ghost" size="sm" icon="trash" wire:click="delete({{ $s->id }})" wire:confirm="{{ __('Supprimer ?') }}" class="text-red-500" />@endcan
 </div>
 </div>
 @endforeach
 </div>
 </div>
 @endforeach

 <flux:modal wire:model="showModal" class="max-w-lg" @close="closeModal">
 <form wire:submit="save" class="space-y-6">
 <flux:heading size="lg">{{ $editingId ? __('Modifier') : __('Nouveau paramètre') }}</flux:heading>
 <flux:input wire:model="key" :label="__('Clé (a-z, _, .)')" required placeholder="hero_title" />
 <flux:input wire:model="group" :label="__('Groupe')" required placeholder="general" />
 <flux:textarea wire:model="value" :label="__('Valeur')" rows="4" />
 <div class="flex justify-end gap-2">
 <flux:button variant="ghost" wire:click="closeModal" type="button">{{ __('Annuler') }}</flux:button>
 <flux:button variant="primary" type="submit">{{ $editingId ? __('Mettre à jour') : __('Créer') }}</flux:button>
 </div>
 </form>
 </flux:modal>
</section>
