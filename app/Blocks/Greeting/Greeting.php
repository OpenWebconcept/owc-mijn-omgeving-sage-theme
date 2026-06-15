<?php

declare(strict_types=1);

namespace OWC\MijnOmgeving\Blocks\Greeting;

use DateTime;
use DateTimeZone;
use Illuminate\Contracts\View\View;

class Greeting
{
	public function render(): View
	{
		$now = new DateTime('now', new DateTimeZone(wp_timezone_string()));
		$hour = (int) $now->format('H');

		$greeting = $this->getGreeting($hour);

		return view('blocks.greeting', ['greeting' => $greeting]);
	}

	private function getGreeting(int $hour): string
	{
		return $this->getMessage($hour);
	}

	private function getMessage(int $hour): string
	{
		return match (true) {
			6 > $hour => 'Goedenacht',
			12 > $hour => 'Goedemorgen',
			18 > $hour => 'Goedemiddag',
			default => 'Goedenavond',
		};
	}
}
