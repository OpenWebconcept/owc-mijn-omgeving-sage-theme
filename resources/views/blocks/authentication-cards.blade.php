<div {!! get_block_wrapper_attributes() !!}>
	<div class="{{ $gridCols }} grid gap-6">
		@if ($displayDigid ?? true)
			<x-home-login type="digid" :provider="$provider" />
		@endif
		@if ($displayEherkenning ?? true)
			<x-home-login type="eherkenning" :provider="$provider" />
		@endif
		@if ($displayEidas ?? true)
			<x-home-login type="eidas" :provider="$provider" />
		@endif
	</div>
</div>
