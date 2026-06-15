<?php

declare(strict_types=1);

namespace OWC\MijnOmgeving\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use WP_Block_Type_Registry;

class HomeLogin extends Component
{
	protected const LOGIN_TYPES = [
		'digid' => [
			'title' => 'Inloggen met DigiD',
			'image' => '/resources/images/logos/digid.svg',
			'label' => 'Geen DigiD? <a href="https://www.digid.nl">Vraag DigiD aan</a>',
			'idp' => 'digid',
			'url' => '/inloggen',
		],
		'eherkenning' => [
			'title' => 'Inloggen met eHerkenning',
			'image' => '/resources/images/logos/eherkenning.svg',
			'label' => 'Geen eHerkenning? <a href="https://www.eherkenning.nl/nl/eherkenning-aanvragen">Vraag eHerkenning aan</a>',
			'idp' => 'eherkenning',
			'url' => '/inloggen-eherkenning',
		],
		'eidas' => [
			'title' => 'Inloggen with your European Digital identity',
			'image' => '/resources/images/logos/eidas.svg',
			'label' => 'For more info on eIDAS, go to <a href="https://www.eid.as/nl/">eid.as</a>.',
			'idp' => 'eidas',
			'url' => '/inloggen-eidas',
		],
	];

	public function __construct(
		public string $type = 'digid',
		public ?string $title = null,
		public ?string $image = null,
		public ?string $label = null,
		public ?string $url = null,
		public bool $isActive = true,
		public string $idp = '',
		public string $buttonText = 'Inloggen',
		public string $provider = 'owc-signicat-openid',
		public string $redirectUrl = '/overzicht'
	) {
		$config = self::LOGIN_TYPES[$type] ?? self::LOGIN_TYPES['digid'];
		$themeConfig = config('theme.home')[$type] ?? [];

		$this->isActive = $themeConfig['is_active'] ?? $this->isActive;
		$this->title = $title ?? $config['title'];
		$this->image = get_theme_file_uri($image ?? $config['image']);
		$this->label = $label ?? $config['label'];
		$this->idp = $config['idp'];
		$this->url = $url ?? home_url($config['url']);
	}

	public function shouldUseBlock(): bool
	{
		return WP_Block_Type_Registry::get_instance()->is_registered($this->provider . '/openid');
	}

	public function getLoginBlock(): ?string
	{
		if (! $this->shouldUseBlock()) {
			return null;
		}

		return do_blocks(sprintf(
			'<!-- wp:%s/openid {"redirectUrl":"%s","idp":"%s"} /-->',
			$this->provider,
			$this->redirectUrl,
			$this->idp
		));
	}

	public function render(): View|Closure|string
	{
		return view('components.home.login');
	}
}
