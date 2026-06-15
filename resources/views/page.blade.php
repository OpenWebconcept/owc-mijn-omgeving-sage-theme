<x-layout>
	@if (!$postData->hasTitle())
		<h1 class="mt-6">{!! $postData->title() !!}</h1>
	@endif
	@while (have_posts())
		@php(the_post())
		@php(the_content())
	@endwhile
</x-layout>
