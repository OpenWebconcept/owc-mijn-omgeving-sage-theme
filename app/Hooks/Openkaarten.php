<?php

declare(strict_types=1);

namespace OWC\MijnOmgeving\Hooks;

use OWC\PrefillGravityForms\Models\UserModel;
use Yard\GeoCode\Pdok\GeoCoder;
use Yard\Hook\Action;
use Yard\Logging\Log;

class Openkaarten
{
	#[Action('wp_head')]
	public function addUserLocationToOpenkaart(): void
	{
		if (! $this->shouldInjectMapData()) {
			return;
		}

		try {
			$userLocation = $this->getUserLocation();
		} catch (\Exception $e) {
			Log::warning('Failed to get residential location of logged in user: '.$e->getMessage());

			return;
		}

		if ([] === $userLocation) {
			return;
		}

		wp_register_script('yard-map-session', false);
		wp_enqueue_script('yard-map-session');

		wp_add_inline_script(
			'yard-map-session',
			'window.yardOpenkaarten = ' . wp_json_encode([
				'userLocation' => $userLocation,
			], JSON_UNESCAPED_UNICODE) . ';',
			'before'
		);
	}

	private function shouldInjectMapData(): bool
	{
		return is_admin() === false;
	}

	private function getUserLocation(): array
	{
		$user = new UserModel();

		if (! $user->isLoggedIn()) {
			return [];
		}

		if ($user->zipcode() === '') {
			throw new \Exception('Record contains no address zipcode.');
		}

		if ($user->houseNumber() === '') {
			throw new \Exception('Record contains no address house number.');
		}

		$address = sprintf('%s %s', $user->zipcode(), $user->houseNumber());

		$coder = new GeoCoder(Log::getLogger());
		$point = $coder->geocode($address);

		if (null === $point) {
			throw new \Exception("Geocoder could not find a location for '$address'");
		}

		return [
			'lat' => $point->lat,
			'lng' => $point->lon,
		];
	}
}
