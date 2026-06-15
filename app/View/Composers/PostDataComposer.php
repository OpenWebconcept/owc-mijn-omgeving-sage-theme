<?php

declare(strict_types=1);

namespace OWC\MijnOmgeving\View\Composers;

use OWC\MijnOmgeving\Data\PostData;
use Roots\Acorn\View\Composer;

class PostDataComposer extends Composer
{
	/**
	 * List of views served by this composer.
	 *
	 * @var array
	 */
	protected static $views = [
		'partials.content-single',
		'partials.content-single-*',
		'template-*',
		'page',
	];

	/**
	 * Data to be passed to view before rendering, but after merging.
	 */
	public function override(): array
	{
		$postData = PostData::from($GLOBALS['post']);

		return [
			'postData' => $postData,
		];
	}
}
