@if (has_nav_menu('footer_navigation') || $cookieLawPluginActive)
	<div class="border-t-2 border-gray-100 py-4">
		<div class="container flex flex-wrap gap-x-3 gap-y-1">
			@if ($cookieLawPluginActive)
				<button class="cky-banner-element hocus:underline text-sm">Cookievoorkeuren wijzigen</button>
			@endif

			@if (has_nav_menu('footer_navigation'))
				@php($footerMenu = \Log1x\Navi\Navi::make()->build('footer_navigation'))

				@if ($footerMenu->isNotEmpty())
					<x-brave::nav aria-label="{{ __('Footer navigatie', 'sage') }}">
						<x-brave::nav.list class="footer-menu flex flex-wrap items-center list-none h-full m-0 p-0 gap-x-3 gap-y-1">
							@foreach ($footerMenu->all() as $item)
								<x-brave::nav.item @class([
									'menu-item',
									'current-menu-item' => $item->active,
								])>
									<x-brave::nav.link :item="$item" />
								</x-brave::nav.item>
							@endforeach
						</x-brave::nav.list>
					</x-brave::nav>
				@endif
			@endif
		</div>
	</div>
@endif
