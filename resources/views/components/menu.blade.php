@props([
    'name' => null,
    'itemClass' => '',
    'children' => '',
    'childActive' => 'text-blue-500',
])

@php($menu = \Log1x\Navi\Navi::make()->build($name))

@if ($menu->isNotEmpty())
	<ul {{ $attributes }}>
		@foreach ($menu->all() as $item)
			<li>
				<a href="{{ $item->url }}" @class([$item->classes, $active => $item->active])>
					{{ $item->label }}
				</a>

				@if ($item->children)
					<ul @class([$children])>
						@foreach ($item->children as $child)
							<li>
								<a href="{{ $child->url }}" @class([$child->classes, $childActive => $child->active])>
									{{ $child->label }}
								</a>
							</li>
						@endforeach
					</ul>
				@endif
			</li>
		@endforeach
	</ul>
@endif
