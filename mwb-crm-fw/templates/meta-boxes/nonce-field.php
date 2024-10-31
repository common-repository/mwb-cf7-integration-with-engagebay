<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the nonce of feeds section.
 *
 * @link       https://makewebbetter.com
 * @since      1.0.0
 *
 * @package    Mwb_Cf7_Integration_With_Engagebay
 * @subpackage Mwb_Cf7_Integration_With_Engagebay/mwb-crm-fw/templates/meta-boxes
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	exit;
}

?>
<input type="hidden" name="meta_box_nonce" value="<?php echo esc_attr( wp_create_nonce( 'meta_box_nonce' ) ); ?>" >
