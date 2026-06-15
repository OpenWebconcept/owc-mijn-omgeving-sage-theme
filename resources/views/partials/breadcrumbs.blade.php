@if (!is_front_page() && function_exists('seopress_display_breadcrumbs'))
	<div class="breadcrumbs-wrapper bg-(--breadcrumbs-bg-color) h-(--breadcrumbs-height) flex max-w-full items-center">
		<div class="container">
			{!! seopress_display_breadcrumbs() !!}
		</div>
	</div>
@endif
