<x-nlds.denhaag.side-navigation.list {{ $attributes }}>
	@foreach ($menu as $item)
		<x-nlds.denhaag.side-navigation.item :href="$item->url" :is-active="$item->active" :title="$item->label" :iconHtml="$getMenuItemIcon($item)" />
	@endforeach

	@if ($hasLogout && $logoutUrl)
		<li>
			<hr class="denhaag-divider text-gray-300" role="presentation">
		</li>
		<x-nlds.denhaag.side-navigation.item :href="$logoutUrl" title="Uitloggen"
			:iconHtml="\OWC\MijnOmgeving\Helpers\Icon::render('log-out', 'denhaag-icon w-5')" />
	@endif
</x-nlds.denhaag.side-navigation.list>
