<?php

use App\Enums\EmployeeStatus;
use App\Enums\QuoteRequestStatus;
use App\Models\Department;
use App\Models\Employee;
use App\Models\QuoteRequest;
use App\Models\Team;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Tableau de bord')] class extends Component
{
 #[Computed]
 public function stats(): array
 {
 return [
 'departments' => Department::count(),
 'departmentsActive' => Department::where('is_active', true)->count(),
 'teams' => Team::count(),
 'teamsActive' => Team::where('is_active', true)->count(),
 'employees' => Employee::count(),
 'employeesActive' => Employee::where('status', EmployeeStatus::Active)->count(),
 'users' => User::count(),
 'usersActive' => User::where('is_active', true)->count(),
 'quoteRequests' => QuoteRequest::count(),
 'quoteRequestsPending' => QuoteRequest::whereIn('status', [
 QuoteRequestStatus::New,
 QuoteRequestStatus::Qualified,
 QuoteRequestStatus::UnderReview,
 ])->count(),
 ];
 }

 #[Computed]
 public function recentEmployees()
 {
 return Employee::with('department')->latest()->limit(5)->get();
 }

 #[Computed]
 public function recentQuoteRequests()
 {
 return QuoteRequest::latest()->limit(5)->get();
 }
}; ?>

<section class="w-full">
 <flux:heading size="xl" level="1">{{ __('Tableau de bord') }}</flux:heading>
 <flux:subheading class="mb-6">{{ __('Vue d’ensemble SIBEA — organisation, effectifs et activité commerciale') }}</flux:subheading>

 <div class="grid auto-rows-min gap-4 md:grid-cols-2 xl:grid-cols-4 mb-6">
 <flux:card class="space-y-2">
 <div class="flex items-center justify-between">
 <flux:heading size="sm" level="3">{{ __('Départements') }}</flux:heading>
 <flux:icon.building-office class="size-5 text-zinc-400" />
 </div>
 <flux:heading size="xl" level="2">{{ $this->stats['departments'] }}</flux:heading>
 <flux:text class="text-sm">
 {{ __(':active actifs sur :total', ['active' => $this->stats['departmentsActive'], 'total' => $this->stats['departments']]) }}
 </flux:text>
 @can('viewAny', App\Models\Department::class)
 <flux:button variant="ghost" size="sm" :href="route('admin.departments')" wire:navigate>{{ __('Gérer') }}</flux:button>
 @endcan
 </flux:card>

 <flux:card class="space-y-2">
 <div class="flex items-center justify-between">
 <flux:heading size="sm" level="3">{{ __('Équipes') }}</flux:heading>
 <flux:icon.user-group class="size-5 text-zinc-400" />
 </div>
 <flux:heading size="xl" level="2">{{ $this->stats['teams'] }}</flux:heading>
 <flux:text class="text-sm">
 {{ __(':active actives sur :total', ['active' => $this->stats['teamsActive'], 'total' => $this->stats['teams']]) }}
 </flux:text>
 @can('viewAny', App\Models\Team::class)
 <flux:button variant="ghost" size="sm" :href="route('admin.teams')" wire:navigate>{{ __('Gérer') }}</flux:button>
 @endcan
 </flux:card>

 <flux:card class="space-y-2">
 <div class="flex items-center justify-between">
 <flux:heading size="sm" level="3">{{ __('Employés') }}</flux:heading>
 <flux:icon.users class="size-5 text-zinc-400" />
 </div>
 <flux:heading size="xl" level="2">{{ $this->stats['employees'] }}</flux:heading>
 <flux:text class="text-sm">
 {{ __(':active actifs sur :total', ['active' => $this->stats['employeesActive'], 'total' => $this->stats['employees']]) }}
 </flux:text>
 @can('viewAny', App\Models\Employee::class)
 <flux:button variant="ghost" size="sm" :href="route('admin.employees')" wire:navigate>{{ __('Gérer') }}</flux:button>
 @endcan
 </flux:card>

 <flux:card class="space-y-2">
 <div class="flex items-center justify-between">
 <flux:heading size="sm" level="3">{{ __('Utilisateurs') }}</flux:heading>
 <flux:icon.shield-check class="size-5 text-zinc-400" />
 </div>
 <flux:heading size="xl" level="2">{{ $this->stats['users'] }}</flux:heading>
 <flux:text class="text-sm">
 {{ __(':active actifs sur :total', ['active' => $this->stats['usersActive'], 'total' => $this->stats['users']]) }}
 </flux:text>
 @can('viewAny', App\Models\User::class)
 <flux:button variant="ghost" size="sm" :href="route('admin.users')" wire:navigate>{{ __('Gérer') }}</flux:button>
 @endcan
 </flux:card>
 </div>

 <div class="grid gap-4 lg:grid-cols-2">
 <flux:card>
 <div class="flex items-center justify-between mb-4">
 <flux:heading size="lg">{{ __('Employés récents') }}</flux:heading>
 <flux:badge size="sm">{{ $this->recentEmployees->count() }}</flux:badge>
 </div>
 <div class="divide-y divide-zinc-200">
 @forelse($this->recentEmployees as $emp)
 <div class="flex items-center justify-between py-3">
 <div>
 <div class="font-medium text-sm">{{ $emp->full_name }}</div>
 <div class="text-xs text-zinc-500">{{ $emp->registration_number }} · {{ $emp->job_title }}</div>
 </div>
 <div class="text-right">
 <flux:badge :variant="$emp->status->badgeColor()" size="sm">{{ $emp->status->label() }}</flux:badge>
 <div class="text-xs text-zinc-400 mt-1">{{ $emp->department?->name ?? __('Aucun département') }}</div>
 </div>
 </div>
 @empty
 <flux:text class="py-6 text-center">{{ __('Aucun employé.') }}</flux:text>
 @endforelse
 </div>
 </flux:card>

 <flux:card>
 <div class="flex items-center justify-between mb-4">
 <flux:heading size="lg">{{ __('Demandes de devis') }}</flux:heading>
 <flux:badge variant="warning" size="sm">{{ __(':count en attente', ['count' => $this->stats['quoteRequestsPending']]) }}</flux:badge>
 </div>
 <div class="text-sm text-zinc-500 mb-3">
 {{ __(':total au total', ['total' => $this->stats['quoteRequests']]) }}
 </div>
 <div class="divide-y divide-zinc-200">
 @forelse($this->recentQuoteRequests as $qr)
 <div class="flex items-center justify-between py-3">
 <div>
 <div class="font-medium text-sm">{{ $qr->full_name }} — {{ $qr->reference }}</div>
 <div class="text-xs text-zinc-500">{{ $qr->email }} · {{ $qr->service_type }}</div>
 </div>
 <flux:badge size="sm">{{ $qr->status->label() }}</flux:badge>
 </div>
 @empty
 <flux:text class="py-6 text-center">{{ __('Aucune demande.') }}</flux:text>
 @endforelse
 </div>
 </flux:card>
 </div>
</section>
