<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_Cf7_Integration_With_Engagebay
 * @subpackage Mwb_Cf7_Integration_With_Engagebay/mwb-crm-fw/templates/tab-contents
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$api_key  = get_option( 'mwb-cf7-' . $this->crm_slug . '-api-key', '' );
$base_url = get_option( 'mwb-cf7-' . $this->crm_slug . '-base-url', '' );

?>
<div class="mwb-reauth__body row-hide">
	<div class="mwb-crm-reauth-wrap">
		<div class="mwb-reauth__body-close">
			<img src="<?php echo esc_url( MWB_CF7_INTEGRATION_WITH_ENGAGEBAY_URL . 'admin/images/cancel.png' ); ?>" alt="Close">
		</div>
		<!-- Login form start -->
		<form method="post" id="mwb_cf7_integration_account_form">

			<div class="mwb_cf7_integration_table_wrapper">
				<div class="mwb_cf7_integration_account_setup">
					<h2>
						<?php esc_html_e( 'Reauthorize Connection', 'mwb-cf7-integration-with-engagebay' ); ?>
					</h2>
				</div>

				<table class="mwb_cf7_integration_table">
					<tbody>

						<!-- api key start  -->
						<tr class="mwb-api-fields">
							<th>							
								<label><?php esc_html_e( 'API Key', 'mwb-cf7-integration-with-engagebay' ); ?></label>
							</th>

							<td>
								<input type="text"  name="mwb_account[api_key]" id="mwb-<?php echo esc_attr( $this->crm_slug ); ?>-cf7-api-key" value="<?php echo esc_html( $api_key ); ?>" required placeholder="<?php esc_html_e( 'Enter your API key here', 'mwb-cf7-integration-with-engagebay' ); ?>" readonly>
							</td>
						</tr>
						<!-- api key end -->
						<!-- base url  -->
						<tr class="mwb-api-fields">
							<td>
								<input type="hidden" name="mwb_account[base_url]" id="mwb-<?php echo esc_attr( $this->crm_slug ); ?>-cf7-base-url" value="<?php echo esc_html( $base_url ); ?>">
							</td>
						</tr>
						<!-- base url end -->
						<!-- Save & connect account start -->
						<tr>
							<th>
							</th>
							<td>
								<a href="<?php echo esc_url( wp_nonce_url( admin_url( '?mwb-cf7-engagebay-integration-perform-reauth=1' ) ) ); ?>" class="mwb-btn mwb-btn--filled mwb_cf7_integration_submit_account" id="mwb-<?php echo esc_attr( $this->crm_slug ); ?>-cf7-authorize-button" >
									<?php esc_html_e( 'Reauthorize', 'mwb-cf7-integration-with-engagebay' ); ?>
								</a>
							</td>
						</tr>
						<!-- Save & connect account end -->
					</tbody>
				</table>
			</div>
		</form>
		<!-- Login form end -->

	</div>
</div>
