@props([
    'icon' => 'info',
    'text' => '',
    'url' => '',
])

<li {{ $attributes->merge(['class' => 'meta-item flex items-baseline gap-x-4 gap-y-2']) }}>
	<x-icon :name="$icon" class="text-primary size-5 shrink-0" />

	@if ($url)
		<a href="{{ $url }}" class="hocus:underline text-inherit no-underline">
			{!! $text !!}
		</a>
	@else
		{!! $text !!}
	@endif
</li>
