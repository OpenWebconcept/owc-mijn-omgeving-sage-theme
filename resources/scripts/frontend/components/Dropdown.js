/**
 * External dependencies
 */
import * as focusTrap from 'focus-trap';

/**
 * Internal dependencies
 */
import { checkCanFocusTrap } from '@yardinternet/brave-frontend-kit';

export default () => {
	const events = () => {
		const dropdowns = Array.from(
			document.querySelectorAll( '.js-dropdown' )
		);

		if ( dropdowns.length === 0 ) return;

		dropdowns.forEach( ( dropdown ) => {
			const trigger = dropdown.querySelector( '.js-dropdown-trigger' );
			const menu = dropdown.querySelector( '.js-dropdown-menu' );

			if ( ! trigger || ! menu ) return;

			initDropdown( menu, trigger );
		} );
	};

	const initDropdown = ( menu, trigger ) => {
		const focusTrapDropdown = focusTrap.createFocusTrap( menu, {
			clickOutsideDeactivates: true,
			allowOutsideClick: true,
			checkCanFocusTrap,

			onActivate: () => {
				menu.classList.remove( '!hidden' );
				menu.classList.add( '!block' );
				trigger.setAttribute( 'aria-expanded', 'true' );
				trigger.setAttribute( 'aria-label', 'Sluit menu' );
			},
			onDeactivate: () => {
				menu.classList.add( '!hidden' );
				menu.classList.remove( '!block' );
				trigger.setAttribute( 'aria-expanded', 'false' );
				trigger.setAttribute( 'aria-label', 'Open menu' );
			},
		} );

		trigger.addEventListener( 'click', ( event ) => {
			event.preventDefault();

			focusTrapDropdown.activate();
		} );
	};

	events();
};
