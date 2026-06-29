<x-layout>
	<div class="layout-sidebar-menu-main container grid grid-cols-12 gap-4 py-6 md:gap-8 xl:gap-12">
		<div class="layout-sidebar-menu-main__aside col-span-12 md:col-span-4 xl:col-span-3">
			<x-menu.sidebar class="top-(--combined-bar-height) sticky md:!block" />
		</div>
		<article class="layout-sidebar-menu-main__article normalize-child-margin col-span-12 md:col-span-8 xl:col-span-9">
			{{ $main }}
		</article>
	</div>
	@if (isset($bottom))
		{{ $bottom }}
	@endif
</x-layout>
