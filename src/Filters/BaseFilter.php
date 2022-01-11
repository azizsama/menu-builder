<?php

namespace AzizSama\MenuBuilder\Filters;

use AzizSama\MenuBuilder\Contracts\Filter;

abstract class BaseFilter implements Filter
{
    /**
     * Apply the filter.
     * 
     * @param  array $item
     * @return array
     */
    public function apply(array $item): array
    {
        return $item;
    }

    /**
     * Enable the item
     * 
     * @param array $item
     * @return array
     */
    public function enable(array $item): array
    {
        if ($this->isEnabled($item)) {
            return $item;
        }

        if (isset($item['disabled'])) {
            unset($item['disabled']);
        }

        return $item;
    }

    /**
     * Disable the item
     * 
     * @param array $item
     * @return array
     */
    public function disable(array $item): array
    {
        if (!$this->isEnabled($item)) {
            return $item;
        }

        $item['disabled'] = true;

        return $item;
    }

    /**
     * Check if the item is enabled
     * 
     * @param  array $item
     * @return boolean
     */
    public function isEnabled(array $item): bool
    {
        return !isset($item['disabled']) || $item['disabled'] !== true;
    }
}
