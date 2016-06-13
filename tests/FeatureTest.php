<?php

use Illuminate\Foundation\Auth\User;
use Mpociot\FeatureSwitch\Feature;

class FeatureTest extends \Orchestra\Testbench\TestCase
{

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('teamwork.user_model', 'User');

        \Schema::create('users', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function test_can_set_feature_name()
    {
        $feature = new Feature();
        $feature->name('Foo');

        $this->assertSame('Foo', $feature->getName());
    }

    public function test_can_set_feature_user()
    {
        $user = new User();
        $user->name = 'Test';
        $user->save();

        $feature = new Feature();
        $feature->user($user);

        $this->assertSame([$user->getAuthIdentifier()], $feature->getUsers());
    }

    public function test_can_set_multiple_feature_users()
    {
        $user1 = new User();
        $user1->name = 'Test';
        $user1->save();

        $user2 = new User();
        $user2->name = 'Foo';
        $user2->save();

        $feature = new Feature();
        $feature->user($user1);
        $feature->user($user2);

        $this->assertSame([$user1->getAuthIdentifier(), $user2->getAuthIdentifier()], $feature->getUsers());

        $feature = new Feature();
        $feature->users([$user1, $user2]);
        $this->assertSame([$user1->getAuthIdentifier(), $user2->getAuthIdentifier()], $feature->getUsers());
    }

    public function test_can_set_feature_percentage()
    {
        $feature = new Feature();
        $feature->percentage(20.8);
        $this->assertSame(20.8, $feature->getPercentage());


        $feature = new Feature();
        $feature->percentage('Foo');
        $this->assertSame(0.0, $feature->getPercentage());
    }

    public function test_can_set_feature_group()
    {
        $feature = new Feature();
        $feature->group('Foo');

        $this->assertSame(['Foo'], $feature->getGroups());
    }

    public function test_can_chain_methods()
    {
        $user = new User();
        $user->name = 'Test';
        $user->save();

        $feature = (new Feature())
            ->name('Chat')
            ->percentage(80)
            ->users([$user])
            ->group('all_users');

        Deploy::activate($feature);
        
        $this->assertSame('Chat', $feature->getName());
        $this->assertSame(80.0, $feature->getPercentage());
        $this->assertSame([1], $feature->getUsers());
        $this->assertSame(['all_users'], $feature->getGroups());
    }

    public function test_feature_without_percentage_and_user_is_always_active()
    {
        $feature = new Feature();
        $feature->name('Foo');

        $this->assertTrue($feature->isActive());
        $this->assertSame(100.0, $feature->getPercentage());
    }
}

class Deploy {
    public static function activate($foo)
    {

    }
}