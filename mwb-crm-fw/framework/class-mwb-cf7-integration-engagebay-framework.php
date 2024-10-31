<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @package    Mwb_Cf7_Integration_With_Engagebay
 * @subpackage Mwb_Cf7_Integration_With_Engagebay/mwb-crm-fw/framework
 *
 * @link  https://makewebbetter.com/
 * @since 1.0.0
 */

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
class Mwb_Cf7_Integration_Engagebay_Framework {

	/**
	 * Current crm slug.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $crm_slug    The current crm slug.
	 */
	public $crm_slug;

	/**
	 * Current crm name.
	 *
	 * @since     1.0.0
	 * @access    public
	 * @var       string   $crm_name    The current crm name.
	 */
	public $crm_name;

	/**
	 *  The instance of this class.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $instance    The instance of this class.
	 */
	private static $instance;

	/**
	 * Instance of the Mwb_Cf7_Integration_Engagebay_Api_Base class.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      object   $crm_api_module   Instance of Mwb_Cf7_Integration_Engagebay_Api_Base class.
	 */
	public $crm_api_module;

	/**
	 * Current CRM API class.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $crm_class   Name of the current CRM API class.
	 */
	public $crm_class = '';

	/**
	 * Instance of the plugin main class.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      object   $core_class    Name of the plugin core class.
	 */
	public $core_class = 'Mwb_Cf7_Integration_With_Engagebay';

	/**
	 * Main Mwb_Cf7_Integration_Connect_Framework Instance.
	 *
	 * Ensures only one instance of Mwb_Cf7_Integration_Connect_Framework is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @return Mwb_Cf7_Integration_Connect_Framework - Main instance.
	 */
	public static function get_instance() {

		if ( is_null( self::$instance ) ) {

			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Main Mwb_Cf7_Integration_Connect_Framework Instance.
	 *
	 * Ensures only one instance of Mwb_Cf7_Integration_Connect_Framework is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		self::$instance = $this;

		// Initialise CRM name and slug.
		$this->crm_slug = $this->core_class::get_current_crm( 'slug' );
		$this->crm_name = $this->core_class::get_current_crm();

		// Initialise CRM API class.
		$this->crm_class      = 'Mwb_Cf7_Integration_' . $this->crm_name . '_Api';
		$this->crm_api_module = $this->crm_class::get_instance();

	}

	/**
	 * Returns the mapping index from CF7 we require.
	 *
	 * @param    int $cf_id   The object we need index for.
	 * @since    1.0.0
	 * @return   array|bool     The current mapping step required.
	 */
	public function get_cf7_meta( $cf_id = false ) {

		if ( false == $cf_id ) { // phpcs:ignore
			return;
		}

		$fields = array();

		$id         = $cf_id;
		$form_input = get_post_meta( $id, '_form', true );

		if ( class_exists( 'WPCF7_FormTagsManager' ) ) {
			$tag_manager = WPCF7_FormTagsManager::get_instance();
			$tag_manager->scan( $form_input );
			$form_tags = $tag_manager->get_scanned_tags();

		} elseif ( class_exists( 'WPCF7_ShortcodeManager' ) ) {
			$tag_manager = WPCF7_ShortcodeManager::get_instance();
			$tag_manager->do_shortcode( $form_input );
			$form_tags = $tag_manager->get_scanned_tags();
		}

		if ( ! empty( $form_tags ) && is_array( $form_tags ) ) {

			foreach ( $form_tags as $tag ) {

				if ( is_object( $tag ) ) {
					$tag = (array) $tag;
				}

				if ( ! empty( $tag['name'] ) ) {

					$id           = str_replace( ' ', '', $tag['name'] );
					$tag['label'] = ucwords( str_replace( array( '-', '_' ), ' ', $tag['name'] ) );
					$tag['type_'] = $tag['type'];
					$tag['type']  = $tag['basetype'];
					$tag['req']   = false !== strpos( $tag['type'], '*' ) ? 'true' : '';

					if ( 'select' == $tag['type'] && ! empty( $tag['options'] ) && false !== array_search( 'multiple', $tag['options'] ) ) { // @codingStandardsIgnoreLine
						$tag['type'] = 'multiselect';
					}

					$fields[ $id ] = $tag;
				}
			}

			$fields = apply_filters( 'mwb_' . $this->crm_slug . '_cf7_form_fields', $fields );
		}

		return ! empty( $fields ) ? $fields : false;
	}

	/**
	 * Current CF7 fields keys with Labels.
	 *
	 * @param array $dataset array for woo keys.
	 *
	 * @since 1.0.0
	 *
	 * @return array - Current Woo meta keys with Labels to Woo keys.
	 */
	public function parse_labels( $dataset ) {

		$fields = array();
		if ( ! empty( $dataset ) && is_array( $dataset ) ) {
			foreach ( $dataset as $key => $value ) {

				if ( ! empty( $value ) && is_array( $value ) ) {
					foreach ( $value as $k => $v ) {

						// Create CF7 Index & Labels.
						$fields[ $v['name'] ] = $v['label'];
					}
				}
			}
		}
		return array(
			'CF7 Fields' => $fields,
		);
	}

	/**
	 * Feeds conditional filter options.
	 *
	 * @since     1.0.0
	 * @return    array
	 */
	public function get_avialable_form_filters() {

		$filter = array(
			'exact_match'       => esc_html__( 'Matches exactly', 'mwb-cf7-integration-with-engagebay' ),
			'no_exact_match'    => esc_html__( 'Does not match exactly', 'mwb-cf7-integration-with-engagebay' ),
			'contains'          => esc_html__( 'Contains (Text)', 'mwb-cf7-integration-with-engagebay' ),
			'not_contains'      => esc_html__( 'Does not contain (Text)', 'mwb-cf7-integration-with-engagebay' ),
			'exist'             => esc_html__( 'Exist in (Text)', 'mwb-cf7-integration-with-engagebay' ),
			'not_exist'         => esc_html__( 'Does not Exists in (Text)', 'mwb-cf7-integration-with-engagebay' ),
			'starts'            => esc_html__( 'Starts with (Text)', 'mwb-cf7-integration-with-engagebay' ),
			'not_starts'        => esc_html__( 'Does not start with (Text)', 'mwb-cf7-integration-with-engagebay' ),
			'ends'              => esc_html__( 'Ends with (Text)', 'mwb-cf7-integration-with-engagebay' ),
			'not_ends'          => esc_html__( 'Does not end with (Text)', 'mwb-cf7-integration-with-engagebay' ),
			'less_than'         => esc_html__( 'Less than (Text)', 'mwb-cf7-integration-with-engagebay' ),
			'greater_than'      => esc_html__( 'Greater than (Text)', 'mwb-cf7-integration-with-engagebay' ),
			'less_than_date'    => esc_html__( 'Less than (Date/Time)', 'mwb-cf7-integration-with-engagebay' ),
			'greater_than_date' => esc_html__( 'Greater than (Date/Time)', 'mwb-cf7-integration-with-engagebay' ),
			'equal_date'        => esc_html__( 'Equals (Date/Time)', 'mwb-cf7-integration-with-engagebay' ),
			'empty'             => esc_html__( 'Is empty', 'mwb-cf7-integration-with-engagebay' ),
			'not_empty'         => esc_html__( 'Is not empty', 'mwb-cf7-integration-with-engagebay' ),
		);

		return apply_filters( 'mwb_' . $this->crm_slug . '_cf7_condition_filter', $filter );
	}

	/**
	 * Get title of a particur feed.
	 *
	 * @param    int $feed_id    ID of feed.
	 * @since    1.0.0
	 * @return   array.
	 */
	public function get_feed_title( $feed_id ) {
		$title = esc_html__( 'Feed #', 'mwb-cf7-integration-with-engagebay' ) . $feed_id;
		$feed  = get_post( $feed_id );
		$title = ! empty( $feed->post_title ) ? $feed->post_title : $title;
		return $title;
	}

	/**
	 * Check if crm is connected.
	 *
	 * @since    1.0.0
	 * @return   boolean
	 */
	public function is_crm_connected() {
		$connect = get_option( 'mwb-cf7-' . $this->crm_slug . '-crm-active', false );

		if ( false != $connect ) { // phpcs:ignore
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Get all Contact forms.
	 *
	 * @since     1.0.0
	 * @return    array    An array of all contact forms.
	 */
	public function get_available_cf7_forms() {

		$args = array(
			'post_type'   => 'wpcf7_contact_form',
			'numberposts' => -1,
			'order'       => 'ASC',
		);

		return get_posts( $args );
	}

	/**
	 * Get available crm objects.
	 *
	 * @since    1.0.0
	 * @return   array $crm_objects    An array of crm objects.
	 */
	public function get_available_crm_objects() {

		$not_connected = esc_html__( 'Application is not connected to Engagebay', 'mwb-cf7-integration-with-engagebay' );

		$crm_objects = $this->crm_api_module->get_modules_data();

		return isset( $crm_objects ) ? $crm_objects : $not_connected;
	}

	/**
	 * Get all feeds.
	 *
	 * @since     1.0.0
	 * @return    array    An array of all feeds.
	 */
	public function get_available_crm_feeds() {

		$args = array(
			'post_type'   => 'mwb_' . $this->crm_slug . '_cf7',
			'post_status' => array( 'publish', 'draft' ),
			'numberposts' => -1,
			'order'       => 'ASC',
			'meta_query'  => array( // phpcs:ignore
				array(
					'key'     => 'mwb-' . $this->crm_slug . '-cf7-dependent-on',
					'compare' => 'NOT EXISTS',
				),
			),
		);

		return get_posts( $args );
	}

	/**
	 * Get token expiry details.
	 *
	 * @since     1.0.0
	 * @return    bool|integer
	 */
	public function get_crm_token_details() {

		$owner_account = get_option( 'mwb-cf7-' . $this->crm_slug . '-owner-account', false );

		if ( empty( $owner_account ) ) {

			$owner_account = $this->crm_api_module->get_owner_account();
		}

		return $owner_account;
	}

	/**
	 * Default settings of the plugin.
	 *
	 * @since     1.0.0
	 * @return    array
	 */
	public function get_plugin_default_settings() {

		$default_settings = array(
			'enable_logs'  => 'yes',
			'data_delete'  => 'no',
			'enable_sync'  => 'yes',
			'enable_notif' => 'no',
			'email_notif'  => get_bloginfo( 'admin_email' ),
			'delete_logs'  => 7,
		);

		return $default_settings;
	}


	/**
	 * Get settings of the plugin.
	 *
	 * @param      string $setting    Setting value to get.
	 * @since      1.0.0
	 * @return     string
	 */
	public function get_settings_details( $setting = false ) {

		if ( empty( $setting ) || false == $setting ) { // phpcs:ignore
			return false;
		}

		$result = '';
		$option = get_option( 'mwb-' . $this->crm_slug . '-cf7-setting', $this->get_plugin_default_settings() );

		if ( ! empty( $option ) && is_array( $option ) ) {

			switch ( $setting ) {

				case 'logs':
					$result = ! empty( $option['enable_logs'] ) ? sanitize_text_field( wp_unslash( $option['enable_logs'] ) ) : '';
					break;

				case 'delete':
					$result = ! empty( $option['data_delete'] ) ? sanitize_text_field( wp_unslash( $option['data_delete'] ) ) : '';
					break;

				case 'notif':
					$result = ! empty( $option['enable_notif'] ) ? sanitize_text_field( wp_unslash( $option['enable_notif'] ) ) : '';
					break;

				case 'email':
					$result = ! empty( $option['email_notif'] ) ? sanitize_email( wp_unslash( $option['email_notif'] ) ) : '';
					break;

				case 'delete_logs':
					$result = ! empty( $option['delete_logs'] ) ? sanitize_text_field( wp_unslash( $option['delete_logs'] ) ) : '30';
					break;

				case 'dont_save_sub':
					$result = ! empty( $option['disable_save_submissions'] ) ? sanitize_text_field( wp_unslash( $option['disable_save_submissions'] ) ) : '';
					break;

				case 'dont_track':
					$result = ! empty( $option['dont_save_submission'] ) ? map_deep( wp_unslash( $option['dont_save_submission'] ), 'sanitize_text_field' ) : array();
					break;

				case 'dont_track_ip':
					$result = ! empty( $option['disable_ip_tracking'] ) ? sanitize_text_field( wp_unslash( $option['disable_ip_tracking'] ) ) : '';
			}
		}
		return $result;
	}

	/**
	 * Change post status.
	 *
	 * @param      int    $id       Post ID.
	 * @param      string $status   Status of post.
	 * @return     bool
	 */
	public function change_post_status( $id, $status ) {

		if ( ! empty( $id ) && ! empty( $status ) ) {

			$post                = get_post( $id, 'ARRAY_A' );
			$post['post_status'] = $status;
			$response            = wp_update_post( $post );

			if ( $response && 0 != $response ) { // phpcs:ignore
				return true;
			}
		}
		return false;
	}

	/**
	 * Fetch all logs.
	 *
	 * @since     1.0.0
	 * @return    array
	 */
	public function get_sync_logs() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'mwb_' . $this->crm_slug . '_cf7_log';
		$query      = "SELECT * FROM `$table_name`";
		$response   = $wpdb->get_results( $query, ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		return $response;
	}

	/**
	 * Get feeds by form.
	 *
	 * @param     integer $form_id     Form ID.
	 * @since     1.0.0
	 * @return    array
	 */
	public function get_feeds_by_form( $form_id = false ) {
		if ( false == $form_id ) { // phpcs:ignore
			return;
		}

		$feeds = get_posts(
			array(
				'post_type'   => 'mwb_' . $this->crm_slug . '_cf7',
				'post_status' => array( 'publish', 'draft' ),
				'numberposts' => -1,
				'meta_query'  => array( // @codingStandardsIgnoreLine
					array(
						'relation' => 'AND',
						array(
							'key'     => 'mwb-' . $this->crm_slug . '-cf7-form',
							'compare' => 'EXISTS',
						),
						array(
							'key'     => 'mwb-' . $this->crm_slug . '-cf7-form',
							'value'   => $form_id,
							'compare' => '==',
						),
					),
				),
			)
		);

		return $feeds;
	}

	/**
	 * Clear log table.
	 *
	 * @since     1.0.0
	 * @return    void
	 */
	public function delete_sync_log() {
		global $wpdb;
		$table_name     = $wpdb->prefix . 'mwb_' . $this->crm_slug . '_cf7_log';
		$log_data_query = "TRUNCATE TABLE {$table_name}"; // phpcs:ignore
		$wpdb->query( $log_data_query, ARRAY_A ); // @phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

		// delete the existing log file.
		$log_file = WP_CONTENT_DIR . '/uploads/mwb-' . $this->crm_slug . '-cf7-logs/mwb-' . $this->crm_slug . '-cf7-sync-log.log';
		if ( file_exists( $log_file ) ) {
			unlink( $log_file );
		}
	}

	/**
	 * Delete logs after N no days.
	 *
	 * @param  int $timestamp delete timestamp.
	 */
	public function mwb_cf7_engagebay_clear_log_data( $timestamp ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'mwb_' . $this->crm_slug . '_cf7_log';
		$query      = $wpdb->prepare( "DELETE FROM {$table_name} WHERE `time` < %d", $timestamp ); //phpcs:ignore
		$wpdb->query( $query ); //phpcs:ignore
	}

	// End of class.
}
