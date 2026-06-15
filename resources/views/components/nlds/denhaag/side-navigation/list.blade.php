<nav {{ $attributes->merge(['class' => 'denhaag-side-navigation ']) }}>
	<ul class="denhaag-side-navigation__list">
		{{ $slot }}
	</ul>
</nav>
