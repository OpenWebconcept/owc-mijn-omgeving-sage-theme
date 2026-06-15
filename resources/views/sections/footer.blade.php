@if (config('theme.footer.is_active', true))
	<footer class="footer bg-(--footer-bg-color) **:text-(--footer-text-color)">
		<x-brave-pattern-content slug="footer" />
		@include('partials.footer.bottom-bar')
	</footer>
@else
	@include('partials.footer.bottom-bar')
@endif
