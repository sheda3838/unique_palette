@props(['active'])

@php
$classes = ($active ?? false)
? 'responsive-nav-link-custom responsive-nav-link-active'
: 'responsive-nav-link-custom responsive-nav-link-inactive';
@endphp

<a wire:navigate {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>