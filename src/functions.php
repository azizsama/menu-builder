<?php

if (! function_exists('menuBuilder')) {
    /**
     * Get the Menu Builder instance.
     * 
     * @return \AzizSama\MenuBuilder\MenuBuilder
     */
    function menuBuilder() {
        return app('menu-builder');
    }
}