/**
 * External dependencies
 */
import A11yCookieYes from '@yardinternet/a11y-cookie-yes';
import {
	A11yCards,
	A11yMobileMenu,
	Accordion,
	BraveNavigationManager,
	BraveDialogManager,
	WebShareApi,
} from '@yardinternet/brave-frontend-kit';

/**
 * Internal dependencies
 */
import Openkaarten from './components/Openkaarten';

/**
 * Application entrypoint
 */
window.addEventListener( 'DOMContentLoaded', () => {
	A11yCookieYes.getInstance();
	new A11yCards();
	new A11yMobileMenu();
	new Accordion();
	new BraveDialogManager();
	new BraveNavigationManager();
	new WebShareApi();

	Openkaarten();
} );
