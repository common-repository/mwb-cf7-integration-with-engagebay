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
 * @subpackage Mwb_Cf7_Integration_With_Engagebay/extra-templates
 * @author     MakeWebBetter <https://makewebbetter.com>
 */

?>
<div class="mwb-crm-setup-content-wrap">
	<div class="mwb-crm-setup-list-wrap">
		<ul class="mwb-crm-setup-list">
			<li>
				<?php esc_html_e( 'Login to your EngageBay account.', 'mwb-cf7-integration-with-engagebay' ); ?>
			</li>
			<li>
				<?php esc_html_e( 'Click on the user profile icon at the right top menu.', 'mwb-cf7-integration-with-engagebay' ); ?>
			</li>
			<li>
				<?php esc_html_e( 'Click on Account Settings. It will open a new tab.', 'mwb-cf7-integration-with-engagebay' ); ?>
			</li>
			<li>
				<?php esc_html_e( 'Click on API and Tracking Code tab.', 'mwb-cf7-integration-with-engagebay' ); ?>
			</li>
			<li>
				<?php esc_html_e( 'Copy "REST API Key" from there and enter it in your Authentication form.', 'mwb-cf7-integration-with-engagebay' ); ?>
			</li>
		</ul>
		<h4 class="mwb_setup_heading"><?php esc_html_e( 'How to get EngageBay base url?', 'mwb-cf7-integration-with-engagebay' ); ?></h4>
		<ul class="mwb-crm-setup-list">
			<li>
				<?php esc_html_e( 'In Account Settings page click on Domain Settings tab.', 'mwb-cf7-integration-with-engagebay' ); ?>
			</li>
			<li>
				<?php esc_html_e( 'From General copy "Domain Name" field data along with ".engagebay.com". For example - domain.engagebay.com', 'mwb-cf7-integration-with-engagebay' ); ?>
			</li>
		</ul>
	</div>
</div>
