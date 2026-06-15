<?php

declare(strict_types=1);

namespace OWC\MijnOmgeving\View\Components\Menu;

use OWC\MijnOmgeving\Helpers\Prefill;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Log1x\Navi\Navi;

class Sidebar extends Component
{
	public const ICON_TYPE_MUNICIPALITY = 'municipality';
	public const ICON_TYPE_FONTAWESOME = 'fontawesome';
	public const ICON_NONE = 'none';

	public const ACF_FIELD_FONTAWESOME_ICON = 'menu_item_icon';
	public const ACF_FIELD_MUNICIPALITY_ICON = 'menu_item_muncipality_icon';

	public mixed $menu;
	public bool $hasFallbackMenu;
	public bool $hasLogout = false;
	public ?string $logoutUrl = null;

	public function __construct()
	{
		$this->menu = $this->resolveMenu();
	}

	protected function resolveMenu(): array
	{
		$menu = Navi::make()->build('sidebar_navigation');
		$this->hasFallbackMenu = $menu->isEmpty();

		return $menu->isNotEmpty()
				? $menu->all()
				: $this->fallbackMenu();
	}

	protected function fallbackMenu(): array
	{
		/* Fallback menu from design. Buren needs it for example. */
		return collect([
			[
				'slug' => 'overzicht',
				'label' => 'Overzicht',
				'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" aria-hidden="true" class="denhaag-icon w-5" viewBox="0 0 24 24"><path fill="currentColor" d="M3 5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5zm6 0H5v4h4V5zm4 0a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2V5zm6 0h-4v4h4V5zM3 15a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4zm6 0H5v4h4v-4zm4 0a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2v-4zm6 0h-4v4h4v-4z"/></svg>',
			],
			[
				'slug' => 'mijn-zaken',
				'label' => 'Mijn zaken',
				'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" aria-hidden="true" class="denhaag-icon w-5" viewBox="0 0 24 24"><path fill="currentColor" d="M2 5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v2a2 2 0 0 1-1.017 1.742c.011.084.017.17.017.258v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9c0-.087.006-.174.017-.258A2 2 0 0 1 2 7V5zm18 2V5H4v2h16zM5 9v10h14V9H5zm3 3a1 1 0 0 1 1-1h6a1 1 0 1 1 0 2H9a1 1 0 0 1-1-1z"/></svg>',
			],
			[
				'slug' => 'formulieren',
				'label' => 'Formulieren',
				'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" aria-hidden="true" class="denhaag-icon w-5" viewBox="0 0 24 24"><path fill="currentColor" fill-rule="evenodd" d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2Zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1ZM5 5v16h14V5h-2v3H7V5H5Z" clip-rule="evenodd"/></svg>',
			],
			[
				'slug' => 'mijn-gegevens',
				'label' => 'Mijn gegevens',
				'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" aria-hidden="true" class="denhaag-icon w-5" viewBox="0 0 24 24"><path fill="currentColor" d="M12 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8zM6 8a6 6 0 1 1 12 0A6 6 0 0 1 6 8zm2 10a3 3 0 0 0-3 3 1 1 0 1 1-2 0 5 5 0 0 1 5-5h8a5 5 0 0 1 5 5 1 1 0 1 1-2 0 3 3 0 0 0-3-3H8z"/></svg>',
			],
		])->map(fn ($item) => (object) [
			'url' => home_url($item['slug']),
			'label' => $item['label'],
			'active' => request()->is($item['slug']),
			'icon' => $item['icon'],
			'id' => '0',
		])->all();
	}

	public function getMenuItemIcon($item): string
	{
		if (isset($item->icon)) {
			return $item->icon;
		}

		$iconHTML = $this->generateIconHtml($this->getIcon($item));

		return $iconHTML;
	}

	protected function getIcon($item): ?array
	{
		if (! function_exists('get_field')) {
			return null;
		}

		$fieldIcon = get_field(self::ACF_FIELD_FONTAWESOME_ICON, $item->id);
		if (! empty($fieldIcon)) {
			return [
				'type' => self::ICON_TYPE_FONTAWESOME,
				'icon' => esc_attr($fieldIcon),
			];
		}

		$municipalityIcon = get_field(self::ACF_FIELD_MUNICIPALITY_ICON, $item->id);
		if (! empty($municipalityIcon) && self::ICON_NONE !== $municipalityIcon) {
			return [
				'type' => self::ICON_TYPE_MUNICIPALITY,
				'icon' => esc_attr($municipalityIcon),
			];
		}

		return null;
	}

	private function generateIconHtml(?array $iconData): string
	{
		if (! $iconData) {
			return '';
		}

		return match ($iconData['type']) {
			self::ICON_TYPE_FONTAWESOME => sprintf(
				'<i class="fa-fw fa-regular  fa-%s"></i>',
				$iconData['icon']
			),
			self::ICON_TYPE_MUNICIPALITY => $this->getMunicipalityIconHtml($iconData['icon']),
			default => '',
		};
	}

	private function getMunicipalityIconHtml(string $iconName): string
	{
		$svgPath = get_template_directory() . "/resources/images/municipality-icons/{$iconName}.svg";

		if (! file_exists($svgPath)) {
			return '';
		}

		$svg = file_get_contents($svgPath);

		if (strpos($svg, '<svg') !== false && strpos($svg, 'class="') === false) {
			$svg = preg_replace('/<svg\b/', '<svg class="denhaag-icon w-5"', $svg, 1);
		}

		return $svg;
	}

	public function render(): View|Closure|string
	{
		$bsn = Prefill::currentUserBSN();
		$kvk = Prefill::currentUserKVK();

		if ('' !== $bsn || '' !== $kvk) {
			$this->hasLogout = true;
		}

		if ('' !== $bsn) {
			$this->logoutUrl = home_url('sso-logout?idp=digid');
		} elseif ('' !== $kvk) {
			$this->logoutUrl = home_url('sso-logout?idp=eherkenning');
		}

		return view('components.menu.sidebar');
	}
}
