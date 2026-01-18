@props(['active'])

@php
$classes = ($active ?? false)
? 'nav-link-custom nav-link-active'
: 'nav-link-custom nav-link-inactive';
@endphp

<a wire:navigate {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>