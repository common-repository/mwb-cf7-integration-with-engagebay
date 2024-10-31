<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the primary field of feeds section.
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
<div id="mwb-primary-field-section-wrapper"  class="mwb-feeds__content  mwb-content-wrap row-hide">
	<a class="mwb-feeds__header-link">
		<?php esc_html_e( 'Primary Field', 'mwb-cf7-integration-with-engagebay' ); ?>
	</a>
	<div class="mwb-feeds__meta-box-main-wrapper">
		<div class="mwb-feeds__meta-box-wrap">
			<div class="mwb-form-wrapper">
				<select id="primary-field-select" name="primary_field">
					<option value=""><?php esc_html_e( 'Select an Option', 'mwb-cf7-integration-with-engagebay' ); ?></option>
					<?php $mapping_exists = ! empty( $params['mapping_data'] ); ?>
					<?php foreach ( $params['crm_fields'] as $key => $fields_data ) : ?>
						<?php
						if ( empty( $fields_data['name'] ) ) {
							$fields_data['name'] = $key;
						}
						if ( $mapping_exists ) {
							if ( ! array_key_exists( $fields_data['name'], $params['mapping_data'] ) ) {
								continue;
							}
						} elseif ( isset( $fields_data['required'] ) && ! $fields_data['required'] ) {
							continue;
						}
						?>
						<option <?php selected( $params['primary_field'], $fields_data['name'] ); ?>  value="<?php echo esc_attr( $fields_data['name'] ); ?>"><?php echo esc_html( $fields_data['label'] ); ?></option>	
					<?php endforeach; ?>
				</select>
				<p class="mwb-description">
					<?php
					esc_html_e(
						'Please select a field which should be used as "primary key" to update an existing record. 
						In case of duplicate records',
						'mwb-cf7-integration-with-engagebay'
					);
					?>
				</p>
			</div>
		</div>
	</div>
</div>

