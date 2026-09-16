<?php

declare(strict_types=1);

namespace OWC\MijnOmgeving\Hooks;

use OWC\MijnOmgeving\Services\ZaakContext;
use Yard\Brave\Hooks\Plugin;
use Yard\Hook\Action;
use Yard\Hook\Filter;

#[Plugin('owc-mijn-services/owc-mijn-services.php')]
class MijnServices
{
	#[Action('owcms::zaak/resolved')]
	public function captureZaakTitle(object $zaak): void
	{
		if (! method_exists($zaak, 'title')) {
			return;
		}

		ZaakContext::setTitle((string) $zaak->title());
	}

	#[Filter('seopress_pro_breadcrumbs_crumbs')]
	public function addZaakBreadcrumbs(array $crumbs): array
	{
		if (! ZaakContext::isZaakDetail() || empty($crumbs)) {
			return $crumbs;
		}

		$trail = [reset($crumbs)];

		if ($overviewCrumb = $this->overviewCrumb()) {
			$trail[] = $overviewCrumb;
		}

		$trail[] = $this->zaakCrumb();

		return $trail;
	}

	private function overviewCrumb(): ?array
	{
		$overviewPageId = ZaakContext::overviewPageId();

		if (null === $overviewPageId) {
			return null;
		}

		$permalink = get_permalink($overviewPageId);

		if (! is_string($permalink)) {
			return null;
		}

		return [
			0 => wp_strip_all_tags(get_the_title($overviewPageId)),
			1 => $permalink,
			2 => $overviewPageId,
		];
	}

	private function zaakCrumb(): array
	{
		$zaakPageId = ZaakContext::zaakPageId();

		return [
			0 => ZaakContext::title() ?? wp_strip_all_tags(get_the_title($zaakPageId)),
			1 => ZaakContext::url() ?? get_permalink($zaakPageId),
			2 => $zaakPageId,
		];
	}
}
