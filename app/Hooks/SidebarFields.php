<?php

declare(strict_types=1);

namespace OWC\MijnOmgeving\Hooks;

use OWC\MijnOmgeving\Services\UserContext;
use Yard\Hook\Action;

class SidebarFields
{
	public const ICON_TYPE_MUNICIPALITY = 'municipality';
	public const ICON_TYPE_FONTAWESOME = 'fontawesome';
	public const ICON_NONE = 'none';

	public const ACF_FIELD_FONTAWESOME_ICON = 'menu_item_icon';
	public const ACF_FIELD_MUNICIPALITY_ICON = 'menu_item_muncipality_icon';
	public const ACF_FIELD_AUTH_METHOD_VISIBILITY = 'menu_item_auth_method_visibility';

	public const AUTH_METHOD_VISIBILITY_ALL = 'all';

	public const MENU_LOCATION = 'sidebar_navigation';

	#[Action('acf/include_fields')]
	public function addAcfFields(): void
	{
		if (! function_exists('acf_add_local_field_group')) {
			return;
		}

		acf_add_local_field_group([
			'key' => 'group_66f54927e0cb1',
			'title' => 'Sidebar',
			'fields' => [
				[
					'key' => 'field_66f549282efec',
					'label' => 'Font Awesome icoon',
					'name' => self::ACF_FIELD_FONTAWESOME_ICON,
					'type' => 'text',
					'instructions' => 'Bijv. "arrow-left". Werkt alleen als er een Font Awesome kit is ingesteld. Laat leeg om een gemeente icoon te gebruiken.',
				],
				[
					'key' => 'field_67dbede85dc28',
					'label' => 'Gemeente icoon',
					'name' => self::ACF_FIELD_MUNICIPALITY_ICON,
					'type' => 'select',
					'choices' => $this->getMunicipalityIcons(),
					'instructions' => 'Bekijk de iconen op <a href="https://www.gemeenteniconen.nl/iconen" target="_blank">https://www.gemeenteniconen.nl/iconen</a>',
				],
				[
					'key' => 'field_67dbede85dc29',
					'label' => 'Zichtbaarheid loginmethode',
					'name' => self::ACF_FIELD_AUTH_METHOD_VISIBILITY,
					'type' => 'select',
					'choices' => [
						self::AUTH_METHOD_VISIBILITY_ALL => 'Alle',
						UserContext::AUTH_METHOD_DIGID => 'DigiD',
						UserContext::AUTH_METHOD_EHERKENNING => 'eHerkenning',
					],
					'instructions' => 'Kies bij welke loginmethode dit menu-item zichtbaar moet zijn.',
					'default_value' => self::AUTH_METHOD_VISIBILITY_ALL,
				],
			],
			'location' => [
				[
					[
						'param' => 'nav_menu_item',
						'operator' => '==',
						'value' => 'location/' . self::MENU_LOCATION,
					],
				],
			],
		]);
	}

	public function getMunicipalityIcons(): array
	{
		$default = [self::ICON_NONE => 'Geen icoon geselecteerd'];

		$iconPaths = glob(get_template_directory() . '/resources/images/municipality-icons/*.svg');

		if (! $iconPaths) {
			return $default;
		}

		$icons = [];
		foreach ($iconPaths as $path) {
			$basename = basename($path, '.svg');
			$icons[$basename] = $basename;
		}

		return array_merge($default, $icons);
	}
}
