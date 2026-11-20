<?php

declare(strict_types=1);

return [
	/*
	 * Filled at runtime from `theme.font.url`, which child themes override.
	 */
	'fonts' => [],

	'disk' => 'storage-cache',

	/*
	 * When something goes wrong fonts are loaded directly from Google.
	 * With fallback disabled, this package will throw an exception.
	 */
	'fallback' => env('WP_ENV') === 'production',
];
