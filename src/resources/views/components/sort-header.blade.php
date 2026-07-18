@props(['column', 'label', 'sort', 'direction'])
@php
    $isActive = $sort === $column;
    $nextDirection = ($isActive && $direction === 'asc') ? 'desc' : 'asc';
    $arrow = $isActive ? ($direction === 'asc' ? '▲' : '▼') : '▼';
@endphp
<th class="px-5 py-3 text-left font-bold whitespace-nowrap">
    <a href="{{ request()->fullUrlWithQuery(['sort' => $column, 'direction' => $nextDirection, 'page' => 1])}}" class="inline-flex items-center gap-1 hover:text-gray-700 {{ $isActive ? 'text-gray-900' : '' }}">
        {{ $label }}
        <span class="text-xs {{ $isActive ? '' : 'text-gray-300' }}">{{ $arrow }}</span>
    </a>
</th>
