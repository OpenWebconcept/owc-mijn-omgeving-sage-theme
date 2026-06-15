@if (has_nav_menu('primary_navigation') ||
		(!is_null($userModel) && !is_null($userDisplayName) && $userModel->isLoggedIn()))
	<button id="js-hamburger" class="hamburger flex h-[46px] flex-col gap-y-2 lg:hidden" aria-controls="js-mobile-menu"
		aria-expanded="false" aria-label="{{ __('Open menu', 'owc-mijn-omgeving') }}">
		<span class="block h-0.5 w-8 rounded-full bg-black transition-all duration-300 ease-in-out"></span>
		<span class="block h-0.5 w-6 rounded-full bg-black transition-all duration-300 ease-in-out"></span>
		<span class="block h-0.5 w-4 rounded-full bg-black transition-all duration-300 ease-in-out"></span>
		<span class="mt-auto text-xs leading-none">{{ __('Menu', 'owc-mijn-omgeving') }}</span>
	</button>
@endif
