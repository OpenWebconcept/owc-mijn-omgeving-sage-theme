<div class="hidden h-full items-center lg:flex">
	@if (!is_null($userModel) && !is_null($userDisplayName) && $userModel->isLoggedIn())
		<div class="brave-nav brave-nav-item group relative">
			<x-brave::nav.link :hasChildren="true" type="button"
				class="hover:text-primary font-(--nav-menu-item-font-weight) font-(family-name:--nav-menu-item-font-family) text-(length:--nav-menu-item-font-size) text-(--nav-menu-item-color) flex cursor-pointer appearance-none items-center gap-1 border-0 bg-transparent py-3 leading-snug">
				Welkom, {{ $userDisplayName }}
				<i class="fa-light fa-angle-down"></i>
			</x-brave::nav.link>

			<x-menu.sidebar data-mode="click"
				class="brave-nav-dropdown group-has-aria-expanded:!block absolute right-0 !hidden !min-w-80 bg-white px-6 py-2 shadow" />
		</div>
	@elseif (config('theme.login_menu.show_login_button', false))
		<a href="{{ home_url() }}" class="is-button ml-6 after:content-none">
			Inloggen
		</a>
	@endif
</div>
