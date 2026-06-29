export default () => {
	const init = () => {
		if ( ! hasUserLocation() ) {
			return;
		}

		window.addEventListener( 'openkaarten:map-ready', handleMapReady );
	};

	const handleMapReady = () => {
		addHomeMarker();
	};

	const addHomeMarker = () => {
		const { lat, lng } = getUserLocation();

		window.openkaarten.addMarker( {
			lat,
			lng,
			flyTo: true,
			flyToOptions: {
				zoom: 12,
				duration: 2,
			},
			popup: 'Thuisadres',
			markerOptions: {
				icon: createHomeIcon(),
				zIndexOffset: 10_000,
			},
		} );
	};

	const getUserLocation = () => {
		const { lat, lng } = window.yardOpenkaarten.userLocation;

		return {
			lat: Number( lat ),
			lng: Number( lng ),
		};
	};

	const createHomeIcon = () =>
		window.L.divIcon( {
			className: 'yard-map-marker',
			html: buildMarkerSvg(),
			iconSize: [ 46, 46 ],
			iconAnchor: [ 23, 23 ],
		} );

	const buildMarkerSvg = () => `
		<svg xmlns="http://www.w3.org/2000/svg" width="46" height="46" viewBox="0 0 48 48">
			<defs>
				<filter id="yardCircleShadow" x="-50%" y="-50%" width="200%" height="200%">
					<feDropShadow dx="0" dy="2" stdDeviation="2" flood-opacity="0.25"/>
				</filter>
			</defs>

			<circle
				cx="24"
				cy="24"
				r="24"
				fill="var(--color-primary)"
				filter="url(#yardCircleShadow)"
			/>

			<path
				d="M24 14 L12 24 H16 V34 H22 V28 H26 V34 H32 V24 H36 Z"
				fill="white"
			/>
		</svg>
	`;

	const hasUserLocation = () => {
		return Boolean(
			window.yardOpenkaarten?.userLocation?.lat &&
				window.yardOpenkaarten?.userLocation?.lng
		);
	};

	init();
};
