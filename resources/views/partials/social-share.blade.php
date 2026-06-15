<div class="flex flex-wrap items-center gap-2">
	<p class="mb-0 mr-2">Delen via:</p>
	@php
		$class =
		    'text-primary-500 hocus:bg-primary-500 bg-primary-100 hocus:text-white flex size-10 items-center justify-center rounded-theme no-underline transition-[transform,visibility] duration-300';
	@endphp
	<x-brave-social-icon type="x-twitter" :class="$class" />
	<x-brave-social-icon type="facebook" :class="$class" />
	<x-brave-social-icon type="linkedin" :class="$class" />
	<x-brave-social-icon type="whatsapp" :class="$class" />
	<x-brave-social-icon type="mail" :class="$class" />
	<x-brave-social-icon type="web-share-api" :class="$class" />
</div>
