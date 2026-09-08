<?php

declare(strict_types=1);

namespace OWC\MijnOmgeving\GravityForms;

use GF_Field;
use GF_Fields;
use GFCommon;
use Yard\Hook\Action;
use Yard\Hook\Filter;

/**
 * Gravity Forms customizations.
 *
 * Rich text field: adds an opt-in "Rich Text" field type (TinyMCE), authored
 * in the Form Editor and displayed as static HTML on the front end - like
 * Gravity Forms' native HTML field, but WYSIWYG instead of plain/code. Gated
 * behind a site-level toggle (Forms > Settings): this only affects whether the
 * field can be newly added, never already-placed instances.
 *
 * GP Limit Dates: replaces the perk's generic "Invalid Date" / "Please enter
 * a valid date." texts with the field's own "Custom Validation Message"
 * setting when one is set, both in the datepicker UI and in server-side
 * validation.
 */
class GravityForms
{
	private const FIELD_TYPE = 'rich_text';
	private const SETTING_CLASS = 'rich_text_content_setting';
	private const SETTING_ID = 'field_rich_text_content';
	private const GLOBAL_OPTION = 'sage_gravityforms_rich_text_editor_enabled';

	/**
	 * Add the site-level toggle to the main Gravity Forms > Settings page.
	 */
	#[Filter('gform_plugin_settings_fields', 10)]
	public function addGlobalSetting(array $fields): array
	{
		// Only admins are allowed to enable/disable this feature.
		if (! current_user_can('manage_options')) {
			return $fields;
		}

		$fields[] = [
			'id' => 'section_frontend_rich_text_editor',
			'title' => esc_html__('Rich Text Editor', 'sage'),
			'description' => esc_html__('Voegt een nieuw "Tekstblok (rich text)" veldtype toe aan de formulierbouwer.', 'sage'),
			'fields' => [
				[
					'name' => self::GLOBAL_OPTION,
					'type' => 'toggle',
					'toggle_label' => esc_html__('Activeer tekstblok (rich text) veldtype', 'sage'),
					'default_value' => $this->isGloballyEnabled(),
					'save_callback' => static function ($field, $value) {
						update_option(self::GLOBAL_OPTION, (bool) $value, false);

						return $value;
					},
				],
			],
		];

		return $fields;
	}

	/**
	 * Registers the field type. Runs on `init`, not `gform_loaded`: Gravity
	 * Forms fires that during `plugins_loaded`, before this theme's hook
	 * attributes are wired up (on `after_setup_theme`), so it would never fire.
	 *
	 * Unconditional so existing instances keep working if the toggle above
	 * is switched off later - hideFieldButtonWhenDisabled() handles that.
	 */
	#[Action('init', 10)]
	public function registerRichTextField(): void
	{
		if (! class_exists(GF_Fields::class) || GF_Fields::exists(self::FIELD_TYPE)) {
			return;
		}

		GF_Fields::register(new RichTextField());
	}

	/**
	 * Hides the "Rich Text" palette button when the toggle is off, without
	 * unregistering the type - existing instances of the field keep working.
	 */
	#[Filter('gform_add_field_buttons', 10)]
	public function hideFieldButtonWhenDisabled(array $fieldGroups): array
	{
		if ($this->isGloballyEnabled()) {
			return $fieldGroups;
		}

		foreach ($fieldGroups as &$group) {
			if (! is_array($group['fields'] ?? null)) {
				continue;
			}

			$group['fields'] = array_values(array_filter(
				$group['fields'],
				static fn (array $button): bool => self::FIELD_TYPE !== ($button['data-type'] ?? null)
			));
		}

		// Unset the reference to avoid accidental modifications later in the code.
		unset($group);

		return $fieldGroups;
	}

	/**
	 * Loads wp.editor.initialize()'s scripts on the form editor screen.
	 * Gravity Forms doesn't load these itself; see addRichTextContentSetting()
	 * for why the editor is initialized lazily rather than via `wp_editor()`.
	 */
	#[Action('admin_enqueue_scripts', 10)]
	public function enqueueRichTextEditorAssets(): void
	{
		if (! class_exists(GFCommon::class) || ! GFCommon::is_form_editor()) {
			return;
		}

		wp_enqueue_editor();
	}

	/**
	 * Renders a hidden, otherwise-unused `wp_editor()` instance, so
	 * TinyMCE's one-time, page-wide bootstrap (skin/plugin/theme setup) runs
	 * through its normal, well-tested page-load path instead of being
	 * triggered cold by our own lazily-initialized editor - see
	 * addRichTextContentSettingScript() for why that first-ever
	 * initialization is otherwise unreliable if the user interacts right
	 * after the page loads.
	 */
	#[Action('admin_footer', 10)]
	public function warmUpTinyMceBootstrap(): void
	{
		if (! class_exists(GFCommon::class) || ! GFCommon::is_form_editor()) {
			return;
		}

		echo '<div style="display:none">';
		wp_editor('', self::SETTING_ID . '_warmup', [
			'textarea_name' => self::SETTING_ID . '_warmup',
			'media_buttons' => false,
			'teeny' => true,
		]);
		echo '</div>';
	}

	/**
	 * Adds the content setting (a plain textarea, TinyMCE-enhanced lazily in
	 * JS) for the "Rich Text" field type's sidebar.
	 *
	 * Not Gravity Forms' built-in "content_setting": that's a hardcoded plain
	 * textarea already wired up by core JS, which would fight with our own
	 * TinyMCE instance. And not a plain `wp_editor()` call either: Gravity
	 * Forms keeps every field setting hidden except the active field's, so
	 * `wp_editor()` here would init TinyMCE while still hidden - leaving it
	 * visually present but non-interactive. addRichTextContentSettingScript()
	 * initializes it lazily instead, once the settings are actually visible.
	 */
	#[Action('gform_field_standard_settings', 10)]
	public function addRichTextContentSetting(int $position): void
	{
		if (50 !== $position) {
			return;
		}

		?>
		<li class="<?php echo esc_attr(self::SETTING_CLASS); ?> field_setting">
			<label for="<?php echo esc_attr(self::SETTING_ID); ?>" class="section_label">
				<?php esc_html_e('Inhoud', 'sage'); ?>
			</label>
			<textarea id="<?php echo esc_attr(self::SETTING_ID); ?>" name="<?php echo esc_attr(self::SETTING_ID); ?>" rows="10" class="widefat"></textarea>
		</li>
		<?php
	}

	/**
	 * Make the setting visible for the "Rich Text" field type and keep the
	 * TinyMCE editor in sync with the field's `content` property in both
	 * directions.
	 *
	 * Gravity Forms outputs the `gform_editor_js` action outside of its own
	 * <script> block (see js.php), so this must open its own <script> tag or
	 * the JS below is never executed.
	 */
	#[Action('gform_editor_js', 10)]
	public function addRichTextContentSettingScript(): void
	{
		?>
		<script type="text/javascript">
			fieldSettings.<?php echo self::FIELD_TYPE; ?> += ', .<?php echo esc_js(self::SETTING_CLASS); ?>';

			(function () {
				var editorId = '<?php echo esc_js(self::SETTING_ID); ?>';
				var editorInitialized = false;

				function setEditorContent(content) {
					content = content || '';
					var editor = window.tinymce && window.tinymce.get(editorId);
					if (editor && ! editor.isHidden()) {
						editor.setContent(content);
					} else {
						var textarea = document.getElementById(editorId);
						if (textarea) {
							textarea.value = content;
						}
					}
				}

				// TinyMCE initialized while hidden (display:none) renders but
				// stays non-interactive, so this only ever runs once the
				// settings panel is actually visible.
				function ensureEditorInitialized() {
					if (editorInitialized || ! window.wp || ! wp.editor) {
						return;
					}
					editorInitialized = true;

					wp.editor.initialize(editorId, {
						tinymce: true,
						quicktags: true,
						mediaButtons: false,
					});
				}

				// Field selected in the editor: push its stored content into the editor.
				// GF fires this via jQuery(document).trigger() (form_editor.js), never as
				// a native DOM event, so this listener has to stay on jQuery.
				jQuery(document).on('gform_load_field_settings', function (event, field) {
					if (field.type !== '<?php echo esc_js(self::FIELD_TYPE); ?>') {
						return;
					}
					// Set the textarea's value before initializing: TinyMCE reads
					// its initial content from the textarea at init time.
					setEditorContent(field.content);

					// This event fires from inside GF's ShowSettings(), before
					// that function reveals the settings panel itself (its
					// .show()/tab-switch calls come later in the same function) -
					// so the panel is still hidden this tick. Defer to the next
					// tick, once it's actually visible.
					setTimeout(ensureEditorInitialized, 0);
				});

				// Same reason as above: WP core (tinymce/plugins/wordpress/plugin.js)
				// fires this via jQuery's triggerHandler(), so jQuery stays here too.
				jQuery(document).on('tinymce-editor-init', function (event, editor) {
					if (editor.id !== editorId) {
						return;
					}
					editor.on('change keyup input', function () {
						SetFieldProperty('content', editor.getContent());
					});
				});

				// Text/QuickTags tab -> field.content
				function handleTextareaInput(event) {
					if (event.target.id !== editorId) {
						return;
					}
					var editor = window.tinymce && window.tinymce.get(editorId);
					if (! editor || editor.isHidden()) {
						SetFieldProperty('content', event.target.value);
					}
				}
				document.addEventListener('input', handleTextareaInput);
				document.addEventListener('change', handleTextareaInput);

				// Gravity Forms binds a click handler on .field_settings (an
				// ancestor of this <li>) that calls event.stopPropagation(),
				// so a bubbling-phase document listener never sees the click.
				// Capture-phase runs top-down before that handler can stop it.
				document.addEventListener('click', function (event) {
					var button = event.target.closest('.<?php echo esc_js(self::SETTING_CLASS); ?> .wp-switch-editor');
					if (! button || ! window.switchEditors) {
						return;
					}
					var mode = button.classList.contains('switch-tmce') ? 'tmce' : 'html';
					window.switchEditors.go(editorId, mode);
				}, true);
			})();
		</script>
		<?php
	}

	/**
	 * GP Limit Dates: expose a date field's custom validation message to the
	 * frontend by adding it to the per-field options that the perk localizes
	 * for its datepicker script (see useFieldErrorMessageForInvalidDate()).
	 */
	#[Filter('gpld_limit_dates_options')]
	public function addFieldErrorMessageToLimitDatesOptions(array $options, array $form): array
	{
		foreach ($form['fields'] ?? [] as $field) {
			$errorMessage = trim(wp_strip_all_tags((string) $field->errorMessage));

			if ('' === $errorMessage || ! isset($options[$field->id])) {
				continue;
			}

			$options[$field->id]['errorMessage'] = $errorMessage;
		}

		return $options;
	}

	/**
	 * GP Limit Dates: show the field's custom validation message instead of
	 * the generic "Invalid Date" text next to the datepicker.
	 *
	 * Attached to the gp-limit-dates handle so it only prints when that script
	 * is actually enqueued (forms with limited date fields) and runs after the
	 * Gravity Forms hooks API, which gp-limit-dates depends on, is available.
	 */
	#[Action('wp_enqueue_scripts')]
	public function useFieldErrorMessageForInvalidDate(): void
	{
		if (! wp_script_is('gp-limit-dates', 'registered')) {
			return;
		}

		wp_add_inline_script('gp-limit-dates', <<<'JS'
		window.gform.addFilter('gpld_invalid_date_error', function (message, fieldId, input, date, fieldData) {
			return fieldData && fieldData.errorMessage ? fieldData.errorMessage : message;
		});
		JS);
	}

	/**
	 * GP Limit Dates: when a submitted date is rejected by its date limits,
	 * show the field's custom validation message instead of the perk's
	 * hardcoded "Please enter a valid date.".
	 *
	 * The perk sets that message unconditionally (priority 10) and ignores the
	 * field's errorMessage setting, while Gravity Forms core does honor it for
	 * its own validations. Matching on the perk's exact (translated) message
	 * limits this override to failures caused by GP Limit Dates.
	 */
	#[Filter('gform_field_validation', 15)]
	public function useFieldErrorMessageForLimitedDates(array $result, string|array $value, array $form, GF_Field $field): array
	{
		if (($result['is_valid'] ?? true) || __('Please enter a valid date.', 'gp-limit-dates') !== ($result['message'] ?? '')) {
			return $result;
		}

		$errorMessage = trim((string) $field->errorMessage);

		if ('' === $errorMessage) {
			return $result;
		}

		$result['message'] = $errorMessage;

		return $result;
	}

	private function isGloballyEnabled(): bool
	{
		return (bool) get_option(self::GLOBAL_OPTION, false);
	}
}
