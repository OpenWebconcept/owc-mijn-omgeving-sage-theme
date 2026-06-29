@props([
    'name' => null,
    'ariaLabel' => '',
    'mode' => 'click',
    'itemClass' => '',
    'linkClass' => '',
    'activeClass' => 'text-blue-500',
    'dropdownClass' => '',
    'childActive' => 'text-blue-500',
])

@php($menu = \Log1x\Navi\Navi::make()->build($name))

@if ($menu->isNotEmpty())
	<x-brave::nav :aria-label="$ariaLabel" {{ $attributes }}>
		<x-brave::nav.list>
			@foreach ($menu->all() as $item)
				<x-brave::nav.item @class(['group', $itemClass])>
					<x-brave::nav.link :item="$item" :class="$linkClass" :activeClass="$activeClass">
						{{ $item->label }}
					</x-brave::nav.link>

					@if ($item->children)
						<x-brave::nav.dropdown :mode="$mode" :class="$dropdownClass">
							@foreach ($item->children as $child)
								<x-brave::nav.item>
									<x-brave::nav.link :item="$child" :class="$linkClass" :activeClass="$childActive">
										{{ $child->label }}
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
