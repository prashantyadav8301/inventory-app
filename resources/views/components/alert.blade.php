@props(['type' => 'success', 'message'])

@php
    $classes = match ($type) {
        'success' => 'bg-green-100 text-green-800 border-green-300',
        'error' => 'bg-red-100 text-red-800 border-red-300',
        default => 'bg-gray-100 text-gray-800 border-gray-300',
    };
@endphp

<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    class="mb-4 px-4 py-3 rounded-lg border flex justify-between items-start {{ $classes }}">

    <span class="text-sm">{{ $message }}</span>

    <button @click="show = false" class="ml-4 text-lg leading-none font-bold opacity-50 hover:opacity-100">
        &times;
    </button>

</div>
