<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the select object section of feeds.
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

$objects = isset( $params['objects'] ) ? $params['objects'] : array();

if ( ! is_array( $objects ) ) {
	echo esc_html( $objects );
	return;
}

?>
<div class="mwb-feeds__content  mwb-content-wrap  mwb-feed__select-object">
	<a class="mwb-feeds__header-link active">
		<?php
		/* translators: %s: Crm name */
		printf( esc_html__( 'Select %s Object', 'mwb-cf7-integration-with-engagebay' ), esc_html( $this->crm_name ) );
		?>
	</a>

	<div class="mwb-feeds__meta-box-main-wrapper">
		<div class="mwb-feeds__meta-box-wrap">
			<div class="mwb-form-wrapper">
				<select name="crm_object" id="mwb-feeds-<?php echo esc_attr( $this->crm_slug ); ?>-object" class="mwb-form__dropdown">
					<option value="-1"><?php esc_html_e( 'Select Object', 'mwb-cf7-integration-with-engagebay' ); ?></option>
					<?php if ( is_array( $objects ) ) : ?>
						<?php foreach ( $objects as $key => $object ) : ?>
							<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $params['selected_object'], $object ); ?> >
								<?php echo esc_html( $object ); ?>
							</option>
						<?php endforeach; ?>
					<?php endif; ?>
				</select>
			</div>
			<div class="mwb-form-wrapper mwb-refresh-wrapper">
				<a id="mwb-<?php echo esc_attr( $this->crm_slug ); ?>-refresh-object" class="button refresh-object"><?php esc_html_e( 'Refresh Objects', 'mwb-cf7-integration-with-engagebay' ); ?></a>
				<a id="mwb-<?php echo esc_attr( $this->crm_slug ); ?>-refresh-fields" class="button refresh-fields"><?php esc_html_e( 'Refresh Fields', 'mwb-cf7-integration-with-engagebay' ); ?></a>
			</div>
		</div>
	</div>
</div>
