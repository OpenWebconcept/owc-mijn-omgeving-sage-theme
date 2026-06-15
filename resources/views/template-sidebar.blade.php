@php
	/**
	 * Template Name: Sidebar (Mijn Services)
	 */
@endphp

<x-layout.sidebar-menu-main>
	<x-slot:main>
		@if (!$postData->hasTitle() && $postData->title() !== 'Zaak')
			<h1>{!! $postData->title() !!}</h1>
		@endif

		{!! $postData->content() !!}
	</x-slot:main>
</x-layout.sidebar-menu-main>
