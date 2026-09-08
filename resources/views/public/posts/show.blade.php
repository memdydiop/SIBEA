<x-layouts.public :title="$post->meta_title ?? $post->title" :metaDescription="$post->meta_description ?? $post->excerpt">
 <div class="bg-background">
 {{-- Breadcrumb --}}
 <div class="mx-auto max-w-3xl px-4 pt-6 sm:px-6 lg:px-8">
 <a href="{{ route('public.posts.index') }}" class="inline-flex items-center gap-1 text-sm text-zinc-500 hover:text-zinc-900"><span>←</span> {{ __('Actualités') }}</a>
 </div>

 <div class="mx-auto max-w-3xl px-4 py-6 sm:px-6 lg:px-8 sm:py-8">
 {{-- Eyebrow --}}
 <div class="flex flex-wrap items-center gap-2 text-xs font-semibold uppercase tracking-wide text-primary-700">
 <span class="rounded-full bg-primary-50 px-2.5 py-1 text-primary-700">{{ $post->category ?? __('Actualité') }}</span>
 @if($post->published_at)<span class="text-zinc-500">{{ $post->published_at->format('d/m/Y') }}</span>@endif
 @if($post->author)<span class="text-zinc-400">·</span><span class="text-zinc-500">{{ $post->author->name }}</span>@endif
 </div>

 <h1 class="mt-3 font-display text-[28px] font-bold tracking-tight text-primary-900 sm:text-[36px] leading-tight">{{ $post->title }}</h1>

 @if($post->excerpt)
 <p class="mt-4 text-[17px] leading-relaxed text-zinc-600">{{ $post->excerpt }}</p>
 @endif

 {{-- Meta stats sobres --}}
 <div class="mt-6 flex flex-wrap gap-2 text-xs text-zinc-500">
 @if($post->category)<span class="inline-flex items-center gap-1 rounded-full bg-white px-2.5 py-1 ring-1 ring-zinc-200">{{ $post->category }}</span>@endif
 @if($post->published_at)<span class="inline-flex items-center gap-1 rounded-full bg-white px-2.5 py-1 ring-1 ring-zinc-200">{{ __('Publié le') }} {{ $post->published_at->format('d/m/Y') }}</span>@endif
 @if($post->author)<span class="inline-flex items-center gap-1 rounded-full bg-white px-2.5 py-1 ring-1 ring-zinc-200">{{ __('Par') }} {{ $post->author->name }}</span>@endif
 </div>

 {{-- Cover 16/9 si existe --}}
 @if(!empty($post->cover_image ?? null))
 <div class="mt-8 aspect-[16/9] overflow-hidden rounded-xl bg-zinc-100">
 <img src="{{ $post->cover_image }}" alt="{{ $post->title }}" class="h-full w-full object-cover">
 </div>
 @endif

 @if($post->content)
 <div class="prose mt-8 max-w-none text-zinc-800 sm:prose-zinc">{!! nl2br(e($post->content)) !!}</div>
 @endif

 @if($post->author)
 <div class="mt-8 flex items-center gap-3 rounded-xl border border-zinc-200 bg-white p-4">
 <div class="flex h-10 w-10 items-center justify-center rounded-full bg-primary-100 font-display text-sm font-bold text-primary-700">{{ Str::upper(Str::substr($post->author->name, 0, 1)) }}</div>
 <div>
 <div class="text-sm font-semibold text-primary-900">{{ $post->author->name }}</div>
 <div class="text-xs text-zinc-500">{{ __('Auteur') }}</div>
 </div>
 </div>
 @endif

 @if($relatedPosts->isNotEmpty())
 <div class="mt-12 border-t border-zinc-200 pt-8">
 <h3 class="font-display text-[18px] font-semibold text-primary-900">{{ __('À lire aussi') }}</h3>
 <div class="mt-6 grid gap-4">
 @foreach($relatedPosts as $rel)
 <a href="{{ route('public.posts.show', $rel->slug) }}" class="group flex gap-4 rounded-xl border border-border bg-surface p-4 shadow-[0_1px_2px_rgba(11,31,51,0.06)] hover:shadow-md transition">
 <div class="hidden h-20 w-28 shrink-0 overflow-hidden rounded-lg bg-zinc-100 sm:block">
 @if(!empty($rel->cover_image ?? null))
 <img src="{{ $rel->cover_image }}" alt="{{ $rel->title }}" class="h-full w-full object-cover group-hover:scale-[1.03] transition duration-300">
 @else
 <div class="h-full w-full bg-gradient-to-br from-primary-900 to-primary-700"></div>
 @endif
 </div>
 <div class="flex-1 min-w-0">
 <div class="text-[11px] font-semibold uppercase tracking-wide text-zinc-500">{{ $rel->category }} @if($rel->published_at)· {{ $rel->published_at->format('d/m/Y') }}@endif</div>
 <div class="mt-1 font-display text-[15px] font-semibold text-primary-900 line-clamp-2 group-hover:text-accent transition">{{ $rel->title }}</div>
 @if($rel->excerpt)<div class="mt-1 line-clamp-2 text-sm text-zinc-600">{{ Str::limit($rel->excerpt, 100) }}</div>@endif
 </div>
 <div class="hidden items-center text-accent sm:flex">→</div>
 </a>
 @endforeach
 </div>
 </div>
 @endif

 <div class="mt-10 flex flex-wrap gap-3">
 <a href="{{ route('public.posts.index') }}" class="inline-flex items-center rounded-sm border border-zinc-300 bg-white px-5 py-2.5 text-sm font-semibold text-zinc-700 hover:bg-zinc-50 transition">{{ __('Toutes les actualités') }}</a>
 <a href="{{ route('public.quote.create') }}" class="inline-flex items-center rounded-sm bg-accent px-5 py-2.5 text-sm font-display font-semibold text-primary-900 hover:bg-accent-300 transition">{{ __('Demander un devis') }}</a>
 </div>
 </div>
 </div>
</x-layouts.public>
