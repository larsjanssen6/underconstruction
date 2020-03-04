# Laravel Under Construction <img width="45" alt="schermafbeelding 2017-09-27 om 23 08 12" src="https://user-images.githubusercontent.com/7254997/30937972-c9632d04-a3d8-11e7-87f3-c44ce2b86d24.png">

[![StyleCI](https://styleci.io/repos/104500164/shield)](https://styleci.io/repos/104500164)
[![Packagist](https://img.shields.io/packagist/l/doctrine/orm.svg)](https://github.com/larsjanssen6/underconstruction/blob/master/LICENSE.md)
 <a href="https://twitter.com/larsjansse">
   <img src="http://img.shields.io/badge/author-@larsjansse-blue.svg?style=flat-square">
 </a>
[![Total Downloads](https://poser.pugx.org/larsjanssen6/underconstruction/downloads)](https://packagist.org/packages/larsjanssen6/underconstruction)

<a href="https://www.buymeacoffee.com/nFdwPZel9" target="_blank"><img src="https://www.buymeacoffee.com/assets/img/custom_images/orange_img.png" alt="Buy Me A Coffee" style="height: auto !important;width: auto !important;" ></a>

This Laravel package makes it possible to set your website in "Under Construction" mode. Only users with the correct 4 digit code can access your site. This package can for example be useful to show your website to a specific client. Everything works out of the box, and it's fully customizable.

<strong style="color:red;">If you appreciate my work please give this repo a star or buy me a coffee ^ 🤗.</strong>

<img width="850" alt="underconstruction" src="https://user-images.githubusercontent.com/7254997/30869205-d96d9962-a2e0-11e7-9044-0a7ff708e6c3.png">

## Installation

Begin by installing this package through Composer (Laravel 5.8, 6 and 7 compatible!).

```bash
composer require larsjanssen6/underconstruction
```

The ```\LarsJanssen\UnderConstruction\UnderConstruction::class``` middleware must be registered in the kernel:

```php
//app/Http/Kernel.php

protected $routeMiddleware = [
  // ...
  'under-construction' => \LarsJanssen\UnderConstruction\UnderConstruction::class,
];
```

### Defaults

Publish the default configuration file.

```bash
php artisan vendor:publish

# Or...

php artisan vendor:publish --provider="LarsJanssen\UnderConstruction\UnderConstructionServiceProvider"
```

This package is fully customizable. This is the content of the published config file `under-construction.php`:

```php
<?php

return [

    /*
     * Activate under construction mode.
     */
    'enabled' => env('UNDER_CONSTRUCTION_ENABLED', true),

    /*
     * Hash for the current pin code
     */
    'hash' => env('UNDER_CONSTRUCTION_HASH', null),

    /*
     * Under construction title.
     */
    'title' => 'Under Construction',

    /*
     * Custom Route Prefix
     * */
    'route-prefix' => env('UNDER_CONSTRUCTION_ROUTE_PREFIX','under'),

    /*
     * Custom Endpoint if you don't want to use 'construction'
     * e.g. if you change to 'checkpoint', the route prefix
     * above will be appended giving you 'under/checkpoint'
     * */
    'custom-endpoint' => env('UNDER_CONSTRUCTION_CUSTOM_ENDPOINT','construction'),


    /*
     * Back button translation.
     */
    'back-button' => 'back',

    /*
    * Show button translation.
    */
    'show-button' => 'show',

    /*
     * Hide button translation.
     */
    'hide-button' => 'hide',

    /*
     * Show loader.
     */
    'show-loader' => true,

    /*
     * Redirect url after a successful login.
     */
    'redirect-url' => '/',

    /*
     * Enable throttle (max login attempts).
     */
    'throttle' => true,

        /*
        |--------------------------------------------------------------------------
        | Throttle settings (only when throttle is true)
        |--------------------------------------------------------------------------
        |
        */

        /*
        * Set the amount of digits (max 6).
        */
        'total_digits' => 4,

        /*
         * Set the maximum number of attempts to allow.
         */
        'max_attempts' => 3,

        /*
         * Show attempts left.
         */
        'show_attempts_left' => true,

        /*
         * Attempts left message.
         */
        'attempts_message' => 'Attempts left: %i',

        /*
         * Too many attempts message.
         */
        'seconds_message' => 'Too many attempts please try again in %i seconds.',

        /*
         * Set the number of minutes to disable login.
         */
        'decay_minutes' => 5,

        /*
         * Prevent the site from being indexed by Robots when locked
         */
        'lock_robots' => true,
];
```

## Usage

You'll have to set a 4 digit code (you can change this up to 6 in config file). You can do that by running this custom
artisan command (in this example the code is ```1234``` ,you can obviously set another code). It
will generate a hash that will be stored in your `.env` file. 

```bash
php artisan code:set 1234
```

You can set routes to be in "Under Construction" mode by using the `under-construction`-middleware on them.

```php
Route::group(['middleware' => 'under-construction'], function () {
    Route::get('/live-site', function() {
        echo 'content!';
    });
});
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
composer test
```

## Contributing

I would love to hear your ideas to improve my codeing style and conventions. Feel free to contribute.

## Security

If you discover any security related issues, please email larsjanssen64@gmail.com. You can also make an issue. 

## Credits

- [Lars Janssen](https://github.com/larsjanssen6)
- [All Contributors](../../contributors)

## About me
I'm Lars Janssen from The Netherlands and like to work on web projects. You can
follow me on <a href="https://twitter.com/larsjansse">Twitter</a>.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## ❤️ Open-Source Software - Give ⭐️
We have included the awesome `symfony/thanks` composer package as a dev
dependency. Let your OS package maintainers know you appreciate them by starring
the packages you use. Simply run composer thanks after installing this package.
(And not to worry, since it's a dev-dependency it won't be installed in your
live environment.)

