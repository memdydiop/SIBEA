<?php

use App\Actions\Audit\LogAuditAction;
use App\Actions\Cms\CreateProgramLotAction;
use App\Actions\Cms\UpdateProgramLotAction;
use App\Models\Program;
use App\Models\ProgramLot;
use Flux\Flux;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

new #[Title('Lots')] class extends Component {
 use WithFileUploads;
 use WithPagination;

 public ?int $program_id = null;
 public string $reference = '';
 public ?string $surface = null;
 public ?string $price = null;
 public string $status = 'disponible';
 public bool $is_viabilise = true;
 public ?string $juridical_status = null;
 public ?string $plan_pdf_path = null;
 public $plan_pdf_upload = null;
 public ?string $latitude = null;
 public ?string $longitude = null;
 public ?string $published_at = null;

 public ?int $editingId = null;
 public bool $showModal = false;
 public string $search = '';
 public ?int $filterProgramId = null;

 public function mount(): void
 {
 Gate::authorize('viewAny', ProgramLot::class);
 }

 #[Computed]
 public function lots()
 {
 return ProgramLot::with('program')
 ->when($this->filterProgramId, fn ($q) => $q->where('program_id', $this->filterProgramId))
 ->when($this->search, fn ($q) => $q->where('reference', 'ilike', "%{$this->search}%"))
 ->orderByDesc('id')
 ->paginate(15);
 }

 #[Computed]
 public function programs()
 {
 return Program::orderBy('order')->get();
 }

 public function openCreate(): void
 {
 Gate::authorize('create', ProgramLot::class);
 $this->reset(['reference', 'surface', 'price', 'editingId', 'is_viabilise', 'juridical_status', 'plan_pdf_path', 'plan_pdf_upload', 'latitude', 'longitude', 'published_at']);
 $this->program_id = $this->filterProgramId ?? $this->programs->first()?->id;
 $this->status = 'disponible';
 $this->is_viabilise = true;
 $this->published_at = now()->format('Y-m-d');
 $this->showModal = true;
 }

 public function openEdit(int $id): void
 {
 $lot = ProgramLot::findOrFail($id);
 Gate::authorize('update', $lot);
 $this->editingId = $lot->id;
 $this->program_id = $lot->program_id;
 $this->reference = $lot->reference;
 $this->surface = $lot->surface !== null ? (string) $lot->surface : null;
 $this->price = $lot->price !== null ? (string) $lot->price : null;
 $this->status = $lot->status instanceof \BackedEnum ? $lot->status->value : (string) $lot->status;
 $this->is_viabilise = $lot->is_viabilise;
 $this->juridical_status = $lot->juridical_status;
 $this->plan_pdf_path = $lot->plan_pdf_path;
 $this->latitude = $lot->latitude !== null ? (string) $lot->latitude : null;
 $this->longitude = $lot->longitude !== null ? (string) $lot->longitude : null;
 $this->published_at = $lot->published_at?->format('Y-m-d');
 $this->showModal = true;
 }

 public function save(CreateProgramLotAction $create, UpdateProgramLotAction $update, LogAuditAction $audit): void
 {
 if ($this->plan_pdf_upload) {
 Validator::make(
 ['plan_pdf_upload' => $this->plan_pdf_upload],
 ['plan_pdf_upload' => ['file', 'mimes:pdf', 'max:5120']],
 )->validate();
 $path = $this->plan_pdf_upload->store('cms/plots', 'public');
 $this->plan_pdf_path = '/storage/'.$path;
 }

 if ($this->plan_pdf_path && str_starts_with($this->plan_pdf_path, 'cms/')) {
 $this->plan_pdf_path = '/storage/'.$this->plan_pdf_path;
 }

 $data = [
 'program_id' => $this->program_id,
 'reference' => $this->reference,
 'surface' => $this->surface !== null && $this->surface !== '' ? $this->surface : null,
 'price' => $this->price !== null && $this->price !== '' ? $this->price : null,
 'status' => $this->status,
 'is_viabilise' => $this->is_viabilise,
 'juridical_status' => $this->juridical_status,
 'plan_pdf_path' => $this->plan_pdf_path,
 'latitude' => $this->latitude !== null && $this->latitude !== '' ? $this->latitude : null,
 'longitude' => $this->longitude !== null && $this->longitude !== '' ? $this->longitude : null,
 'published_at' => $this->published_at,
 ];

 if ($this->editingId) {
 $lot = ProgramLot::findOrFail($this->editingId);
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

 $this->showModal = false;
 $this->reset(['editingId']);
 unset($this->lots);
 }

 public function delete(int $id, LogAuditAction $audit): void
 {
 $lot = ProgramLot::findOrFail($id);
 Gate::authorize('delete', $lot);
 $old = $lot->toArray();
 $lot->delete();
 $audit('PROGRAM_LOT_DELETED', ProgramLot::class, $old, null);
 Flux::toast(variant: 'success', text: __('Lot supprimé.'));
 }

 public function closeModal(): void
 {
 $this->showModal = false;
 $this->reset(['editingId']);
 }

 public function updatedFilterProgramId(): void
 {
 $this->resetPage();
 }
}; ?>

<section class="w-full">
 <flux:heading size="xl" level="1">{{ __('Lots') }}</flux:heading>
 <flux:subheading class="mb-6">{{ __('Gestion des lots par programme — statuts : disponible, option, réservé, vendu') }}</flux:subheading>

 <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
 <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
 <flux:input wire:model.live.debounce.300ms="search" placeholder="{{ __('Rechercher référence...') }}" class="max-w-sm" />
 <flux:select wire:model.live="filterProgramId" placeholder="{{ __('Filtrer par programme') }}" class="max-w-xs">
 <flux:select.option value="">{{ __('Tous les programmes') }}</flux:select.option>
 @foreach($this->programs as $prog)
 <flux:select.option value="{{ $prog->id }}">{{ $prog->title }}</flux:select.option>
 @endforeach
 </flux:select>
 </div>
 @can('create', App\Models\ProgramLot::class)
 <flux:button variant="primary" icon="plus" wire:click="openCreate">{{ __('Nouveau lot') }}</flux:button>
 @endcan
 </div>

 <flux:table :paginate="$this->lots" container:class="rounded-xl border border-zinc-200 bg-white shadow-sm overflow-hidden">
 <flux:table.columns>
 <flux:table.column>{{ __('Référence') }}</flux:table.column>
 <flux:table.column>{{ __('Programme') }}</flux:table.column>
 <flux:table.column align="end">{{ __('Surface') }}</flux:table.column>
 <flux:table.column align="end">{{ __('Prix') }}</flux:table.column>
 <flux:table.column align="center">{{ __('Statut') }}</flux:table.column>
 <flux:table.column align="center">{{ __('Viabilisé') }}</flux:table.column>
 <flux:table.column>{{ __('Juridique') }}</flux:table.column>
 <flux:table.column>{{ __('Plan') }}</flux:table.column>
 <flux:table.column>{{ __('GPS') }}</flux:table.column>
 <flux:table.column align="end">{{ __('Actions') }}</flux:table.column>
 </flux:table.columns>
 <flux:table.rows>
 @forelse($this->lots as $lot)
 <flux:table.row class="hover:bg-zinc-50">
 <flux:table.cell>{{ $lot->reference }}</flux:table.cell>
 <flux:table.cell>{{ $lot->program?->title ?? '—' }}</flux:table.cell>
 <flux:table.cell align="end">{{ $lot->surface !== null ? number_format((float) $lot->surface, 2, ',', ' ') : '—' }}</flux:table.cell>
 <flux:table.cell align="end">{{ $lot->price !== null ? number_format((float) $lot->price, 2, ',', ' ').' FCFA' : '—' }}</flux:table.cell>
 <flux:table.cell align="center">
 @php $lotStatus = $lot->status instanceof \BackedEnum ? $lot->status->value : $lot->status; @endphp
 @if($lotStatus === 'disponible')<flux:badge variant="success" size="sm">{{ __('disponible') }}</flux:badge>
 @elseif($lotStatus === 'option')<flux:badge variant="warning" size="sm">{{ __('option') }}</flux:badge>
 @elseif($lotStatus === 'reserve')<flux:badge variant="warning" size="sm">{{ __('réservé') }}</flux:badge>
 @elseif($lotStatus === 'vendu')<flux:badge variant="danger" size="sm">{{ __('vendu') }}</flux:badge>
 @else<flux:badge size="sm">{{ $lotStatus }}</flux:badge>
 @endif
 </flux:table.cell>
 <flux:table.cell align="center">@if($lot->is_viabilise)<flux:badge variant="success" size="sm">{{ __('Oui') }}</flux:badge>@else<flux:badge variant="zinc" size="sm">{{ __('Non') }}</flux:badge>@endif</flux:table.cell>
 <flux:table.cell>{{ $lot->juridical_status ?? '—' }}</flux:table.cell>
 <flux:table.cell>@if($lot->plan_pdf_path)<a href="{{ $lot->plan_pdf_path }}" target="_blank" class="text-primary-600 underline">PDF</a>@else — @endif</flux:table.cell>
 <flux:table.cell>@if($lot->latitude && $lot->longitude){{ number_format((float)$lot->latitude,4) }},{{ number_format((float)$lot->longitude,4) }}@else — @endif</flux:table.cell>
 <flux:table.cell align="end">
 <div class="flex justify-end gap-1">
 @can('update', $lot)<flux:button variant="ghost" size="sm" icon="pencil-square" wire:click="openEdit({{ $lot->id }})" />@endcan
 @can('delete', $lot)<flux:button variant="ghost" size="sm" icon="trash" wire:click="delete({{ $lot->id }})" wire:confirm="{{ __('Supprimer ?') }}" class="text-red-500" />@endcan
 </div>
 </flux:table.cell>
 </flux:table.row>
 @empty
 <flux:table.row><flux:table.cell align="center" colspan="10">{{ __('Aucun lot.') }}</flux:table.cell></flux:table.row>
 @endforelse
 </flux:table.rows>
 </flux:table>
 </div>
 <div class="p-4 border-t">{{ $this->lots->links() }}</div>
 </div>

 <flux:modal wire:model="showModal" class="max-w-2xl" @close="closeModal">
 <form wire:submit="save" class="space-y-6">
 <flux:heading size="lg">{{ $editingId ? __('Modifier le lot') : __('Nouveau lot') }}</flux:heading>
 <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
 <flux:select wire:model="program_id" :label="__('Programme')" required>
 <flux:select.option value="">{{ __('Choisir un programme') }}</flux:select.option>
 @foreach($this->programs as $prog)
 <flux:select.option value="{{ $prog->id }}">{{ $prog->title }}</flux:select.option>
 @endforeach
 </flux:select>
 <flux:input wire:model="reference" :label="__('Référence')" required placeholder="Lot 0001" />
 <flux:input wire:model="surface" type="number" step="0.01" min="0" :label="__('Surface (m²)')" />
 <flux:input wire:model="price" type="number" step="0.01" min="0" :label="__('Prix (FCFA)')" />
 <flux:select wire:model="status" :label="__('Statut')" required>
 <flux:select.option value="disponible">{{ __('disponible') }}</flux:select.option>
 <flux:select.option value="option">{{ __('option') }}</flux:select.option>
 <flux:select.option value="reserve">{{ __('réservé') }}</flux:select.option>
 <flux:select.option value="vendu">{{ __('vendu') }}</flux:select.option>
 </flux:select>
 <flux:checkbox wire:model="is_viabilise" :label="__('Viabilisé')" />
 <flux:input wire:model="juridical_status" :label="__('Statut juridique (ACD)')" placeholder="ACD, TF" />
 <flux:input wire:model="plan_pdf_path" :label="__('Plan PDF URL')" placeholder="/storage/cms/plots/plan.pdf" class="sm:col-span-2" />
 <flux:input type="file" wire:model="plan_pdf_upload" :label="__('Ou PDF plan (max 5Mo)')" accept="application/pdf" />
 <flux:input wire:model="latitude" type="number" step="0.0000001" :label="__('Latitude')" placeholder="5.3456" />
 <flux:input wire:model="longitude" type="number" step="0.0000001" :label="__('Longitude')" placeholder="-4.0123" />
 <flux:input wire:model="published_at" type="date" :label="__('Date publication')" />
 </div>
 <div class="flex justify-end gap-2">
 <flux:button variant="ghost" wire:click="closeModal" type="button">{{ __('Annuler') }}</flux:button>
 <flux:button variant="primary" type="submit">{{ $editingId ? __('Mettre à jour') : __('Créer') }}</flux:button>
 </div>
 </form>
 </flux:modal>
</section>
