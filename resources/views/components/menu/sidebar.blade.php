<x-nlds.denhaag.side-navigation.list {{ $attributes }}>
    @foreach ($menu as $item)
        <x-nlds.denhaag.side-navigation.item @class([
	        'mt-(--sidebar-separator-spacing)' => $getMarginAbove($item),
        ]) :href="$item->url" :is-active="$item->active" :title="$item->label"
                                             :iconHtml="$getMenuItemIcon($item)" />
    @endforeach

    @if ($hasLogout && $logoutUrl)
        <x-nlds.denhaag.side-navigation.item class="mt-(--sidebar-separator-spacing)" :href="$logoutUrl"
                                             title="Uitloggen"
                                             :iconHtml="\OWC\MijnOmgeving\Helpers\Icon::render('log-out', 'w-5')" />
    @endif
</x-nlds.denhaag.side-navigation.list>
