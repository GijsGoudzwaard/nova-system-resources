# A Laravel Nova tool to show system resources

[![Total Downloads](https://poser.pugx.org/gijsg/system-resources/downloads)](https://packagist.org/packages/gijsg/system-resources)
[![License](https://poser.pugx.org/gijsg/system-resources/license)](https://packagist.org/packages/gijsg/system-resources)

This [Nova](https://nova.laravel.com) tool gives you a live overview of your RAM and CPU usage.

![screenshot of the backup tool](screenshot.png)

## Requirements

You must use either Linux or MacOS.

## Installation

You can install the nova tool in to a Laravel app that uses [Nova](https://nova.laravel.com) via composer:

```bash
composer require gijsg/system-resources
```

Next up, you must register the tool with Nova. This is typically done in the `cards` method of the `NovaServiceProvider`.


```php
// in app/Providers/NovaServiceProvder.php

// ...

protected function cards()
{
    return [
        // ...
        new \GijsG\SystemResources\SystemResources('ram'),
        new \GijsG\SystemResources\SystemResources('cpu'),
    ];
}
```

You can specify the width of the cards using the nova card width notation: `1/2`, `1/4`, `full` etc.
```php
new \GijsG\SystemResources\SystemResources('ram', '1/3'),
```
The MIT License (MIT). Please see [License File](LICENSE) for more information.
