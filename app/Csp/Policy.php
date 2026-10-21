<?php

declare(strict_types=1);

namespace OWC\MijnOmgeving\Csp;

use Spatie\Csp\Directive;
use Spatie\Csp\Keyword;
use Spatie\Csp\Scheme;
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
		$this->restrictInlineSources();
	}

	/**
	 * DigiD norm U/PW.03 disallows 'unsafe-inline' and unrestricted schemes in script-src and style-src.
	 * Inline scripts and styles are allowed through a nonce instead, see OWC\MijnOmgeving\Hooks\Security.
	 * The admin and local development (Vite HMR) still require 'unsafe-inline'.
	 */
	public static function restrictsInlineSources(): bool
	{
		return ! is_admin() && ! (defined('WP_ENV') && in_array(WP_ENV, ['development', 'local'], true));
	}

	private function restrictInlineSources(): void
	{
		if (! self::restrictsInlineSources()) {
			return;
		}

		$this->removeDirectiveValues(Directive::SCRIPT, [Keyword::UNSAFE_INLINE, Scheme::HTTPS]);
		$this->removeDirectiveValues(Directive::STYLE, [Keyword::UNSAFE_INLINE]);

		$this
			->addNonceForDirective(Directive::SCRIPT)
			->addNonceForDirective(Directive::STYLE);
	}

	private function removeDirectiveValues(string $directive, array $values): void
	{
		$values = array_map(fn (string $value): string => $this->sanitizeValue($value), $values);

		$this->directives[$directive] = array_values(array_diff($this->directives[$directive] ?? [], $values));
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
