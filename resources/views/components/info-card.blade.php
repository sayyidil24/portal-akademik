@props(['judul'])

<div {{ $attributes->merge(['class' => 'rounded-lg border border-teal-500 bg-white p-4 shadow-sm dark:bg-slate-800']) }}>
    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ $judul }}</p>
    <div class="mt-1 text-lg font-medium text-slate-800 dark:text-slate-100">{{ $slot }}</div>
</div>
