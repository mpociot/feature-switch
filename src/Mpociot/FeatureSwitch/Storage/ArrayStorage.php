<?php

namespace Mpociot\FeatureSwitch\Storage;

class ArrayStorage implements StorageContract
{

    /**
     * @var array
     */
    private $storage = [];

    /**
     * @param string $key
     * @param string $value
     * @return void
     */
    public function set($key, $value)
    {
        $this->storage[$key] = $value;
    }

    /**
     * @param string $key
     * @param null $default
     * @return null|string
     */
    public function get($key, $default = null)
    {
        return isset($this->storage[$key]) ? $this->storage[$key] : $default;
    }

    /**
     * @param string $key
     * @return void
     */
    public function delete($key)
    {
        if (isset($this->storage[$key])) {
            unset($this->storage[$key]);
        }
    }
}