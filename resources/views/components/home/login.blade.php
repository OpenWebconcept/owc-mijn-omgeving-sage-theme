@if ($isActive)
	<div class="flex flex-col border border-gray-200 bg-white p-6 sm:items-center sm:text-center">
		<header class="flex h-full justify-between gap-4 sm:flex-col sm:items-center">
			<div class="flex h-full flex-col sm:items-center sm:justify-evenly">
				<h3>{{ $title }}</h3>
				<p class="leading-normal">{!! $label !!}</p>
			</div>
			<img src="{{ $image }}" class="size-12 sm:order-first sm:mb-6 sm:size-20" alt="">
		</header>

		@if ($loginBlock = $getLoginBlock())
			{!! $loginBlock !!}
		@else
			<a href="{{ $url }}" class="is-button w-fit after:content-none">
				{{ $buttonText }}
			</a>
		@endif
	</div>
@endif
