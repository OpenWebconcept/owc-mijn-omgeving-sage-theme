@if (has_nav_menu('top_bar_navigation'))
	<div class="top-bar h-(--top-bar-height) hidden bg-gray-100 lg:flex">
		<div class="container relative h-full">
			<nav class="flex h-full items-center justify-end text-sm" aria-label="{{ __('Secundaire navigatie', 'owc-mijn-omgeving') }}">
				@php
					wp_nav_menu([
					    'container' => '',
					    'depth' => 1,
					    'id' => '',
					    'menu_class' => 'top-bar-menu flex items-center list-none h-full m-0 p-0 gap-4',
					    'theme_location' => 'top_bar_navigation',
					]);
				@endphp
			</nav>
		</div>

	</div>
@endif
