<?php

declare(strict_types=1);

return [
	'font' => [
		'url' => 'https://fonts.googleapis.com/css2?family=Open+Sans:wght@300..800&display=swap',
	],
	'logo' => [
		'url' => get_theme_file_uri('/resources/images/logo.svg'),
	],
	'login_menu' => [
		'show_login_button' => false,
		'show_login_button_mobile' => false,
	],
	'home' => [
		'digid' => [
			'is_active' => true,
		],
		'eherkenning' => [
			'is_active' => true,
		],
		'eidas' => [
			'is_active' => true,
		],
	],
	'footer' => [
		'is_active' => true,
	],
];
