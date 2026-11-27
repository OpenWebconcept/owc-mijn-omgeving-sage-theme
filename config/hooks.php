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
	'assets' => \OWC\MijnOmgeving\Hooks\Assets::class,
	'block-components' => null,
	'capabilities' => \OWC\MijnOmgeving\Hooks\Capabilities::class,
	'crosspost' => null,
	'error-handling' => null,
	'facetwp' => null,
	'kcc' => null,
	'mijn-services' => \OWC\MijnOmgeving\Hooks\MijnServices::class,
	'openid-connect' => null,
	'openkaarten' => \OWC\MijnOmgeving\Hooks\Openkaarten::class,
	'owc' => \OWC\MijnOmgeving\Hooks\OWC::class,
	'owc-security' => \OWC\MijnOmgeving\Hooks\Security::class,
	'pronamic' => \OWC\MijnOmgeving\Hooks\Pronamic::class,
	'searchwp' => null,
	'security' => null, // Extended by \OWC\MijnOmgeving\Hooks\Security.
	'setup' => \OWC\MijnOmgeving\Hooks\Setup::class,
	'sidebar-fields' => \OWC\MijnOmgeving\Hooks\SidebarFields::class,
	'simple-history' => null,
	'theme' => \OWC\MijnOmgeving\Hooks\Theme::class,
	'user' => null,
	'warden' => null,
	'weglot' => null,
];
