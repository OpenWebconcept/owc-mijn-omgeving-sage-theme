<header id="js-brave-header"
	class="header justify shadow-(--nav-bar-shadow) border-b-(length:--nav-bar-border-bottom-width) border-b-(--nav-bar-border-bottom-color) relative z-50 w-full bg-white transition-transform duration-500">
	<x-header.top-bar />

	<div
		class="h-(--nav-bar-height) container relative flex items-center justify-between gap-x-3 lg:items-stretch lg:gap-x-4">
		<x-header.site-branding />
		<x-header.navigation />
		<x-header.login-menu />
		<x-header.mobile-menu />
	</div>
</header>
