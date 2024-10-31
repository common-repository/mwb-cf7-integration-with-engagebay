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

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$prefilled_indexes = isset( $params['condition'] ) ? count( $params['condition'] ) : '';
?>
<div id="mwb-condition-filter-section-wrapper"  class="mwb-feeds__content  mwb-content-wrap">
	<a class="mwb-feeds__header-link active">
		<?php esc_html_e( 'Condition Filter', 'mwb-cf7-integration-with-engagebay' ); ?>
	</a>

	<div class="mwb-feeds__meta-box-main-wrapper">
		<div class="mwb-feeds__meta-box-wrap">
			<div class="mwb-form-wrapper  mwb-form-filter-wrapper">

				<div class="mwb-toggle-wrap">
					<input type="checkbox" name="enable_add_condition" id="mwb-<?php echo esc_attr( $this->crm_slug ); ?>-enable-add-condition" value="yes" <?php checked( 'yes', $params['enable_filter'] ); ?> class="mwb-toggle">
					<div class="mwb_field_desc">
						<p>
							<?php esc_html_e( 'Enable to add Conditional filters on form submission', 'mwb-cf7-integration-with-engagebay' ); ?>
						</p>
					</div>
				</div>

				<div class="mwb-initial-filter">
					<?php if ( ! empty( $params['condition'] ) && is_array( $params['condition'] ) ) : ?>
						<?php foreach ( $params['condition'] as $or_index => $and_conditions ) : ?>
							<div class="or-condition-filter" data-or-index="<?php echo esc_html( $or_index ); ?>">
								<div class="mwb-form-filter-row">
									<?php foreach ( $and_conditions as $and_index => $and_condition ) : ?>
										<?php
										$and_condition['form'] = $params['fields'];
										$template_class        = 'Mwb_Cf7_Integration_' . $this->crm_name . '_Template_Manager';
										$template_manager      = $template_class::get_instance();
										$template_manager->render_and_conditon( $and_condition, $and_index, $or_index );
										?>
									<?php endforeach; ?>
									<button data-next-and-index="<?php echo esc_html( ++$and_index ); ?>" data-or-index="<?php echo esc_html( $or_index ); ?>" class="button condition-and-btn"><?php esc_html_e( 'Add "AND" filter', 'mwb-cf7-integration-with-engagebay' ); ?></button>
									<?php if ( 1 != $prefilled_indexes ) :  // phpcs:ignore ?>
										<img src="<?php echo esc_url( MWB_CF7_INTEGRATION_WITH_ENGAGEBAY_URL . 'admin/images/trash.svg' ); ?>" class="dashicons-trash" alt="<?php esc_html_e( 'Trash', 'mwb-cf7-integration-with-engagebay' ); ?>">
									<?php endif; ?>
								</div>
							</div>
						<?php endforeach; ?>
					<?php endif; ?>
					<button data-next-or-index="<?php echo esc_html( ++$prefilled_indexes ); ?>" class="button condition-or-btn"><?php esc_html_e( 'Add "OR" filter', 'mwb-cf7-integration-with-engagebay' ); ?></button>
				</div>
			</div>
		</div>
	</div>
</div>


