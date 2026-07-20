@props(['column', 'label', 'sort', 'direction', 'color'=> 'gray'])
@php
    $isActive = $sort === $column;
    $nextDirection = ($isActive && $direction === 'asc') ? 'desc' : 'asc';
    $arrow = $isActive ? ($direction === 'asc' ? '▲' : '▼') : '▼';
    $colorClasses = [
        'gray' => ['active' => 'text-gray-900', 'hover' => 'hover:text-gray-700', 'arrow' => 'text-gray-300'],
        'book' => ['active' => 'text-book', 'hover' => 'hover:text-book', 'arrow' => 'text-green-300'],
    ];
    $c = $colorClasses[$color] ?? $colorClasses['gray'];
@endphp
<th class="px-5 py-3 text-left font-bold whitespace-nowrap">
    <a href="{{ request()->fullUrlWithQuery(['sort' => $column, 'direction' => $nextDirection, 'page' => 1])}}" class="inline-flex items-center gap-1 {{ $c['hover']}} {{ $isActive ? $c['active']: '' }}">
        {{ $label }}
        <span class="text-xs {{ $isActive ? '' : $c['arrow']}}">{{ $arrow }}</span>
    </a>
</th>
