@php($menu = \Log1x\Navi\Navi::make()->build('primary_navigation'))

@if ($menu->isNotEmpty())
	<ul class="js-menu-primary align-center mb-0 flex h-full list-none justify-center pl-0">
		@foreach ($menu->all() as $item)
			<li @class(['group', 'js-menu-item-is-expandable' => $item->children])>
				<a href="{{ $item->url }}" @class([
					$item->classes,
					'relative flex h-full items-center px-4 text-center font-(family-name:--nav-menu-item-font-family) leading-snug no-underline text-(--nav-menu-item-color) hocus:text-(--nav-menu-item-color-hover) text-(length:--nav-menu-item-font-size) font-(weight:--nav-menu-item-font-weight)',
					'text-(--nav-menu-item-color-active) font-(weight:--nav-menu-item-font-weight-active) ' =>
						$item->active || $item->activeParent,
				])>
					{{ $item->label }}

					@if ($item->children)
						<i class="fa-light fa-chevron-down pl-2"></i>
					@endif

					<span @class([
						'bg-(--nav-menu-item-before-bg-color) ease-base absolute bottom-(--nav-menu-item-before-bottom) left-2 h-(--nav-menu-item-before-height) w-[calc(100%-1rem)] duration-300 xl:left-4 xl:w-[calc(100%-2rem)] invisible scale-0 group-hocus:visible group-hocus:scale-100',
						'visible scale-100' => $item->active || $item->activeParent,
					]) aria-hidden="true"></span>
				</a>

				@if ($item->children)
					<ul
						class="js-sub-menu ease-base invisible absolute mb-0 min-w-48 -translate-y-3 list-none bg-white pl-0 opacity-0 shadow-md transition-all group-[.js-show-sub-menu]:visible group-[.js-show-sub-menu]:translate-y-0 group-[.js-show-sub-menu]:opacity-100">
						@foreach ($item->children as $child)
							<li>
								<a href="{{ $child->url }}" @class([
									$child->classes,
									'flex items-center px-6 py-3 text-left leading-snug text-inherit no-underline group',
									'text-primary' => $child->active,
								])>
									{{ $child->label }}
									<i class="fa-light fa-angle-right ml-auto pl-6 transition-all group-hover:translate-x-1"></i>
								</a>
							</li>
						@endforeach
					</ul>
				@endif
			</li>
		@endforeach
	</ul>
@endif
