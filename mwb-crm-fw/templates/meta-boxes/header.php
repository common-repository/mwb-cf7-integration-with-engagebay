<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the header of feeds section.
 *
 * @link       https://makewebbetter.com
 * @since      1.0.0
 *
 * @package    Mwb_Cf7_Integration_With_Engagebay
 * @subpackage Mwb_Cf7_Integration_With_Engagebay/mwb-crm-fw/templates/meta-boxes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="mwb_cf7_integration__feeds-wrap">
	<div class="mwb-cf7_integration_logo-wrap">
		<div class="mwb-sf_cf7__logo-zoho">
			<img src="<?php echo esc_url( MWB_CF7_INTEGRATION_WITH_ENGAGEBAY_URL . 'admin/images/engagebay.png' ); ?>" alt="Engagebay">
		</div>
		<div class="mwb-cf7_integration_logo-contact">
			<img src="<?php echo esc_url( MWB_CF7_INTEGRATION_WITH_ENGAGEBAY_URL . 'admin/images/contact-form.svg' ); ?>" alt="CF7">
		</div>
	</div>

