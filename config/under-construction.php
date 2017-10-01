<?php

return [
    /**
     * Activate under construction mode.
     */
    'enabled' => env('UNDER_CONSTRUCTION_ENABLED', true),

    /**
     * Under construction title.
     */
    'title' => 'Under c',

    /**
     * Enable throttle (max login attempts).
     */
    'throttle' => true,

    /**
     * If throttle is enabled (true) set the maximum number of attempts to allow.
     */
    'max_attempts' => 3,

    /**
     * If throttle is enabled (true) show attempts left.
     */
    'show_attempts_left' => true,

    /**
     * If throttle is enabled (true) set the number of minutes to disable login.
     */
    'decay_minutes' => 5
];