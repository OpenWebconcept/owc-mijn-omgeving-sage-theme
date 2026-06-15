<?php

declare(strict_types=1);

namespace OWC\MijnOmgeving\Blocks\AuthenticationCards;

use Illuminate\Contracts\View\View;

class AuthenticationCards
{
	public function render(array $attributes, string $content): View
	{
		return view('blocks.authentication-cards', [
			'displayDigid' => $attributes['displayDigid'] ?? true,
			'displayEherkenning' => $attributes['displayEherkenning'] ?? true,
			'displayEidas' => $attributes['displayEidas'] ?? true,
			'gridCols' => $this->getGridCols($attributes),
			'provider' => $attributes['provider'] ?? 'owc-signicat-openid',
		]);
	}

	protected function getGridCols(array $attributes): string
	{
		$active = collect([
			$attributes['displayDigid'] ?? true,
			$attributes['displayEherkenning'] ?? true,
			$attributes['displayEidas'] ?? true,
		])->filter()->count();

		if (1 === $active || 2 === $active) {
			return 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-2';
		}

		return 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3';
	}
}
