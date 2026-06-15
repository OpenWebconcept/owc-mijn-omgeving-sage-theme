<?php

declare(strict_types=1);

namespace OWC\MijnOmgeving\Hooks;

use Yard\Hook\Action;

class SidebarIcons
{
	public const ICON_TYPE_MUNICIPALITY = 'municipality';
	public const ICON_TYPE_FONTAWESOME = 'fontawesome';
	public const ICON_NONE = 'none';

	public const ACF_FIELD_FONTAWESOME_ICON = 'menu_item_icon';
	public const ACF_FIELD_MUNICIPALITY_ICON = 'menu_item_muncipality_icon';

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
					'instructions' => 'Bijv. "arrow-left". Laat leeg om een gemeente icoon te gebruiken.',
				],
				[
					'key' => 'field_67dbede85dc28',
					'label' => 'Gemeente icoon',
					'name' => self::ACF_FIELD_MUNICIPALITY_ICON,
					'type' => 'select',
					'choices' => $this->getMunicipalityIcons(),
					'instructions' => 'Bekijk de iconen op <a href="https://www.gemeenteniconen.nl/iconen" target="_blank">https://www.gemeenteniconen.nl/iconen</a>',
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
