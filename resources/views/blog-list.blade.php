<x-guest-layout>
    @push('meta')
        @if (request()->get('page') == 1 || !request()->get('page'))
        <link rel="canonical" href="{{ route('home') }}" />
        @else
        <link rel="canonical" href="{{ route('home', ['page' => request()->get('page')]) }}" />
        @endif
        <meta name="author" content="Square1">
        <meta name="twitter:card" content="summary">
        <meta name="twitter:site" content="@square1_io">
        <meta name="twitter:title" content="{{ config('app.name') }}">
        <meta name="twitter:creator" content="@square1_io">
        <meta name="twitter:image" content="{{ Tailgraph::url(config('app.name'), '') }}">
        <meta property="og:title" content="{{ config('app.name') }}">
        <meta property="og:type" content="article">
        <meta property="og:url" content="{ route('blog-post', ['id' => $post->id]) }}">
        <meta property="og:image" content="{{ Tailgraph::url(config('app.name'), '') }}">
    @endpush
    <div class="bg-white pt-16 pb-20 px-4 sm:px-6 lg:pt-24 lg:pb-28 lg:px-8">
        <div class="relative max-w-lg mx-auto divide-y-2 divide-gray-200 lg:max-w-7xl">
            <div>
                <div class="flex justify-between">
                    <h1 class="text-3xl tracking-tight font-extrabold text-gray-900 sm:text-4xl">
                        @lang('blog.title')
                    </h1>
                    <form action="{{ route('home') }}" method="GET">
                        <input type="hidden" name="sort" value="{{ request()->get('sort') === 'asc' ? 'desc' : 'asc' }}">
                        <button type="submit" class="inline-flex items-center space-x-2 bg-none">
                            @if (request()->get('sort') === 'asc')
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path></svg>
                            <span>Oldest first</span>
                            @else
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4"></path></svg>
                            <span>Newest first</span>
                            @endif
                        </button>
                    </form>
                </div>
                <div class="mt-3 sm:mt-4 lg:grid lg:grid-cols-2 lg:gap-5 lg:items-center">
                    <p class="text-xl text-gray-500">
                        @lang('blog.description')
                    </p>
                </div>
            </div>
            <div class="mt-6 pt-10 grid gap-16 lg:grid-cols-2 lg:gap-x-5 lg:gap-y-12">
                @foreach ($posts as $post)
                <div>
                    <p class="text-sm text-gray-500">
                        <time datetime="{{ $post->published_at->format('Y-m-d H:i:s')}}">{{ $post->published_at->format('d/m/Y \a\t H:i')}}</time>
                    </p>
                    <a href="{{ route('blog-post', ['id' => $post->id])}}" class="mt-2 block">
                        <p class="text-xl font-semibold text-gray-900">
                            {{ $post->title }}
                        </p>
                        <p class="mt-3 text-base text-gray-500">
                            {{ $post->excerpt }}
                        </p>
                    </a>
                    <div class="mt-3">
                        <a href="{{ route('blog-post', ['id' => $post->id])}}" class="text-base font-semibold text-primary-600 hover:text-primary-500">
                            @lang('blog.read_more')
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-8 pt-8">
                {{ $posts->withQueryString()->links() }}
            </div>
        </div>
    </div>

</x-guest-layout>
