<?php

declare(strict_types=1);

namespace OWC\MijnOmgeving\Services;

use OWC\MijnOmgeving\Hooks\SidebarFields;

final class ZaakContext
{
	public const QUERY_VAR_IDENTIFICATION = 'owc-zaaknummer';
	public const QUERY_VAR_SUPPLIER = 'owc-leverancier';
	public const BLOCK_MIJN_ZAKEN = 'owc-my-services/mijn-zaken';
	public const BLOCK_ZAAK = 'owc-my-services/zaak';
	public const FALLBACK_OVERVIEW_SLUG = 'mijn-zaken';

	private static ?string $title = null;

	private static bool $overviewPageResolved = false;
	private static ?int $cachedOverviewPageId = null;

	public static function isZaakDetail(): bool
	{
		return '' !== self::identification();
	}

	public static function identification(): string
	{
		return sanitize_text_field((string) get_query_var(self::QUERY_VAR_IDENTIFICATION));
	}

	public static function supplier(): string
	{
		return sanitize_text_field((string) get_query_var(self::QUERY_VAR_SUPPLIER));
	}

	public static function url(): ?string
	{
		$identification = self::identification();
		$supplier = self::supplier();

		if ('' === $identification || '' === $supplier) {
			return null;
		}

		return home_url("zaak/{$identification}/{$supplier}");
	}

	public static function zaakPageId(): int
	{
		return (int) get_queried_object_id();
	}

	public static function rendersZaakBlock(): bool
	{
		if (! is_singular()) {
			return false;
		}

		return has_block(self::BLOCK_ZAAK, get_queried_object_id());
	}

	public static function setTitle(string $title): void
	{
		$title = trim(wp_strip_all_tags($title));

		self::$title = '' !== $title ? $title : null;
	}

	public static function title(): ?string
	{
		return self::$title;
	}

	public static function overviewPageId(): ?int
	{
		if (self::$overviewPageResolved) {
			return self::$cachedOverviewPageId;
		}

		self::$overviewPageResolved = true;

		$filtered = (int) apply_filters('owcmo::zaak/overview-page-id', 0);

		if ($filtered > 0) {
			return self::$cachedOverviewPageId = $filtered;
		}

		$conventional = self::findOverviewPageBySlug();

		if (null !== $conventional && self::rendersOverviewBlock($conventional)) {
			return self::$cachedOverviewPageId = $conventional;
		}

		return self::$cachedOverviewPageId = self::findOverviewPagesInSidebarMenu()[0] ?? $conventional;
	}

	public static function isOverviewPage(int $postId): bool
	{
		$overviewPageId = self::overviewPageId();

		return null !== $overviewPageId && $overviewPageId === $postId;
	}

	public static function flush(): void
	{
		self::$title = null;
		self::$overviewPageResolved = false;
		self::$cachedOverviewPageId = null;
	}

	private static function findOverviewPagesInSidebarMenu(): array
	{
		$menuId = get_nav_menu_locations()[SidebarFields::MENU_LOCATION] ?? 0;

		if (! $menuId) {
			return [];
		}

		$items = wp_get_nav_menu_items($menuId);

		if (! is_array($items)) {
			return [];
		}

		$pageIds = [];

		foreach ($items as $item) {
			$postId = (int) ($item->object_id ?? 0);

			if ('post_type' !== ($item->type ?? '') || $postId <= 0) {
				continue;
			}

			if (self::rendersOverviewBlock($postId)) {
				$pageIds[] = $postId;
			}
		}

		return array_values(array_unique($pageIds));
	}

	private static function findOverviewPageBySlug(): ?int
	{
		$page = get_page_by_path(self::FALLBACK_OVERVIEW_SLUG);

		return $page instanceof \WP_Post ? $page->ID : null;
	}

	private static function rendersOverviewBlock(int $postId): bool
	{
		return 'publish' === get_post_status($postId) && has_block(self::BLOCK_MIJN_ZAKEN, $postId);
	}
}
