<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Register The Bootloader
|--------------------------------------------------------------------------
|
| The first thing we will do is schedule a new Acorn application container
| to boot when WordPress is finished loading the theme. The application
| serves as the "glue" for all the components of Laravel and is
| the IoC container for the system binding all of the various parts.
|
*/

define('ACORN_BASEPATH', __DIR__);
\Yard\Nutshell\Application::configure()
	->withProviders([
		\OWC\MijnOmgeving\Providers\ThemeServiceProvider::class,
		\OWC\MijnOmgeving\Providers\GravityFormsServiceProvider::class,
	])
	->boot();
