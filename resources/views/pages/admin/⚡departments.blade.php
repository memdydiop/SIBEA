<?php

use App\Actions\Audit\LogAuditAction;
use App\Actions\Organization\CreateDepartmentAction;
use App\Actions\Organization\UpdateDepartmentAction;
use App\Models\Department;
use App\Models\Employee;
use Flux\Flux;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

new #[Title('Départements')] class extends Component {
 use WithPagination;

 public string $name = '';
 public string $code = '';
 public ?int $parent_id = null;
 public ?int $manager_id = null;
 public ?string $description = null;
 public bool $is_active = true;

 public ?int $editingId = null;
 public bool $showModal = false;
 public string $search = '';

 public function mount(): void
 {
 Gate::authorize('viewAny', Department::class);
 }

 #[Computed]
 public function departments()
 {
 return Department::with(['parent', 'manager'])
 ->when($this->search, fn ($q) => $q->where('name', 'ilike', "%{$this->search}%")->orWhere('code', 'ilike', "%{$this->search}%"))
 ->orderBy('name')
 ->paginate(15);
 }

 #[Computed]
 public function parents()
 {
 return Department::roots()->active()->orderBy('name')->get();
 }

 #[Computed]
 public function employees()
 {
 return Employee::orderBy('last_name')->get();
 }

 public function openCreate(): void
 {
 Gate::authorize('create', Department::class);
 $this->reset(['name', 'code', 'parent_id', 'manager_id', 'description', 'editingId']);
 $this->is_active = true;
 $this->showModal = true;
 }

 public function openEdit(int $id): void
 {
 $department = Department::findOrFail($id);
 Gate::authorize('update', $department);
 $this->editingId = $department->id;
 $this->name = $department->name;
 $this->code = $department->code;
 $this->parent_id = $department->parent_id;
 $this->manager_id = $department->manager_id;
 $this->description = $department->description;
 $this->is_active = $department->is_active;
 $this->showModal = true;
 }

 public function save(CreateDepartmentAction $create, UpdateDepartmentAction $update, LogAuditAction $audit): void
 {
 $data = [
 'name' => $this->name,
 'code' => $this->code,
 'parent_id' => $this->parent_id,
 'manager_id' => $this->manager_id,
 'description' => $this->description,
 'is_active' => $this->is_active,
 ];

 if ($this->editingId) {
 $department = Department::findOrFail($this->editingId);
 Gate::authorize('update', $department);
 $old = $department->toArray();
 $updated = $update($department, $data);
 $audit('DEPARTMENT_UPDATED', $updated, $old, $updated->toArray());
 Flux::toast(variant: 'success', text: __('Département mis à jour.'));
 } else {
 Gate::authorize('create', Department::class);
 $department = $create($data);
 $audit('DEPARTMENT_CREATED', $department, null, $department->toArray());
 Flux::toast(variant: 'success', text: __('Département créé.'));
 }

 $this->showModal = false;
 $this->reset(['editingId']);
 unset($this->departments);
 }

 public function delete(int $id, LogAuditAction $audit): void
 {
 $department = Department::findOrFail($id);
 Gate::authorize('delete', $department);

 if ($department->children()->exists() || $department->employees()->exists() || $department->teams()->exists()) {
 Flux::toast(variant: 'danger', text: __('Impossible de supprimer : département lié à des données.'));
 return;
 }

 $old = $department->toArray();
 $department->delete();
 $audit('DEPARTMENT_DELETED', Department::class, $old, null);
 Flux::toast(variant: 'success', text: __('Département supprimé.'));
 }

 public function closeModal(): void
 {
 $this->showModal = false;
 $this->reset(['editingId']);
 }
}; ?>

<section class="w-full">
 <flux:heading size="xl" level="1">{{ __('Départements & Directions') }}</flux:heading>
 <flux:subheading class="mb-6">{{ __('Organisation interne mono-entreprise : directions, départements et services') }}</flux:subheading>

 <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
 <flux:input wire:model.live.debounce.300ms="search" placeholder="{{ __('Rechercher par nom ou code...') }}" class="max-w-sm" />
 @can('create', App\Models\Department::class)
 <flux:button variant="primary" icon="plus" wire:click="openCreate" data-test="create-department-button">{{ __('Nouveau département') }}</flux:button>
 @endcan
 </div>

 <flux:table :paginate="$this->departments" container:class="rounded-xl border border-zinc-200 bg-white shadow-sm overflow-hidden">
 <flux:table.columns>
 <flux:table.column>{{ __('Nom') }}</flux:table.column>
 <flux:table.column>{{ __('Code') }}</flux:table.column>
 <flux:table.column>{{ __('Parent') }}</flux:table.column>
 <flux:table.column>{{ __('Responsable') }}</flux:table.column>
 <flux:table.column align="center">{{ __('Statut') }}</flux:table.column>
 <flux:table.column align="end">{{ __('Actions') }}</flux:table.column>
 </flux:table.columns>
 <flux:table.rows>
 @forelse($this->departments as $dept)
 <flux:table.row class="hover:bg-zinc-50">
 <flux:table.cell>{{ $dept->name }}</flux:table.cell>
 <flux:table.cell><flux:badge size="sm">{{ $dept->code }}</flux:badge></flux:table.cell>
 <flux:table.cell>{{ $dept->parent?->name ?? '—' }}</flux:table.cell>
 <flux:table.cell>{{ $dept->manager?->full_name ?? '—' }}</flux:table.cell>
 <flux:table.cell align="center">
 @if($dept->is_active)
 <flux:badge variant="success" size="sm">{{ __('Actif') }}</flux:badge>
 @else
 <flux:badge variant="danger" size="sm">{{ __('Inactif') }}</flux:badge>
 @endif
 </flux:table.cell>
 <flux:table.cell align="end">
 <div class="flex justify-end gap-1">
 @can('update', $dept)
 <flux:button variant="ghost" size="sm" icon="pencil-square" wire:click="openEdit({{ $dept->id }})" />
 @endcan
 @can('delete', $dept)
 <flux:button variant="ghost" size="sm" icon="trash" wire:click="delete({{ $dept->id }})" wire:confirm="{{ __('Supprimer ce département ?') }}" class="text-red-500" />
 @endcan
 </div>
 </flux:table.cell>
 </flux:table.row>
 @empty
 <flux:table.row><flux:table.cell align="center" colspan="6">{{ __('Aucun département.') }}</flux:table.cell></flux:table.row>
 @endforelse
 </flux:table.rows>
 </flux:table>
 </div>
 <div class="p-4 border-t border-zinc-200">{{ $this->departments->links() }}</div>
 </div>

 <flux:modal wire:model="showModal" class="max-w-lg" @close="closeModal">
 <form wire:submit="save" class="space-y-6">
 <flux:heading size="lg">{{ $editingId ? __('Modifier le département') : __('Nouveau département') }}</flux:heading>

 <flux:input wire:model="name" :label="__('Nom')" required />
 <flux:input wire:model="code" :label="__('Code unique')" required placeholder="DG, DTT, BE..." />
 <flux:select wire:model="parent_id" :label="__('Direction parente (optionnel)')">
 <flux:select.option value="">{{ __('Aucune — direction racine') }}</flux:select.option>
 @foreach($this->parents as $p)
 @if($editingId !== $p->id)
 <flux:select.option value="{{ $p->id }}">{{ $p->name }} ({{ $p->code }})</flux:select.option>
 @endif
 @endforeach
 </flux:select>
 <flux:select wire:model="manager_id" :label="__('Responsable (optionnel)')">
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
