<?php

namespace App\Helpers;

class SortableLink
{
    public static function render($column, $title = null)
    {
        $title = $title ?: ucfirst($column);
        $sortBy = request('sort_by');
        $sortDirection = request('sort_direction', 'asc');

        $direction = ($sortBy === $column && $sortDirection === 'asc') ? 'desc' : 'asc';
        $icon = '';

        if ($sortBy === $column) {
            $icon = $sortDirection === 'asc'
                ? '<i class="fas fa-sort-up ms-1"></i>'
                : '<i class="fas fa-sort-down ms-1"></i>';
        } else {
            $icon = '<i class="fas fa-sort ms-1"></i>';
        }

        $queryParams = request()->query();
        $queryParams['sort_by'] = $column;
        $queryParams['sort_direction'] = $direction;

        $url = url()->current() . '?' . http_build_query($queryParams);

        return "<a href='{$url}' class='text-decoration-none'>{$title}{$icon}</a>";
    }
}
