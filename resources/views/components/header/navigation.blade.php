@php($menu = \Log1x\Navi\Navi::make()->build('primary_navigation'))

@if ($menu->isNotEmpty())
	<x-brave::nav aria-label="{{ __('Primaire navigatie', 'sage') }}" class="hidden h-full items-center lg:flex">
		<x-brave::nav.list class="align-center mb-0 flex h-full list-none justify-center pl-0">
			@foreach ($menu->all() as $item)
				<x-brave::nav.item class="group">
					<x-brave::nav.link :item="$item"
						class="font-(family-name:--nav-menu-item-font-family) text-(--nav-menu-item-color) hocus:text-(--nav-menu-item-color-hover) text-(length:--nav-menu-item-font-size) font-(--nav-menu-item-font-weight) relative flex h-full cursor-pointer appearance-none items-center border-0 bg-transparent px-4 text-center leading-snug no-underline"
						activeClass="text-(--nav-menu-item-color-active) font-(weight:--nav-menu-item-font-weight-active)">
						{{ $item->label }}

						@if ($item->children)
							<i class="fa-light fa-chevron-down pl-2"></i>
						@endif

						<span @class([
							'bg-(--nav-menu-item-before-bg-color) ease-base absolute bottom-(--nav-menu-item-before-bottom) left-2 h-(--nav-menu-item-before-height) w-[calc(100%-1rem)] duration-300 xl:left-4 xl:w-[calc(100%-2rem)] invisible scale-0 group-hocus:visible group-hocus:scale-100',
							'visible scale-100' => $item->active || $item->activeParent,
						]) aria-hidden="true"></span>
					</x-brave::nav.link>

					@if ($item->children)
						<x-brave::nav.dropdown mode="hover"
							class="ease-base group-has-aria-expanded:visible group-has-aria-expanded:translate-y-0 group-has-aria-expanded:opacity-100 invisible absolute mb-0 min-w-48 -translate-y-3 list-none bg-white pl-0 opacity-0 shadow-md transition-all">
							@foreach ($item->children as $child)
								<x-brave::nav.item>
									<x-brave::nav.link :item="$child"
										class="group flex items-center px-6 py-3 text-left leading-snug text-inherit no-underline"
										activeClass="text-primary">
										{{ $child->label }}
										<i class="fa-light fa-angle-right ml-auto pl-6 transition-all group-hover:translate-x-1"></i>
									</x-brave::nav.link>
								</x-brave::nav.item>
							@endforeach
						</x-brave::nav.dropdown>
					@endif
				</x-brave::nav.item>
			@endforeach
		</x-brave::nav.list>
	</x-brave::nav>
@endif
