/**
 * External dependencies
 */
import A11yCookieYes from '@yardinternet/a11y-cookie-yes';
import { FocusStyle, WebShareApi } from '@yardinternet/brave-frontend-kit';

/**
 * Internal dependencies
 */
import Accordion from './components/Accordion';
import Cards from './components/Cards';
import Dialog from './components/Dialog';
import Dropdown from './components/Dropdown';
import MobileMenu from './components/MobileMenu';
import Navigation from './components/Navigation';

/**
 * Application entrypoint
 */
window.addEventListener( 'DOMContentLoaded', () => {
	A11yCookieYes.getInstance();
	Accordion();
	Cards();
	Dialog();
	Dropdown();
	new FocusStyle();
	MobileMenu();
	Navigation();
	new WebShareApi();
} );

/**
 * @see {@link https://webpack.js.org/api/hot-module-replacement/}
 */
// eslint-disable-next-line no-console
import.meta.webpackHot?.accept( console.error );
