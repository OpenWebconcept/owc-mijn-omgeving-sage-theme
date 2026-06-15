<?php

declare(strict_types=1);

namespace OWC\MijnOmgeving\Csp;

use Spatie\Csp\Directive;
use Yard\Csp\Policies\Basic;

class Policy extends Basic
{
	public function configure()
	{
		parent::configure();

		// Add site specific csp directives below
		$this
			->addDirective(DIRECTIVE::IMG, 'https://*.tile.osm.org')
			->addDirective(DIRECTIVE::CONNECT, 'https://nominatim.openstreetmap.org');

		$this->registerSignicatConnectSource();
		$this->registerAnoigoConnectSource();
	}

	private function registerSignicatConnectSource(): void
	{
		$configurationUrl = get_option('owc_signicat_openid_configuration_url_settings', null);

		if (! is_string($configurationUrl) || '' === $configurationUrl) {
			return;
		}

		$parsed = parse_url($configurationUrl);

		if (! is_array($parsed) || ! isset($parsed['scheme'], $parsed['host'])) {
			return;
		}

		$configurationUrl = $parsed['scheme'] . '://' . $parsed['host'];

		$this->addDirective(DIRECTIVE::CONNECT, $configurationUrl);
	}

	private function registerAnoigoConnectSource(): void
	{
		$configurationUrl = get_option('owc_anoigo_openid_configuration_url_settings', null);

		if (! is_string($configurationUrl) || '' === $configurationUrl) {
			return;
		}

		$parsed = parse_url($configurationUrl);

		if (! is_array($parsed) || ! isset($parsed['scheme'], $parsed['host'])) {
			return;
		}

		$configurationUrl = $parsed['scheme'] . '://' . $parsed['host'];

		$this->addDirective(DIRECTIVE::CONNECT, $configurationUrl);
	}
}
