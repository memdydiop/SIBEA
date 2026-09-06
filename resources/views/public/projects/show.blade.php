<x-layouts.public :title="$project->meta_title ?? $project->title" :metaDescription="$project->meta_description ?? $project->description">
    <div class="bg-background">
        {{-- Breadcrumb --}}
        <div class="mx-auto max-w-7xl px-4 pt-6 sm:px-6 lg:px-8">
            <a href="{{ route('public.projects.index') }}" class="inline-flex items-center gap-1 text-sm text-zinc-500 hover:text-zinc-900"><span>←</span> {{ __('Réalisations') }}</a>
        </div>

        {{-- Hero image 16/9 --}}
        @if($project->cover_image)
            <div class="mx-auto mt-4 max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="aspect-[16/9] overflow-hidden rounded-xl bg-zinc-100 sm:aspect-[2/1]">
                    <img src="{{ $project->cover_image }}" alt="{{ $project->title }}" class="h-full w-full object-cover">
                </div>
            </div>
        @endif

        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 sm:py-10">
            {{-- Eyebrow --}}
            <div class="flex flex-wrap items-center gap-2 text-xs font-semibold uppercase tracking-wide text-primary-700">
                <span class="rounded-full bg-primary-50 px-2.5 py-1 text-primary-700">{{ $project->category ?? __('Réalisation') }}</span>
                @if($project->year)<span class="text-zinc-500">{{ $project->year }}</span>@endif
                @if($project->location)<span class="text-zinc-400">·</span><span class="text-zinc-500">{{ $project->location }}</span>@endif
                @if($project->client_name)<span class="text-zinc-400">·</span><span class="text-zinc-500">{{ $project->client_name }}</span>@endif
            </div>

            <h1 class="mt-3 font-display text-[28px] font-bold tracking-tight text-primary-900 sm:text-[36px]">{{ $project->title }}</h1>

            @if($project->description)
                <p class="mt-3 max-w-3xl text-[17px] leading-relaxed text-zinc-600">{{ $project->description }}</p>
            @endif

            {{-- Stats sobres CDC 3 colonnes --}}
            <div class="mt-6 grid max-w-xl grid-cols-3 gap-4 rounded-xl border border-zinc-200 bg-white p-4 sm:p-5">
                <div class="text-center">
                    <div class="font-display text-xl font-bold text-primary-900">{{ $project->year ?? '—' }}</div>
                    <div class="text-xs uppercase tracking-wide text-zinc-500">{{ __('Année') }}</div>
                </div>
                <div class="text-center border-x border-zinc-200">
                    <div class="font-display text-xl font-bold text-primary-900 truncate px-1">{{ $project->category ?? '—' }}</div>
                    <div class="text-xs uppercase tracking-wide text-zinc-500">{{ __('Catégorie') }}</div>
                </div>
                <div class="text-center">
                    <div class="font-display text-xl font-bold text-accent">@if($project->key_figures){{ count($project->key_figures) }}@else 1 @endif</div>
                    <div class="text-xs uppercase tracking-wide text-zinc-500">{{ __('Chiffres clés') }}</div>
                </div>
            </div>

            @if($project->key_figures)
                <div class="mt-8">
                    <h2 class="font-display text-[18px] font-semibold text-primary-900">{{ __('Chiffres clés') }}</h2>
                    <div class="mt-4 grid gap-4 sm:grid-cols-3">
                        @foreach($project->key_figures as $key => $value)
                            <div class="rounded-xl border border-zinc-200 bg-white p-5 text-center shadow-[0_1px_2px_rgba(11,31,51,0.06)]">
                                <div class="text-xs font-semibold uppercase tracking-wide text-zinc-500">{{ $key }}</div>
                                <div class="mt-1 font-display text-xl font-bold text-primary-900">{{ $value }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Prose --}}
            {{-- Note: description already shown as lead; keep prose for extended content if needed --}}

            @if($project->gallery)
                <div class="mt-10">
                    <h2 class="font-display text-[18px] font-semibold text-primary-900">{{ __('Galerie') }}</h2>
                    <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach($project->gallery as $img)
                            <div class="aspect-[4/3] overflow-hidden rounded-xl bg-zinc-100 border border-zinc-200">
                                <img src="{{ $img }}" alt="{{ $project->title }} — {{ __('galerie') }}" class="h-full w-full object-cover hover:scale-[1.02] transition duration-300">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($relatedProjects->isNotEmpty())
                <div class="mt-12 border-t border-zinc-200 pt-8">
                    <div class="flex items-end justify-between">
                        <h3 class="font-display text-[18px] font-semibold text-primary-900">{{ __('Projets similaires') }}</h3>
                        <a href="{{ route('public.projects.index') }}" class="hidden text-sm font-semibold text-primary-700 hover:text-accent sm:inline-flex">{{ __('Tout voir →') }}</a>
                    </div>
                    <div class="mt-6 grid gap-6 sm:grid-cols-3">
                        @foreach($relatedProjects as $rel)
                            <a href="{{ route('public.projects.show', $rel->slug) }}" class="group flex flex-col overflow-hidden rounded-xl border border-border bg-surface shadow-[0_1px_2px_rgba(11,31,51,0.06)] hover:shadow-md transition">
                                <div class="aspect-[4/3] overflow-hidden bg-neutral-100 relative">
                                    @if($rel->cover_image)
                                        <img src="{{ $rel->cover_image }}" alt="{{ $rel->title }}" class="h-full w-full object-cover group-hover:scale-[1.03] transition duration-300">
                                    @else
                                        <div class="h-full w-full bg-gradient-to-br from-primary-900 via-primary-800 to-primary-700"></div>
                                    @endif
                                    <div class="absolute left-3 top-3">
                                        <span class="rounded-full bg-white/90 px-2 py-0.5 text-[11px] font-semibold text-primary-900 backdrop-blur">{{ $rel->category }}</span>
                                    </div>
                                </div>
                                <div class="p-4">
                                    <div class="text-[11px] font-semibold uppercase tracking-wide text-neutral-500">{{ $rel->category }} @if($rel->year)· {{ $rel->year }}@endif</div>
                                    <div class="mt-1 font-display text-[15px] font-semibold text-primary-900 line-clamp-2 group-hover:text-accent transition">{{ $rel->title }}</div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="mt-10 flex flex-wrap gap-3">
                <a href="{{ route('public.quote.create') }}" class="inline-flex items-center justify-center rounded-sm bg-accent px-6 py-3 text-sm font-display font-semibold text-primary-900 hover:bg-accent-300 transition">{{ __('Demander un devis pour ce type de projet') }}</a>
                <a href="{{ route('public.contact') }}" class="inline-flex items-center justify-center rounded-sm border border-zinc-300 bg-white px-6 py-3 text-sm font-semibold text-zinc-700 hover:bg-zinc-50 transition">{{ __('Nous contacter') }}</a>
            </div>
        </div>
    </div>
</x-layouts.public>
