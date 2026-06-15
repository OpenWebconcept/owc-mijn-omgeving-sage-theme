@props(['href', 'title', 'isActive' => false, 'iconHtml' => null, 'badge' => null])

<li class="denhaag-side-navigation__item">
	<a href="{{ $href }}" @class([
		'denhaag-side-navigation__link',
		'denhaag-side-navigation__link--current' => $isActive,
	]) aria-current="{{ $isActive ? 'page' : 'false' }}">

		@if ($iconHtml)
			{!! $iconHtml !!}
		@endif

		<span class="denhaag-side-navigation__link-label">
			{!! $title !!}
		</span>

		@if (!is_null($badge))
			<span class="nl-number-badge">{{ $badge }}</span>
		@endif
	</a>
</li>
