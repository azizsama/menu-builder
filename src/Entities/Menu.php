<?php

namespace AzizSama\MenuBuilder\Entities;

use AzizSama\MenuBuilder\Concerns\HasAttribute;

class Menu
{
    use HasAttribute;

    /**
     * Create a new Menu instance
     * 
     * @param array $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        foreach ($attributes as $attr => $value) {
            $this->setAttribute($attr, $value);
        }
    }

    /**
     * Get the item's icon as HTML
     * 
     * @return string
     */
    public function icon()
    {
        if (!$this->icon) {
            return '';
        }

        return $this->icon;
    }
}
