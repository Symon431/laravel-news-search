@props(['href' => null, 'type' => 'button']) {{-- ADD 'href' to the props, and set default type to 'button' --}}

@php
    // Extract the classes from your button tag and store them in a variable
    $classes = 'inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150';
@endphp

{{-- Conditional rendering: If 'href' is provided, render an <a> tag --}}
@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    {{-- Otherwise, render a <button> tag (default behavior) --}}
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
