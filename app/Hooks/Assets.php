<?php

declare(strict_types=1);

namespace OWC\MijnOmgeving\Hooks;

use Illuminate\Support\Facades\Vite;
use Yard\Hook\Action;
use Yard\Hook\Filter;

class Assets
{
	#[Action('wp_head', 99)]
	public function registerFrontendAssets(): void
	{
		Vite::useHotFile($this->hotFile());

		echo Vite::withEntryPoints(array_map($this->viteEntry(...), [
			'resources/styles/frontend.css',
			'resources/scripts/frontend/frontend.js',
		]))->toHtml();
	}

	#[Action('admin_head')]
	public function registerBlockEditorAssets()
	{
		if (! get_current_screen()?->is_block_editor()) {
			return;
		}

		$dependencies = json_decode(Vite::content('editor.deps.json'));

		foreach ($dependencies as $dependency) {
			if (! wp_script_is($dependency)) {
				wp_enqueue_script($dependency);
			}
		}

		Vite::useHotFile($this->hotFile());

		echo Vite::withEntryPoints(array_map($this->viteEntry(...), [
			'resources/scripts/editor/editor.js',
		]))->toHtml();
	}

	/**
	 * Inject styles through hook for pattern preview window and iframe'd editor
	 */
	#[Filter('block_editor_settings_all')]
	public function injectEditorStyles($settings)
	{
		Vite::useHotFile(get_parent_theme_file_path('public/hot'));

		$style = Vite::asset($this->viteEntry('resources/styles/editor.css'));

		$settings['styles'][] = [
			'css' => "@import url('{$style}')",
		];

		return $settings;
	}

	/**
	 * This hook fires both in the editor and on the front end of your site.
	 */
	#[Action('enqueue_block_assets')]
	public function registerBlockAssets(): void
	{
		wp_enqueue_script('fontawesome', config('app.fontawesome.url'), [], null, true);
		wp_enqueue_style('theme-font', config('theme.font.url'), [], null);
	}

	/**
	 * @see https://make.wordpress.org/core/2021/07/01/block-styles-loading-enhancements-in-wordpress-5-8/
	 */
	#[Action('should_load_separate_core_block_assets')]
	public function loadSeparateCoreBlockAssets(): bool
	{
		return true;
	}

	#[Action('yard::gutenberg/allowed-blocks-whitelisted-prefixes', 20)]
	public function addWhitelistedPrefixes(array $prefixes): array
	{
		return array_merge($prefixes, ['owc']);
	}

	/**
	 * Locate the Vite hot file. Theme-root writes it here; brave-root writes it
	 * to sage's public dir. TODO: point the brave-root dev server at this theme.
	 */
	private function hotFile(): string
	{
		$themeHot = get_theme_file_path('public/hot');

		if (file_exists($themeHot)) {
			return $themeHot;
		}

		return get_theme_root() . '/sage/public/hot';
	}

	/**
	 * Prefix a theme-relative entry with the theme path when the manifest was
	 * built brave-root (keys under web/app/themes/<theme>/), else return as-is.
	 */
	private function viteEntry(string $path): string
	{
		static $prefix;

		if (! isset($prefix)) {
			$manifestPath = get_theme_file_path('public/build/manifest.json');
			$manifest = is_readable($manifestPath)
				? (array) json_decode((string) file_get_contents($manifestPath), true)
				: [];

			$prefix = '';

			foreach (array_keys($manifest) as $key) {
				if (str_starts_with($key, 'web/app/themes/')) {
					$prefix = 'web/app/themes/' . get_stylesheet() . '/';

					break;
				}
			}
		}

		return $prefix . $path;
	}
}
