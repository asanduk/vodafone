@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center text-sm font-medium leading-5 text-gray-900 bg-gray-50 focus:outline-none transition duration-150 ease-in-out'
            : 'inline-flex items-center text-sm font-medium leading-5 text-gray-700 hover:bg-gray-50 focus:outline-none transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
