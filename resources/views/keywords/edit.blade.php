<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Keyword') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form method="POST" action="{{ route('keywords.update', $keyword) }}">
                    @csrf {{-- CSRF protection --}}
                    @method('PUT') {{-- Spooof PUT method for HTML forms --}}

                    <div>
                        <x-input-label for="keyword" :value="__('Keyword')" />
                        <x-text-input id="keyword" name="keyword" type="text" class="mt-1 block w-full" :value="old('keyword', $keyword->keyword)" required autofocus />
                        <x-input-error :messages="$errors->get('keyword')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <x-primary-button>
                            {{ __('Update Keyword') }}
                        </x-primary-button>
                        <x-secondary-button href="{{ route('keywords.index') }}" class="ml-4">
                            {{ __('Cancel') }}
                        </x-secondary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
