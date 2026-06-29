@if (config('theme.footer.is_active', true))
	<footer class="footer bg-(--footer-bg-color) **:text-(--footer-text-color)">
		<x-brave-pattern-content slug="footer" />
		<x-footer.bottom-bar />
	</footer>
@else
	<x-footer.bottom-bar />
@endif
