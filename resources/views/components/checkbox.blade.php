@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['type' => 'checkbox', 'class' => 'rounded border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-indigo-600 focus:ring-indigo-500 mt-1 shadow-sm']) !!}>
