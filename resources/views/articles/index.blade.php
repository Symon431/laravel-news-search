<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Fetched Articles') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">

                {{-- Success/Error Messages --}}
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif
                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Your Fetched Articles</h3>
                    <a href="{{ route('articles.fetch.form') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        {{ __('Find Latest Articles') }}
                    </a>
                </div>

                @if ($articles->isEmpty())
                    <p class="text-gray-600 dark:text-gray-400">No articles found yet. Click "Find Latest Articles" to fetch some!</p>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($articles as $article)
                            <div class="bg-gray-100 dark:bg-gray-700 rounded-lg shadow-md overflow-hidden flex flex-col">
                                @if ($article->image_url)
                                    <img src="{{ $article->image_url }}" alt="{{ $article->title }}" class="w-full h-48 object-cover" onerror="this.onerror=null;this.src='https://placehold.co/600x400/E0E0E0/333333?text=No+Image';" loading="lazy">
                                @else
                                    <img src="https://placehold.co/600x400/E0E0E0/333333?text=No+Image" alt="No image available" class="w-full h-48 object-cover">
                                @endif
                                <div class="p-4 flex-grow">
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">{{ $article->title }}</h4>
                                    <p class="text-gray-700 dark:text-gray-300 text-sm mb-3 line-clamp-3">{{ $article->summary }}</p>
                                    <p class="text-gray-500 dark:text-gray-400 text-xs mb-1">Source: {{ $article->source ?? 'N/A' }}</p>
                                    <p class="text-gray-500 dark:text-gray-400 text-xs">Published: {{ $article->published_at ? \Carbon\Carbon::parse($article->published_at)->diffForHumans() : 'N/A' }}</p>
                                </div>
                                <div class="p-4 border-t border-gray-200 dark:border-gray-600 flex justify-between items-center">
                                    <a href="{{ $article->url }}" target="_blank" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200 text-sm font-medium">
                                        Read More
                                    </a>
                                    <form action="{{ route('articles.destroy', $article) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-200 text-sm" onclick="return confirm('Are you sure you want to delete this article?');">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        {{ $articles->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
