@props([
    'type' => 'info',
    'message' => ''
])

@php
$config = [
    'success' => [
        'bg' => 'bg-green-50',
        'border' => 'border-green-500',
        'text' => 'text-green-800',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>'
    ],

    'error' => [
        'bg' => 'bg-red-50',
        'border' => 'border-red-500',
        'text' => 'text-red-800',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.007v.008H12v-.008Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z" />
                  </svg>'
    ],

    'warning' => [
        'bg' => 'bg-yellow-50',
        'border' => 'border-yellow-500',
        'text' => 'text-yellow-800',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86 1.82 18a2.25 2.25 0 0 0 1.93 3.37h16.5A2.25 2.25 0 0 0 22.18 18L13.71 3.86a2.25 2.25 0 0 0-3.42 0Z" />
                  </svg>'
    ],

    'info' => [
        'bg' => 'bg-blue-50',
        'border' => 'border-blue-500',
        'text' => 'text-blue-800',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 17.25h.008v.008H12v-.008Zm0-10.5v6" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>'
    ],
];
@endphp

<div class="flex items-start gap-3 p-4 rounded-xl border-l-4 shadow-sm {{ $config[$type]['bg'] }} {{ $config[$type]['border'] }}">
    <div class="{{ $config[$type]['text'] }}">
        {!! $config[$type]['icon'] !!}
    </div>

    <div class="flex-1">
        <p class="font-medium {{ $config[$type]['text'] }}">
            {{ $message }}
        </p>
    </div>
</div>
