<?php

namespace AzizSama\MenuBuilder\Builder;

use AzizSama\MenuBuilder\Entities\Menu;
use AzizSama\MenuBuilder\Support\MenuCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Builder
{
    protected const ADD_AFTER = 0;
    protected const ADD_BEFORE = 1;
    protected const ADD_INSIDE = 2;

    /**
     * The menu items
     * 
     * @var array
     */
    protected $items = [];

    /**
     * The filters should applied to the menu items
     * 
     * @var array
     */
    protected $filters = [];


    /**
     * Create a new menu builder instance
     * 
     * @param  array $filters
     * @return void
     */
    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * Add the menu group
     * 
     * @param  string $name
     * @return \AzizSama\MenuBuilder\Builder\Builder
     */
    public function group(string $name): self
    {
        $this->items[$name] = [];

        return $this;
    }

    /**
     * Add the menu item
     * 
     * @param string $group
     * @param mixed $items
     * @return \AzizSama\MenuBuilder\Builder\Builder
     */
    public function add(string $group = 'main', ...$items): self
    {
        $results = $this->transformItems($items);
        if (!isset($this->items[$group])) {
            $this->items[$group] = [];
        }
        if (!empty($results)) {
            array_push($this->items[$group], ...$results);
        }
        return $this;
    }

    /**
     * Add the menu item before the specified item
     * 
     * @param mixed $before
     * @param mixed $items
     * @return \AzizSama\MenuBuilder\Builder\Builder
     */
    public function addBefore($before, ...$items): self
    {
        $results = $this->transformItems($items);
        if (!empty($results)) {
            $this->addItem($before, static::ADD_BEFORE, ...$results);
        }
        return $this;
    }

    /**
     * Add the menu item after the specified item
     * 
     * @param mixed $after
     * @param mixed $items
     * @return \AzizSama\MenuBuilder\Builder\Builder
     */
    public function addAfter($after, ...$items): self
    {
        $results = $this->transformItems($items);
        if (!empty($results)) {
            $this->addItem($after, static::ADD_AFTER, ...$results);
        }
        return $this;
    }

    /**
     * Add the menu item inside the specified item
     * 
     * @param mixed $inside
     * @param mixed $items
     * @return \AzizSama\MenuBuilder\Builder\Builder
     */
    public function addInside($inside, ...$items): self
    {
        $results = $this->transformItems($items);
        if (!empty($results)) {
            $this->addItem($inside, static::ADD_INSIDE, ...$results);
        }
        return $this;
    }

    /**
     * Add item after or before or inside the specified item
     * 
     * @param mixed $key
     * @param int $type
     * @param mixed $items
     */
    public function addItem($key, $type, ...$items)
    {
        if (!($itemPath = $this->getIndex($key, $this->items))) {
            return;
        }

        if (!is_array($itemPath)) {
            dd($itemPath);
        }

        $index = end($itemPath);
        reset($itemPath);

        if ($type === self::ADD_INSIDE) {
            $targetPath = implode('.', array_merge($itemPath, ['childs']));
            $targetArr = Arr::get($this->items, $targetPath, []);
            array_push($targetArr, ...$items);
        } else {
            $targetPath = implode('.', array_slice($itemPath, 0, -1)) ?: null;
            $targetArr = Arr::get($this->items, $targetPath, $this->items);
            $offset = ($type === self::ADD_AFTER) ? 1 : 0;
            array_splice($targetArr, $index + $offset, 0, $items);
        }

        Arr::set($this->items, $targetPath, $targetArr);

        $this->items = $this->transformItems($this->items);
    }

    /**
     * Remove specified items by keys
     *
     * @param mixed $key
     * @return void
     */
    public function remove(...$keys)
    {
        foreach ($keys as $key) {
            $this->removeItem($key);
        }
    }

    /**
     * Add badge to the specified item
     * 
     * @param mixed $key
     * @param string $text
     * @param string $color
     * @return \AzizSama\MenuBuilder\Builder\Builder
     */
    public function addBadge($key, $text, $color = 'red'): self
    {
        return $this->edit($key, [
            'badge-color' => $color,
            'badge-text' => $text
        ]);
    }

    /**
     * Remove badge from the specified item
     * 
     * @param mixed $key
     * @return \AzizSama\MenuBuilder\Builder\Builder
     */
    public function removeBadge($key): self
    {
        return $this->edit($key, [
            'badge-color' => null,
            'badge-text' => null
        ]);
    }

    /**
     * Edit the specified item
     * 
     * @param mixed $key
     * @param mixed $item
     * @return \AzizSama\MenuBuilder\Builder\Builder
     */
    public function edit($key, $item): self
    {
        if (!($itemPath = $this->getIndex($key, $this->items))) {
            return $this;
        }

        $index = end($itemPath);
        reset($itemPath);

        $targetPath = implode('.', array_slice($itemPath, 0, -1)) ?: null;
        $targetArr = Arr::get($this->items, $targetPath, $this->items);
        $merged = array_merge($targetArr[$index], $item);
        $targetArr[$index] = $merged;

        Arr::set($this->items, $targetPath, $targetArr);

        $this->items = $this->transformItems($this->items);

        return $this;
    }

    /**
     * Remove specified item by key
     * 
     * @param mixed $key
     * @return \AzizSama\MenuBuilder\Builder\Builder
     */
    public function removeItem($key): self
    {
        if (!($itemPath = $this->getIndex($key, $this->items))) {
            return $this;
        }

        $index = end($itemPath);
        reset($itemPath);

        $targetPath = implode('.', array_slice($itemPath, 0, -1)) ?: null;
        $targetArr = Arr::get($this->items, $targetPath, $this->items);
        array_splice($targetArr, $index, 1);

        Arr::set($this->items, $targetPath, $targetArr);

        $this->items = $this->transformItems($this->items);

        return $this;
    }

    /**
     * Get the item index by key
     * 
     * @param mixed $key
     * @param array $items
     * @return mixed
     */
    protected function getIndex($key, array $items)
    {
        foreach ($items as $idx => $item) {
            if (isset($item['key']) && $item['key'] === $key) {
                return [$idx];
            } elseif (isset($item['childs'])) {
                $childPath = $this->getIndex($key, $item['childs']);
                if (!empty($childPath)) {
                    return array_merge([$idx, 'childs'], $childPath);
                }
            }
        }
        return [];
    }

    /**
     * Transform and the given items and return the results
     * 
     * @param  array $items
     * @return array
     */
    protected function transformItems(array $items)
    {
        $results = [];
        foreach ($items as $item) {
            $result = $this->transformItem($item);
            if (!empty($result) && !self::isDisabled($result)) {
                array_push($results, $result);
            }
        }
        return $results;
    }

    /**
     * Transform and apply filters to the given item and return the result
     * 
     * @param  mixed $item
     * @return mixed
     */
    protected function transformItem($item)
    {
        $result = $this->transform($item);
        foreach ($this->filters as $filter) {
            if (self::isDisabled($result)) {
                return $result;
            }

            $result = $filter->apply($result);

            if (isset($result['childs'])) {
                $result['childs'] = $this->transformItems($result['childs']);
            }
        }
        return $result;
    }

    /**
     * Transform the given item.
     * 
     * @param  array $item
     * @return array
     */
    protected function transform(array $item)
    {
        if (isset($item['href'])) {
            $item['href'] = Str::startsWith($item['href'], 'http') ? $item['href'] : url($item['href']);
        } else {
            $item['href'] = isset($item['route'])
                ? route($item['route'])
                : (isset($item['url'])
                    ? (Str::startsWith($item['url'], 'http')
                        ? $item['url']
                        : url($item['url'])
                    )
                    : ''
                );
        }

        $item['icon'] = isset($item['icon'])
            ? $item['icon']
            : null;

        $item['target'] = isset($item['target'])
            ? $item['target']
            : '';

        self::setDataset($item);

        $item['active'] = self::setActive($item);

        if (isset($item['childs']) && !empty($item['childs'])) {
            $childs = [];
            foreach ($item['childs'] as $child) {
                $child = $this->transform($child);
                $item['active'] = array_merge($item['active'], $child['active']);
                $childs[] = $child;
            }
            $item['childs'] = $childs;
        }

        if (!isset($item['key'])) {
            $item['key'] = Str::random(strlen($item['text']));
        }

        return $item;
    }

    /**
     * Check if the item is active
     * 
     * @param array $item
     * @return bool
     */
    private static function setActive(array $item)
    {
        return isset($item['active'])
            ? array_merge($item['active'], [parse_url($item['href'], PHP_URL_PATH)])
            : array(parse_url($item['href'], PHP_URL_PATH));
    }

    /**
     * Set the dataset attribute
     * 
     * @param array $item
     * @return void
     */
    private static function setDataset(array $item)
    {
        foreach ($item as $key => $value) {
            if (Str::start($key, 'data-')) {
                $item['dataset'][$key] = $value;
                unset($item[$key]);
            }
        }
        return $item;
    }

    /**
     * Get the items
     * 
     * @return array
     */
    public function items()
    {
        return $this->items;
    }

    /**
     * Get the results as a collection
     * 
     * @return \AzizSama\MenuBuilder\Support\MenuCollection
     */
    public function get()
    {
        $items = [];
        foreach ($this->items as $key => $item) {
            if (is_string($key)) {
                foreach ($item as $it) {
                    $items[$key][] = self::castAsItem($it);
                }
            } else {
                $items[] = self::castAsItem($item);
            }
        }
        return new MenuCollection($items);
    }

    /**
     * Cast the given menu as a \AzizSama\MenuBuilder\Entities\Menu
     * 
     * @param  array $item
     * @return \AzizSama\MenuBuilder\Entities\Menu
     */
    protected static function castAsItem(array $item)
    {
        return new Menu($item);
    }

    /**
     * Check if the given item is disabled
     * 
     * @param  array $item
     * @return bool
     */
    public static function isDisabled(array $item)
    {
        return isset($item['disabled']) && $item['disabled'] !== true;
    }
}
