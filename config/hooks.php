<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Hook Classes
|--------------------------------------------------------------------------
| List every hook class that should be registered. Plugin-conditional hooks
| carry a #[Plugin(‘plugin/file.php’)] attribute on the class itself — the
| brave-hooks Registrar checks that attribute and only wires them up when
| the plugin is active, so no separate "plugins" section is needed here.
|
*/

return [
	\OWC\MijnOmgeving\Hooks\Assets::class,
	\OWC\MijnOmgeving\Hooks\Capabilities::class,
	\OWC\MijnOmgeving\Hooks\OWC::class,
	\OWC\MijnOmgeving\Hooks\Pronamic::class,
	\OWC\MijnOmgeving\Hooks\Setup::class,
	\OWC\MijnOmgeving\Hooks\Theme::class,
	\OWC\MijnOmgeving\Hooks\SidebarIcons::class,
	\Yard\Brave\Hooks\Authorization::class,
	\Yard\Brave\Hooks\Security::class,
	\Yard\Brave\Hooks\ACF::class,
	\Yard\Brave\Hooks\Elasticsearch::class,
	\Yard\Brave\Hooks\GravityForms::class,
	\Yard\Brave\Hooks\Imagify::class,
	\Yard\Brave\Hooks\SEOPress::class,
	\Yard\Brave\Hooks\Gutenberg::class,
];
