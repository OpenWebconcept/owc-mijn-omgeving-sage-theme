<div class="hidden h-full items-center lg:flex">
	@if (!is_null($userModel) && !is_null($userDisplayName) && $userModel->isLoggedIn())
		<div class="js-dropdown relative">
			<button href="#"
				class="js-dropdown-trigger hover:text-primary font-(--nav-menu-item-font-weight) font-(family-name:--nav-menu-item-font-family) text-(length:--nav-menu-item-font-size) text-(--nav-menu-item-color) flex items-center gap-1 py-3"
				aria-expanded="false">
				Welkom, {!! $userDisplayName !!}
				<i class="fa-light fa-angle-down"></i>
			</button>
			<x-menu.sidebar class="js-dropdown-menu absolute right-0 !hidden !min-w-80 bg-white px-6 py-2 shadow" />
		</div>
	@elseif (config('theme.login_menu.show_login_button', false))
		<a href="{{ home_url() }}" class="is-button ml-6 after:content-none">
			Inloggen
		</a>
	@endif
</div>
