@if (is_front_page() || function_exists('seopress_display_breadcrumbs'))
	<div class="breadcrumbs-wrapper bg-(--breadcrumbs-bg-color) h-(--breadcrumbs-height) mb-(--breadcrumbs-margin-bottom) flex max-w-full items-center">
		@if (!is_front_page())
			<div class="container">
				{!! seopress_display_breadcrumbs() !!}
			</div>
		@endif
	</div>
@endif
