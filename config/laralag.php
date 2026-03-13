<?php

/*
 * You can place your custom package configuration in here.
 */
return [
    'permissions' => [
        /*
         * Ignored routes for permissions.
         */
        'ignored_routes' => [
            'front.index',
            'landing.index',
        ],
    ],
    /*
     * The route to redirect to after successful login.
     */
    'home_route' => 'dashboard.index',
    /*
     * The route to redirect to for the landing page.
     */
    'landing_route' => 'home.index',
    /*
     * Indicates whether the application has a landing page.
     */
    'has_landing' => false,
    /*
     * The blade template for the dashboard.
     */
    'dashboard_blade' => '',
    /*
     * The blade template for the custom login page.
     */
    'custom_login_blade' => '',
    /*
     * The blade template for the custom register page.
     */
    'custom_register_blade' => '',
    /*
     * The name of the application.
     */
    'app_name' => env('APP_NAME', 'Laralag'),
    /*
     * The short name of the application.
     */
    'app_short_name' => env('APP_SHORT_NAME', 'LAG'),
    /*
     * Indicates whether the theme customizer is enabled.
     */
    'theme_customizer_enabled' => env('THEME_CUSTOMIZER_ENABLED', false),
    /*
     * Use Bootstrap pagination styling.
     * Set to false if your app uses Tailwind or other CSS framework.
     */
    'use_bootstrap_pagination' => true,
    /*
     * Default number of items per page in generated index controllers.
     * Can be overridden per-controller after generation.
     */
    'default_pagination' => 10,
    /*
     * Labels used for boolean (tinyint) fields in generated selects and views.
     * Customize for your locale — e.g. ['1' => 'Yes', '0' => 'No'].
     */
    'boolean_labels' => ['1' => 'Ya', '0' => 'Tidak'],
];
