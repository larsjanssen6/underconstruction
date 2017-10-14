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
];
