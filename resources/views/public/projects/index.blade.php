<x-layouts.public :title="__('Réalisations')">
 {{-- Hero CDC --}}
 <section class="relative overflow-hidden bg-primary-900 text-white">
 <div class="absolute inset-0">
 <div class="h-full w-full bg-gradient-to-br from-primary-900 via-primary-800 to-primary-700"></div>
 <div class="absolute inset-0 bg-primary-900/70"></div>
 <div class="absolute inset-0 opacity-[0.04]" style="background-image: linear-gradient(to right, white 1px, transparent 1px), linear-gradient(to bottom, white 1px, transparent 1px); background-size: 40px 40px;"></div>
 </div>
 <div class="relative mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8 sm:py-16">
 <p class="font-display text-xs font-semibold uppercase tracking-[0.18em] text-accent">{{ __('Portfolio') }}</p>
 <h1 class="mt-3 font-display text-[32px] font-extrabold leading-tight sm:text-[40px]">{{ __('Réalisations') }}</h1>
 <p class="mt-3 max-w-2xl text-[16px] leading-relaxed text-white/80">{{ __('Nos projets livrés : bâtiment, VRD, génie civil, énergie — infrastructures durables et chantiers livrés dans les délais.') }}</p>
 <div class="mt-6 flex flex-wrap gap-3">
 <a href="{{ route('public.quote.create') }}" class="inline-flex items-center rounded-sm bg-accent px-5 py-2.5 text-sm font-display font-semibold text-primary-900 hover:bg-accent-300 transition">{{ __('Demander un devis') }}</a>
 <a href="{{ route('public.contact') }}" class="inline-flex items-center rounded-sm border border-white/20 bg-transparent px-5 py-2.5 text-sm font-semibold text-white hover:bg-white/10 transition">{{ __('Nous contacter') }}</a>
 </div>
 </div>
 </section>

 <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 sm:py-12">
 @if($categories->isNotEmpty())
 <div class="flex flex-wrap gap-2">
 <a href="{{ route('public.projects.index') }}" class="rounded-full px-3.5 py-1.5 text-sm font-medium transition {{ !$activeCategory ? 'bg-primary-900 text-white' : 'border border-border bg-surface text-zinc-700 hover:bg-zinc-50' }}">{{ __('Tous') }}</a>
 @foreach($categories as $cat)
 <a href="{{ route('public.projects.index', ['categorie' => $cat]) }}" class="rounded-full px-3.5 py-1.5 text-sm font-medium transition {{ $activeCategory === $cat ? 'bg-primary-900 text-white' : 'border border-border bg-surface text-zinc-700 hover:bg-zinc-50' }}">{{ $cat }}</a>
 @endforeach
 </div>
 @endif

 @if($projects->isEmpty())
 <div class="mt-8 rounded-xl border border-dashed border-zinc-300 bg-zinc-50 p-12 text-center">
 <p class="text-sm text-zinc-500">{{ __('Aucune réalisation.') }}</p>
 <a href="{{ route('home') }}" class="mt-4 inline-flex text-sm font-semibold text-primary-700 hover:text-accent">{{ __('Retour à l’accueil →') }}</a>
 </div>
 @else
 <div class="mt-3 flex items-center justify-between">
 <p class="text-sm text-zinc-500">{{ $projects->total() }} {{ __('projet(s) trouvé(s)') }}</p>
 </div>

 <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
 @foreach($projects as $project)
 <a href="{{ route('public.projects.show', $project->slug) }}" class="group flex flex-col overflow-hidden rounded-xl border border-border bg-surface shadow-[0_1px_2px_rgba(11,31,51,0.06)] hover:shadow-md transition">
 <div class="aspect-[4/3] bg-neutral-100 overflow-hidden relative">
 @if($project->cover_image)
 <img src="{{ $project->cover_image }}" alt="{{ $project->title }}" class="h-full w-full object-cover group-hover:scale-[1.03] transition duration-300">
 <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition"></div>
 @else
 <div class="h-full w-full bg-gradient-to-br from-primary-900 via-primary-800 to-primary-700 flex items-center justify-center">
 <span class="text-white/60 text-xs font-semibold uppercase tracking-wide">{{ $project->category ?? __('Réalisation') }}</span>
 </div>
 @endif
 <div class="absolute left-3 top-3 flex gap-1.5">
 @if($project->category)<span class="rounded-full bg-white/90 px-2 py-0.5 text-[11px] font-semibold text-primary-900 backdrop-blur">{{ $project->category }}</span>@endif
 @if($project->year)<span class="rounded-full bg-accent px-2 py-0.5 text-[11px] font-semibold text-primary-900">{{ $project->year }}</span>@endif
 </div>
 </div>
 <div class="flex flex-1 flex-col p-5">
 <div class="flex items-center gap-1.5 text-[11px] font-semibold uppercase tracking-wide text-neutral-500">
 @if($project->location)<span>{{ $project->location }}</span>@endif
 @if($project->client_name)<span>· {{ Str::limit($project->client_name, 20) }}</span>@endif
 </div>
 <h3 class="mt-2 font-display text-[17px] font-semibold leading-tight text-primary-900 line-clamp-2 group-hover:text-accent transition">{{ $project->title }}</h3>
 @if($project->description)
 <p class="mt-2 line-clamp-2 text-sm leading-relaxed text-neutral-600">{{ Str::limit(strip_tags($project->description), 110) }}</p>
 @endif
 <div class="mt-4 inline-flex items-center gap-1 text-xs font-semibold text-accent group-hover:text-primary-700">{{ __('Découvrir') }} →</div>
 </div>
 </a>
 @endforeach
 </div>

 <div class="mt-8">{{ $projects->links() }}</div>
 @endif
 </div>
</x-layouts.public>
