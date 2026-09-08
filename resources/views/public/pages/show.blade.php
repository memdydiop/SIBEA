<x-layouts.public :title="$page->meta_title ?? $page->title" :metaDescription="$page->meta_description ?? $page->excerpt" :ogImage="$page->cover_image">
 <div class="bg-background">
 {{-- Hero compact primary-900 --}}
 <section class="relative overflow-hidden bg-primary-900 text-white">
 <div class="absolute inset-0">
 @if($page->cover_image)
 <img src="{{ $page->cover_image }}" alt="" class="h-full w-full object-cover opacity-20">
 @else
 <div class="h-full w-full bg-gradient-to-br from-primary-900 via-primary-800 to-primary-700"></div>
 @endif
 <div class="absolute inset-0 bg-primary-900/70"></div>
 <div class="absolute inset-0 opacity-[0.04]" style="background-image: linear-gradient(to right, white 1px, transparent 1px), linear-gradient(to bottom, white 1px, transparent 1px); background-size: 40px 40px;"></div>
 </div>
 <div class="relative mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8 sm:py-14">
 <p class="font-display text-xs font-semibold uppercase tracking-[0.18em] text-accent">{{ __('Page') }}</p>
 <h1 class="mt-3 font-display text-[32px] font-extrabold leading-tight sm:text-[40px]">{{ $page->title }}</h1>
 @if($page->excerpt)
 <p class="mt-3 text-[16px] leading-relaxed text-white/80">{{ $page->excerpt }}</p>
 @endif
 </div>
 </section>

 <div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8 sm:py-10">
 {{-- Cover 16/9 si image distincte du hero --}}
 @if($page->cover_image)
 <div class="aspect-[16/9] overflow-hidden rounded-xl bg-zinc-100">
 <img src="{{ $page->cover_image }}" alt="{{ $page->title }}" class="h-full w-full object-cover">
 </div>
 @endif

 <div class="mt-4 flex flex-wrap gap-2 text-xs text-zinc-500">
 <span class="inline-flex items-center gap-1 rounded-full bg-white px-2.5 py-1 ring-1 ring-zinc-200">{{ __('Page') }}</span>
 @if($page->updated_at)<span class="inline-flex items-center gap-1 rounded-full bg-white px-2.5 py-1 ring-1 ring-zinc-200">{{ __('Mis à jour le') }} {{ $page->updated_at->format('d/m/Y') }}</span>@endif
 </div>

 @if($page->content)
 <div class="prose mt-8 max-w-none text-zinc-800 sm:prose-zinc">{!! nl2br(e($page->content)) !!}</div>
 @endif

 <div class="mt-10 flex flex-wrap gap-3">
 <a href="{{ route('public.contact') }}" class="inline-flex items-center rounded-sm bg-accent px-5 py-2.5 text-sm font-display font-semibold text-primary-900 hover:bg-accent-300 transition">{{ __('Nous contacter') }}</a>
 <a href="{{ route('home') }}" class="inline-flex items-center rounded-sm border border-zinc-300 bg-white px-5 py-2.5 text-sm font-semibold text-zinc-700 hover:bg-zinc-50 transition">{{ __('Retour accueil') }}</a>
 </div>
 </div>
 </div>
</x-layouts.public>
