## PHP Feature Switcher (WIP)

A PHP feature switcher / feature toggle library with a fluent, easy to use, API.

```php
$switch = new FeatureSwitch(new ArrayStorage());
$switch->activate(
    (new Feature())
        ->name('Chat')
        ->percentage(50)
);
```

### License

The PHP Feature Switcher is free software licensed under the MIT license.
