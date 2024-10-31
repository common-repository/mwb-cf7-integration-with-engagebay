<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the settings page.
 *
 * @link       https://makewebbetter.com
 * @since      1.0.0
 *
 * @package    Mwb_Cf7_Integration_With_Engagebay
 * @subpackage Mwb_Cf7_Integration_With_Engagebay/mwb-crm-fw/templates/tab-contents
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="mwb_cf7_integration_account-wrap">

	<div class="mwb-cf7_integration_logo-wrap">
		<div class="mwb-cf7_integration_logo-crm">
			<img src="<?php echo esc_url( MWB_CF7_INTEGRATION_WITH_ENGAGEBAY_URL . 'admin/images/engagebay.png' ); ?>" alt="Engagebay">
		</div>
		<div class="mwb-cf7_integration_logo-contact">
			<img src="<?php echo esc_url( MWB_CF7_INTEGRATION_WITH_ENGAGEBAY_URL . 'admin/images/contact-form.svg' ); ?>" alt="CF7">
		</div>
	</div>

	<form method="post" id="mwb_cf7_integration_settings_form">

		<div class="mwb_cf7_integration_table_wrapper">
			<table class="mwb_cf7_integration_table">
				<tbody>

					<!-- Enable logs start -->
					<tr>
						<th>
							<label><?php esc_html_e( 'Enable logs', 'mwb-cf7-integration-with-engagebay' ); ?></label>
						</th>

						<td>
							<?php
							$desc = esc_html__( 'Enable logging of all the form data to be sent over Engagebay', 'mwb-cf7-integration-with-engagebay' );
							echo esc_html( $params['admin_class']::mwb_cf7_integration_tooltip( $desc ) );

							$enable_logs = ! empty( $params['option']['enable_logs'] ) ? sanitize_text_field( wp_unslash( $params['option']['enable_logs'] ) ) : '';
							?>
							<input type="checkbox" name="mwb_setting[enable_logs]" value="yes" <?php checked( 'yes', $enable_logs ); ?>>
						</td>
					</tr>
					<!-- Enable logs end-->

					<!-- Data delete start -->
					<tr>
						<th>
							<label><?php esc_html_e( 'Plugin Data', 'mwb-cf7-integration-with-engagebay' ); ?></label>
						</th>

						<td>
							<?php
							$desc = esc_html__( 'Enable to delete the plugin data after uninstallation of plugin', 'mwb-cf7-integration-with-engagebay' );
							echo esc_html( $params['admin_class']::mwb_cf7_integration_tooltip( $desc ) );

							$data_delete = ! empty( $params['option']['data_delete'] ) ? sanitize_text_field( wp_unslash( $params['option']['data_delete'] ) ) : '';
							?>
							<input type="checkbox" name="mwb_setting[data_delete]" value="yes"  <?php checked( 'yes', $data_delete ); ?>>
						</td>
					</tr>
					<!-- data delete end -->

					<!-- Enable email notif start -->
					<tr>
						<th>
							<label><?php esc_html_e( 'Email notification', 'mwb-cf7-integration-with-engagebay' ); ?></label>
						</th>

						<td>
							<?php
								$desc = esc_html__( 'Enable email notification on errors while syncing data to Engagebay', 'mwb-cf7-integration-with-engagebay' );
								echo esc_html( $params['admin_class']::mwb_cf7_integration_tooltip( $desc ) );

								$enable_notif = ! empty( $params['option']['enable_notif'] ) ? sanitize_text_field( wp_unslash( $params['option']['enable_notif'] ) ) : '';
							?>
							<input type="checkbox" name="mwb_setting[enable_notif]" value="yes" <?php checked( 'yes', $enable_notif ); ?> >
						</td>
					</tr>
					<!-- Enable email notif end -->

					<!-- Email field start -->
					<tr >
						<th>
						</th>
						<td>
							<div id="mwb_<?php echo esc_attr( $this->crm_slug ); ?>_cf7_email_notif" class="<?php echo esc_attr( ( 'yes' != $enable_notif ) ? 'is_hidden' : '' ); // phpcs:ignore ?>">
								<?php

									$email_notif = ! empty( $params['option']['email_notif'] ) ? sanitize_email( wp_unslash( $params['option']['email_notif'] ) ) : get_bloginfo( 'admin_email' );
								?>
								<input type="email" name="mwb_setting[email_notif]" value="<?php echo esc_html( $email_notif ); ?>" >
							</div>
						</td>
					</tr>	
					<!-- Email field end -->

					<!-- Delete logs start -->
					<tr>
						<th>
							<label><?php esc_html_e( 'Delete logs after N days', 'mwb-cf7-integration-with-engagebay' ); ?></label>
						</th>

						<td>
							<?php
							$desc = esc_html__( 'This will delete the logs data after N no. of days', 'mwb-cf7-integration-with-engagebay' );
							echo esc_html( $params['admin_class']::mwb_cf7_integration_tooltip( $desc ) );

							$delete_logs = ! empty( $params['option']['delete_logs'] ) ? sanitize_text_field( wp_unslash( $params['option']['delete_logs'] ) ) : 7;
							?>
							<input type="number" name="mwb_setting[delete_logs]" min="7" maxlenght="4" step="1" pattern="[0-9]" value="<?php echo esc_html( $delete_logs ); ?>">
						</td>
					</tr>
					<!-- Delete logs end -->

					<?php do_action( 'mwb_' . $this->crm_slug . '_cf7_add_settings' ); ?>

					<!-- Save settings start -->
					<tr>
						<th>
						</th>
						<td>
							<input type="submit" name="mwb_cf7_integration_submit_setting" class="mwb_cf7_integration_submit_setting" value="<?php esc_html_e( 'Save', 'mwb-cf7-integration-with-engagebay' ); ?>" >
						</td>
					</tr>
					<!-- Save settings end -->

				</tbody>
			</table>
		</div>
	</form>
</div>
