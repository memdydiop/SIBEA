<?php

use App\Actions\Audit\LogAuditAction;
use App\Actions\Cms\CreateProgramAction;
use App\Actions\Cms\CreateProgramLotAction;
use App\Actions\Cms\UpdateProgramAction;
use App\Actions\Cms\UpdateProgramLotAction;
use App\Models\Program;
use App\Models\ProgramLot;
use Flux\Flux;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

new #[Title('Programmes')] class extends Component {
    use WithPagination;
    use WithFileUploads;

    public string $slug = '';
    public string $title = '';
    public ?string $city = null;
    public ?string $municipality = null;
    public ?string $district = null;
    public ?string $total_area = null;
    public ?string $excerpt = null;
    public ?string $description = null;
    public ?string $cover_path = null;
    public $cover_image_upload = null;
    public ?string $meta_title = null;
    public ?string $meta_description = null;
    public int $total_lots = 0;
    public bool $is_published = true;
    public ?string $published_at = null;
    public int $order = 0;

    public ?int $editingId = null;
    public bool $showModal = false;
    public string $search = '';
    public ?int $expandedProgramId = null;

    // Lots inline (gestion depuis Programme) — référence = numéro, max d'infos
    public ?int $lotEditingId = null;
    public bool $showLotModal = false;
    public ?int $lot_program_id = null;
    public string $lot_reference = '';
    public ?string $lot_surface = null;
    public ?string $lot_price = null;
    public string $lot_status = 'disponible';
    public bool $lot_is_viabilise = true;
    public ?string $lot_juridical_status = null;
    public ?string $lot_plan_pdf_path = null;
    public $lot_plan_pdf_upload = null;
    public ?string $lot_latitude = null;
    public ?string $lot_longitude = null;
    public ?string $lot_published_at = null;

    public function mount(): void
    {
        Gate::authorize('viewAny', Program::class);
    }

    #[Computed]
    public function programs()
    {
        return Program::when($this->search, fn ($q) => $q->where('title', 'ilike', "%{$this->search}%")->orWhere('slug', 'ilike', "%{$this->search}%"))
            ->orderBy('order')
            ->paginate(15);
    }

    #[Computed]
    public function expandedProgram()
    {
        return $this->expandedProgramId ? Program::withCount('lots')->find($this->expandedProgramId) : null;
    }

    #[Computed]
    public function expandedLots()
    {
        if (! $this->expandedProgramId) {
            return collect();
        }

        return ProgramLot::where('program_id', $this->expandedProgramId)->orderByDesc('id')->get();
    }

    public function toggleLots(int $programId): void
    {
        $this->expandedProgramId = $this->expandedProgramId === $programId ? null : $programId;
        unset($this->expandedProgram, $this->expandedLots);
    }

    public function openCreateLot(?int $programId = null): void
    {
        Gate::authorize('create', ProgramLot::class);
        $this->reset(['lot_reference', 'lot_surface', 'lot_price', 'lot_is_viabilise', 'lot_juridical_status', 'lot_plan_pdf_path', 'lot_plan_pdf_upload', 'lot_latitude', 'lot_longitude', 'lot_published_at', 'lotEditingId']);
        $this->lot_program_id = $programId ?? $this->expandedProgramId ?? $this->programs()->first()?->id;
        $this->lot_status = 'disponible';
        $this->lot_is_viabilise = true;
        $this->lot_published_at = now()->format('Y-m-d');
        $this->showLotModal = true;
    }

    public function openEditLot(int $id): void
    {
        $lot = ProgramLot::findOrFail($id);
        Gate::authorize('update', $lot);
        $this->lotEditingId = $lot->id;
        $this->lot_program_id = $lot->program_id;
        $this->lot_reference = $lot->reference;
        $this->lot_surface = $lot->surface !== null ? (string) $lot->surface : null;
        $this->lot_price = $lot->price !== null ? (string) $lot->price : null;
        $this->lot_status = $lot->status instanceof \BackedEnum ? $lot->status->value : (string) $lot->status;
        $this->lot_is_viabilise = $lot->is_viabilise;
        $this->lot_juridical_status = $lot->juridical_status;
        $this->lot_plan_pdf_path = $lot->plan_pdf_path;
        $this->lot_latitude = $lot->latitude !== null ? (string) $lot->latitude : null;
        $this->lot_longitude = $lot->longitude !== null ? (string) $lot->longitude : null;
        $this->lot_published_at = $lot->published_at?->format('Y-m-d');
        $this->showLotModal = true;
    }

    public function saveLot(CreateProgramLotAction $create, UpdateProgramLotAction $update, LogAuditAction $audit): void
    {
        if ($this->lot_plan_pdf_upload) {
            Validator::make(['plan_pdf_upload' => $this->lot_plan_pdf_upload], ['plan_pdf_upload' => ['file', 'mimes:pdf', 'max:5120']])->validate();
            $path = $this->lot_plan_pdf_upload->store('cms/plots', 'public');
            $this->lot_plan_pdf_path = '/storage/'.$path;
        }
        if ($this->lot_plan_pdf_path && str_starts_with($this->lot_plan_pdf_path, 'cms/')) {
            $this->lot_plan_pdf_path = '/storage/'.$this->lot_plan_pdf_path;
        }

        $data = [
            'program_id' => $this->lot_program_id,
            'reference' => $this->lot_reference,
            'surface' => $this->lot_surface !== null && $this->lot_surface !== '' ? $this->lot_surface : null,
            'price' => $this->lot_price !== null && $this->lot_price !== '' ? $this->lot_price : null,
            'status' => $this->lot_status,
            'is_viabilise' => $this->lot_is_viabilise,
            'juridical_status' => $this->lot_juridical_status,
            'plan_pdf_path' => $this->lot_plan_pdf_path,
            'latitude' => $this->lot_latitude !== null && $this->lot_latitude !== '' ? $this->lot_latitude : null,
            'longitude' => $this->lot_longitude !== null && $this->lot_longitude !== '' ? $this->lot_longitude : null,
            'published_at' => $this->lot_published_at,
        ];

        if ($this->lotEditingId) {
            $lot = ProgramLot::findOrFail($this->lotEditingId);
            Gate::authorize('update', $lot);
            $old = $lot->toArray();
            $updated = $update($lot, $data);
            $audit('PROGRAM_LOT_UPDATED', $updated, $old, $updated->toArray());
            Flux::toast(variant: 'success', text: __('Lot mis à jour.'));
        } else {
            Gate::authorize('create', ProgramLot::class);
            $lot = $create($data);
            $audit('PROGRAM_LOT_CREATED', $lot, null, $lot->toArray());
            Flux::toast(variant: 'success', text: __('Lot créé.'));
        }

        // Synchronise total_lots dénormalisé
        if ($this->lot_program_id) {
            $prog = Program::find($this->lot_program_id);
            if ($prog) {
                $prog->update(['total_lots' => $prog->lots()->count()]);
            }
        }

        $this->showLotModal = false;
        $this->reset(['lotEditingId']);
        unset($this->expandedLots, $this->expandedProgram, $this->programs);
    }

    public function deleteLot(int $id, LogAuditAction $audit): void
    {
        $lot = ProgramLot::findOrFail($id);
        Gate::authorize('delete', $lot);
        $programId = $lot->program_id;
        $old = $lot->toArray();
        $lot->delete();
        $audit('PROGRAM_LOT_DELETED', ProgramLot::class, $old, null);
        Flux::toast(variant: 'success', text: __('Lot supprimé.'));

        $prog = Program::find($programId);
        if ($prog) {
            $prog->update(['total_lots' => $prog->lots()->count()]);
        }

        unset($this->expandedLots, $this->expandedProgram, $this->programs);
    }

    public function closeLotModal(): void
    {
        $this->showLotModal = false;
        $this->reset(['lotEditingId']);
    }

    public function openCreate(): void
    {
        Gate::authorize('create', Program::class);
        $this->reset(['slug', 'title', 'city', 'municipality', 'district', 'total_area', 'excerpt', 'description', 'cover_path', 'cover_image_upload', 'editingId']);
        $this->total_lots = 0;
        $this->order = 0;
        $this->is_published = true;
        $this->published_at = now()->format('Y-m-d');
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $program = Program::findOrFail($id);
        Gate::authorize('update', $program);
        $this->editingId = $program->id;
        $this->slug = $program->slug;
        $this->title = $program->title;
        $this->city = $program->city;
        $this->municipality = $program->municipality;
        $this->district = $program->district;
        $this->total_area = $program->total_area !== null ? (string) $program->total_area : null;
        $this->excerpt = $program->excerpt;
        $this->description = $program->description;
        $this->meta_title = $program->meta_title;
        $this->meta_description = $program->meta_description;
        $this->cover_path = $program->cover_path;
        $this->total_lots = $program->total_lots;
        $this->is_published = $program->is_published;
        $this->published_at = $program->published_at?->format('Y-m-d');
        $this->order = $program->order;
        $this->showModal = true;
    }

    public function save(CreateProgramAction $create, UpdateProgramAction $update, LogAuditAction $audit): void
    {
        if ($this->cover_image_upload) {
            Validator::make(
                ['cover_image_upload' => $this->cover_image_upload],
                ['cover_image_upload' => ['image', 'mimes:jpeg,png,webp,svg', 'max:2048', 'dimensions:max_width=4000,max_height=4000']],
            )->validate();

            $path = $this->cover_image_upload->store('cms/programs', 'public');
            $this->cover_path = '/storage/'.$path;
        }

        if ($this->cover_path && str_starts_with($this->cover_path, 'cms/')) {
            $this->cover_path = '/storage/'.$this->cover_path;
        }

        $data = [
            'slug' => $this->slug ?: null,
            'title' => $this->title,
            'city' => $this->city,
            'municipality' => $this->municipality,
            'district' => $this->district,
            'total_area' => $this->total_area !== null && $this->total_area !== '' ? $this->total_area : null,
            'excerpt' => $this->excerpt,
            'description' => $this->description,
            'cover_path' => $this->cover_path,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'total_lots' => $this->total_lots,
            'is_published' => $this->is_published,
            'published_at' => $this->published_at,
            'order' => $this->order,
        ];

        if ($this->editingId) {
            $program = Program::findOrFail($this->editingId);
            Gate::authorize('update', $program);
            $old = $program->toArray();
            $updated = $update($program, $data);
            $audit('PROGRAM_UPDATED', $updated, $old, $updated->toArray());
            Flux::toast(variant: 'success', text: __('Programme mis à jour.'));
        } else {
            Gate::authorize('create', Program::class);
            $program = $create($data);
            $audit('PROGRAM_CREATED', $program, null, $program->toArray());
            Flux::toast(variant: 'success', text: __('Programme créé.'));
        }

        $this->showModal = false;
        $this->reset(['editingId']);
        unset($this->programs);
    }

    public function delete(int $id, LogAuditAction $audit): void
    {
        $program = Program::findOrFail($id);
        Gate::authorize('delete', $program);
        $old = $program->toArray();
        $program->delete();
        $audit('PROGRAM_DELETED', Program::class, $old, null);
        Flux::toast(variant: 'success', text: __('Programme supprimé.'));
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->reset(['editingId']);
    }
}; ?>

<section class="w-full">
    <flux:heading size="xl" level="1">{{ __('Programmes') }}</flux:heading>
    <flux:subheading class="mb-6">{{ __('Gestion des programmes immobiliers — vitrine SIBEA') }}</flux:subheading>

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
        <flux:input wire:model.live.debounce.300ms="search" placeholder="{{ __('Rechercher titre ou slug...') }}" class="max-w-sm" />
        @can('create', App\Models\Program::class)
            <flux:button variant="primary" icon="plus" wire:click="openCreate">{{ __('Nouveau programme') }}</flux:button>
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
                        <th class="text-left px-4 py-3">{{ __('Ville') }}</th>
                        <th class="text-left px-4 py-3">{{ __('Commune') }}</th>
                        <th class="text-left px-4 py-3">{{ __('Quartier') }}</th>
                        <th class="text-center px-4 py-3">{{ __('Lots') }}</th>
                        <th class="text-center px-4 py-3">{{ __('Publié') }}</th>
                        <th class="text-right px-4 py-3">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse($this->programs as $program)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/30 {{ $expandedProgramId === $program->id ? 'bg-zinc-50 dark:bg-zinc-800/40' : '' }}">
                            <td class="px-4 py-3">{{ $program->order }}</td>
                            <td class="px-4 py-3 font-medium">{{ $program->title }}</td>
                            <td class="px-4 py-3"><flux:badge size="sm">{{ $program->slug }}</flux:badge></td>
                            <td class="px-4 py-3">{{ $program->city ?? '—' }}</td>
                            <td class="px-4 py-3">{{ $program->municipality ?? '—' }}</td>
                            <td class="px-4 py-3">{{ $program->district ?? '—' }}</td>
                            <td class="px-4 py-3 text-center">
                                <div class="inline-flex items-center gap-1">
                                    <span>{{ $program->total_lots }}</span>
                                    <flux:button variant="ghost" size="xs" :icon="$expandedProgramId === $program->id ? 'chevron-up' : 'chevron-down'" wire:click="toggleLots({{ $program->id }})" :label="__('Lots')" />
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center">@if($program->is_published)<flux:badge variant="success" size="sm">{{ __('Oui') }}</flux:badge>@else<flux:badge variant="zinc" size="sm">{{ __('Non') }}</flux:badge>@endif</td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-1">
                                    @can('create', App\Models\ProgramLot::class)<flux:button variant="ghost" size="sm" icon="squares-plus" wire:click="openCreateLot({{ $program->id }})" :label="__('Lot')" />@endcan
                                    @can('update', $program)<flux:button variant="ghost" size="sm" icon="pencil-square" wire:click="openEdit({{ $program->id }})" />@endcan
                                    @can('delete', $program)<flux:button variant="ghost" size="sm" icon="trash" wire:click="delete({{ $program->id }})" wire:confirm="{{ __('Supprimer ?') }}" class="text-red-500" />@endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="px-4 py-8 text-center text-zinc-500">{{ __('Aucun programme.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t flex items-center justify-between">
            <div>{{ $this->programs->links() }}</div>
            <div class="text-xs text-zinc-500">{{ __('Cliquez sur chevron pour gérer les lots du programme sans changer de page.') }}</div>
        </div>
    </div>

    {{-- Lots intégrés au programme sélectionné --}}
    @if($expandedProgramId && $this->expandedProgram)
        <div class="mt-6 border rounded-lg border-zinc-200 dark:border-zinc-700 overflow-hidden">
            <div class="bg-zinc-50 dark:bg-zinc-800/50 px-4 py-3 flex items-center justify-between">
                <div class="font-semibold text-sm">{{ __('Lots de') }} {{ $this->expandedProgram->title }} <flux:badge size="sm">{{ $this->expandedLots->count() }} / {{ $this->expandedProgram->total_lots }} {{ __('lots') }}</flux:badge></div>
                <div class="flex gap-2">
                    @can('create', App\Models\ProgramLot::class)<flux:button variant="primary" size="sm" icon="plus" wire:click="openCreateLot({{ $this->expandedProgram->id }})">{{ __('Nouveau lot') }}</flux:button>@endcan
                    <flux:button variant="ghost" size="sm" icon="x-mark" wire:click="$set('expandedProgramId', null)">{{ __('Fermer') }}</flux:button>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-white dark:bg-zinc-900 text-zinc-500">
                        <tr>
                            <th class="text-left px-4 py-2">{{ __('Référence') }}</th>
                            <th class="text-right px-4 py-2">{{ __('Surface') }}</th>
                            <th class="text-right px-4 py-2">{{ __('Prix') }}</th>
                            <th class="text-center px-4 py-2">{{ __('Statut') }}</th>
                            <th class="text-center px-4 py-2">{{ __('Viabilisé') }}</th>
                            <th class="text-left px-4 py-2">{{ __('Juridique') }}</th>
                            <th class="text-left px-4 py-2">{{ __('GPS') }}</th>
                            <th class="text-right px-4 py-2">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                        @forelse($this->expandedLots as $lot)
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/30">
                                <td class="px-4 py-2 font-mono text-xs">{{ $lot->reference }}</td>
                                <td class="px-4 py-2 text-right text-xs">{{ $lot->surface !== null ? number_format((float) $lot->surface, 2, ',', ' ') : '—' }}</td>
                                <td class="px-4 py-2 text-right text-xs">{{ $lot->price !== null ? number_format((float) $lot->price, 2, ',', ' ').' FCFA' : '—' }}</td>
                                <td class="px-4 py-2 text-center">
                                    @php $ls = $lot->status instanceof \BackedEnum ? $lot->status->value : $lot->status; @endphp
                                    @if($ls === 'disponible')<flux:badge variant="success" size="sm">{{ __('disponible') }}</flux:badge>
                                    @elseif($ls === 'reserve')<flux:badge variant="warning" size="sm">{{ __('réservé') }}</flux:badge>
                                    @elseif($ls === 'vendu')<flux:badge variant="danger" size="sm">{{ __('vendu') }}</flux:badge>
                                    @else<flux:badge size="sm">{{ $ls }}</flux:badge>
                                    @endif
                                </td>
                                <td class="px-4 py-2 text-center">@if($lot->is_viabilise)<flux:badge variant="success" size="sm">Oui</flux:badge>@else<flux:badge variant="zinc" size="sm">Non</flux:badge>@endif</td>
                                <td class="px-4 py-2 text-xs">{{ $lot->juridical_status ?? '—' }}</td>
                                <td class="px-4 py-2 text-xs font-mono">@if($lot->latitude && $lot->longitude){{ number_format((float)$lot->latitude,4) }},{{ number_format((float)$lot->longitude,4) }}@else — @endif</td>
                                <td class="px-4 py-2 text-right">
                                    <div class="flex justify-end gap-1">
                                        @if($lot->plan_pdf_path)<a href="{{ $lot->plan_pdf_path }}" target="_blank" class="text-xs underline text-primary-600">PDF</a>@endif
                                        @can('update', $lot)<flux:button variant="ghost" size="xs" icon="pencil-square" wire:click="openEditLot({{ $lot->id }})" />@endcan
                                        @can('delete', $lot)<flux:button variant="ghost" size="xs" icon="trash" wire:click="deleteLot({{ $lot->id }})" wire:confirm="{{ __('Supprimer ?') }}" class="text-red-500" />@endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="px-4 py-8 text-center text-zinc-500">{{ __('Aucun lot pour ce programme. Créez le premier.') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <flux:modal wire:model="showModal" class="max-w-2xl" @close="closeModal">
        <form wire:submit="save" class="space-y-6">
            <flux:heading size="lg">{{ $editingId ? __('Modifier le programme') : __('Nouveau programme') }}</flux:heading>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <flux:input wire:model="title" :label="__('Titre')" required class="sm:col-span-2" />
                <flux:input wire:model="slug" :label="__('Slug (auto si vide)')" placeholder="les-jardins-de-cocody" />
                <flux:input wire:model="city" :label="__('Ville')" placeholder="Abidjan" />
                <flux:input wire:model="municipality" :label="__('Commune')" placeholder="Cocody" />
                <flux:input wire:model="district" :label="__('Quartier')" placeholder="Riviera Golf" />
                <flux:input wire:model="total_area" type="number" step="0.01" :label="__('Surface totale (m²)')" placeholder="25000" />
                <flux:input wire:model="order" type="number" :label="__('Ordre')" min="0" />
                <flux:input wire:model="total_lots" type="number" :label="__('Nombre total de lots')" min="0" />
                <flux:input wire:model="published_at" type="date" :label="__('Date publication')" />
                <div class="flex flex-col gap-2 justify-end">
                    <flux:checkbox wire:model="is_published" :label="__('Publié')" />
                </div>
                <flux:input wire:model="cover_path" :label="__('Image couverture URL')" placeholder="https:// ou /storage/cms/programs/..." class="sm:col-span-2" />
                <flux:input type="file" wire:model="cover_image_upload" :label="__('Ou fichier image (jpeg,png,webp,svg max 2Mo)')" accept="image/jpeg,image/png,image/webp,image/svg+xml" />
                <div class="sm:col-span-2 -mt-2 flex items-center gap-2">
                    <a href="{{ route('admin.media') }}" target="_blank" class="text-xs text-zinc-500 underline hover:text-zinc-700">{{ __('Ouvrir la médiathèque') }} →</a>
                    <span class="text-xs text-zinc-400">{{ __('ou uploader ci-dessus') }}</span>
                </div>
                @if($cover_path)
                    <div class="sm:col-span-2">
                        <div class="text-xs text-zinc-500 mb-2">{{ __('Aperçu :') }}</div>
                        <img src="{{ $cover_path }}" alt="cover preview" class="h-32 w-full object-cover rounded border">
                    </div>
                @endif
                @if($cover_image_upload)
                    <div class="sm:col-span-2 text-xs text-zinc-500">{{ __('Nouveau fichier sélectionné — sera stocké dans cms/programs') }}</div>
                @endif
                <div class="sm:col-span-2"><flux:textarea wire:model="excerpt" :label="__('Extrait')" rows="2" placeholder="{{ __('Résumé court pour cartes') }}" /></div>
                <div class="sm:col-span-2"><flux:textarea wire:model="description" :label="__('Description')" rows="4" placeholder="{{ __('Contenu détaillé') }}" /></div>
                <flux:input wire:model="meta_title" :label="__('Meta titre (SEO)')" placeholder="SEO" />
                <flux:input wire:model="meta_description" :label="__('Meta description (SEO)')" />
            </div>
            <div class="flex justify-end gap-2">
                <flux:button variant="ghost" wire:click="closeModal" type="button">{{ __('Annuler') }}</flux:button>
                <flux:button variant="primary" type="submit">{{ $editingId ? __('Mettre à jour') : __('Créer') }}</flux:button>
            </div>
        </form>
    </flux:modal>

    <flux:modal wire:model="showLotModal" class="max-w-xl" @close="closeLotModal">
        <form wire:submit="saveLot" class="space-y-6">
            <flux:heading size="lg">{{ $lotEditingId ? __('Modifier le lot') : __('Nouveau lot') }} @if($lot_program_id) <span class="text-sm font-normal text-zinc-500">— {{ Program::find($lot_program_id)?->title }}</span> @endif</flux:heading>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <flux:select wire:model="lot_program_id" :label="__('Programme')" required>
                    <flux:select.option value="">{{ __('Choisir un programme') }}</flux:select.option>
                    @foreach(\App\Models\Program::orderBy('order')->get() as $prog)
                        <flux:select.option value="{{ $prog->id }}">{{ $prog->title }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:input wire:model="lot_reference" :label="__('Référence')" required placeholder="Lot 0001" />
                <flux:input wire:model="lot_surface" type="number" step="0.01" min="0" :label="__('Surface (m²)')" />
                <flux:input wire:model="lot_price" type="number" step="0.01" min="0" :label="__('Prix (FCFA)')" />
                <flux:select wire:model="lot_status" :label="__('Statut')" required>
                    <flux:select.option value="disponible">{{ __('disponible') }}</flux:select.option>
                    <flux:select.option value="option">{{ __('option') }}</flux:select.option>
                    <flux:select.option value="reserve">{{ __('réservé') }}</flux:select.option>
                    <flux:select.option value="vendu">{{ __('vendu') }}</flux:select.option>
                </flux:select>
                <flux:checkbox wire:model="lot_is_viabilise" :label="__('Viabilisé')" />
                <flux:input wire:model="lot_juridical_status" :label="__('Statut juridique (ACD)')" placeholder="ACD, TF" />
                <flux:input wire:model="lot_plan_pdf_path" :label="__('Plan PDF URL')" placeholder="/storage/cms/plots/plan.pdf" />
                <flux:input type="file" wire:model="lot_plan_pdf_upload" :label="__('Ou PDF plan (max 5Mo)')" accept="application/pdf" />
                <flux:input wire:model="lot_latitude" type="number" step="0.0000001" :label="__('Latitude')" placeholder="5.3456" />
                <flux:input wire:model="lot_longitude" type="number" step="0.0000001" :label="__('Longitude')" placeholder="-4.0123" />
                <flux:input wire:model="lot_published_at" type="date" :label="__('Date publication')" />
            </div>
            <div class="flex justify-end gap-2">
                <flux:button variant="ghost" wire:click="closeLotModal" type="button">{{ __('Annuler') }}</flux:button>
                <flux:button variant="primary" type="submit">{{ $lotEditingId ? __('Mettre à jour') : __('Créer') }}</flux:button>
            </div>
        </form>
    </flux:modal>
</section>
