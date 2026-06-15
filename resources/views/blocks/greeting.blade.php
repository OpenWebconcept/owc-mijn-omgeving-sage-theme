<h1 class="wp-block-theme-greeting">
	@if (!is_null($userModel) && !is_null($userDisplayName) && $userModel->isLoggedIn())
		{!! sprintf('%s, %s', $greeting, $userDisplayName) !!}
	@else
		{!! $greeting !!}
	@endif
</h1>
