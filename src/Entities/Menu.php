<?php

namespace AzizSama\MenuBuilder\Entities;

class Menu
{
    /**
     * Create a new Menu instance
     * 
     * @param array $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        foreach ($attributes as $attr => $value) {
            $this->{$attr} = $value;
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
