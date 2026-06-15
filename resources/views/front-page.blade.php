<x-layout>
	<div class="bg-(--breadcrumbs-bg-color-home) h-(--breadcrumbs-height-home) mb-12 max-w-full"></div>
	@while (have_posts())
		@php(the_post())
		@php(the_content())
	@endwhile
</x-layout>
