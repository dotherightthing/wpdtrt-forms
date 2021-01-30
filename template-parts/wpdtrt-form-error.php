<?php
/**
 * Template partial for an inline form error
 *
 * This file contains PHP, and HTML.
 *
 * @link        http://dotherightthing.co.nz
 * @since       0.1.0
 *
 * @package     Wpdtrt_Form
 * @subpackage  Wpdtrt_Form/views
 */

global $wpdtrt_form_plugin;
$sanitized_form_data = $wpdtrt_form_plugin->helper_sanitize_form_data();

if ( '1' === $errors_inline ) {
	?>
<span id="<?php echo $id; ?>-validation" class="wpdtrt-form__validation">
	<?php
	if ( isset( $sanitized_form_data[ $name ] ) ) {
		if ( ( '' === $sanitized_form_data[ $name ] ) && ( '1' === $errors_inline ) ) {
			echo "<strong>{$error}</strong>";
		}
	}
	?>
</span>
	<?php
}
?>
