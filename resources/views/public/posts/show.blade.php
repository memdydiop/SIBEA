<x-layouts.public :title="$post->meta_title ?? $post->title" :metaDescription="$post->meta_description ?? $post->excerpt">
    <div class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8">
        <a href="{{ route('public.posts.index') }}" class="text-sm text-zinc-500 hover:text-zinc-900">← {{ __('Actualités') }}</a>
        <div class="mt-4 text-xs uppercase tracking-wide text-zinc-500">{{ $post->category }} @if($post->published_at) · {{ $post->published_at->format('d/m/Y') }} @endif</div>
        <h1 class="mt-2 text-3xl font-bold tracking-tight">{{ $post->title }}</h1>
        @if($post->excerpt)
            <p class="mt-4 text-lg text-zinc-600">{{ $post->excerpt }}</p>
        @endif
        @if($post->content)
            <div class="prose mt-8 max-w-none text-zinc-800">{!! nl2br(e($post->content)) !!}</div>
        @endif
        @if($post->author)
            <div class="mt-8 border-t border-zinc-200 pt-6 text-sm text-zinc-500">{{ __('Par') }} {{ $post->author->name }}</div>
        @endif

        @if($relatedPosts->isNotEmpty())
            <div class="mt-12 border-t border-zinc-200 pt-8">
                <h3 class="font-semibold">{{ __('À lire aussi') }}</h3>
                <div class="mt-4 grid gap-4">
                    @foreach($relatedPosts as $rel)
                        <a href="{{ route('public.posts.show', $rel->slug) }}" class="rounded-xl border border-zinc-200 p-4 hover:shadow-sm">
                            <div class="font-medium">{{ $rel->title }}</div>
                            <div class="text-sm text-zinc-600">{{ $rel->excerpt }}</div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-layouts.public>
