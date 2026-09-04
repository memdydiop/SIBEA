<x-layouts.public :title="__('Réalisations')">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight">{{ __('Réalisations') }}</h1>
        <p class="mt-2 text-zinc-600">{{ __('Nos projets livrés : bâtiment, VRD, génie civil, énergie.') }}</p>

        @if($categories->isNotEmpty())
            <div class="mt-6 flex flex-wrap gap-2">
                <a href="{{ route('public.projects.index') }}" class="rounded-full px-3 py-1 text-sm {{ !$activeCategory ? 'bg-zinc-900 text-white' : 'border border-zinc-200 hover:bg-zinc-50' }}">{{ __('Tous') }}</a>
                @foreach($categories as $cat)
                    <a href="{{ route('public.projects.index', ['categorie' => $cat]) }}" class="rounded-full px-3 py-1 text-sm {{ $activeCategory === $cat ? 'bg-zinc-900 text-white' : 'border border-zinc-200 hover:bg-zinc-50' }}">{{ $cat }}</a>
                @endforeach
            </div>
        @endif

        <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($projects as $project)
                <a href="{{ route('public.projects.show', $project->slug) }}" class="overflow-hidden rounded-xl border border-zinc-200 bg-white hover:shadow-sm group">
                    <div class="aspect-[4/3] bg-zinc-100 overflow-hidden">
                        @if($project->cover_image)
                            <img src="{{ $project->cover_image }}" alt="{{ $project->title }}" class="h-full w-full object-cover group-hover:scale-[1.02] transition duration-300">
                        @else
                            <div class="h-full w-full bg-zinc-100 group-hover:bg-zinc-200 transition"></div>
                        @endif
                    </div>
                    <div class="p-4">
                        <div class="text-xs uppercase tracking-wide text-zinc-500">{{ $project->category }} · {{ $project->year }}</div>
                        <div class="mt-1 font-semibold">{{ $project->title }}</div>
                        <div class="text-sm text-zinc-600 line-clamp-2">{{ $project->location }} @if($project->client_name) — {{ $project->client_name }} @endif</div>
                    </div>
                </a>
            @empty
                <p class="text-sm text-zinc-500">{{ __('Aucune réalisation.') }}</p>
            @endforelse
        </div>

        <div class="mt-8">{{ $projects->links() }}</div>
    </div>
</x-layouts.public>
