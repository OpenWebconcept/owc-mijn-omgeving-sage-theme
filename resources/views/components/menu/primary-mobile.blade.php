@php($menu = \Log1x\Navi\Navi::make()->build('primary_navigation'))

@if ($menu->isNotEmpty())
	<ul class="js-menu-primary w-full list-none pl-0 mb-0">
		@foreach ($menu->all() as $item)
			<li @class(['group', 'js-menu-item-is-expandable' => $item->children])>
				<a href="{{ $item->url }}" @class([
					$item->classes,
					'flex items-baseline py-3 text-black no-underline !text-xl font-normal flex-4',
					'text-(--nav-menu-item-color-active) ' =>
						$item->active || $item->activeParent,
				])>
					<i class="fa-light fa-angle-right text-primary w-8 shrink-0"></i>
					<span class="flex-1">{{ $item->label }}</span>

					@if ($item->children)
						<i class="fa-light fa-chevron-down pl-2"></i>
					@endif
				</a>

				@if ($item->children)
					<ul
						class="js-sub-menu ease-base invisible mb-2 list-none px-3 transition-all duration-300 group-[.js-show-sub-menu]:!visible">
						@foreach ($item->children as $child)
							<li
								class="h-0 opacity-0 transition-all duration-300 group-[.js-show-sub-menu]:h-8 group-[.js-show-sub-menu]:opacity-100">
								<a href="{{ $child->url }}" @class([
									$child->classes,
									'p-2 text-base text-gray-700 text-black no-underline',
									'text-primary' => $child->active,
								])>
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
