# Laravel 5.5 Under Construction <img width="45" alt="schermafbeelding 2017-09-27 om 23 08 12" src="https://user-images.githubusercontent.com/7254997/30937972-c9632d04-a3d8-11e7-87f3-c44ce2b86d24.png">

[![StyleCI](https://styleci.io/repos/104500164/shield)](https://styleci.io/repos/104500164)

[![Quality Score](https://img.shields.io/scrutinizer/g/larsjanssen6/laravel-demo-mode.svg?style=flat-square)](https://scrutinizer-ci.com/g/larsjanssen6/underconstruction)

This Laravel packages makes it possible to set your website in under construction mode. Only users with a correct 4 digits code have access to your site. You can set the code with this custom artisan command:

``` bash
php artisan code:set "yourcode"
```

<img width="850" alt="underconstruction" src="https://user-images.githubusercontent.com/7254997/30869205-d96d9962-a2e0-11e7-9044-0a7ff708e6c3.png">

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
