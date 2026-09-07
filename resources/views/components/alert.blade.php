<div
	role="{{ $alertRole() }}"
	aria-live="{{ $alertLive() }}"
	@class([
		'border-l-4 p-4 my-6 flex items-center gap-3',
		$alertClasses(),
		$attributes->get('class'),
	])
>
	<x-icon :name="$alertIcon()" :class="'size-6 shrink-0 ' . $alertIconColor()" />
	<div>
		{{ $slot }}
	</div>
</div>
