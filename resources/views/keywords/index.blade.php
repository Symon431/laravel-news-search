<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Keywords') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Add New Keyword</h3>
                <form method="POST" action="{{ route('keywords.store') }}">
                    @csrf {{-- CSRF protection is crucial for all POST forms in Laravel --}}

                    <div>
                        <x-input-label for="keyword" :value="__('Keyword')" />
                        <x-text-input id="keyword" name="keyword" type="text" class="mt-1 block w-full" :value="old('keyword')" required autofocus />
                        <x-input-error :messages="$errors->get('keyword')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <x-primary-button>
                            {{ __('Add Keyword') }}
                        </x-primary-button>
                    </div>
                </form>


                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mt-8 mb-4">Your Keywords</h3>
                    @if ($keywords->isEmpty())
                        <p class="text-gray-600 dark:text-gray-400">You haven't added any keywords yet.</p>

                    @endif

                    @foreach ($keywords as $keyword)
                        <li class="flex items-center justify-between py-2">
                            <span>{{ $keyword->keyword }}</span>
                            <div class="flex items-center ml-4">
                                {{-- Edit Button/Link --}}
                                <a href="{{ route('keywords.edit', $keyword) }}" class="text-indigo-600 hover:text-indigo-900 text-sm mr-4">
                                    {{ __('Edit') }}
                                </a>

                                {{-- Delete Button/Form --}}
                                <form action="{{ route('keywords.destroy', $keyword) }}" method="POST">
                                    @csrf
                                    @method('DELETE') {{-- Use DELETE method for deletion --}}
                                    <button type="submit" class="text-red-600 hover:text-red-900 text-sm" onclick="return confirm('Are you sure you want to delete this keyword?');">
                                        {{ __('Delete') }}
                                    </button>
                                </form>
                            </div>
                        </li>
                    @endforeach

            </div>
        </div>
    </div>
</x-app-layout>
