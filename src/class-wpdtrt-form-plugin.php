<?php
/**
 * File: src/class-wpdtrt-form-plugin.php
 *
 * Plugin sub class.
 *
 * Since:
 *   0.9.1 - DTRT WordPress Plugin Boilerplate Generator
 */

/**
 * Class: WPDTRT_Form_Plugin
 *
 * Extends the base class to inherit boilerplate functionality, adds application-specific methods.
 *
 * Since:
 *   0.9.1 - DTRT WordPress Plugin Boilerplate Generator
 */
class WPDTRT_Form_Plugin extends DoTheRightThing\WPDTRT_Plugin_Boilerplate\r_1_7_15\Plugin {

	/**
	 * Constructor: __construct
	 *
	 * Supplement plugin initialisation.
	 *
	 * Parameters:
	 *   $options - Plugin options
	 *
	 * Since:
	 *   0.9.1 - DTRT WordPress Plugin Boilerplate Generator
	 */
	public function __construct( $options ) { // phpcs:ignore

		// edit here.
		parent::__construct( $options );
	}

	/**
	 * Group: WordPress Integration
	 * _____________________________________
	 */

	/**
	 * Function: wp_setup
	 *
	 * Supplement plugin's WordPress setup.
	 *
	 * Note:
	 * - Default priority is 10. A higher priority runs later.
	 *
	 * See:
	 * - <Action order: https://codex.wordpress.org/Plugin_API/Action_Reference>
	 *
	 * Since:
	 *   0.9.1 - DTRT WordPress Plugin Boilerplate Generator
	 */
	protected function wp_setup() { // phpcs:ignore

		parent::wp_setup();

		// About: add actions and filters here.
		add_filter( 'wpdtrt_form_set_api_endpoint', [ $this, 'filter_set_api_endpoint' ] );
	}

	/**
	 * Group: Getters and Setters
	 * _____________________________________
	 */

	/**
	 * Group: Renderers
	 * _____________________________________
	 */

	/**
	 * Add project-specific frontend scripts
	 *
	 * Use this function to:
	 * - load scripts in addition to js/frontend-es5.js (via wp_enqueue_script)
	 * - add keys to <%= nameSafe %>_config (via wp_localize_script)
	 *
	 * Don't use function this to:
	 * - add ES6 scripts requiring transpiling (load them using frontend.txt instead)
	 *
	 * @see wpdtrt-plugin-boilerplate/src/Plugin.php
	 */
	public function render_js_frontend() {
		$attach_to_footer = true;

		wp_register_script( 'jquery_validate',
			$this->get_url() . 'node_modules/jquery-validation/dist/jquery.validate.js',
			array(
				// load these registered dependencies first.
				'jquery',
			),
			'1.16.0',
			$attach_to_footer
		);

		wp_enqueue_script( $this->get_prefix(),
			$this->get_url() . 'js/frontend-es5.js',
			array(
				// load these registered dependencies first.
				'jquery',
				'jquery_validate',
			),
			$this->get_version(),
			$attach_to_footer
		);

		// note: after wp_enqueue_script.
		wp_localize_script( $this->get_prefix(),
			$this->get_prefix() . '_config',
			array(
				// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php,
				// but we need to explicitly expose it to frontend pages.
				'ajaxurl' => admin_url( 'admin-ajax.php' ), // wpdtrt_foobar_config.ajaxurl.
				'options' => $this->get_options(), // wpdtrt_foobar_config.options.
			)
		);

		// If editing this function, remove this line to replace the parent function.
		// parent::render_js_frontend();.
	}

	/**
	 * Group: Filters
	 * _____________________________________
	 */

	/**
	 * Set the API endpoint
	 *  The filter is applied in wpplugin->get_api_data()
	 *
	 * @return      string $endpoint
	 *
	 * @since       1.3.4
	 *
	 * @example
	 *  add_filter( 'wpdtrt_form_set_api_endpoint', [$this, 'filter_set_api_endpoint'] );
	 */
	public function filter_set_api_endpoint() {
		$plugin_options = $this->get_plugin_options();
		$endpoint       = '';

		if ( key_exists( 'value', $plugin_options['template'] ) ) {
			$template = $plugin_options['template']['value'];
			$endpoint = ( WPDTRT_FORMS_URL . 'data/form-' . $template . '.json' );
		}

		return $endpoint;
	}

	/**
	 * Group: Helpers
	 * _____________________________________
	 */

	/**
	 * Sanitize form data
	 *  Validating: Ensure that submitted data matches your expectations
	 *  Santization: Clean user input to fit within an acceptable range
	 *  Escaping: Secure existing data prior to rendering it to the end user
	 *
	 * @return      Sanitized form data
	 *
	 * @see https://www.sitepoint.com/build-your-own-wordpress-contact-form-plugin-in-5-minutes/
	 * @see https://premium.wpmudev.org/blog/how-to-build-your-own-wordpress-contact-form-and-why/
	 * @see http://www.butlerblog.com/2012/09/23/testing-the-wp_mail-function/
	 * @see https://codeseekah.com/2012/02/27/getting-error-feedback-from-wp_mail/
	 * @see https://codex.wordpress.org/Option_Reference
	 * @see https://codex.wordpress.org/Validating_Sanitizing_and_Escaping_User_Data
	 * @see /wp-includes/formatting.php
	 * @see https://developer.wordpress.org/reference/functions/sanitize_email/
	 * @see https://developer.wordpress.org/reference/functions/sanitize_text_field/
	 * @see https://developer.wordpress.org/reference/functions/sanitize_textarea_field/
	 * @see https://developer.wordpress.org/reference/functions/wp_mail/
	 * @todo Form seems to submit even if Subject field is empty
	 */
	public function helper_sanitize_form_data() {

		$submitted_data = array();

		// if the submit button is clicked, send the email.
		if ( isset( $_POST['wpdtrt_form_submitted'] ) ) {

			$wpdtrt_form_options = get_option( 'wpdtrt_form' );

			// this requires json_decode to use the optional second argument to return an associative array.
			$data            = $this->get_plugin_data();
			$template_fields = $data['template_fields'];

			// sanitize form values
			// empty or unsanitary values are output as ''.
			foreach ( $template_fields as $template_field ) {

				// some fields like checkbox don't need sanitizing.
				if ( isset( $template_field['sanitizer'] ) ) {

					$sanitizer = $template_field['sanitizer'];

					/**
					 * Verify that the contents of a variable can be called as a function
					 *
					 * @see http://php.net/is_callable
					 */
					if ( is_callable( $sanitizer ) ) {

						/**
						 * Call the callback given by the first parameter
						 *
						 * @see http://php.net/manual/en/function.call-user-func.php
						 */
						$submitted_data[ $template_field['id'] ] = call_user_func( $sanitizer, $_POST[ 'wpdtrt_form_' . $template_field['id'] ] );
					}
				}
			}
		}

		return $submitted_data;
	}

	/**
	 * Send an email using $_POST data
	 *
	 * @return $sentmail Whether the email contents were sent successfully.
	 *
	 * @see https://developer.wordpress.org/reference/functions/wp_mail/
	 * @see http://www.wordpresscheatsheets.com/how-to-send-html-emails-from-wordpress-using-wp_mail-function
	 * @todo Use template loader
	 */
	public function helper_sendmail() {

		// if the submit button is clicked, send the email.
		if ( isset( $_POST['wpdtrt_form_submitted'] ) ) {

			$submitted_data = $this->helper_sanitize_form_data();

			$blogname = get_option( 'blogname' );

			// get the blog administrator's email address.
			$to = get_option( 'admin_email' );

			$headers = 'From: ' . $submitted_data['name'] . '<' . $submitted_data['email'] . '>' . "\r\n";

			if ( '' !== $submitted_data['message'] ) {

				$message = $submitted_data['message'] . "\r\n\r\n";

				if ( isset( $_POST['wpdtrt_form_email_updates'] ) ) {
					$message .= 'I would like to receive email updates.' . "\r\n\r\n";
				}

				$message .= '---' . "\r\n\r\n";
				$message .= 'Sent from the "' . $blogname . '" Contact Form.';
			} else {
				$message = '';
			}

			$sentmail = wp_mail( $to, $submitted_data['subject'], $message, $headers );

			$plugin_options = $this->get_plugin_options();

			if ( key_exists( 'errors_list', $plugin_options ) ) {
				$errors_list = $plugin_options['errors_list'];
			} else {
				$errors_list = false;
			}

			$data = $this->get_plugin_data();
			require WPDTRT_FORMS_PATH . 'template-parts/wpdtrt-form-status.php';

			return $sentmail;
		}
	}
}