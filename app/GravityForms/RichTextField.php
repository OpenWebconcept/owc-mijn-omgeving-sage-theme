<?php

declare(strict_types=1);

namespace OWC\MijnOmgeving\GravityForms;

use GF_Field;
use GFCommon;

/**
 * A display-only field type that renders admin-authored rich (HTML) content
 * on the front end, editable via a WYSIWYG (TinyMCE) editor in the Form
 * Editor's field settings sidebar (see OWC\MijnOmgeving\GravityForms\GravityForms
 * for the sidebar markup/JS that writes into $this->content).
 *
 * Modelled on Gravity Forms' native GF_Field_HTML.
 */
class RichTextField extends GF_Field
{
	public $type = 'rich_text';

	public function get_form_editor_field_title(): string
	{
		return esc_attr__('Tekstblok (rich text)', 'sage');
	}

	public function get_form_editor_field_description(): string
	{
		return esc_attr__('Plaatst een blok opgemaakte tekst (WYSIWYG) op een willekeurige plek in het formulier.', 'sage');
	}

	public function get_form_editor_field_icon(): string
	{
		// Reuses Gravity Forms' own admin icon font, no new asset needed.
		return 'gform-icon--html-code';
	}

	/**
	 * @return array<string, string>
	 */
	public function get_form_editor_button(): array
	{
		return [
			'group' => 'standard_fields',
			'text' => $this->get_form_editor_field_title(),
			'icon' => $this->get_form_editor_field_icon(),
			'description' => $this->get_form_editor_field_description(),
		];
	}

	/**
	 * @return string[]
	 */
	public function get_form_editor_field_settings(): array
	{
		return [
			// Our own custom setting - NOT Gravity Forms' built-in 'content_setting',
			// which is a hardcoded plain textarea already wired up by GF core JS.
			'rich_text_content_setting',
			'disable_margins_setting',
			'conditional_logic_field_setting',
			'label_setting',
			'css_class_setting',
			'visibility_setting',
		];
	}

	public function get_field_input($form, $value = '', $entry = null): string
	{
		if ($this->is_entry_detail() || $this->is_form_editor()) {
			return sprintf(
				'<div class="gf-html-container"><span class="gf_blockheader">%s</span><span>%s</span></div>',
				esc_html__('Tekstblok (rich text)', 'sage'),
				esc_html__('De inhoud wordt hier niet getoond. Bekijk het formulier om de inhoud te zien.', 'sage')
			);
		}

		$content = GFCommon::replace_variables_prepopulate((string) $this->content);

		return $this->doShortcode($content);
	}

	public function get_field_content($value, $force_frontend_label, $form): string
	{
		if (! $this->is_entry_detail() && ! $this->is_form_editor()) {
			return '{FIELD}';
		}

		$formId = $form['id'];
		$fieldLabel = $this->get_field_label($force_frontend_label, $value);
		$fieldId = 0 === $formId ? "input_{$this->id}" : "input_{$formId}_{$this->id}";

		return sprintf(
			"<label class='gfield_label gform-field-label' for='%s'>%s</label>{FIELD}",
			esc_attr($fieldId),
			esc_html($fieldLabel)
		);
	}

	public function sanitize_settings(): void
	{
		parent::sanitize_settings();

		$this->content = GFCommon::maybe_wp_kses((string) $this->content);
	}

	private function doShortcode(string $content): string
	{
		if (isset($GLOBALS['wp_embed'])) {
			$content = $GLOBALS['wp_embed']->run_shortcode($content);
		}

		return do_shortcode($content);
	}
}
