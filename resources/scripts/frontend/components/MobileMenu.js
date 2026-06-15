/**
 * External dependencies
 */
import { disableBodyScroll, enableBodyScroll } from 'body-scroll-lock';
import * as focusTrap from 'focus-trap';

/**
 * Internal dependencies
 */
import { checkCanFocusTrap } from '@yardinternet/brave-frontend-kit';

export default () => {
	let focusTrapMobileMenu;
	const closeBtn = document.querySelector( '#js-mobile-menu-close-btn' );
	const hamburger = document.querySelector( '#js-hamburger' );
	const mobileMenu = document.querySelector( '#js-mobile-menu' );
	const expandableMenuItems = document.querySelectorAll(
		'.mobile-menu .menu-item-has-children'
	);
	const focusTrapOptions = {
		allowOutsideClick: true,
		checkCanFocusTrap,
		onActivate: () => onActivateFocusTrap(),
		onDeactivate: () => onDeactivateFocusTrap(),
	};

	const events = () => {
		if ( ! closeBtn || ! hamburger || ! mobileMenu ) return;

		focusTrapMobileMenu = focusTrap.createFocusTrap(
			mobileMenu,
			focusTrapOptions
		);

		closeBtn.addEventListener( 'click', focusTrapMobileMenu.deactivate );
		hamburger.addEventListener( 'click', () => {
			focusTrapMobileMenu.active
				? focusTrapMobileMenu.deactivate()
				: focusTrapMobileMenu.activate();
		} );

		initExpandableMenuItems();
	};

	/**
	 * Show mobile menu. Lock body scroll and add correct aria attributes.
	 */
	const onActivateFocusTrap = () => {
		disableBodyScroll( mobileMenu );

		hamburger.setAttribute( 'aria-expanded', 'true' );
		hamburger.setAttribute( 'aria-label', 'Sluit menu' );
		mobileMenu.setAttribute( 'aria-hidden', 'false' );

		mobileMenu.animate(
			[
				{
					transform: 'translateX(100%)',
					opacity: '0',
					visibility: 'hidden',
				},
				{
					transform: 'translateX(0)',
					opacity: '1',
					visibility: 'visible',
				},
			],
			{
				duration: 500,
				easing: 'cubic-bezier(0.22,1,0.36,1)',
				fill: 'both',
			}
		);
	};

	/**
	 * Hide mobile menu. Unlock body scroll and add correct aria attributes.
	 */
	const onDeactivateFocusTrap = () => {
		enableBodyScroll( mobileMenu );

		closeAllExpandableMenuItems();

		hamburger.setAttribute( 'aria-expanded', 'false' );
		hamburger.setAttribute( 'aria-label', 'Open menu' );
		mobileMenu.setAttribute( 'aria-hidden', 'true' );

		mobileMenu.animate(
			[
				{
					transform: 'translateX(0)',
					opacity: '1',
					visibility: 'visible',
				},
				{
					transform: 'translateX(100%)',
					opacity: '0',
					visibility: 'hidden',
				},
			],
			{
				duration: 500,
				easing: 'ease-out',
				fill: 'both',
			}
		);
	};

	/**
	 * Initialize expandable menu items and add necessary aria attributes.
	 */
	const initExpandableMenuItems = () => {
		expandableMenuItems.forEach( ( item ) => {
			const link = item.querySelector( 'a' );

			link.setAttribute( 'aria-haspopup', 'true' );
			link.setAttribute( 'aria-expanded', 'false' );

			link.addEventListener( 'click', ( event ) =>
				onClickExpandableMenuItem( event, item, link )
			);
		} );
	};

	/**
	 * Handle click event. Prevent opening link, add aria-expanded and toggle class.
	 *
	 * @param {Event}       event - The click event
	 * @param {Element}     item  - The expandable item element
	 * @param {HTMLElement} link  - The link element within the expandable item
	 */
	const onClickExpandableMenuItem = ( event, item, link ) => {
		event.preventDefault();

		const isOpen = link.getAttribute( 'aria-expanded' ) === 'true';
		link.setAttribute( 'aria-expanded', String( ! isOpen ) );

		item.classList.toggle( 'show-sub-menu' );
	};

	/**
	 * Close all expandable menu items. Set aria-expanded to false and remove active class.
	 */
	const closeAllExpandableMenuItems = () => {
		expandableMenuItems.forEach( ( item ) => {
			const link = item.querySelector( 'a' );
			link.setAttribute( 'aria-expanded', 'false' );
			item.classList.remove( 'show-sub-menu' );
		} );
	};

	events();
};
