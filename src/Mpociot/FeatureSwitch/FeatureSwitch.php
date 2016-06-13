<?php

namespace Mpociot\FeatureSwitch;

use InvalidArgumentException;
use Mpociot\FeatureSwitch\Storage\StorageContract;

/**
 * Class FeatureSwitch
 * @package Mpociot\FeatureSwitch
 */
class FeatureSwitch
{
    /**
     * Key used to store single features in storage
     */
    const FEATURE_KEY = 'feature_';

    /**
     * Key used to store all feature names in storage
     */
    const FEATURES_KEY = 'features_';

    /**
     * @var StorageContract
     */
    protected $storage;

    /**
     * FeatureSwitch constructor.
     * @param StorageContract $storage
     */
    public function __construct(StorageContract $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param Feature $feature
     * @throws InvalidArgumentException
     * @return bool
     */
    public function activate(Feature $feature)
    {
        if ($feature->getName() === null) {
            throw new InvalidArgumentException('Feature name cannot be null');
        }

        $this->save($feature);

        return true;
    }

    /**
     * @param $name
     * @return bool
     */
    public function deactivate($name)
    {
        $this->storage->delete(self::FEATURE_KEY . $name);

        $features = $this->getFeatures();

        if (in_array($name, $features)) {
            $this->updateFeatures(array_diff($features, [$name]));
        }

        return true;
    }

    /**
     * @param $featureName
     * @param null $user
     * @return bool
     */
    public function isActive($featureName, $user = null)
    {
        return $this->getFeatureByName($featureName)->isActive($user);
    }

    /**
     * @return array
     */
    public function getFeatures()
    {
        return $this->storage->get(self::FEATURES_KEY, []);
    }

    /**
     * @param $name
     * @return Feature|NullFeature
     */
    protected function getFeatureByName($name)
    {
        if (in_array($name, $this->getFeatures())) {
            return new Feature(json_decode($this->storage->get(self::FEATURE_KEY . $name)));
        }
        return new NullFeature();
    }

    /**
     * @param Feature $feature
     */
    protected function save(Feature $feature)
    {
        $this->storage->set(self::FEATURE_KEY . $feature->getName(), $feature->serialize());

        $features = $this->getFeatures();
        $features[] = $feature->getName();

        $this->updateFeatures($features);
    }

    /**
     * @param array $features
     */
    protected function updateFeatures($features = [])
    {
        $this->storage->set(self::FEATURES_KEY, array_unique($features));
    }

}