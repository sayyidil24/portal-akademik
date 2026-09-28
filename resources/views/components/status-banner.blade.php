@props(['tipe' => 'info'])

@php
    $kelasWarna = [
        'success' => 'bg-green-100 border-green-500 text-green-900 dark:bg-green-900/40 dark:text-green-100',
        'error'   => 'bg-red-100 border-red-500 text-red-900 dark:bg-red-900/40 dark:text-red-100',
        'warning' => 'bg-yellow-100 border-yellow-500 text-yellow-900 dark:bg-yellow-900/40 dark:text-yellow-100',
        'info'    => 'bg-blue-100 border-blue-500 text-blue-900 dark:bg-blue-900/40 dark:text-blue-100',
    ][$tipe] ?? 'bg-gray-100 border-gray-500 text-gray-900 dark:bg-gray-800 dark:text-gray-100';
@endphp

<div role="alert" {{ $attributes->merge(['class' => "rounded-md border-l-4 p-4 shadow-sm {$kelasWarna}"]) }}>
    <div class="font-medium">{{ $slot }}</div>
</div>
