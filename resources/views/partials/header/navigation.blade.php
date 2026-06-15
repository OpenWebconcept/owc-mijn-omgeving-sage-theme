@if (has_nav_menu('primary_navigation'))
	<nav class="hidden items-center lg:flex" aria-label="{{ __('Primaire navigatie', 'owc-mijn-omgeving') }}">
		<x-menu.primary />
	</nav>
@endif
