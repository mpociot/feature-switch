<?php

use Mpociot\FeatureSwitch\Feature;
use Mpociot\FeatureSwitch\FeatureSwitch;
use Mpociot\FeatureSwitch\Storage\ArrayStorage;

class FeatureSwitchTest extends PHPUnit_Framework_TestCase {

    public function test_can_activate_feature()
    {
        $switch = new FeatureSwitch(new ArrayStorage());

        $this->assertFalse($switch->isActive('Foo'));

        $switch->activate(
            (new Feature())->name('Foo')->percentage(80)
        );

        // Feature should be available in storage
        $features = $switch->getFeatures();
        $this->assertSame(['Foo'], $features);
        
        $this->assertTrue($switch->isActive('Foo'));
    }

    public function test_can_deactivate_feature()
    {
        $switch = new FeatureSwitch(new ArrayStorage());
        $switch->activate(
            (new Feature())->name('Foo')->percentage(80)
        );

        $switch->deactivate('Foo');

        // Feature should no longer be available in storage
        $features = $switch->getFeatures();
        $this->assertSame([], $features);

        $this->assertFalse($switch->isActive('Foo'));
    }


    public function test_can_retrieve_feature()
    {
        $switch = new FeatureSwitch(new ArrayStorage());
        $switch->activate(
            (new Feature())->name('Foo')->percentage(80)
        );

        $switch->deactivate('Foo');

        // Feature should no longer be available in storage
        $features = $switch->getFeatures();
        $this->assertSame([], $features);

        $this->assertFalse($switch->isActive('Foo'));
    }

}