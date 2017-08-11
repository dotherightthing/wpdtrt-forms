<?php
/**
 * Template partial for a form label
 *
 * This file contains PHP, and HTML.
 *
 * @link        http://dotherightthing.co.nz
 * @since       0.1.0
 *
 * @package     Wpdtrt_Forms
 * @subpackage  Wpdtrt_Forms/views
 */
?>

<label class="wpdtrt-forms-label<?php echo $required_label_class; ?>" for="<?php echo $id; ?>">
<?php echo $label; ?>:
<?php if ( $required ): ?>
  <span class="wpdtrt-forms-required-text wpdtrt-forms-hidden"> (required)</span>
<?php endif; ?>
</label>