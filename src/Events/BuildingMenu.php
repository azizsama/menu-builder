<?php

namespace AzizSama\MenuBuilder\Events;

use AzizSama\MenuBuilder\Builder\Builder;

class BuildingMenu
{
    /**
     * The menu builder instance.
     * 
     * @var \AzizSama\MenuBuilder\Builder\Builder
     */
    public $builder;

    /**
     * Create a new event instance.
     * 
     * @param \AzizSama\MenuBuilder\Builder\Builder $builder
     * @return void
     */
    public function __construct(Builder $builder)
    {
        $this->builder = $builder;
    }
}