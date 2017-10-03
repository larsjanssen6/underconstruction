# Laravel 5.5 Under Construction <img width="45" alt="schermafbeelding 2017-09-27 om 23 08 12" src="https://user-images.githubusercontent.com/7254997/30937972-c9632d04-a3d8-11e7-87f3-c44ce2b86d24.png">

[![StyleCI](https://styleci.io/repos/104500164/shield)](https://styleci.io/repos/104500164)
[![Build Status](https://scrutinizer-ci.com/g/larsjanssen6/underconstruction/badges/build.png?b=master)](https://scrutinizer-ci.com/g/larsjanssen6/underconstruction/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/larsjanssen6/underconstruction/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/larsjanssen6/underconstruction/?branch=master)

This Laravel packages makes it possible to set your website in under construction mode. Only users with a correct 4 digits code have access to your site. This package could for example be useful if you want to show your website to a specific client. Everything works out of the box, and it's fully customizable.

<img width="850" alt="underconstruction" src="https://user-images.githubusercontent.com/7254997/30869205-d96d9962-a2e0-11e7-9044-0a7ff708e6c3.png">

## Installation

Begin by installing this package through Composer.

```bash
composer require larsjanssen6/underconstruction
```

Then the  ```service provider``` must be installed.

> Laravel 5.5+ users: this step may be skipped, as we can auto-register the package with the framework.

```php

// config/app.php

'providers' => [
    '...',
    'LarsJanssen\UnderConstruction\UnderConstructionServiceProvider'
];
```

The ```\LarsJanssen\UnderConstruction\UnderConstruction::class``` middleware must be registered in the kernel:

```php
//app/Http/Kernel.php

protected $routeMiddleware = [
  ...
  'under-construction' => \LarsJanssen\UnderConstruction\UnderConstruction::class,
];
```

### Defaults

Publish the default configuration file.

```bash
php artisan vendor:publish

// Or...

php artisan vendor:publish --provider="LarsJanssen\UnderConstruction\UnderConstructionServiceProvider"
```


This package is fully customizable this is the content of the published config file `under-construction.php`:

```php
return [

    /**
     * Activate under construction mode.
     */
    'enabled' => env('UNDER_CONSTRUCTION_ENABLED', true),

    /**
     * Under construction title.
     */
    'title' => 'Under Construction',

    /**
     * Back button translation.
     */
    'back-button' => 'back',

    /**
     * Redirect url after a successful login.
     */
    'redirect-url' => '/',

    /**
     * Enable throttle (max login attempts).
     */
    'throttle' => true,

            /*
            |--------------------------------------------------------------------------
            | Throttle settings (only when throttle is true)
            |--------------------------------------------------------------------------
            |

            */
            /**
             * Set the maximum number of attempts to allow.
             */
            'max_attempts' => 3,

            /**
             * Show attempts left.
             */
            'show_attempts_left' => true,

            /**
             * Attempts left message.
             */
            'attempts_message' => 'Attempts left: %i',

            /**
             * Too many attempts message.
             */
            'seconds_message' => 'Too many attempts please try again in %i seconds.',

            /**
             * Set the number of minutes to disable login.
             */
            'decay_minutes' => 5
];
```

## Usage

You'll have to set a 4 digit code. You can do that by running this custom
artisan command (in this example code ```1234``` is set obviously you can set another code).

```bash
php artisan code:set 1234
```

You can set routes in under construction mode by using `under-construction`-middleware on them.

```php
Route::group(['middleware' => 'under-construction'], function () {
    Route::get('/live-site', function() {
        echo 'content!';
    });
});
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
composer test
```

## Contributing

I would love to hear your ideas to improve my code style and conventions. Feel free to contribute.

## Security

If you discover any security related issues, please email mail@larsjanssen.net. You could also make an issue. 

## Credits

- [Lars Janssen](https://github.com/larsjanssen6)
- [All Contributors](../../contributors)

## About me
I'm Lars Janssen from The Netherlands and like working on web projects. You could
follow me on <a href="https://twitter.com/larsjansse">twitter</a>.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
