<?php

use App\Actions\Audit\LogAuditAction;
use App\Models\Testimonial;
use Flux\Flux;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

new #[Title('Témoignages')] class extends Component {
    use WithPagination;

    public string $author_name = '';
    public ?string $company = null;
    public ?string $role = null;
    public string $content = '';
    public int $rating = 5;
    public ?string $avatar_url = null;
    public int $order = 0;
    public bool $is_active = true;

    public ?int $editingId = null;
    public bool $showModal = false;
    public string $search = '';

    public function mount(): void
    {
        Gate::authorize('viewAny', Testimonial::class);
    }

    #[Computed]
    public function testimonials()
    {
        return Testimonial::when($this->search, fn ($q) => $q->where('author_name', 'ilike', "%{$this->search}%"))
            ->orderBy('order')
            ->paginate(15);
    }

    public function openCreate(): void
    {
        Gate::authorize('create', Testimonial::class);
        $this->reset(['author_name', 'company', 'role', 'content', 'avatar_url', 'editingId']);
        $this->rating = 5;
        $this->order = 0;
        $this->is_active = true;
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $t = Testimonial::findOrFail($id);
        Gate::authorize('update', $t);
        $this->editingId = $t->id;
        $this->author_name = $t->author_name;
        $this->company = $t->company;
        $this->role = $t->role;
        $this->content = $t->content;
        $this->rating = $t->rating;
        $this->avatar_url = $t->avatar_url;
        $this->order = $t->order;
        $this->is_active = $t->is_active;
        $this->showModal = true;
    }

    public function save(LogAuditAction $audit): void
    {
        $data = Validator::make([
            'author_name' => $this->author_name,
            'company' => $this->company,
            'role' => $this->role,
            'content' => $this->content,
            'rating' => $this->rating,
            'avatar_url' => $this->avatar_url,
            'order' => $this->order,
            'is_active' => $this->is_active,
        ], [
            'author_name' => ['required', 'string', 'max:150'],
            'company' => ['nullable', 'string', 'max:150'],
            'role' => ['nullable', 'string', 'max:100'],
            'content' => ['required', 'string'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'avatar_url' => ['nullable', 'string', 'max:255', 'url'],
            'order' => ['nullable', 'integer'],
            'is_active' => ['boolean'],
        ])->validate();

        if ($this->editingId) {
            $t = Testimonial::findOrFail($this->editingId);
            Gate::authorize('update', $t);
            $old = $t->toArray();
            $t->update($data);
            $audit('TESTIMONIAL_UPDATED', $t, $old, $t->toArray());
            Flux::toast(variant: 'success', text: __('Témoignage mis à jour.'));
        } else {
            Gate::authorize('create', Testimonial::class);
            $t = Testimonial::create($data);
            $audit('TESTIMONIAL_CREATED', $t, null, $t->toArray());
            Flux::toast(variant: 'success', text: __('Témoignage créé.'));
        }

        $this->showModal = false;
        $this->reset(['editingId']);
        unset($this->testimonials);
    }

    public function delete(int $id, LogAuditAction $audit): void
    {
        $t = Testimonial::findOrFail($id);
        Gate::authorize('delete', $t);
        $old = $t->toArray();
        $t->delete();
        $audit('TESTIMONIAL_DELETED', Testimonial::class, $old, null);
        Flux::toast(variant: 'success', text: __('Témoignage supprimé.'));
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->reset(['editingId']);
    }
}; ?>

<section class="w-full">
    <flux:heading size="xl" level="1">{{ __('Témoignages') }}</flux:heading>
    <flux:subheading class="mb-6">{{ __('Avis clients — affichés sur l’accueil') }}</flux:subheading>

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
        <flux:input wire:model.live.debounce.300ms="search" placeholder="{{ __('Rechercher auteur...') }}" class="max-w-sm" />
        @can('create', App\Models\Testimonial::class)
            <flux:button variant="primary" icon="plus" wire:click="openCreate">{{ __('Nouveau témoignage') }}</flux:button>
        @endcan
    </div>

    <div class="border rounded-lg border-zinc-200 dark:border-zinc-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-zinc-50 dark:bg-zinc-800/50 text-zinc-500">
                    <tr>
                        <th class="text-left px-4 py-3">{{ __('Auteur') }}</th>
                        <th class="text-left px-4 py-3">{{ __('Société') }}</th>
                        <th class="text-center px-4 py-3">{{ __('Note') }}</th>
                        <th class="text-center px-4 py-3">{{ __('Actif') }}</th>
                        <th class="text-right px-4 py-3">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse($this->testimonials as $t)
                        <tr>
                            <td class="px-4 py-3 font-medium">{{ $t->author_name }}<div class="text-xs text-zinc-500">{{ $t->role }}</div></td>
                            <td class="px-4 py-3">{{ $t->company ?? '—' }}</td>
                            <td class="px-4 py-3 text-center">{{ $t->rating }}/5</td>
                            <td class="px-4 py-3 text-center">@if($t->is_active)<flux:badge variant="success" size="sm">{{ __('Oui') }}</flux:badge>@else<flux:badge variant="zinc" size="sm">{{ __('Non') }}</flux:badge>@endif</td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-1">
                                    @can('update', $t)<flux:button variant="ghost" size="sm" icon="pencil-square" wire:click="openEdit({{ $t->id }})" />@endcan
                                    @can('delete', $t)<flux:button variant="ghost" size="sm" icon="trash" wire:click="delete({{ $t->id }})" wire:confirm="{{ __('Supprimer ?') }}" class="text-red-500" />@endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-8 text-center text-zinc-500">{{ __('Aucun témoignage.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t">{{ $this->testimonials->links() }}</div>
    </div>

    <flux:modal wire:model="showModal" class="max-w-2xl" @close="closeModal">
        <form wire:submit="save" class="space-y-6">
            <flux:heading size="lg">{{ $editingId ? __('Modifier') : __('Nouveau témoignage') }}</flux:heading>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <flux:input wire:model="author_name" :label="__('Auteur')" required />
                <flux:input wire:model="company" :label="__('Société')" />
                <flux:input wire:model="role" :label="__('Rôle')" />
                <flux:input wire:model="rating" type="number" min="1" max="5" :label="__('Note /5')" required />
                <flux:input wire:model="avatar_url" :label="__('Avatar URL')" />
                <flux:input wire:model="order" type="number" :label="__('Ordre')" />
                <flux:checkbox wire:model="is_active" :label="__('Actif')" />
                <div class="sm:col-span-2"><flux:textarea wire:model="content" :label="__('Contenu')" rows="4" required /></div>
            </div>
            <div class="flex justify-end gap-2">
                <flux:button variant="ghost" wire:click="closeModal" type="button">{{ __('Annuler') }}</flux:button>
                <flux:button variant="primary" type="submit">{{ $editingId ? __('Mettre à jour') : __('Créer') }}</flux:button>
            </div>
        </form>
    </flux:modal>
</section>
