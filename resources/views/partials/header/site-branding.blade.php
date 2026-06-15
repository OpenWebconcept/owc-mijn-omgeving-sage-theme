<a href="{{ home_url() }}" aria-label="Home {{ $siteName }}"
	class="hover:text-primary flex items-center text-black no-underline transition-all">
	<img alt="Logo {{ $siteName }}" class="site-branding-logo h-(--logo-height) object-contain"
		src="{{ config('theme.logo.url') }}">
	@if (config('theme.logo.text'))
		<span class="ml-4 text-2xl font-bold no-underline">{{ config('theme.logo.text') }}</span>
	@endif
</a>
