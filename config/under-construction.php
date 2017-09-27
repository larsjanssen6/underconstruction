<?php

return [
    /**
     * Activate under construction mode.
     */
    'enabled' => env('UNDER_CONSTRUCTION_ENABLED', true),

    /**
     * Enable throttle (max login attempts).
     */
    'throttle' => true,

    /**
     * If throttle is enabled (true) set the maximum number of attempts to allow.
     */
    'throttle_attempts' => 4,

    /**
     * If throttle is enabled (true) set the number of minutes to throttle for.
     */
    'throttle_minutes' => 5
];