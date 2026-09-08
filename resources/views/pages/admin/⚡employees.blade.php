<?php

use App\Actions\Audit\LogAuditAction;
use App\Actions\Organization\CreateEmployeeAction;
use App\Actions\Organization\UpdateEmployeeAction;
use App\Enums\ContractType;
use App\Enums\EmployeeStatus;
use App\Models\Department;
use App\Models\Employee;
use Flux\Flux;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

new #[Title('Employés')] class extends Component {
 use WithPagination;

 public string $registration_number = '';
 public string $first_name = '';
 public string $last_name = '';
 public string $job_title = '';
 public ?int $department_id = null;
 public ?string $email = null;
 public ?string $phone = null;
 public string $contract_type = 'cdi';
 public string $status = 'actif';
 public ?string $hire_date = null;
 public ?string $end_date = null;
 public bool $is_public = false;

 public ?int $editingId = null;
 public bool $showModal = false;
 public string $search = '';

 public function mount(): void
 {
 Gate::authorize('viewAny', Employee::class);
 }

 #[Computed]
 public function employees()
 {
 return Employee::with(['department'])
 ->when($this->search, fn ($q) => $q->where('first_name', 'ilike', "%{$this->search}%")->orWhere('last_name', 'ilike', "%{$this->search}%")->orWhere('registration_number', 'ilike', "%{$this->search}%"))
 ->orderBy('last_name')
 ->paginate(15);
 }

 #[Computed]
 public function departments()
 {
 return Department::active()->orderBy('name')->get();
 }

 public function openCreate(): void
 {
 Gate::authorize('create', Employee::class);
 $this->reset(['registration_number', 'first_name', 'last_name', 'job_title', 'email', 'phone', 'hire_date', 'end_date', 'editingId']);
 $this->is_public = false;
 $this->contract_type = ContractType::Cdi->value;
 $this->status = EmployeeStatus::Active->value;
 $this->department_id = $this->departments->first()?->id;
 $this->showModal = true;
 }

 public function openEdit(int $id): void
 {
 $employee = Employee::findOrFail($id);
 Gate::authorize('update', $employee);
 $this->editingId = $employee->id;
 $this->registration_number = $employee->registration_number;
 $this->first_name = $employee->first_name;
 $this->last_name = $employee->last_name;
 $this->job_title = $employee->job_title;
 $this->department_id = $employee->department_id;
 $this->email = $employee->email;
 $this->phone = $employee->phone;
 $this->contract_type = $employee->contract_type->value;
 $this->status = $employee->status->value;
 $this->hire_date = $employee->hire_date?->format('Y-m-d');
 $this->is_public = $employee->is_public;
 $this->end_date = $employee->end_date?->format('Y-m-d');
 $this->showModal = true;
 }

 public function save(CreateEmployeeAction $create, UpdateEmployeeAction $update, LogAuditAction $audit): void
 {
 $data = [
 'registration_number' => $this->registration_number ?: null,
 'first_name' => $this->first_name,
 'last_name' => $this->last_name,
 'job_title' => $this->job_title,
 'department_id' => $this->department_id,
 'email' => $this->email,
 'phone' => $this->phone,
 'contract_type' => $this->contract_type,
 'status' => $this->status,
 'hire_date' => $this->hire_date,
 'is_public' => $this->is_public,
 'end_date' => $this->end_date,
 ];

 if ($this->editingId) {
 $employee = Employee::findOrFail($this->editingId);
 Gate::authorize('update', $employee);
 $old = $employee->toArray();
 $updated = $update($employee, $data);
 $audit('EMPLOYEE_UPDATED', $updated, $old, $updated->toArray());
 Flux::toast(variant: 'success', text: __('Employé mis à jour.'));
 } else {
 Gate::authorize('create', Employee::class);
 $employee = $create($data);
 $audit('EMPLOYEE_CREATED', $employee, null, $employee->toArray());
 Flux::toast(variant: 'success', text: __('Employé créé.'));
 }

 $this->showModal = false;
 $this->reset(['editingId']);
 unset($this->employees);
 }

 public function delete(int $id, LogAuditAction $audit): void
 {
 $employee = Employee::findOrFail($id);
 Gate::authorize('delete', $employee);
 $old = $employee->toArray();
 $employee->delete();
 $audit('EMPLOYEE_DELETED', Employee::class, $old, null);
 Flux::toast(variant: 'success', text: __('Employé archivé.'));
 }

 public function closeModal(): void
 {
 $this->showModal = false;
 $this->reset(['editingId']);
 }
}; ?>

<section class="w-full">
 <flux:heading size="xl" level="1">{{ __('Employés') }}</flux:heading>
 <flux:subheading class="mb-6">{{ __('Personnel — distinction Employee (terrain) vs User (compte) CDC 6.2') }}</flux:subheading>

 <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
 <flux:input wire:model.live.debounce.300ms="search" placeholder="{{ __('Rechercher matricule, nom...') }}" class="max-w-sm" />
 @can('create', App\Models\Employee::class)
 <flux:button variant="primary" icon="plus" wire:click="openCreate" data-test="create-employee-button">{{ __('Nouvel employé') }}</flux:button>
 @endcan
 </div>

 <flux:table :paginate="$this->employees" container:class="rounded-xl border border-zinc-200 bg-white shadow-sm overflow-hidden">
 <flux:table.columns>
 <flux:table.column>{{ __('Matricule') }}</flux:table.column>
 <flux:table.column>{{ __('Nom') }}</flux:table.column>
 <flux:table.column>{{ __('Fonction') }}</flux:table.column>
 <flux:table.column>{{ __('Département') }}</flux:table.column>
 <flux:table.column>{{ __('Contrat') }}</flux:table.column>
 <flux:table.column align="center">{{ __('Public') }}</flux:table.column>
 <flux:table.column align="center">{{ __('Statut') }}</flux:table.column>
 <flux:table.column align="end">{{ __('Actions') }}</flux:table.column>
 </flux:table.columns>
 <flux:table.rows>
 @forelse($this->employees as $emp)
 <flux:table.row class="hover:bg-zinc-50">
 <flux:table.cell><flux:badge size="sm">{{ $emp->registration_number }}</flux:badge></flux:table.cell>
 <flux:table.cell>{{ $emp->full_name }}</flux:table.cell>
 <flux:table.cell>{{ $emp->job_title }}</flux:table.cell>
 <flux:table.cell>{{ $emp->department?->name ?? '—' }}</flux:table.cell>
 <flux:table.cell>{{ $emp->contract_type->label() }}</flux:table.cell>
 <flux:table.cell align="center">@if($emp->is_public)<flux:badge variant="success" size="sm">{{ __('Oui') }}</flux:badge>@else — @endif</flux:table.cell>
 <flux:table.cell align="center"><flux:badge :variant="$emp->status->badgeColor()" size="sm">{{ $emp->status->label() }}</flux:badge></flux:table.cell>
 <flux:table.cell align="end">
 <div class="flex justify-end gap-1">
 @can('update', $emp)<flux:button variant="ghost" size="sm" icon="pencil-square" wire:click="openEdit({{ $emp->id }})" />@endcan
 @can('delete', $emp)<flux:button variant="ghost" size="sm" icon="trash" wire:click="delete({{ $emp->id }})" wire:confirm="{{ __('Archiver cet employé ?') }}" class="text-red-500" />@endcan
 </div>
 </flux:table.cell>
 </flux:table.row>
 @empty
 <flux:table.row><flux:table.cell align="center" colspan="8">{{ __('Aucun employé.') }}</flux:table.cell></flux:table.row>
 @endforelse
 </flux:table.rows>
 </flux:table>
 </div>
 <div class="p-4 border-t">{{ $this->employees->links() }}</div>
 </div>

 <flux:modal wire:model="showModal" class="max-w-2xl" @close="closeModal">
 <form wire:submit="save" class="space-y-6">
 <flux:heading size="lg">{{ $editingId ? __('Modifier l’employé') : __('Nouvel employé') }}</flux:heading>

 <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
 <flux:input wire:model="registration_number" :label="__('Matricule (auto si vide)')" placeholder="EMP-0000-0002" />
 <flux:select wire:model="department_id" :label="__('Département')">
 <flux:select.option value="">{{ __('Aucun') }}</flux:select.option>
 @foreach($this->departments as $dept)
 <flux:select.option value="{{ $dept->id }}">{{ $dept->name }}</flux:select.option>
 @endforeach
 </flux:select>
 <flux:input wire:model="first_name" :label="__('Prénom')" required />
 <flux:input wire:model="last_name" :label="__('Nom')" required />
 <flux:input wire:model="job_title" :label="__('Fonction / Poste')" required placeholder="Chef de chantier" />
 <flux:select wire:model="contract_type" :label="__('Type de contrat')" required>
 @foreach(App\Enums\ContractType::cases() as $type)
 <flux:select.option value="{{ $type->value }}">{{ $type->label() }}</flux:select.option>
 @endforeach
 </flux:select>
 <flux:input wire:model="email" :label="__('Email')" type="email" />
 <flux:input wire:model="phone" :label="__('Téléphone')" />
 <flux:select wire:model="status" :label="__('Statut')" required>
 @foreach(App\Enums\EmployeeStatus::cases() as $st)
 <flux:select.option value="{{ $st->value }}">{{ $st->label() }}</flux:select.option>
 @endforeach
 </flux:select>
 <flux:input wire:model="hire_date" :label="__('Date d’embauche')" type="date" />
 <flux:input wire:model="end_date" :label="__('Date de fin (optionnel)')" type="date" />
 <flux:checkbox wire:model="is_public" :label="__('Visible sur vitrine équipe')" />
 </div>

 <div class="flex justify-end gap-2">
 <flux:button variant="ghost" wire:click="closeModal" type="button">{{ __('Annuler') }}</flux:button>
 <flux:button variant="primary" type="submit">{{ $editingId ? __('Mettre à jour') : __('Créer') }}</flux:button>
 </div>
 </form>
 </flux:modal>
</section>
