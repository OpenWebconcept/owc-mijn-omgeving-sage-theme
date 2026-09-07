<?php

declare(strict_types=1);

namespace OWC\MijnOmgeving\View\Components;

use Illuminate\View\Component;

class Alert extends Component
{
	public string $type;

	public function __construct(string $type = 'warning')
	{
		$this->type = $type;
	}

	public function alertClasses(): string
	{
		return match($this->type) {
			'success' => 'bg-green-100 border-green-500 text-black',
			'danger' => 'bg-red-100 border-red-500 text-black',
			default => 'bg-yellow-100 border-yellow-500 text-black',
		};
	}

	public function alertIcon(): string
	{
		return match($this->type) {
			'success' => 'circle-check',
			'danger' => 'circle-x',
			default => 'triangle-alert',
		};
	}

	public function alertIconColor(): string
	{
		return match($this->type) {
			'success' => 'text-green-700',
			'danger' => 'text-red-700',
			default => 'text-yellow-700',
		};
	}

	public function render(): \Illuminate\View\View
	{
		return view('components.alert');
	}
}
