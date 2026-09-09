<x-layouts.public :title="__('Programmes immobiliers')">
    {{-- Hero CDC — aligné sur réalisations --}}
    <section class="relative overflow-hidden bg-primary-900 text-white">
        <div class="absolute inset-0">
            <div class="h-full w-full bg-gradient-to-br from-primary-900 via-primary-800 to-primary-700"></div>
            <div class="absolute inset-0 bg-primary-900/70"></div>
            <div class="absolute inset-0 opacity-[0.04]" style="background-image: linear-gradient(to right, white 1px, transparent 1px), linear-gradient(to bottom, white 1px, transparent 1px); background-size: 40px 40px;"></div>
        </div>
        <div class="relative mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8 sm:py-16">
            <p class="font-display text-xs font-semibold uppercase tracking-[0.18em] text-accent">{{ __('Lotissements') }}</p>
            <h1 class="mt-3 font-display text-[32px] font-extrabold leading-tight sm:text-[40px]">{{ __('Programmes immobiliers') }}</h1>
            <p class="mt-3 max-w-2xl text-[16px] leading-relaxed text-white/80">{{ __('Lotissements viabilisés — terrains et villas disponibles, titres fonciers sécurisés.') }}</p>
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('public.quote.create') }}" class="inline-flex items-center rounded-sm bg-accent px-5 py-2.5 text-sm font-display font-semibold text-primary-900 hover:bg-accent-300 transition">{{ __('Demander un devis') }}</a>
                <a href="{{ route('public.contact') }}" class="inline-flex items-center rounded-sm border border-white/20 bg-transparent px-5 py-2.5 text-sm font-semibold text-white hover:bg-white/10 transition">{{ __('Nous contacter') }}</a>
            </div>
        </div>
    </section>

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 sm:py-12">
        @if($cities->isNotEmpty())
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('public.programs.index') }}" class="rounded-full px-3.5 py-1.5 text-sm font-medium transition {{ !$activeCity ? 'bg-primary-900 text-white' : 'border border-border bg-surface text-zinc-700 hover:bg-zinc-50' }}">{{ __('Tous') }}</a>
                @foreach($cities as $city)
                    <a href="{{ route('public.programs.index', ['ville' => $city]) }}" class="rounded-full px-3.5 py-1.5 text-sm font-medium transition {{ $activeCity === $city ? 'bg-primary-900 text-white' : 'border border-border bg-surface text-zinc-700 hover:bg-zinc-50' }}">{{ $city }}</a>
                @endforeach
            </div>
        @endif

        @if ($programs->isEmpty())
            <div class="mt-8 rounded-xl border border-dashed border-zinc-300 bg-zinc-50 p-12 text-center">
                <p class="text-sm text-zinc-500">{{ __('Aucun programme disponible pour le moment.') }}</p>
                <a href="{{ route('home') }}" class="mt-4 inline-flex text-sm font-semibold text-primary-700 hover:text-accent">{{ __('Retour à l’accueil →') }}</a>
            </div>
        @else
            <div class="mt-3 flex items-center justify-between">
                <p class="text-sm text-zinc-500">{{ $programs->total() }} {{ __('programme(s) trouvé(s)') }}</p>
            </div>

            <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($programs as $program)
                    <a href="{{ route('public.programs.show', $program->slug) }}"
                        class="group relative flex flex-col overflow-hidden rounded-2xl border border-border bg-surface shadow-[0_8px_30px_rgba(11,31,51,0.06)] hover:shadow-[0_16px_40px_rgba(11,31,51,0.12)] hover:-translate-y-1 transition-all">
                        <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-primary-900 via-accent to-primary-700 opacity-0 group-hover:opacity-100 transition"></div>
                        <div class="aspect-[4/3] bg-neutral-100 overflow-hidden relative">
                            @if ($program->cover_path)
                                <img src="{{ $program->cover_path }}" alt="{{ $program->title }}"
                                    class="h-full w-full object-cover group-hover:scale-[1.05] transition duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-black/10 to-transparent opacity-60 group-hover:opacity-80 transition"></div>
                            @else
                                <div class="h-full w-full bg-gradient-to-br from-primary-900 via-primary-800 to-primary-700 flex items-center justify-center">
                                    <span class="text-white/60 text-xs font-semibold uppercase tracking-wide">{{ $program->city ?? __('Lotissement') }}</span>
                                </div>
                            @endif
                            <div class="absolute left-3 top-3 flex gap-1.5">
                                @if($program->city)<span class="rounded-full bg-white px-2.5 py-1 text-[11px] font-semibold text-primary-900 shadow-sm">{{ $program->city }}</span>@endif
                                @if($program->total_lots)<span class="rounded-full bg-accent px-2.5 py-1 text-[11px] font-bold text-primary-900">{{ $program->total_lots }} {{ __('lots') }}</span>@endif
                            </div>
                            <div class="absolute bottom-3 right-3 flex h-8 w-8 items-center justify-center rounded-full bg-white text-primary-900 opacity-0 shadow-sm group-hover:opacity-100 translate-x-2 group-hover:translate-x-0 transition-all">→</div>
                        </div>
                        <div class="flex flex-1 flex-col p-5">
                            <div class="flex items-center gap-1.5 text-[11px] font-semibold uppercase tracking-wide text-neutral-500">
                                @if($program->city)<span>{{ $program->city }}</span>@endif
                                @if($program->municipality)<span>· {{ Str::limit($program->municipality, 20) }}</span>@endif
                            </div>
                            <h3 class="mt-2 font-display text-[17px] font-bold leading-tight text-primary-900 line-clamp-2 group-hover:text-primary-700 transition">{{ $program->title }}</h3>
                            @if ($program->excerpt)
                                <p class="mt-2 line-clamp-2 text-sm leading-relaxed text-neutral-600">{{ Str::limit(strip_tags($program->excerpt), 110) }}</p>
                            @endif
                            <div class="mt-3 flex items-center gap-2 text-xs text-zinc-500">
                                @if($program->total_area)<span>{{ number_format((float) $program->total_area, 0, ',', ' ') }} m²</span>@endif
                                @if($program->published_at)<span>· {{ $program->published_at->format('d/m/Y') }}</span>@endif
                            </div>
                            <div class="mt-4 inline-flex items-center gap-1.5 text-xs font-semibold text-accent group-hover:gap-2 transition-all">{{ __('Découvrir') }} <span>→</span></div>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="mt-8">{{ $programs->links() }}</div>
        @endif
    </div>
</x-layouts.public>
