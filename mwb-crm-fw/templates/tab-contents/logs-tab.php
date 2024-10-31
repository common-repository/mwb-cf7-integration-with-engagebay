<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the Engagebay logs listing aspects of the plugin.
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
<div class="mwb-cf7-integration__logs-wrap" id="mwb-<?php echo esc_attr( $this->crm_slug ); ?>-cf7-logs" ajax_url="<?php echo esc_attr( admin_url( 'admin-ajax.php' ) ); ?>">
	<div class="mwb-cf7_integration_logo-wrap">
		<div class="mwb-cf7_integration_logo-crm">
			<img src="<?php echo esc_url( MWB_CF7_INTEGRATION_WITH_ENGAGEBAY_URL . 'admin/images/engagebay.png' ); ?>" alt="Engagebay">
		</div>
		<div class="mwb-cf7_integration_logo-contact">
			<img src="<?php echo esc_url( MWB_CF7_INTEGRATION_WITH_ENGAGEBAY_URL . 'admin/images/contact-form.svg' ); ?>" alt="CF7">
		</div>
		<?php if ( $params['log_enable'] ) : ?>
				<ul class="mwb-logs__settings-list">
					<li class="mwb-logs__settings-list-item">
						<a id="mwb-<?php echo esc_attr( $this->crm_slug ); ?>-cf7-clear-log" href="javascript:void(0)" class="mwb-logs__setting-link">
							<?php esc_html_e( 'Clear Log', 'mwb-cf7-integration-with-engagebay' ); ?>	
						</a>
					</li>
					<li class="mwb-logs__settings-list-item">
						<a id="mwb-<?php echo esc_attr( $this->crm_slug ); ?>-cf7-download-log" href="javascript:void(0)"  class="mwb-logs__setting-link">
							<?php esc_html_e( 'Download', 'mwb-cf7-integration-with-engagebay' ); ?>	
						</a>
					</li>
				</ul>
		<?php endif; ?>
	</div>
	<div>
		<div>
			<?php if ( $params['log_enable'] ) : ?>
			<div class="mwb-cf7-integration__logs-table-wrap">
				<table id="mwb-<?php echo esc_attr( $this->crm_slug ); ?>-cf7-table" class="display mwb-cf7-integration__logs-table dt-responsive nowrap" style="width: 100%;">
					<thead>
						<tr>
							<th><?php esc_html_e( 'Expand', 'mwb-cf7-integration-with-engagebay' ); ?></th>
							<th><?php esc_html_e( 'Feed', 'mwb-cf7-integration-with-engagebay' ); ?></th>
							<th><?php esc_html_e( 'Feed ID', 'mwb-cf7-integration-with-engagebay' ); ?></th>
							<th>
								<?php
								/* translators: %s: CRM name. */
								printf( esc_html__( '%s Object', 'mwb-cf7-integration-with-engagebay' ), esc_html( $this->crm_name ) );
								?>
							</th>
							<th>
								<?php
								/* translators: %s: CRM name. */
								printf( esc_html__( '%s ID', 'mwb-cf7-integration-with-engagebay' ), esc_html( $this->crm_name ) );
								?>
							</th>
							<th><?php esc_html_e( 'Event', 'mwb-cf7-integration-with-engagebay' ); ?></th>
							<th><?php esc_html_e( 'Timestamp', 'mwb-cf7-integration-with-engagebay' ); ?></th>
							<th class=""><?php esc_html_e( 'Request', 'mwb-cf7-integration-with-engagebay' ); ?></th>
							<th class=""><?php esc_html_e( 'Response', 'mwb-cf7-integration-with-engagebay' ); ?></th>
						</tr>
					</thead>
				</table>
			</div>
			<?php else : ?>
				<div class="mwb-content-wrap">
					<?php esc_html_e( 'Please enable the logs from ', 'mwb-cf7-integration-with-engagebay' ); ?><a href="<?php echo esc_url( 'admin.php?page=mwb_' . $this->crm_slug . '_cf7_page&tab=settings' ); ?>" target="_blank"><?php esc_html_e( 'Settings tab', 'mwb-cf7-integration-with-engagebay' ); ?></a>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
