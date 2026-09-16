@php
	/**
	 * Template Name: Sidebar (Mijn Services)
	 */
	use OWC\MijnOmgeving\Services\ZaakContext;
@endphp

<x-layout.sidebar-menu-main>
	<x-slot:main>
		@if ($postData->shouldShowTitle() && !ZaakContext::rendersZaakBlock())
			<h1>{!! $postData->title() !!}</h1>
		@endif

		{!! $postData->content() !!}
	</x-slot:main>
</x-layout.sidebar-menu-main>
