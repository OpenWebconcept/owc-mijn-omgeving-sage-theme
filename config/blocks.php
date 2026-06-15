<?php

declare(strict_types=1);

use OWC\MijnOmgeving\Blocks\AuthenticationCards\AuthenticationCards;
use OWC\MijnOmgeving\Blocks\BackButton\BackButton;
use OWC\MijnOmgeving\Blocks\Greeting\Greeting;

return [
	/**
	 * Register a block type with the same parameters as the `register_block_type` function.
	 *
	 * @see https://developer.wordpress.org/reference/functions/register_block_type/
	 */
	'authentication-cards' => [
		'block_type' => 'authentication-cards',
		'args' => [
			'render_callback' => (new AuthenticationCards())->render(...),
		],
	],
	'back-button' => [
		'block_type' => 'back-button',
		'args' => [
			'render_callback' => (new BackButton())->render(...),
		],
	],
	'greeting' => [
		'block_type' => 'greeting',
		'args' => [
			'render_callback' => (new Greeting())->render(...),
		],
	],
];
