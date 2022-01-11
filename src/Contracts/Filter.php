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
    public function apply();

    /**
     * Enable the item
     * 
     * @return array
     */
    public function enable();

    /**
     * Disable the item
     * 
     * @return array
     */
    public function disable();

    /**
     * Check if the item is enabled
     * 
     * @param  array $item
     * @return boolean
     */
    public function isEnabled();
}