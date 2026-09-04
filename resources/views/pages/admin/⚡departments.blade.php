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

    <div class="border rounded-lg border-zinc-200 dark:border-zinc-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-zinc-50 dark:bg-zinc-800/50 text-zinc-500 dark:text-zinc-400">
                    <tr>
                        <th class="text-left font-medium px-4 py-3">{{ __('Nom') }}</th>
                        <th class="text-left font-medium px-4 py-3">{{ __('Code') }}</th>
                        <th class="text-left font-medium px-4 py-3">{{ __('Parent') }}</th>
                        <th class="text-left font-medium px-4 py-3">{{ __('Responsable') }}</th>
                        <th class="text-center font-medium px-4 py-3">{{ __('Statut') }}</th>
                        <th class="text-right font-medium px-4 py-3">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse($this->departments as $dept)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/30">
                            <td class="px-4 py-3 font-medium">{{ $dept->name }}</td>
                            <td class="px-4 py-3"><flux:badge size="sm">{{ $dept->code }}</flux:badge></td>
                            <td class="px-4 py-3 text-zinc-500">{{ $dept->parent?->name ?? '—' }}</td>
                            <td class="px-4 py-3">{{ $dept->manager?->full_name ?? '—' }}</td>
                            <td class="px-4 py-3 text-center">
                                @if($dept->is_active)
                                    <flux:badge variant="success" size="sm">{{ __('Actif') }}</flux:badge>
                                @else
                                    <flux:badge variant="danger" size="sm">{{ __('Inactif') }}</flux:badge>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-1">
                                    @can('update', $dept)
                                        <flux:button variant="ghost" size="sm" icon="pencil-square" wire:click="openEdit({{ $dept->id }})" />
                                    @endcan
                                    @can('delete', $dept)
                                        <flux:button variant="ghost" size="sm" icon="trash" wire:click="delete({{ $dept->id }})" wire:confirm="{{ __('Supprimer ce département ?') }}" class="text-red-500" />
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-8 text-center text-zinc-500">{{ __('Aucun département.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-zinc-200 dark:border-zinc-700">{{ $this->departments->links() }}</div>
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
