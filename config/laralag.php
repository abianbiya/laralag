<?php

/*
 * You can place your custom package configuration in here.
 */
return [
	'permissions' => [
		'ignored_routes' => [
			'front.index',
			'landing.index'
		],
	],
	'home_route' => 'dashboard.index',
	'landing_route' => 'home.index',
	'has_landing' => true,
	'dashboard_blade' => '',
	'custom_login_blade' => '',
	'custom_register_blade' => '',
	'app_name' => env('APP_NAME', 'Laralag'),
	'app_short_name' => env('APP_SHORT_NAME', 'LAG'),
	'theme_customizer_enabled' => env('THEME_CUSTOMIZER_ENABLED', false),
];