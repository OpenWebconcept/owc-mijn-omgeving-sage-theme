<?php

declare(strict_types=1);

namespace OWC\MijnOmgeving\Hooks;

use OWC\MijnOmgeving\Csp\NoncedStyles;
use OWC\MijnOmgeving\Csp\Policy;
use Yard\Hook\Action;
use Yard\Hook\Filter;
use WP_Styles;

class Security
{
	/**
	 * Overrides the Referrer-Policy set by Yard\Brave\Hooks\Security, DigiD norm U/PW.03 requires 'no-referrer' or 'same-origin'.
	 */
	#[Action('send_headers', 20)]
	public function sendReferrerPolicyHeader(): void
	{
		header('Referrer-Policy: same-origin', true);
	}

	#[Action('wp_enqueue_scripts', PHP_INT_MAX)]
	public function addNonceToInlineStyles(): void
	{
		global $wp_styles;

		if (! Policy::restrictsInlineSources() || ! $wp_styles instanceof WP_Styles || $wp_styles instanceof NoncedStyles) {
			return;
		}

		$wp_styles = NoncedStyles::fromStyles($wp_styles);
	}

	/**
	 * The Den Haag components (used by owc-mijn-services) read window.NONCE for the styles they inject.
	 */
	#[Action('wp_head', 1)]
	public function exposeNonceForInjectedStyles(): void
	{
		if (! Policy::restrictsInlineSources()) {
			return;
		}

		wp_print_inline_script_tag('window.NONCE = document.currentScript.nonce;');
	}

	/**
	 * Gravity Forms AJAX submissions render in an iframe (with its own nonce) and copy the
	 * markup into the page, where jQuery executes the scripts. Scripts carrying the iframe's
	 * genuine nonce get the nonce of the page, before Gravity Forms copies them.
	 * Also hides the live regions of Gravity Forms, which are hidden through a blocked style attribute.
	 */
	#[Action('wp_enqueue_scripts')]
	public function addGravityFormsCompatibility(): void
	{
		if (! Policy::restrictsInlineSources() || ! wp_script_is('gform_gravityforms', 'registered')) {
			return;
		}

		wp_add_inline_script(
			'gform_gravityforms',
			<<<'JS'
			(function () {
				var nonce = document.currentScript && document.currentScript.nonce;
				if (!nonce) return;
				document.addEventListener('load', function (event) {
					var frame = event.target;
					if (!frame || frame.tagName !== 'IFRAME' || frame.id.indexOf('gform_ajax_frame_') !== 0) return;
					var frameNonce = frame.contentWindow && frame.contentWindow.owcCspNonce;
					if (!frameNonce) return;
					frame.contentDocument.querySelectorAll('script').forEach(function (script) {
						if (script.nonce === frameNonce) script.setAttribute('nonce', nonce);
					});
				}, true);
			})();
			JS,
			'before'
		);

		wp_register_style('owc-gravityforms-csp', false);
		wp_enqueue_style('owc-gravityforms-csp');
		wp_add_inline_style(
			'owc-gravityforms-csp',
			'body > div[aria-live][aria-relevant="additions text"][aria-atomic="true"]{position:absolute;margin:-1px;padding:0;height:1px;width:1px;overflow:hidden;clip:rect(1px,1px,1px,1px);clip-path:inset(50%);border:0;word-wrap:normal!important}'
		);
	}

	/**
	 * Only a script with the genuine nonce can execute, so the nonce it exposes can be trusted by the script above.
	 */
	#[Filter('gform_get_form_filter')]
	public function exposeNonceInGravityForms(string $formString): string
	{
		if (! Policy::restrictsInlineSources()) {
			return $formString;
		}

		return wp_get_inline_script_tag('window.owcCspNonce = document.currentScript.nonce;') . $formString;
	}

	/**
	 * Gravity Forms Zero Spam prints its config as an inline script without nonce.
	 */
	#[Filter('script_loader_tag', 20)]
	public function addNonceToZeroSpamConfig(string $tag, string $handle): string
	{
		if ('gf-zero-spam' !== $handle) {
			return $tag;
		}

		return str_replace(
			"<script type='text/javascript'>var gfZeroSpamConfig",
			sprintf("<script type='text/javascript' nonce='%s'>var gfZeroSpamConfig", esc_attr(csp_nonce())),
			$tag
		);
	}
}
