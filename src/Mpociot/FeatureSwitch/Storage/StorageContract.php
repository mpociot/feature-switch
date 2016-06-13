<?php

namespace Mpociot\FeatureSwitch\Storage;

/**
 * Interface StorageContract
 * @package Mpociot\FeatureSwitch\Storage
 */
interface StorageContract
{

    /**
     * @param string $key
     * @param string $value
     * @return void
     */
    public function set($key, $value);

    /**
     * @param string $key
     * @param null $default
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * @param string $key
     * @return void
     */
    public function delete($key);

}