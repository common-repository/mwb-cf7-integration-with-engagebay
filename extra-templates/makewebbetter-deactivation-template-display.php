<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://makewebbetter.com
 * @since      1.0.0
 *
 * @package    Mwb_Cf7_Integration_With_Engagebay
 * @subpackage Mwb_Cf7_Integration_With_Engagebay/extra-templates
 * @author     MakeWebBetter <https://makewebbetter.com>
 */

global $pagenow;
if ( empty( $pagenow ) || 'plugins.php' != $pagenow ) { // phpcs:ignore
	return false;
}

$form_fields = apply_filters( 'mwb_deactivation_form_fields', array() );
?>
<?php if ( ! empty( $form_fields ) ) : ?>
	<div class="mwb-onboarding-section">
		<div class="mwb-on-boarding-wrapper-background">
		<div class="mwb-on-boarding-wrapper">
			<div class="mwb-on-boarding-close-btn">
				<a href="#">
					<span class="close-form">x</span>
				</a>
			</div>
			<h3 class="mwb-on-boarding-heading"></h3>
			<p class="mwb-on-boarding-desc"><?php esc_html_e( 'May we have a little info about why you are deactivating?', 'mwb-cf7-integration-with-engagebay' ); ?></p>
			<form action="#" method="post" class="mwb-on-boarding-form">
				<?php foreach ( $form_fields as $key => $field_attr ) : ?>
					<?php $this->render_field_html( $field_attr, 'deactivating' ); ?>
				<?php endforeach; ?>
				<div class="mwb-on-boarding-form-btn__wrapper">
					<div class="mwb-on-boarding-form-submit mwb-on-boarding-form-verify ">
					<input type="submit" class="mwb-on-boarding-submit mwb-on-boarding-verify " value="<?php esc_html_e( 'Send Us', 'mwb-cf7-integration-with-engagebay' ); ?>">
				</div>
				<div class="mwb-on-boarding-form-no_thanks">
					<a href="#" class="mwb-deactivation-no_thanks"><?php esc_html_e( 'Skip and Deactivate Now', 'mwb-cf7-integration-with-engagebay' ); ?></a>
				</div>
				</div>
			</form>
		</div>
	</div>
	</div>
<?php endif; ?>
