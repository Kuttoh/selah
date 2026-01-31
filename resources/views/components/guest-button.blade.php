@props([
    'href' => null,
    'type' => 'button',
    'disabled' => false,
    'fullWidth' => false,
])

@php
$baseClasses = 'px-5 py-2 bg-[#1b1b18] dark:bg-[#eeeeec] dark:border-[#eeeeec] dark:text-[#1C1C1A] dark:hover:bg-white dark:hover:border-white hover:bg-black hover:border-black border border-black text-white rounded-sm text-sm leading-normal';
$widthClass = $fullWidth ? 'w-full' : 'inline-block';
$disabledClass = $disabled ? 'opacity-50 cursor-not-allowed' : '';
$classes = "$baseClasses $widthClass $disabledClass";
@endphp

@if($href)
    <a
        href="{{ $href }}"
        {{ $attributes->merge(['class' => $classes]) }}
    >
        {{ $slot }}
    </a>
@else
    <button
        type="{{ $type }}"
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes->merge(['class' => $classes]) }}
    >
        {{ $slot }}
    </button>
@endif
