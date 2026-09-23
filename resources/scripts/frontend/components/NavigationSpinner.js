import { __ } from '@wordpress/i18n';

const SHOW_DELAY_MS = 750;
const TIMEOUT_MS = 7500;
const BASE_CLASS = 'owc-navigation-spinner';
const TRANSITION_CLASS = `${ BASE_CLASS }-transition`;
const SVG_NAMESPACE = 'http://www.w3.org/2000/svg';

const ICON_PATH =
	'M20.5 12A8.5 8.5 0 1 1 12 3.5s1 0 1 1-1 1-1 1a6.5 6.5 0 1 0 6.5 6.5s0-1 1-1 1 1 1 1';

let container = null;
let label = null;
let showTimer = null;
let timeoutTimer = null;
let listenersRegistered = false;
let clickListenerRegistered = false;

const createIcon = () => {
	const icon = document.createElementNS( SVG_NAMESPACE, 'svg' );

	icon.setAttribute( 'class', `${ BASE_CLASS }__icon` );
	icon.setAttribute( 'viewBox', '0 0 24 24' );
	icon.setAttribute( 'fill', 'none' );
	icon.setAttribute( 'focusable', 'false' );
	icon.setAttribute( 'aria-hidden', 'true' );

	const path = document.createElementNS( SVG_NAMESPACE, 'path' );

	path.setAttribute( 'fill', 'currentColor' );
	path.setAttribute( 'd', ICON_PATH );

	icon.appendChild( path );

	return icon;
};

const clearTimers = () => {
	window.clearTimeout( showTimer );
	window.clearTimeout( timeoutTimer );
	showTimer = null;
	timeoutTimer = null;
};

export const hideNavigationSpinner = () => {
	clearTimers();

	if ( ! container ) {
		return;
	}

	container.hidden = true;
	label.textContent = '';
};

const registerLifecycleListeners = () => {
	if ( listenersRegistered ) {
		return;
	}

	listenersRegistered = true;

	window.addEventListener( 'pagehide', hideNavigationSpinner );

	window.addEventListener( 'pageshow', ( event ) => {
		if ( event.persisted ) {
			hideNavigationSpinner();
		}
	} );
};

const ensureContainer = () => {
	if ( container ) {
		return;
	}

	container = document.createElement( 'div' );
	container.className = BASE_CLASS;
	container.setAttribute( 'role', 'status' );
	container.setAttribute( 'aria-live', 'polite' );
	container.hidden = true;

	label = document.createElement( 'span' );
	label.className = `${ BASE_CLASS }__label`;

	container.append( createIcon(), label );
	document.body.appendChild( container );

	registerLifecycleListeners();
};

const updateLabel = ( text ) => {
	const prefersReducedMotion = window.matchMedia(
		'(prefers-reduced-motion: reduce)'
	).matches;

	if ( ! ( 'startViewTransition' in document ) || prefersReducedMotion ) {
		label.textContent = text;
		return;
	}

	document.documentElement.classList.add( TRANSITION_CLASS );

	const transition = document.startViewTransition( () => {
		label.textContent = text;
	} );

	transition.finished.finally( () => {
		document.documentElement.classList.remove( TRANSITION_CLASS );
	} );
};

export const showNavigationSpinner = () => {
	ensureContainer();
	clearTimers();

	container.hidden = true;
	label.textContent = '';

	showTimer = window.setTimeout( () => {
		container.hidden = false;
		label.textContent = __( 'Bezig met laden…', 'owc-mijn-services' );
	}, SHOW_DELAY_MS );

	timeoutTimer = window.setTimeout( () => {
		container.hidden = false;
		updateLabel(
			__( 'Het laden duurt langer dan verwacht.', 'owc-mijn-services' )
		);
	}, TIMEOUT_MS );
};

const withoutHash = ( url ) => url.split( '#' )[ 0 ];

const startsNavigation = ( event, anchor ) => {
	if ( event.defaultPrevented || 0 !== event.button ) {
		return false;
	}

	if ( event.metaKey || event.ctrlKey || event.shiftKey || event.altKey ) {
		return false;
	}

	const target = anchor.getAttribute( 'target' );

	if ( target && '_self' !== target ) {
		return false;
	}

	if ( anchor.hasAttribute( 'download' ) ) {
		return false;
	}

	const href = anchor.getAttribute( 'href' );

	if ( ! href || href.startsWith( '#' ) ) {
		return false;
	}

	let url;

	try {
		url = new URL( href, window.location.href );
	} catch {
		return false;
	}

	if ( url.origin !== window.location.origin ) {
		return false;
	}

	return (
		! url.hash ||
		withoutHash( url.href ) !== withoutHash( window.location.href )
	);
};

const handleDocumentClick = ( event ) => {
	const anchor = event.target?.closest?.( 'a[href]' );

	if ( anchor && startsNavigation( event, anchor ) ) {
		showNavigationSpinner();
	}
};

export default () => {
	if ( clickListenerRegistered ) {
		return;
	}

	clickListenerRegistered = true;

	document.addEventListener( 'click', handleDocumentClick );
};
