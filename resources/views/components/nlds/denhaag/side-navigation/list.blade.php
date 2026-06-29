<nav {{ $attributes->merge(['class' => 'denhaag-side-navigation ']) }} aria-label="{{ __('Zijbalk', 'sage') }}">
	<ul class="denhaag-side-navigation__list">
		{{ $slot }}
	</ul>
</nav>
