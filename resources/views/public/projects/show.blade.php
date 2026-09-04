<x-layouts.public :title="$project->meta_title ?? $project->title" :metaDescription="$project->meta_description ?? $project->description">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <a href="{{ route('public.projects.index') }}" class="text-sm text-zinc-500 hover:text-zinc-900">← {{ __('Réalisations') }}</a>
        <div class="mt-4 flex flex-wrap gap-2 text-xs uppercase tracking-wide text-zinc-500">
            <span>{{ $project->category }}</span>
            @if($project->year)<span>· {{ $project->year }}</span>@endif
            @if($project->location)<span>· {{ $project->location }}</span>@endif
            @if($project->client_name)<span>· {{ $project->client_name }}</span>@endif
        </div>
        <h1 class="mt-2 text-3xl font-bold tracking-tight">{{ $project->title }}</h1>
        @if($project->cover_image)
            <div class="mt-6 aspect-[16/9] overflow-hidden rounded-xl bg-zinc-100">
                <img src="{{ $project->cover_image }}" alt="{{ $project->title }}" class="h-full w-full object-cover">
            </div>
        @endif
        @if($project->description)
            <p class="mt-4 max-w-3xl text-zinc-700">{{ $project->description }}</p>
        @endif

        @if($project->key_figures)
            <div class="mt-8 grid gap-4 sm:grid-cols-3">
                @foreach($project->key_figures as $key => $value)
                    <div class="rounded-xl border border-zinc-200 p-4 text-center">
                        <div class="text-sm text-zinc-500">{{ $key }}</div>
                        <div class="text-xl font-bold">{{ $value }}</div>
                    </div>
                @endforeach
            </div>
        @endif

        @if($project->gallery)
            <div class="mt-8 grid gap-4 sm:grid-cols-2">
                @foreach($project->gallery as $img)
                    <div class="aspect-[4/3] overflow-hidden rounded-xl bg-zinc-100">
                        <img src="{{ $img }}" alt="{{ $project->title }} galerie" class="h-full w-full object-cover">
                    </div>
                @endforeach
            </div>
        @endif

        @if($relatedProjects->isNotEmpty())
            <div class="mt-12 border-t border-zinc-200 pt-8">
                <h3 class="font-semibold">{{ __('Projets similaires') }}</h3>
                <div class="mt-4 grid gap-6 sm:grid-cols-3">
                    @foreach($relatedProjects as $rel)
                        <a href="{{ route('public.projects.show', $rel->slug) }}" class="rounded-xl border border-zinc-200 p-4 hover:shadow-sm">
                            <div class="text-xs text-zinc-500">{{ $rel->category }} · {{ $rel->year }}</div>
                            <div class="mt-1 font-semibold">{{ $rel->title }}</div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-layouts.public>
