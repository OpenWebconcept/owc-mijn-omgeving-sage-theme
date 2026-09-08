<?php

declare(strict_types=1);

namespace OWC\MijnOmgeving\Providers;

use Illuminate\Support\ServiceProvider;
use OWC\MijnOmgeving\GravityForms\GravityForms;
use Yard\Hook\Registrar;

/**
 * Boots the Gravity Forms hook class on its own, rather than via the
 * theme's generic `config/hooks.php` list, so that everything related to
 * Gravity Forms (hooks, custom field types) lives together under
 * OWC\MijnOmgeving\GravityForms and is wired up from a single, dedicated
 * place.
 */
class GravityFormsServiceProvider extends ServiceProvider
{
	public function boot(): void
	{
		(new Registrar([GravityForms::class]))->registerHooks();
	}
}
