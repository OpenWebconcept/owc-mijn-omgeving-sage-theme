@if (has_nav_menu('top_bar_navigation'))
	<div class="top-bar h-(--top-bar-height) hidden bg-gray-100 lg:flex">
		<div class="container relative h-full">
			@php($topBarMenu = \Log1x\Navi\Navi::make()->build('top_bar_navigation'))

			@if ($topBarMenu->isNotEmpty())
				<x-brave::nav aria-label="{{ __('Secundaire navigatie', 'sage') }}"
					class="flex h-full items-center justify-end text-sm">
					<x-brave::nav.list class="top-bar-menu m-0 flex h-full list-none items-center gap-4 p-0">
						@foreach ($topBarMenu->all() as $item)
							<x-brave::nav.item>
								<x-brave::nav.link :item="$item" class="hover:text-primary text-inherit no-underline"
									activeClass="text-primary" />
							</x-brave::nav.item>
						@endforeach
					</x-brave::nav.list>
				</x-brave::nav>
			@endif
		</div>
	</div>
@endif
