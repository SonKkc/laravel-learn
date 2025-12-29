@php
    // Normalize status key to lowercase string
    $s = is_string($status ?? '') ? strtolower($status) : '';

    $map = [
        'new' => ['label' => 'Pending', 'class' => 'bg-gray-100 text-gray-700'],
        'pending' => ['label' => 'Pending', 'class' => 'bg-gray-100 text-gray-700'],
        'processing' => ['label' => 'Processing', 'class' => 'bg-yellow-100 text-yellow-700'],
        'shipped' => ['label' => 'Shipped', 'class' => 'bg-blue-100 text-blue-700'],
        'delivered' => ['label' => 'Delivered', 'class' => 'bg-green-100 text-green-700'],
        'completed' => ['label' => 'Delivered', 'class' => 'bg-green-100 text-green-700'],
        'cancelled' => ['label' => 'Cancelled', 'class' => 'bg-red-100 text-red-700'],
        'canceled' => ['label' => 'Cancelled', 'class' => 'bg-red-100 text-red-700'],
        'refunded' => ['label' => 'Refunded', 'class' => 'bg-indigo-100 text-indigo-700'],
        'requesting' => ['label' => 'Requesting', 'class' => 'bg-indigo-50 text-indigo-700'],
        'requested' => ['label' => 'Requesting', 'class' => 'bg-indigo-50 text-indigo-700'],
        'failed' => ['label' => 'Failed', 'class' => 'bg-red-100 text-red-700'],
        'on-hold' => ['label' => 'On Hold', 'class' => 'bg-gray-100 text-gray-700'],
    ];

    $entry = $map[$s] ?? null;
    $label = $entry['label'] ?? (strlen($s) ? ucfirst($s) : '—');
    $classes = $entry['class'] ?? 'bg-gray-100 text-gray-700';
    // Default sizing utilities for badges
    $base = 'inline-block px-2 py-1 rounded-full text-xs font-medium';
    $fullClass = trim($base . ' ' . $classes);
@endphp

<span class="{{ $fullClass }}">{{ $label }}</span>
