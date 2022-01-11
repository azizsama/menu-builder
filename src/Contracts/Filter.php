<?php

namespace AzizSama\MenuBuilder\Contracts;

/**
 * Filter interface
 * 
 * @method array apply(array $item)
 * @method array enable(array $item)
 * @method array disable(array $item)
 * @method array isEnabled(array $item) 
 */
interface Filter
{
    /**
     * Apply the filter.
     * 
     * @param  array $item
     * @return array
     */
    public function apply(array $item): array;

    /**
     * Enable the item
     * 
     * @param array $item
     * @return array
     */
    public function enable(array $item): array;

    /**
     * Disable the item
     * 
     * @param array $item
     * @return array
     */
    public function disable(array $item): array;

    /**
     * Check if the item is enabled
     * 
     * @param  array $item
     * @return boolean
     */
    public function isEnabled(array $item): bool;
}
