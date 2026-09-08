<x-layouts.public :title="$service->meta_title ?? $service->title" :metaDescription="$service->meta_description ?? $service->excerpt">
 <div class="bg-background">
 {{-- Breadcrumb --}}
 <div class="mx-auto max-w-7xl px-4 pt-6 sm:px-6 lg:px-8">
 <a href="{{ $service->expertise ? route('public.expertises.show', $service->expertise->slug) : route('public.expertises.index') }}" class="inline-flex items-center gap-1 text-sm text-zinc-500 hover:text-zinc-900"><span>←</span> {{ $service->expertise?->title ?? __('Expertises') }}</a>
 </div>

 {{-- Hero image 16/9 si disponible --}}
 @if(!empty($service->cover_image ?? null))
 <div class="mx-auto mt-4 max-w-7xl px-4 sm:px-6 lg:px-8">
 <div class="aspect-[16/9] overflow-hidden rounded-xl bg-zinc-100 sm:aspect-[2/1]">
 <img src="{{ $service->cover_image }}" alt="{{ $service->title }}" class="h-full w-full object-cover">
 </div>
 </div>
 @endif

 <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 sm:py-10">
 {{-- Eyebrow + meta --}}
 <div class="flex flex-wrap items-center gap-2 text-xs font-semibold uppercase tracking-wide text-primary-700">
 <span class="rounded-full bg-primary-50 px-2.5 py-1 text-primary-700">{{ __('Service') }}</span>
 @if($service->expertise)
 <a href="{{ route('public.expertises.show', $service->expertise->slug) }}" class="text-zinc-500 hover:text-primary-700">{{ $service->expertise->title }}</a>
 @endif
 </div>

 <h1 class="mt-3 font-display text-[28px] font-bold tracking-tight text-primary-900 sm:text-[36px]">{{ $service->title }}</h1>

 @if($service->excerpt)
 <p class="mt-3 max-w-3xl text-[17px] leading-relaxed text-zinc-600">{{ $service->excerpt }}</p>
 @endif

 {{-- Stats sobres CDC 3 colonnes --}}
 <div class="mt-6 grid max-w-xl grid-cols-3 gap-4 rounded-xl border border-zinc-200 bg-white p-4 sm:p-5">
 <div class="text-center">
 <div class="font-display text-xl font-bold text-primary-900">{{ $relatedServices->count() + 1 }}</div>
 <div class="text-xs uppercase tracking-wide text-zinc-500">{{ __('Services') }}</div>
 </div>
 <div class="text-center border-x border-zinc-200">
 <div class="font-display text-xl font-bold text-primary-900">{{ $service->expertise?->services_count ?? '—' }}</div>
 <div class="text-xs uppercase tracking-wide text-zinc-500">{{ __('De l’expertise') }}</div>
 </div>
 <div class="text-center">
 <div class="font-display text-xl font-bold text-accent">48h</div>
 <div class="text-xs uppercase tracking-wide text-zinc-500">{{ __('Réponse devis') }}</div>
 </div>
 </div>

 @if($service->content)
 <div class="prose mt-8 max-w-none text-zinc-800 sm:prose-zinc">{!! nl2br(e($service->content)) !!}</div>
 @endif

 @if($relatedServices->isNotEmpty())
 <div class="mt-10">
 <div class="flex items-end justify-between gap-4">
 <div>
 <h2 class="font-display text-[22px] font-bold tracking-tight text-primary-900">{{ __('Autres services') }}</h2>
 <p class="mt-1 text-sm text-zinc-500">{{ __('Découvrez les autres prestations de cette expertise.') }}</p>
 </div>
 </div>
 <div class="mt-6 grid gap-6 sm:grid-cols-2">
 @foreach($relatedServices as $rel)
 <a href="{{ route('public.services.show', $rel->slug) }}" class="group flex flex-col overflow-hidden rounded-xl border border-border bg-surface shadow-[0_1px_2px_rgba(11,31,51,0.06)] hover:shadow-md transition">
 <div class="aspect-[4/3] overflow-hidden relative">
 @if(!empty($rel->cover_image ?? null))
 <img src="{{ $rel->cover_image }}" alt="{{ $rel->title }}" class="h-full w-full object-cover group-hover:scale-[1.03] transition duration-300">
 <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent opacity-0 group-hover:opacity-100 transition"></div>
 @else
 <div class="h-full w-full bg-gradient-to-br from-primary-900 via-primary-800 to-primary-700 flex items-center justify-center">
 <span class="font-display text-3xl font-extrabold text-white/20">{{ Str::upper(Str::substr($rel->title, 0, 2)) }}</span>
 </div>
 @endif
 </div>
 <div class="flex flex-1 flex-col p-5">
 <h3 class="font-display text-[16px] font-semibold leading-tight text-primary-900 group-hover:text-accent transition">{{ $rel->title }}</h3>
 @if($rel->excerpt)
 <p class="mt-2 line-clamp-2 text-sm leading-relaxed text-neutral-600">{{ $rel->excerpt }}</p>
 @endif
 <div class="mt-3 inline-flex items-center gap-1 text-xs font-semibold text-accent group-hover:text-primary-700">{{ __('Voir le service') }} →</div>
 </div>
 </a>
 @endforeach
 </div>
 </div>
 @endif

 <div class="mt-10 rounded-xl bg-primary-900 p-6 sm:p-8 text-white">
 <h3 class="font-display text-[18px] font-semibold">{{ __('Besoin de ce service ?') }}</h3>
 <p class="mt-2 text-sm leading-relaxed text-white/70">{{ __('Demandez un devis détaillé, réponse sous 48h. Notre équipe étudie votre projet et vous accompagne de l’étude à la réception.') }}</p>
 <div class="mt-6 flex flex-wrap gap-3">
 <a href="{{ route('public.quote.create') }}" class="inline-flex items-center rounded-sm bg-accent px-5 py-2.5 text-sm font-display font-semibold text-primary-900 hover:bg-accent-300 transition">{{ __('Demander un devis') }}</a>
 <a href="{{ route('public.contact') }}" class="inline-flex items-center rounded-sm border border-white/20 bg-transparent px-5 py-2.5 text-sm font-semibold text-white hover:bg-white/10 transition">{{ __('Nous contacter') }}</a>
 </div>
 </div>
 </div>
 </div>
</x-layouts.public>
