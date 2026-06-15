<?php

declare(strict_types=1);

namespace OWC\MijnOmgeving\Services;

final class UserContext
{
	private static bool $resolved = false;
	private static ?object $cachedUserModel = null;

	public function userModel(): ?object
	{
		if (self::$resolved) {
			return self::$cachedUserModel;
		}

		self::$resolved = true;

		return self::$cachedUserModel =
			$this->getUserModelDigiD()
			?? $this->getUserModelKVK();
	}

	public static function flush(): void
	{
		self::$resolved = false;
		self::$cachedUserModel = null;
	}

	private function getUserModelDigiD(): ?object
	{
		$bsn = \OWC\MijnOmgeving\Helpers\Prefill::currentUserBSN();

		return '' !== $bsn ? new \OWC\PrefillGravityForms\Models\UserModel() : null;
	}

	private function getUserModelKVK(): ?object
	{
		$kvk = \OWC\MijnOmgeving\Helpers\Prefill::currentUserKVK();

		return '' !== $kvk ? new \OWC\PrefillGravityFormsKVK\Models\OrganizationModel() : null;
	}

	public function userDisplayName(): ?string
	{
		$userModel = $this->userModel();

		if (! is_object($userModel) || ! method_exists($userModel, 'isLoggedIn') || ! $userModel->isLoggedIn()) {
			return null;
		}

		if (class_exists(\OWC\PrefillGravityForms\Models\UserModel::class) && $userModel instanceof \OWC\PrefillGravityForms\Models\UserModel) {
			return $userModel->fullName(withInitials: true);
		}

		if (class_exists(\OWC\PrefillGravityFormsKVK\Models\OrganizationModel::class) && $userModel instanceof \OWC\PrefillGravityFormsKVK\Models\OrganizationModel) {
			return $userModel->name();
		}

		return null;
	}
}
