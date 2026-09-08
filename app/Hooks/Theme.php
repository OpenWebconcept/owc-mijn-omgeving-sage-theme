<?php

declare(strict_types=1);

namespace OWC\MijnOmgeving\Hooks;

use Illuminate\Support\Facades\View;
use Yard\Hook\Action;
use Yard\Hook\Filter;

class Theme extends \Yard\Brave\Hooks\Theme
{
	#[Action('body_class')]
	public function bodyClass(array $classes): array
	{
		$classes = parent::bodyClass($classes);

		// Add extra body classes here

		return $classes;
	}

	#[Filter('admin_body_class')]
	public function adminBodyClass(string $classes): string
	{
		$classes = parent::adminBodyClass($classes);

		// Add extra admin body classes here

		return $classes;
	}

	#[Filter('the_password_form')]
	public function changePasswordForm(string $template): string
	{
		if (! View::exists('partials.password-form')) {
			return $template;
		}

		return View::make('partials.password-form')->render();
	}

	#[Filter('wp') ]
	public function removeWpautopForPasswordForm()
	{
		if (is_singular() && post_password_required()) {
			remove_filter('the_content', 'wpautop');
		}
	}

	#[Filter('excerpt_allowed_wrapper_blocks')]
	public function addInnerBlocksToExcerpt(array $blocks): array
	{
		$blocksToAdd = [
			'core/media-text',
		];

		return array_merge($blocks, $blocksToAdd);
	}

	#[Filter('gform_enable_legacy_markup')]
	public function disableLegacyMarkup(): bool
	{
		return false;
	}

	#[Action('wp_head')]
	public function addGlobalsToFrontendWindowObject(): void
	{
		wp_print_inline_script_tag(
			'window.theme = Object.assign({}, window.theme || {}, ' . wp_json_encode([
				'is_deepl_enabled' => $this->isDeepLEnabled(),
			], JSON_UNESCAPED_UNICODE) . ');'
		);
	}

	private function isDeepLEnabled(): bool
	{
		if (! function_exists('is_plugin_active')) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		return is_plugin_active('yard-deepl/yard-deepl.php');
	}
}
