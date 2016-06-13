<?php

namespace Mpociot\FeatureSwitch;

use Illuminate\Contracts\Auth\Authenticatable;

/**
 * Class NullFeature
 * @package Mpociot\FeatureSwitch
 */
class NullFeature extends Feature
{

    /**
     * @param Authenticatable|null $user
     * @return bool
     */
    public function isActive(Authenticatable $user = null)
    {
        return false;
    }

}