<?php
/**
 * API requests
 *
 * This file contains PHP.
 *
 * @link        http://dotherightthing.co.nz
 * @since       0.1.0
 *
 * @package     Wpdtrt_Forms
 * @subpackage  Wpdtrt_Forms/app
 */

if ( !function_exists( 'wpdtrt_forms_get_data' ) ) {

  /**
   * Request the data from the API
   *
   * @param       string $wpdtrt_forms_datatype
   *    The type of data to return.
   * @return      object $wpdtrt_forms_data
   *    The body of the JSON response
   *
   * @since       0.1.0
   * @uses        ../../../../wp-includes/http.php
   * @see         https://developer.wordpress.org/reference/functions/wp_remote_get/
   */
  function wpdtrt_forms_get_data( $wpdtrt_forms_datatype ) {

    $endpoint = WPDTRT_FORMS_URL . 'data/wpdtrt-forms-form-' . $wpdtrt_forms_datatype . '.json';

    $args = array(
      'timeout' => 30 // seconds to wait for the request to complete
    );

    $response = wp_remote_get(
      $endpoint,
      $args
    );

    /**
     * Return the body, not the header
     * Note: There is an optional boolean argument, which returns an associative array if TRUE
     */
    $wpdtrt_forms_data = json_decode( $response['body'], true );

    return $wpdtrt_forms_data;
  }

}

if ( !function_exists( 'wpdtrt_forms_data_refresh' ) ) {

  /**
   * Refresh the data from the API
   *    The 'action' key's value, 'wpdtrt_forms_data_refresh',
   *    matches the latter half of the action 'wp_ajax_wpdtrt_forms_data_refresh' in our AJAX handler.
   *    This is because it is used to call the server side PHP function through admin-ajax.php.
   *    If an action is not specified, admin-ajax.php will exit, and return 0 in the process.
   *
   * @since       0.1.0
   * @see         https://codex.wordpress.org/AJAX_in_Plugins
   */
  function wpdtrt_forms_data_refresh() {

    $wpdtrt_forms_options = get_option('wpdtrt_forms');
    $last_updated = isset( $wpdtrt_forms_options['last_updated'] ) ? $wpdtrt_forms_options['last_updated'] : 0;

    $current_time = time();
    $update_difference = $current_time - $last_updated;
    $one_hour = (1 * 60 * 60);

    if ( $update_difference > $one_hour ) {

      $wpdtrt_forms_datatype = $wpdtrt_forms_options['wpdtrt_forms_datatype'];

      $wpdtrt_forms_options['wpdtrt_forms_data'] = wpdtrt_forms_get_data( $wpdtrt_forms_datatype );

      // inspecting the database will allow us to check
      // whether the profile is being updated
      $wpdtrt_forms_options['last_updated'] = time();

      update_option('wpdtrt_forms', $wpdtrt_forms_options);
    }

    /**
     * Let the Ajax know when the entire function has completed
     *
     * wp_die() vs die() vs exit()
     * Most of the time you should be using wp_die() in your Ajax callback function.
     * This provides better integration with WordPress and makes it easier to test your code.
     */
    //////wp_die();

  }

  add_action('wp_ajax_wpdtrt_forms_data_refresh', 'wpdtrt_forms_data_refresh');

}

?>