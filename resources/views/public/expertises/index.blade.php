<x-layouts.public :title="__('Expertises')">
    {{-- Hero CDC — primary-900 overlay + motif --}}
    <section class="relative overflow-hidden bg-primary-900 text-white">
        <div class="absolute inset-0">
            <div class="h-full w-full bg-gradient-to-br from-primary-900 via-primary-800 to-primary-700"></div>
            <div class="absolute inset-0 bg-primary-900/70"></div>
            <div class="absolute inset-0 opacity-[0.04]" style="background-image: linear-gradient(to right, white 1px, transparent 1px), linear-gradient(to bottom, white 1px, transparent 1px); background-size: 40px 40px;"></div>
        </div>
        <div class="relative mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8 sm:py-16">
            <p class="font-display text-xs font-semibold uppercase tracking-[0.18em] text-accent">{{ __('Savoir-faire') }}</p>
            <h1 class="mt-3 font-display text-[32px] font-extrabold leading-tight sm:text-[40px]">{{ __('Nos expertises') }}</h1>
            <p class="mt-3 max-w-2xl text-[16px] leading-relaxed text-white/80">{{ __('Une entreprise mono-entreprise couvrant l’ensemble de la chaîne BTP : de l’étude à la réception — bâtiment, génie civil, VRD, énergie.') }}</p>
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('public.quote.create') }}" class="inline-flex items-center rounded-sm bg-accent px-5 py-2.5 text-sm font-display font-semibold text-primary-900 hover:bg-accent-300 transition">{{ __('Demander un devis') }}</a>
                <a href="{{ route('public.projects.index') }}" class="inline-flex items-center rounded-sm border border-white/20 bg-transparent px-5 py-2.5 text-sm font-semibold text-white hover:bg-white/10 transition">{{ __('Voir nos réalisations') }}</a>
            </div>
        </div>
    </section>

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 sm:py-12">
        @if($expertises->isEmpty())
            <div class="rounded-xl border border-dashed border-zinc-300 bg-zinc-50 p-12 text-center">
                <p class="text-sm text-zinc-500">{{ __('Aucune expertise.') }}</p>
                <a href="{{ route('home') }}" class="mt-4 inline-flex text-sm font-semibold text-primary-700 hover:text-accent">{{ __('Retour à l’accueil →') }}</a>
            </div>
        @else
            <div class="flex items-center justify-between">
                <p class="text-sm text-zinc-500">{{ $expertises->count() }} {{ __('expertise(s)') }}</p>
            </div>

            <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($expertises as $exp)
                    <a href="{{ route('public.expertises.show', $exp->slug) }}" class="group flex flex-col overflow-hidden rounded-xl border border-border bg-surface shadow-[0_1px_2px_rgba(11,31,51,0.06)] hover:shadow-md transition">
                        <div class="aspect-[4/3] overflow-hidden relative bg-gradient-to-br from-primary-900 via-primary-800 to-primary-700 flex items-center justify-center">
                            @if(!empty($exp->cover_image ?? null))
                                <img src="{{ $exp->cover_image }}" alt="{{ $exp->title }}" class="h-full w-full object-cover group-hover:scale-[1.03] transition duration-300">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition"></div>
                            @else
                                <span class="font-display text-5xl font-extrabold text-white/20">{{ Str::upper(Str::substr($exp->title, 0, 2)) }}</span>
                            @endif
                            <div class="absolute left-3 top-3 flex gap-1.5">
                                <span class="rounded-full bg-white/90 px-2 py-0.5 text-[11px] font-semibold text-primary-900 backdrop-blur">{{ $exp->services_count }} {{ __('services') }}</span>
                            </div>
                        </div>
                        <div class="flex flex-1 flex-col p-5">
                            <h3 class="font-display text-[17px] font-semibold leading-tight text-primary-900 line-clamp-2 group-hover:text-accent transition">{{ $exp->title }}</h3>
                            @if($exp->excerpt)
                                <p class="mt-2 line-clamp-2 text-sm leading-relaxed text-neutral-600">{{ $exp->excerpt }}</p>
                            @endif
                            <div class="mt-4 inline-flex items-center gap-1 text-xs font-semibold text-accent group-hover:text-primary-700">{{ __('Découvrir') }} →</div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</x-layouts.public>
