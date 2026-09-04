<?php

use App\Actions\Audit\LogAuditAction;
use App\Models\SiteSetting;
use Flux\Flux;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Paramètres du site')] class extends Component {
    public string $search = '';
    public string $key = '';
    public ?string $value = null;
    public string $group = 'general';
    public ?int $editingId = null;
    public bool $showModal = false;

    public function mount(): void
    {
        Gate::authorize('viewAny', SiteSetting::class);
    }

    #[Computed]
    public function settings()
    {
        return SiteSetting::when($this->search, fn ($q) => $q->where('key', 'ilike', "%{$this->search}%"))
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

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->reset(['editingId']);
    }
}; ?>

<section class="w-full">
    <flux:heading size="xl" level="1">{{ __('Paramètres du site') }}</flux:heading>
    <flux:subheading class="mb-6">{{ __('Clés vitrine : hero_title, hero_subtitle, contact_* etc. Group general/contact/seo') }}</flux:subheading>

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
        <flux:input wire:model.live.debounce.300ms="search" placeholder="{{ __('Rechercher clé...') }}" class="max-w-sm" />
        @can('create', App\Models\SiteSetting::class)
            <flux:button variant="primary" icon="plus" wire:click="openCreate">{{ __('Nouveau paramètre') }}</flux:button>
        @endcan
    </div>

    @foreach($this->groupedSettings as $group => $items)
        <div class="mb-6 border rounded-lg border-zinc-200 dark:border-zinc-700 overflow-hidden">
            <div class="bg-zinc-50 dark:bg-zinc-800/50 px-4 py-2 text-sm font-semibold">{{ $group }}</div>
            <div class="divide-y divide-zinc-200 dark:divide-zinc-700">
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
