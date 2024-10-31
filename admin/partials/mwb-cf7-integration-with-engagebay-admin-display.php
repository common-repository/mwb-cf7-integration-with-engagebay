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
 * @subpackage Mwb_Cf7_Integration_With_Engagebay/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$headings = $this->add_plugin_headings();
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<main class="mwb-cf7-integration-main">
	<header class="mwb-cf7-integration-header">
		<h1 class="mwb-cf7-integration-header__title"><?php echo esc_html( ! empty( $headings['name'] ) ? $headings['name'] : '' ); ?></h1>
		<span class="mwb-cf7-integration-version"><?php echo sprintf( 'v%s', esc_html( ! empty( $headings['version'] ) ? $headings['version'] : '' ) ); ?></span>
	</header>
	<?php if ( true == get_option( 'mwb-cf7-' . $this->crm_slug . '-authorised' ) ) : // phpcs:ignore ?>
		<!-- Dashboard Screen -->
		<?php do_action( 'mwb_' . $this->crm_slug . '_cf7_nav_tab' ); ?>
	<?php else : ?>
		<!-- Authorisation Screen -->
		<?php do_action( 'mwb_' . $this->crm_slug . '_cf7_auth_screen' ); ?>
	<?php endif; ?>
</main>
