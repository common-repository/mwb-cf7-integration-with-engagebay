<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the accounts creation page.
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

	<!-- Logo section start -->
	<div class="mwb-cf7_integration_logo-wrap">
		<div class="mwb-cf7_integration_logo-crm">
			<img src="<?php echo esc_url( MWB_CF7_INTEGRATION_WITH_ENGAGEBAY_URL . 'admin/images/engagebay.png' ); ?>" alt="Engagebay">
		</div>
		<div class="mwb-cf7_integration_logo-contact">
			<img src="<?php echo esc_url( MWB_CF7_INTEGRATION_WITH_ENGAGEBAY_URL . 'admin/images/contact-form.svg' ); ?>" alt="CF7">
		</div>
	</div>
	<!-- Logo section end -->

	<!--============================================================================================
										Dashboard page start.
	================================================================================================-->

	<!-- Connection status start -->
	<div class="mwb_cf7_integration_crm_connected">
		<ul>
			<li class="mwb-cf7_intergation_conn-row">
				<div class="mwb-cf7-integration__content-wrap">
					<div class="mwb-section__sub-heading__wrap">
						<h3 class="mwb-section__sub-heading">
							<?php echo sprintf( '%s %s', esc_html( $this->crm_name ), esc_html__( 'Connection Status', 'mwb-cf7-integration-with-engagebay' ) ); ?>
						</h3>
						<div class="mwb-dashboard__header-text">
							<span class="is-connected" >
								<?php esc_html_e( 'Connected', 'mwb-cf7-integration-with-engagebay' ); ?>
							</span>
						</div>
					</div>

					<div class="mwb-cf7-integration__status-wrap">
						<div class="mwb-cf7-integration__left-col">
							<div class="mwb-cf7-integration-token-notice__wrap">
								<?php if ( ! empty( $params['owner_name'] ) ) : ?>
									<p>
										<?php
										/* translators: %s: owner name */
										printf( esc_html__( 'Account Owner : %s', 'mwb-cf7-integration-with-engagebay' ), esc_html( $params['owner_name'] ) );
										?>
									</p>
								<?php endif; ?>
							</div>
						</div>

						<div class="mwb-cf7-integration__right-col">
							<a id="mwb_cf7_integration_reauthorize" href="<?php echo esc_url( wp_nonce_url( admin_url( '?mwb-cf7-engagebay-integration-perform-reauth=1' ) ) ); ?>" class="mwb-btn mwb-btn--filled">
								<?php esc_html_e( 'Reauthorize', 'mwb-cf7-integration-with-engagebay' ); ?>
							</a>
							<a id="mwb_cf7_integration_disconnect" href="javascript:void(0)" class="mwb-btn mwb-btn--filled">
								<?php esc_html_e( 'Disconnect', 'mwb-cf7-integration-with-engagebay' ); ?>
							</a>
						</div>
					</div>
				</div>
			</li>
		</ul>
	</div>
	<!-- Connection status end -->

	<!-- About list start -->
	<div class="mwb-dashboard__about">
		<div class="mwb-dashboard__about-list">
			<div class="mwb-content__list-item-text">
				<h2 class="mwb-section__heading"><?php esc_html_e( 'Synced Contact Forms', 'mwb-cf7-integration-with-engagebay' ); ?></h2>
				<div class="mwb-dashboard__about-number">
					<span><?php echo esc_html( ! empty( $params['count'] ) ? $params['count'] : '0' ); ?></span>
				</div>
				<div class="mwb-dashboard__about-number-desc">
					<p>

						<i><?php esc_html_e( 'Total number of Contact Form 7 submission data which are synced over Engagebay.', 'mwb-cf7-integration-with-engagebay' ); ?>
						<a href="<?php echo esc_url( admin_url( 'admin.php?page=mwb_' . $this->crm_slug . '_cf7_page&tab=logs' ) ); ?>" target="_blank"><?php esc_html_e( 'View log', 'mwb-cf7-integration-with-engagebay' ); ?></a></i>
					</p>
				</div>
			</div>
			<div class="mwb-content__list-item-image">
				<img src="<?php echo esc_url( MWB_CF7_INTEGRATION_WITH_ENGAGEBAY_URL . 'admin/images/deals.svg' ); ?>" alt="<?php esc_html_e( 'Synced Contact Forms', 'mwb-cf7-integration-with-engagebay' ); ?>">
			</div>
		</div>

		<?php do_action( 'mwb_' . $this->crm_slug . '_cf7_about_list' ); ?>

	</div>
	<!-- About list end -->

	<!-- Support section start -->
	<div class="mwb-content-wrap">
		<ul class="mwb-about__list">
			<li class="mwb-about__list-item">
				<div class="mwb-about__list-item-text">
					<p><?php esc_html_e( 'Need any help ? Check our documentation.', 'mwb-cf7-integration-with-engagebay' ); ?></p>
				</div>
				<div class="mwb-about__list-item-btn">
					<a href="<?php echo esc_url( ! empty( $params['links']['doc'] ) ? $params['links']['doc'] : '' ); ?>" class="mwb-btn mwb-btn--filled" target="_blank"><?php esc_html_e( 'Documentation', 'mwb-cf7-integration-with-engagebay' ); ?></a>
				</div>
			</li>
			<li class="mwb-about__list-item">
				<div class="mwb-about__list-item-text">
					<p><?php esc_html_e( 'Facing any issue ? Open a support ticket.', 'mwb-cf7-integration-with-engagebay' ); ?></p>
				</div>
				<div class="mwb-about__list-item-btn">
					<a href="<?php echo esc_url( ! empty( $params['links']['ticket'] ) ? $params['links']['ticket'] : '' ); ?>" class="mwb-btn mwb-btn--filled" target="_blank"><?php esc_html_e( 'Support', 'mwb-cf7-integration-with-engagebay' ); ?></a>
				</div>
			</li>
			<li class="mwb-about__list-item">
				<div class="mwb-about__list-item-text">
					<p><?php esc_html_e( 'Need personalized solution, contact us !', 'mwb-cf7-integration-with-engagebay' ); ?></p>
				</div>
				<div class="mwb-about__list-item-btn">
					<a href="<?php echo esc_url( ! empty( $params['links']['contact'] ) ? $params['links']['contact'] : '' ); ?>" class="mwb-btn mwb-btn--filled" target="_blank"><?php esc_html_e( 'Connect', 'mwb-cf7-integration-with-engagebay' ); ?></a>
				</div>
			</li>
		</ul>	
	</div>
	<!-- Support section end -->

</div>

