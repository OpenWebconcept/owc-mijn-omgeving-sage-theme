<x-layout>
	<div class="container my-10 md:my-16">
		<div class="normalize-child-margin rounded-theme mx-auto text-center md:w-3/4 lg:w-3/5">
			<h1>404</h1>
			<p class="text-(length:--h2-font-size)"><strong>Oeps</strong>, dit was niet de bedoeling!</p>
			<p class="mt-6 text-lg">De opgevraagde pagina is niet (meer) beschikbaar. We hebben onze website vernieuwd, het kan
				zijn dat de pagina die u zoekt niet meer bestaat.
			</p>

			<div class="flex justify-center gap-4">
				<a href="{{ home_url() }}" class="is-button">
					Naar de startpagina
				</a>
			</div>
		</div>
	</div>
</x-layout>
