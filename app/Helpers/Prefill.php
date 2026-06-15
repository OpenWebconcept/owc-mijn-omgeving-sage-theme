<?php

declare(strict_types=1);

namespace OWC\MijnOmgeving\Helpers;

class Prefill
{
	public static function currentUserBSN(): string
	{
		if (! self::isPluginActive('prefill-gravity-forms/prefill-gravity-forms.php')) {
			return '';
		}

		if (! method_exists('OWC\PrefillGravityForms\Helpers', 'currentUserHasBSN')) {
			return '';
		}

		return (string) \OWC\PrefillGravityForms\Helpers::currentUserHasBSN();
	}

	public static function currentUserKVK(): string
	{
		if (! self::isPluginActive('owc-gravityforms-kvk-prefill/owc-gravityforms-kvk-prefill.php')) {
			return '';
		}

		if (! method_exists('OWC\PrefillGravityFormsKVK\Traits\Helpers', 'currentUserHasKVK')) {
			return '';
		}

		return (string) \OWC\PrefillGravityFormsKVK\Traits\Helpers::currentUserHasKVK();
	}

	private static function isPluginActive(string $pluginPath): bool
	{
		if (! function_exists('is_plugin_active')) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		return is_plugin_active($pluginPath);
	}
}
