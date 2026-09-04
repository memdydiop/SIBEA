<x-layouts.public :title="__('Notre équipe')">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight">{{ __('Notre équipe') }}</h1>
        <p class="mt-2 max-w-2xl text-zinc-600">{{ __('Des professionnels qualifiés, organisés en directions, départements et équipes opérationnelles — mono-entreprise, pas de filiales.') }}</p>

        <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($employees as $emp)
                <div class="rounded-xl border border-zinc-200 p-6">
                    <div class="font-semibold">{{ $emp->full_name }}</div>
                    <div class="text-sm text-zinc-600">{{ $emp->job_title }}</div>
                    <div class="mt-1 text-xs text-zinc-500">{{ $emp->department?->name ?? __('Aucun département') }} · {{ $emp->registration_number }}</div>
                </div>
            @empty
                <p class="text-sm text-zinc-500">{{ __('Aucun membre d’équipe publié.') }}</p>
            @endforelse
        </div>

        <div class="mt-8">{{ $employees->links() }}</div>

        @if($teams->isNotEmpty())
            <h2 class="mt-12 text-xl font-semibold">{{ __('Nos équipes opérationnelles') }}</h2>
            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                @foreach($teams as $team)
                    <div class="rounded-xl border border-zinc-200 p-4">
                        <div class="font-medium">{{ $team->name }} <span class="text-xs text-zinc-500">({{ $team->code }})</span></div>
                        <div class="text-sm text-zinc-600">{{ $team->description }}</div>
                        <div class="mt-1 text-xs text-zinc-500">{{ __('Chef') }}: {{ $team->leader?->full_name ?? '—' }} · {{ $team->members->count() }} {{ __('membres') }}</div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-layouts.public>
