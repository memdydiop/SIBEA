<?php

use App\Actions\Audit\LogAuditAction;
use App\Actions\Organization\CreateTeamAction;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Team;
use Flux\Flux;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

new #[Title('Équipes')] class extends Component {
 use WithPagination;

 public int $department_id = 0;
 public string $name = '';
 public string $code = '';
 public ?int $leader_id = null;
 public ?string $description = null;
 public bool $is_active = true;

 public ?int $editingId = null;
 public bool $showModal = false;
 public string $search = '';

 public function mount(): void
 {
 Gate::authorize('viewAny', Team::class);
 }

 #[Computed]
 public function teams()
 {
 return Team::with(['department', 'leader'])
 ->when($this->search, fn ($q) => $q->where('name', 'ilike', "%{$this->search}%")->orWhere('code', 'ilike', "%{$this->search}%"))
 ->orderBy('name')
 ->paginate(15);
 }

 #[Computed]
 public function departments()
 {
 return Department::active()->orderBy('name')->get();
 }

 #[Computed]
 public function employees()
 {
 return Employee::orderBy('last_name')->get();
 }

 public function openCreate(): void
 {
 Gate::authorize('create', Team::class);
 $this->reset(['name', 'code', 'leader_id', 'description', 'editingId']);
 $this->is_active = true;
 $this->department_id = $this->departments->first()?->id ?? 0;
 $this->showModal = true;
 }

 public function openEdit(int $id): void
 {
 $team = Team::findOrFail($id);
 Gate::authorize('update', $team);
 $this->editingId = $team->id;
 $this->department_id = $team->department_id;
 $this->name = $team->name;
 $this->code = $team->code;
 $this->leader_id = $team->leader_id;
 $this->description = $team->description;
 $this->is_active = $team->is_active;
 $this->showModal = true;
 }

 public function save(CreateTeamAction $create, LogAuditAction $audit): void
 {
 $data = [
 'department_id' => $this->department_id,
 'name' => $this->name,
 'code' => $this->code,
 'leader_id' => $this->leader_id,
 'description' => $this->description,
 'is_active' => $this->is_active,
 ];

 if ($this->editingId) {
 $team = Team::findOrFail($this->editingId);
 Gate::authorize('update', $team);
 $old = $team->toArray();
 $team->update([
 'department_id' => $data['department_id'],
 'name' => $data['name'],
 'code' => strtoupper($data['code']),
 'leader_id' => $data['leader_id'],
 'description' => $data['description'],
 'is_active' => $data['is_active'],
 ]);
 $audit('TEAM_UPDATED', $team, $old, $team->fresh()->toArray());
 Flux::toast(variant: 'success', text: __('Équipe mise à jour.'));
 } else {
 Gate::authorize('create', Team::class);
 $team = $create($data);
 $audit('TEAM_CREATED', $team, null, $team->toArray());
 Flux::toast(variant: 'success', text: __('Équipe créée.'));
 }

 $this->showModal = false;
 $this->reset(['editingId']);
 unset($this->teams);
 }

 public function delete(int $id, LogAuditAction $audit): void
 {
 $team = Team::findOrFail($id);
 Gate::authorize('delete', $team);
 if ($team->members()->exists()) {
 Flux::toast(variant: 'danger', text: __('Équipe liée à des membres. Retirez les membres d’abord.'));
 return;
 }
 $old = $team->toArray();
 $team->delete();
 $audit('TEAM_DELETED', Team::class, $old, null);
 Flux::toast(variant: 'success', text: __('Équipe supprimée.'));
 }

 public function closeModal(): void
 {
 $this->showModal = false;
 $this->reset(['editingId']);
 }
}; ?>

<section class="w-full">
 <flux:heading size="xl" level="1">{{ __('Équipes opérationnelles') }}</flux:heading>
 <flux:subheading class="mb-6">{{ __('Équipes rattachées aux départements — exécution terrain') }}</flux:subheading>

 <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
 <flux:input wire:model.live.debounce.300ms="search" placeholder="{{ __('Rechercher...') }}" class="max-w-sm" />
 @can('create', App\Models\Team::class)
 <flux:button variant="primary" icon="plus" wire:click="openCreate" data-test="create-team-button">{{ __('Nouvelle équipe') }}</flux:button>
 @endcan
 </div>

 <flux:table :paginate="$this->teams" container:class="rounded-xl border border-zinc-200 bg-white shadow-sm overflow-hidden">
 <flux:table.columns>
 <flux:table.column>{{ __('Nom') }}</flux:table.column>
 <flux:table.column>{{ __('Code') }}</flux:table.column>
 <flux:table.column>{{ __('Département') }}</flux:table.column>
 <flux:table.column>{{ __('Chef d’équipe') }}</flux:table.column>
 <flux:table.column align="center">{{ __('Statut') }}</flux:table.column>
 <flux:table.column align="end">{{ __('Actions') }}</flux:table.column>
 </flux:table.columns>
 <flux:table.rows>
 @forelse($this->teams as $team)
 <flux:table.row class="hover:bg-zinc-50">
 <flux:table.cell>{{ $team->name }}</flux:table.cell>
 <flux:table.cell><flux:badge size="sm">{{ $team->code }}</flux:badge></flux:table.cell>
 <flux:table.cell>{{ $team->department->name }}</flux:table.cell>
 <flux:table.cell>{{ $team->leader?->full_name ?? '—' }}</flux:table.cell>
 <flux:table.cell align="center">
 @if($team->is_active)<flux:badge variant="success" size="sm">{{ __('Actif') }}</flux:badge>
 @else<flux:badge variant="danger" size="sm">{{ __('Inactif') }}</flux:badge>@endif
 </flux:table.cell>
 <flux:table.cell align="end">
 <div class="flex justify-end gap-1">
 @can('update', $team)<flux:button variant="ghost" size="sm" icon="pencil-square" wire:click="openEdit({{ $team->id }})" />@endcan
 @can('delete', $team)<flux:button variant="ghost" size="sm" icon="trash" wire:click="delete({{ $team->id }})" wire:confirm="{{ __('Supprimer ?') }}" class="text-red-500" />@endcan
 </div>
 </flux:table.cell>
 </flux:table.row>
 @empty
 <flux:table.row><flux:table.cell align="center" colspan="6">{{ __('Aucune équipe.') }}</flux:table.cell></flux:table.row>
 @endforelse
 </flux:table.rows>
 </flux:table>
 </div>
 <div class="p-4 border-t">{{ $this->teams->links() }}</div>
 </div>

 <flux:modal wire:model="showModal" class="max-w-lg" @close="closeModal">
 <form wire:submit="save" class="space-y-6">
 <flux:heading size="lg">{{ $editingId ? __('Modifier l’équipe') : __('Nouvelle équipe') }}</flux:heading>
 <flux:select wire:model="department_id" :label="__('Département')" required>
 @foreach($this->departments as $dept)
 <flux:select.option value="{{ $dept->id }}">{{ $dept->name }} ({{ $dept->code }})</flux:select.option>
 @endforeach
 </flux:select>
 <flux:input wire:model="name" :label="__('Nom')" required />
 <flux:input wire:model="code" :label="__('Code')" required placeholder="EQ-GO-02" />
 <flux:select wire:model="leader_id" :label="__('Chef d’équipe (optionnel)')">
 <flux:select.option value="">{{ __('Aucun') }}</flux:select.option>
 @foreach($this->employees as $emp)
 <flux:select.option value="{{ $emp->id }}">{{ $emp->full_name }} — {{ $emp->job_title }}</flux:select.option>
 @endforeach
 </flux:select>
 <flux:textarea wire:model="description" :label="__('Description')" rows="2" />
 <flux:checkbox wire:model="is_active" :label="__('Actif')" />
 <div class="flex justify-end gap-2">
 <flux:button variant="ghost" wire:click="closeModal" type="button">{{ __('Annuler') }}</flux:button>
 <flux:button variant="primary" type="submit">{{ $editingId ? __('Mettre à jour') : __('Créer') }}</flux:button>
 </div>
 </form>
 </flux:modal>
</section>
