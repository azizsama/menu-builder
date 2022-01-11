<?php

namespace AzizSama\MenuBuilder\Filters;

use AzizSama\MenuBuilder\Filters\BaseFilter;

class AuthFilter extends BaseFilter
{
    /**
     * Apply the filter to the given item.
     *
     * @param  array $item
     * @return array
     */
    public function apply(array $item): array
    {
        if (isset($item['auth']) && !auth()->check()) {
            return $this->disable($item);
        }

        return $item;
    }
}
