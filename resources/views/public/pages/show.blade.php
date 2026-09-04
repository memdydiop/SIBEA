<x-layouts.public :title="$page->meta_title ?? $page->title" :metaDescription="$page->meta_description ?? $page->excerpt" :ogImage="$page->cover_image">
    <div class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8">
        @if($page->cover_image)
            <img src="{{ $page->cover_image }}" alt="{{ $page->title }}" class="mb-8 w-full rounded-xl object-cover">
        @endif
        <h1 class="text-3xl font-bold tracking-tight">{{ $page->title }}</h1>
        @if($page->excerpt)
            <p class="mt-4 text-lg text-zinc-600">{{ $page->excerpt }}</p>
        @endif
        @if($page->content)
            <div class="prose mt-8 max-w-none text-zinc-800">{!! nl2br(e($page->content)) !!}</div>
        @endif
    </div>
</x-layouts.public>
