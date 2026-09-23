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
	FocusStyle,
	WebShareApi,
} from '@yardinternet/brave-frontend-kit';
import A11yToolbar from '@yardinternet/a11y-toolbar';

/**
 * Internal dependencies
 */
import NavigationSpinner from './components/NavigationSpinner';
import Openkaarten from './components/Openkaarten';

import iconVolume from '../../images/icons/volume-2.svg?raw';
import iconTextSize from '../../images/icons/a-large-small.svg?raw';
import iconContrast from '../../images/icons/contrast.svg?raw';
import iconPrinter from '../../images/icons/printer.svg?raw';
import iconBook from '../../images/icons/book-a.svg?raw';

/**
 * Application entrypoint
 */
window.addEventListener( 'DOMContentLoaded', () => {
	A11yCookieYes.getInstance();
	new A11yCards();
	new A11yMobileMenu();
	new A11yToolbar( '.js-a11y-toolbar', {
		showContrastButton: false,
		showTextSizeButton: false,
		showReadSpeakerButton: false,
		showPrintButton: false,
		showDeepLButton: window.theme?.is_deepl_enabled ?? false,
		showLanguageButton: false,
		iconOptions: {
			/* Unlike the others, this one only accepts a class; it is masked in CSS. */
			toggleIcon: 'a11y-toolbar__toggle-icon',
			readSpeakerIcon: iconVolume,
			textSizeIcon: iconTextSize,
			contrastIcon: iconContrast,
			printIcon: iconPrinter,
			openDyslexicIcon: iconBook,
		},
	} ).init();
	new Accordion();
	new BraveDialogManager();
	new BraveNavigationManager();
	new FocusStyle();
	new WebShareApi();

	NavigationSpinner();
	Openkaarten();
} );
