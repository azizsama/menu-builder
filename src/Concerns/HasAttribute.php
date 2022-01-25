<?php

namespace AzizSama\MenuBuilder\Concerns;

trait HasAttribute
{
    protected $attributes = [];

    public function __get($key)
    {
        return $this->getAttribute($key);
    }

    public function __set($key, $value)
    {
        $this->setAttribute($key, $value);
    }

    public function getAttribute($key)
    {
        return $this->attributes[$key] ?? null;
    }

    public function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    public function hasAttribute($key)
    {
        return isset($this->attributes[$key]);
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function getKeys()
    {
        return array_keys($this->attributes);
    }

    public function getValues()
    {
        return array_values($this->attributes);
    }

    public function toArray()
    {
        return $this->attributes;
    }

    public function toJson()
    {
        return json_encode($this->attributes);
    }

    public function only($keys)
    {
        if (is_string($keys)) {
            $keys = func_get_args();
        }

        return array_intersect_key($this->attributes, array_flip($keys));
    }

    public function except($keys)
    {
        if (is_string($keys)) {
            $keys = func_get_args();
        }

        return array_diff_key($this->attributes, array_flip($keys));
    }
}
