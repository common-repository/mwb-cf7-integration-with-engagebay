<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_Cf7_Integration_With_Engagebay
 * @subpackage Mwb_Cf7_Integration_With_Engagebay/mwb-crm-fw/framework
 */

if ( ! class_exists( 'Mwb_Cf7_Integration_Engagebay_Framework' ) ) {
	wp_die( 'Mwb_Cf7_Integration_Engagebay_Framework does not exists.' );
}

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Mwb_Cf7_Integration_With_Engagebay
 * @subpackage Mwb_Cf7_Integration_With_Engagebay/mwb-crm-fw/framework
 * @author     makewebbetter <webmaster@makewebbetter.com>
 */
class Mwb_Cf7_Integration_Connect_Engagebay_Framework extends Mwb_Cf7_Integration_Engagebay_Framework {

	/**
	 *  The instance of this class.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $instance    The instance of this class.
	 */
	private static $instance;

	/**
	 * Main Mwb_Cf7_Integration_Connect_Engagebay_Framework Instance.
	 *
	 * Ensures only one instance of Mwb_Cf7_Integration_Connect_Engagebay_Framework is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @return Mwb_Cf7_Integration_Connect_Engagebay_Framework - Main instance.
	 */
	public static function get_instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Get current mapping scenerio for current CRM connection.
	 *
	 * @param    mixed $form_id   CF7 Form ID.
	 * @since    1.0.0
	 * @return   array - Current CRM to CF7 mapping.
	 */
	public function getMappingDataset( $form_id = '' ) {

		if ( empty( $form_id ) ) {
			return;
		}

		$obj_type = array(
			'wpcf7',
		);

		$formatted_dataset = array();
		foreach ( $obj_type as $key => $obj ) {
			$formatted_dataset[ $obj ] = $this->getMappingOptions( $form_id );
		}

		$formatted_dataset = $this->parse_labels( $formatted_dataset );
		return $formatted_dataset;
	}

	/**
	 * Get current mapping scenerio for current CRM connection.
	 *
	 * @param    string $id    CF7 form ID.
	 * @since    1.0.0
	 * @return   array         Current CRM to CF7 mapping.
	 */
	public function getMappingOptions( $id = false ) {
		return $this->get_cf7_meta( $id );
	}

	/**
	 * Get available filter options.
	 *
	 * @since    1.0.0
	 * @return   array
	 */
	public function getFilterMappingDataset() {
		return $this->get_avialable_form_filters();
	}

	/**
	 * Create log folder.
	 *
	 * @param     string $path    Name of log folder.
	 * @since     1.0.0
	 * @return    mixed
	 */
	public function create_log_folder( $path ) {

		$basepath = WP_CONTENT_DIR . '/uploads/';
		$fullpath = $basepath . $path;

		if ( ! empty( $fullpath ) ) {

			if ( ! is_dir( $fullpath ) ) {

				$folder = mkdir( $fullpath, 0755, true );

				if ( $folder ) {
					return $fullpath;
				}
			} else {
				return $fullpath;
			}
		}
		return false;
	}

	/**
	 * Get link to data sent over crm.
	 *
	 * @param      string $crm_id   An array of data synced over crm.
	 * @param      string $feed_id  Feed ID.
	 * @since      1.0.0
	 * @return     string
	 */
	public function get_crm_link( $crm_id = false, $feed_id ) {

		if ( false == $crm_id || empty( $feed_id ) ) { // phpcs:ignore
			return;
		}
		$link = '';

		$base_url = get_option( 'mwb-cf7-' . $this->crm_slug . '-base-url', false );
		$module   = get_post_meta( $feed_id, 'mwb-' . $this->crm_slug . '-cf7-object', true );

		if ( ! empty( $module ) ) {
			if ( ! empty( $base_url ) ) {
				if ( 'Contacts' === $module ) {
					$link = $base_url . '/home#list/0/subscriber/' . $crm_id;
				} elseif ( 'Deals' === $module ) {
					$link = $base_url . '/home#deal/' . $crm_id;
				} elseif ( 'Companies' === $module ) {
					$link = $base_url . '/home#company/' . $crm_id;
				} elseif ( 'Tasks' === $module ) {
					$link = $base_url . '/home#tasks/' . $crm_id;
				}
			}
		}

		return $link;

	}

	/**
	 * Returns count of synced data.
	 *
	 * @since     1.0.0
	 * @return    integer
	 */
	public function get_synced_forms_count() {

		global $wpdb;
		$table_name  = $wpdb->prefix . 'mwb_engagebay_cf7_log';
		$col_name    = 'engagebay_id';
		$count_query = "SELECT COUNT(*) as `total_count` FROM {$table_name} WHERE {$col_name} != '-'"; // phpcs:ignore
		$count_data  = $wpdb->get_col( $count_query ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		$total_count = isset( $count_data[0] ) ? $count_data[0] : '0';

		return $total_count;
	}

	/**
	 * Loads plugin templates.
	 *
	 * @param     string $file_path     Relative path of file.
	 * @since     1.0.0
	 * @return    void
	 */
	public static function load_template( $file_path = '' ) {

		$filepath = MWB_CF7_INTEGRATION_WITH_ENGAGEBAY_DIRPATH . $file_path;
		if ( file_exists( $filepath ) ) {
			require_once $filepath;
		} else {
			echo esc_html__( 'The file "', 'mwb-cf7-integration-with-engagebay' ) . $filepath . esc_html__( '" does not exist.', 'mwb-cf7-integration-with-engagebay' );
		}
	}

	// End of class.
}
