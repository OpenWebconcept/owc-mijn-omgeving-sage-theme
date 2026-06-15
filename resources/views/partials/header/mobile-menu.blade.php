@if (has_nav_menu('primary_navigation') ||
		(!is_null($userModel) && !is_null($userDisplayName) && $userModel->isLoggedIn()))
	<div id="js-mobile-menu"
		class="mobile-menu ease-base z-1020 invisible fixed bottom-0 left-0 right-0 top-[calc(var(--nav-bar-height)+var(--wp-admin-bar-height))] w-full overflow-y-scroll bg-white opacity-0 transition-all duration-500">
		<nav class="flex h-full flex-col items-start px-8 py-8" aria-label="{{ __('Mobiele navigatie', 'owc-mijn-omgeving') }}">

			<x-menu.primary-mobile />

			@if (!is_null($userModel) && !is_null($userDisplayName) && $userModel->isLoggedIn())
				<x-menu.sidebar class="block! mb-6 mt-4" />
			@elseif (config('theme.login_menu.show_login_button_mobile', true))
				<a href="{{ home_url() }}" class="is-button mb-4 after:content-none">
					Inloggen
				</a>
			@endif

			<button id="js-mobile-menu-close-btn" class="is-button on-focus-visible focus:!static">{{ __('Sluiten', 'owc-mijn-omgeving') }}
			</button>
		</nav>
	</div>
@endif
