<x-layout>
	@while (have_posts())
		@php(the_post())
		<h1 class="mt-6">@php(the_title())</h1>
		@php(the_content())
	@endwhile
</x-layout>
