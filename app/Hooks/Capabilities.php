<?php

declare(strict_types=1);

namespace OWC\MijnOmgeving\Hooks;

use Yard\Hook\Filter;

class Capabilities
{
	#[Filter('owcms::settings/allowed_settings_capability')]
	public function filterAllowedInformatieobjecttypenCapability(string $capability): string
	{
		return 'yard_superuser';
	}

	#[Filter('owcgfzgw::form_settings/cache_capability')]
	public function filterCacheCapability(string $capability): string
	{
		return 'yard_superuser';
	}

	#[Filter('owc_zgw_transaction_roles_to_grant_capabilities', 20)]
	public function rolesToGrantTransactionCapabilities(array $roles): array
	{
		$roles[] = 'superuser';

		return $roles;
	}

	#[Filter('owc_activity_log_admin_page_overview_cap')]
	public function filterActivityLogAdminPageOverviewCap(string $capability): string
	{
		return 'yard_superuser';
	}
}
