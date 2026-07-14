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
import Openkaarten from './components/Openkaarten';

/**
 * Application entrypoint
 */
window.addEventListener('DOMContentLoaded', () => {
	A11yCookieYes.getInstance();
	new A11yCards();
	new A11yMobileMenu();
	new A11yToolbar('.js-a11y-toolbar', {
		showContrastButton: false,
		showTextSizeButton: false,
		showReadSpeakerButton: false,
		showPrintButton: true,
		showDeepLButton: window.theme?.is_deepl_enabled ?? false,
		showLanguageButton: true,
	}).init();
	new Accordion();
	new BraveDialogManager();
	new BraveNavigationManager();
	new FocusStyle();
	new WebShareApi();

	Openkaarten();
});
