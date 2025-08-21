<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}

                    {{-- Option 1: Place it directly after the text, perhaps with some margin --}}
                    <div class="mt-4"> {{-- mt-4 adds margin-top for spacing --}}
                        <x-secondary-button href="/keywords">
                            {{ __('Go to Keywords') }} {{-- Text inside the button --}}
                        </x-secondary-button>
                    </div>



                </div>
            </div>
        </div>
    </div>
</x-app-layout>
