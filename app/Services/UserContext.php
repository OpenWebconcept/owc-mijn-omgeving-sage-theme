<?php

declare(strict_types=1);

namespace OWC\MijnOmgeving\Services;

use OWC\MijnOmgeving\Helpers\Prefill;

final class UserContext
{
	public const AUTH_METHOD_DIGID = 'digid';
	public const AUTH_METHOD_EHERKENNING = 'eherkenning';

	private static bool $resolved = false;
	private static ?object $cachedUserModel = null;

	private static bool $authMethodResolved = false;
	private static ?string $cachedAuthMethod = null;

	public function userModel(): ?object
	{
		if (self::$resolved) {
			return self::$cachedUserModel;
		}

		self::$resolved = true;

		return self::$cachedUserModel = match ($this->authMethod()) {
			self::AUTH_METHOD_DIGID => new \OWC\PrefillGravityForms\Models\UserModel(),
			self::AUTH_METHOD_EHERKENNING => new \OWC\PrefillGravityFormsKVK\Models\OrganizationModel(),
			default => null,
		};
	}

	/**
	 * The method the current user logged in with, or null when none can be determined.
	 */
	public function authMethod(): ?string
	{
		if (self::$authMethodResolved) {
			return self::$cachedAuthMethod;
		}

		self::$authMethodResolved = true;

		if ('' !== Prefill::currentUserBSN()) {
			return self::$cachedAuthMethod = self::AUTH_METHOD_DIGID;
		}

		if ('' !== Prefill::currentUserKVK()) {
			return self::$cachedAuthMethod = self::AUTH_METHOD_EHERKENNING;
		}

		return self::$cachedAuthMethod = null;
	}

	public static function flush(): void
	{
		self::$resolved = false;
		self::$cachedUserModel = null;
		self::$authMethodResolved = false;
		self::$cachedAuthMethod = null;
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
