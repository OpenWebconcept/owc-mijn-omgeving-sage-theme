<?php

declare(strict_types=1);

namespace OWC\MijnOmgeving\Hooks;

use Yard\Hook\Filter;

class Capabilities
{
	#[Filter('owc_zgw_transaction_roles_to_grant_capabilities', 20)]
	public function rolesToGrantTransactionCapabilities(array $roles): array
	{
		$roles[] = 'superuser';

		return $roles;
	}
}
