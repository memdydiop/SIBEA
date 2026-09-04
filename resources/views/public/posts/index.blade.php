<x-layouts.public :title="__('Actualités')">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight">{{ __('Actualités') }}</h1>
        <p class="mt-2 text-zinc-600">{{ __('Suivez nos chantiers, innovations et engagements.') }}</p>

        @if($categories->isNotEmpty())
            <div class="mt-6 flex flex-wrap gap-2">
                <a href="{{ route('public.posts.index') }}" class="rounded-full px-3 py-1 text-sm {{ !$activeCategory ? 'bg-zinc-900 text-white' : 'border border-zinc-200 hover:bg-zinc-50' }}">{{ __('Toutes') }}</a>
                @foreach($categories as $cat)
                    <a href="{{ route('public.posts.index', ['categorie' => $cat]) }}" class="rounded-full px-3 py-1 text-sm {{ $activeCategory === $cat ? 'bg-zinc-900 text-white' : 'border border-zinc-200 hover:bg-zinc-50' }}">{{ $cat }}</a>
                @endforeach
            </div>
        @endif

        <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($posts as $post)
                <a href="{{ route('public.posts.show', $post->slug) }}" class="rounded-xl border border-zinc-200 p-6 hover:shadow-sm">
                    <div class="text-xs text-zinc-500">{{ $post->category }} · {{ $post->published_at?->format('d/m/Y') }}</div>
                    <h3 class="mt-2 font-semibold">{{ $post->title }}</h3>
                    <p class="mt-2 line-clamp-2 text-sm text-zinc-600">{{ $post->excerpt }}</p>
                </a>
            @empty
                <p class="text-sm text-zinc-500">{{ __('Aucune actualité.') }}</p>
            @endforelse
        </div>

        <div class="mt-8">{{ $posts->links() }}</div>
    </div>
</x-layouts.public>
