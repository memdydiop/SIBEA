<?php

use App\Actions\Audit\LogAuditAction;
use App\Actions\User\ActivateUserAction;
use App\Actions\User\DeactivateUserAction;
use App\Models\User;
use Flux\Flux;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

new #[Title('Utilisateurs')] class extends Component {
 use WithPagination;

 public string $search = '';
 public ?int $selectedUserId = null;
 public string $selectedRole = '';
 public bool $showRoleModal = false;

 public function mount(): void
 {
 Gate::authorize('viewAny', User::class);
 }

 #[Computed]
 public function users()
 {
 return User::with(['roles', 'employee'])
 ->when($this->search, fn ($q) => $q->where('name', 'ilike', "%{$this->search}%")->orWhere('email', 'ilike', "%{$this->search}%"))
 ->orderBy('name')
 ->paginate(15);
 }

 #[Computed]
 public function roles()
 {
 return Role::orderBy('name')->get();
 }

 public function toggleActive(int $id, ActivateUserAction $activate, DeactivateUserAction $deactivate): void
 {
 $user = User::findOrFail($id);
 Gate::authorize('update', $user);
 if ($user->id === auth()->id()) {
 Flux::toast(variant: 'danger', text: __('Vous ne pouvez pas désactiver votre propre compte.'));
 return;
 }
 if ($user->is_active) {
 $deactivate($user);
 Flux::toast(variant: 'success', text: __('Utilisateur désactivé.'));
 } else {
 $activate($user);
 Flux::toast(variant: 'success', text: __('Utilisateur activé.'));
 }
 unset($this->users);
 }

 public function openRoleModal(int $id): void
 {
 $user = User::findOrFail($id);
 Gate::authorize('update', $user);
 $this->selectedUserId = $user->id;
 $this->selectedRole = $user->roles->first()?->name ?? '';
 $this->showRoleModal = true;
 }

 public function assignRole(LogAuditAction $audit): void
 {
 $user = User::findOrFail($this->selectedUserId);
 Gate::authorize('update', $user);
 $old = $user->roles->pluck('name')->toArray();
 $user->syncRoles([$this->selectedRole]);
 $audit('ROLE_ASSIGNED', $user, ['roles' => $old], ['roles' => [$this->selectedRole]]);
 Flux::toast(variant: 'success', text: __('Rôle mis à jour.'));
 $this->showRoleModal = false;
 unset($this->users);
 }

 public function closeRoleModal(): void
 {
 $this->showRoleModal = false;
 $this->selectedUserId = null;
 }
}; ?>

<section class="w-full">
 <flux:heading size="xl" level="1">{{ __('Utilisateurs & Comptes') }}</flux:heading>
 <flux:subheading class="mb-6">{{ __('Comptes donnant accès au système — liés optionnellement à un Employee CDC 6.2') }}</flux:subheading>

 <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
 <flux:input wire:model.live.debounce.300ms="search" placeholder="{{ __('Rechercher nom ou email...') }}" class="max-w-sm" />
 <flux:text class="text-sm text-zinc-500">{{ __('Désactivation bloque la connexion (Fortify is_active)') }}</flux:text>
 </div>

 <flux:table :paginate="$this->users" container:class="rounded-xl border border-zinc-200 bg-white shadow-sm overflow-hidden">
 <flux:table.columns>
 <flux:table.column>{{ __('Nom') }}</flux:table.column>
 <flux:table.column>{{ __('Email') }}</flux:table.column>
 <flux:table.column>{{ __('Employé lié') }}</flux:table.column>
 <flux:table.column>{{ __('Rôle') }}</flux:table.column>
 <flux:table.column align="center">{{ __('Actif') }}</flux:table.column>
 <flux:table.column align="end">{{ __('Actions') }}</flux:table.column>
 </flux:table.columns>
 <flux:table.rows>
 @forelse($this->users as $u)
 <flux:table.row class="hover:bg-zinc-50">
 <flux:table.cell>{{ $u->name }}</flux:table.cell>
 <flux:table.cell>{{ $u->email }}</flux:table.cell>
 <flux:table.cell>{{ $u->employee?->full_name ?? '—' }} <span class="text-xs text-zinc-400">{{ $u->employee?->registration_number ?? '' }}</span></flux:table.cell>
 <flux:table.cell>
 @forelse($u->roles as $r)<flux:badge size="sm">{{ $r->name }}</flux:badge>
 @empty<span class="text-zinc-400 text-xs">{{ __('Aucun') }}</span>@endforelse
 </flux:table.cell>
 <flux:table.cell align="center">
 @if($u->is_active)<flux:badge variant="success" size="sm">{{ __('Oui') }}</flux:badge>
 @else<flux:badge variant="danger" size="sm">{{ __('Non') }}</flux:badge>@endif
 </flux:table.cell>
 <flux:table.cell align="end">
 <div class="flex justify-end gap-1">
 @can('update', $u)
 <flux:button variant="ghost" size="sm" :icon="$u->is_active ? 'no-symbol' : 'check-circle'" wire:click="toggleActive({{ $u->id }})" :tooltip="$u->is_active ? __('Désactiver') : __('Activer')" />
 <flux:button variant="ghost" size="sm" icon="shield-check" wire:click="openRoleModal({{ $u->id }})" tooltip="{{ __('Rôle') }}" />
 @endcan
 </div>
 </flux:table.cell>
 </flux:table.row>
 @empty
 <flux:table.row><flux:table.cell align="center" colspan="6">{{ __('Aucun utilisateur.') }}</flux:table.cell></flux:table.row>
 @endforelse
 </flux:table.rows>
 </flux:table>
 </div>
 <div class="p-4 border-t">{{ $this->users->links() }}</div>
 </div>

 <flux:modal wire:model="showRoleModal" class="max-w-md" @close="closeRoleModal">
 <form wire:submit="assignRole" class="space-y-6">
 <flux:heading size="lg">{{ __('Assigner un rôle') }}</flux:heading>
 <flux:select wire:model="selectedRole" :label="__('Rôle Spatie (module.action)')" required>
 <flux:select.option value="">{{ __('Choisir') }}</flux:select.option>
 @foreach($this->roles as $role)
 <flux:select.option value="{{ $role->name }}">{{ $role->name }}</flux:select.option>
 @endforeach
 </flux:select>
 <div class="flex justify-end gap-2">
 <flux:button variant="ghost" wire:click="closeRoleModal" type="button">{{ __('Annuler') }}</flux:button>
 <flux:button variant="primary" type="submit">{{ __('Enregistrer') }}</flux:button>
 </div>
 </form>
 </flux:modal>
</section>
