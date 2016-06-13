<?php

namespace Mpociot\FeatureSwitch;

use Illuminate\Contracts\Auth\Authenticatable;

/**
 * Class Feature
 * @package Mpociot\FeatureSwitch
 */
class Feature
{
    /**
     * @var array
     */
    protected $groups = [];

    /**
     * @var
     */
    protected $name;

    /**
     * @var array
     */
    protected $users = [];

    /**
     * @var
     */
    protected $percentage = 100.0;

    public function __construct($data = null)
    {
        if (is_array($data)) {
            $this->name = $data['name'];
            $this->groups = $data['groups'];
            $this->users = $data['users'];
            $this->percentage = $data['percentage'];
        }
    }

    /**
     * @param $name
     * @return $this
     */
    public function name($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param Authenticatable $user
     * @return $this
     */
    public function user(Authenticatable $user)
    {
        if (!in_array($user->getAuthIdentifier(), $this->users)) {
            $this->users[] = $user->getAuthIdentifier();
        }
        return $this;
    }

    /**
     * @param $users
     * @return $this
     */
    public function users($users)
    {
        foreach ($users as $user) {
            $this->user($user);
        }
        return $this;
    }

    /**
     * @param $percentage
     * @return $this
     */
    public function percentage($percentage)
    {
        $this->percentage = floatval($percentage);
        return $this;
    }

    /**
     * @param $group
     * @return $this
     */
    public function group($group)
    {
        $this->groups[] = $group;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @return mixed
     */
    public function getPercentage()
    {
        return $this->percentage;
    }

    /**
     * @param Authenticatable|null $user
     * @return bool
     */
    public function isActive(Authenticatable $user = null)
    {
        if ($user === null) {
            return true;
        }

        return $this->isUserInPercentage($user) || $this->isUserActive($user);
    }

    /**
     * @param Authenticatable $user
     * @return bool
     */
    private function isUserInPercentage(Authenticatable $user)
    {
        return abs(crc32($user->getAuthIdentifier()) % 100) < $this->percentage;
    }

    /**ww
     * @param Authenticatable|null $user
     * @return bool
     */
    private function isUserActive(Authenticatable $user)
    {
        return in_array($user->getAuthIdentifier(), $this->users);
    }

    /**
     * @return array
     */
    protected function toArray()
    {
        return [
            'name' => $this->getName(),
            'groups' => $this->getGroups(),
            'users' => $this->getUsers(),
            'percentage' => $this->getPercentage(),
        ];
    }

    /**
     * @return string
     */
    public function serialize()
    {
        return json_encode($this->toArray());
    }

}