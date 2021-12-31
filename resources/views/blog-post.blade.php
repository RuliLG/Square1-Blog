<x-guest-layout>
    @section('title', $post->title . ' | ' . config('app.name'))
    @section('metadescription', $post->excerpt)
    @push('meta')
        <link rel="canonical" href="{{ route('blog-post', ['id' => $post->id]) }}" />
        <meta name="author" content="{{ $post->owner->name }}">
        <meta name="twitter:card" content="summary">
        <meta name="twitter:site" content="@square1_io">
        <meta name="twitter:title" content="{{ $post->title }}">
        <meta name="twitter:description" content="{{ $post->excerpt }}">
        <meta name="twitter:creator" content="@square1_io">
        <meta name="twitter:image" content="{{ Tailgraph::url($post->title, $post->excerpt) }}">
        <meta property="og:title" content="{{ $post->title }}">
        <meta property="og:type" content="article">
        <meta property="og:url" content="{ route('blog-post', ['id' => $post->id]) }}">
        <meta property="og:image" content="{{ Tailgraph::url($post->title, $post->excerpt) }}">
        <meta property="og:description" content="{{ $post->excerpt }}">
    @endpush
    <div class="relative py-16 bg-white overflow-hidden">
        <div class="hidden lg:block lg:absolute lg:inset-y-0 lg:h-full lg:w-full">
            <div class="relative h-full text-lg max-w-prose mx-auto" aria-hidden="true">
                <svg class="absolute top-12 left-full transform translate-x-32" width="404" height="384" fill="none" viewBox="0 0 404 384">
                    <defs>
                        <pattern id="74b3fd99-0a6f-4271-bef2-e80eeafdf357" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                            <rect x="0" y="0" width="4" height="4" class="text-gray-200" fill="currentColor" />
                        </pattern>
                    </defs>
                    <rect width="404" height="384" fill="url(#74b3fd99-0a6f-4271-bef2-e80eeafdf357)" />
                </svg>
                <svg class="absolute top-1/2 right-full transform -translate-y-1/2 -translate-x-32" width="404" height="384" fill="none" viewBox="0 0 404 384">
                    <defs>
                        <pattern id="f210dbf6-a58d-4871-961e-36d5016a0f49" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                            <rect x="0" y="0" width="4" height="4" class="text-gray-200" fill="currentColor" />
                        </pattern>
                    </defs>
                    <rect width="404" height="384" fill="url(#f210dbf6-a58d-4871-961e-36d5016a0f49)" />
                </svg>
                <svg class="absolute bottom-12 left-full transform translate-x-32" width="404" height="384" fill="none" viewBox="0 0 404 384">
                    <defs>
                        <pattern id="d3eb07ae-5182-43e6-857d-35c643af9034" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                            <rect x="0" y="0" width="4" height="4" class="text-gray-200" fill="currentColor" />
                        </pattern>
                    </defs>
                    <rect width="404" height="384" fill="url(#d3eb07ae-5182-43e6-857d-35c643af9034)" />
                </svg>
            </div>
        </div>
        <div class="relative px-4 sm:px-6 lg:px-8">
            <div class="text-lg max-w-prose mx-auto">
                <h1 class="mt-2 block text-3xl text-center leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">{{ $post->title }}</h1>
            </div>
            <div class="mt-6 prose prose-indigo prose-lg text-gray-500 mx-auto">
                {!! $post->description !!}
            </div>
        </div>
    </div>

</x-guest-layout>
