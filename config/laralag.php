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
];