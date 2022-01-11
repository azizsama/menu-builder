<?php

namespace AzizSama\MenuBuilder;

use AzizSama\MenuBuilder\Builder\Builder;
use AzizSama\MenuBuilder\Events\BuildingMenu;
use AzizSama\MenuBuilder\Support\MenuCollection;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\Cache;

class MenuBuilder
{
    /**
     * The filters should be applied.
     * 
     * @var array
     */
    protected $filters = [];

    /**
     * Indicates if the menu is should be cached.
     * 
     * @var boolean
     */
    protected $cache = false;

    /**
     * The cache key.
     * 
     * @var string
     */
    protected $cacheKey = 'menu-builder';

    /**
     * The cache time.
     * 
     * @var int
     */
    protected $cacheTime = 60;

    /**
     * The Dispatcher instance.
     * 
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected $events;

    /**
     * The menu results.
     * 
     * @var array
     */
    protected $results = [];
    
    /**
     * Create a new MenuBuilder instance
     * 
     * @param array $filters
     * @param \Illuminate\Contracts\Events\Dispatcher $events
     * @param bool $cache
     * @param string $cacheKey
     * @param int $cacheTime
     * @return void
     */
    public function __construct(array $filters = [], Dispatcher $events = null, $cache = false, $cacheKey = 'menu-builder', $cacheTime = 60)
    {
        $this->filters = $this->createFilters($filters);
        $this->cache = $cache;
        $this->cacheKey = $cacheKey;
        $this->cacheTime = $cacheTime;
        $this->events = $events;
    }

    /**
     * Create the Filter.
     * 
     * @param array $filters
     * @return array
     */
    public function createFilters(array $filters = [])
    {
        return array_map(fn($filter) => app()->make($filter), $filters);
    }

    /**
     * Build the menu
     * 
     * @return void
     */
    public function build()
    {
        if ($this->cache) {
            $this->results = Cache::get($this->cacheKey, []);
            if (! empty($this->results)) {
                return;
            }
        }

        $builder = new Builder($this->filters);
        $this->events->dispatch(new BuildingMenu($builder));
        $this->results = $builder->get();
    }

    /**
     * Get the menu collection.
     * 
     * @param string|null $group
     * @return \AzizSama\MenuBuilder\Support\MenuCollection
     */
    public function get(string $group = null)
    {
        if (empty($this->results)) {
            $this->build();
        }
        $collection = new MenuCollection($this->results);
        return $group !== null ? $collection->get($group) : $collection;
    }
}