<?php

declare(strict_types=1);

namespace OWC\MijnOmgeving\View\Components;

use Exception;
use Illuminate\View\DynamicComponent;

class DynamicCard extends DynamicComponent
{
	public function __construct(string $card = 'card')
	{
		try {
			$this->compiler()->componentClass('card.' . $card);
			$component = 'card.' . $card;
		} catch (Exception $e) {
			$component = 'card';
		}

		parent::__construct($component);
	}
}
