<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_Cf7_Integration_With_Engagebay
 * @subpackage Mwb_Cf7_Integration_With_Engagebay/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Mwb_Cf7_Integration_With_Engagebay
 * @subpackage Mwb_Cf7_Integration_With_Engagebay/admin
 * @author     MakeWebBetter <https://makewebbetter.com/>
 */
class Mwb_Cf7_Integration_With_Engagebay_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

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
	 * Instance of the plugin main class.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      object   $core_class    Name of the plugin core class.
	 */
	public $core_class = 'Mwb_Cf7_Integration_With_Engagebay';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string $plugin_name       The name of this plugin.
	 * @param    string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		// Initialise CRM name and slug.
		$this->crm_slug = $this->core_class::get_current_crm( 'slug' );
		$this->crm_name = $this->core_class::get_current_crm();

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Mwb_Cf7_Integration_With_Engagebay_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Mwb_Cf7_Integration_With_Engagebay_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		if ( $this->is_valid_screen() ) {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/mwb-cf7-integration-with-engagebay-admin.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->plugin_name . '-select2', plugin_dir_url( dirname( __FILE__ ) ) . 'packages/select2/select2.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->plugin_name . '-tooltip', plugin_dir_url( dirname( __FILE__ ) ) . 'packages/jq-tiptip/tooltip.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->plugin_name . '-datatable-css', plugin_dir_url( dirname( __FILE__ ) ) . 'packages/datatables/media/css/jquery.dataTables.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->plugin_name . '-animate', plugin_dir_url( dirname( __FILE__ ) ) . 'packages/animate/animate.min.css', array(), $this->version, 'all' );
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Mwb_Cf7_Integration_With_Engagebay_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Mwb_Cf7_Integration_With_Engagebay_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		if ( $this->is_valid_screen() ) {
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/mwb-cf7-integration-with-engagebay-admin.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( $this->plugin_name . '-select2', plugin_dir_url( dirname( __FILE__ ) ) . 'packages/select2/select2.min.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( $this->plugin_name . '-swal2', plugin_dir_url( dirname( __FILE__ ) ) . 'packages/sweet-alert2/sweet-alert2.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( $this->plugin_name . '-tooltip', plugin_dir_url( dirname( __FILE__ ) ) . 'packages/jq-tiptip/jquery.tipTip.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( $this->plugin_name . '-datatable-js', plugin_dir_url( dirname( __FILE__ ) ) . 'packages/datatables/media/js/jquery.dataTables.min.js', array(), $this->version, false );
			wp_enqueue_script( $this->plugin_name . '-datatable-responsive-js', plugin_dir_url( dirname( __FILE__ ) ) . 'packages/datatables.net-responsive/js/dataTables.responsive.min.js', array(), $this->version, false );

			$ajax_data = array(
				'crm'                     => $this->crm_slug,
				'ajaxUrl'                 => admin_url( 'admin-ajax.php' ),
				'ajaxNonce'               => wp_create_nonce( 'mwb_cf7_' . $this->crm_slug . '_nonce' ),
				'ajaxAction'              => 'mwb_cf7_' . $this->crm_slug . '_ajax_request',
				'feedBackLink'            => admin_url( 'admin.php?page=mwb_' . $this->crm_slug . '_cf7_page&tab=feeds' ),
				'feedBackText'            => esc_html__( 'Back to feeds', 'mwb-cf7-integration-with-engagebay' ),
				'isPage'                  => isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : '', // phpcs:ignore
				'criticalError'           => esc_html__( 'Internal server error', 'mwb-cf7-integration-with-engagebay' ),
				'trashIcon'               => MWB_CF7_INTEGRATION_WITH_ENGAGEBAY_URL . 'admin/images/trash.svg',
				'api_key_image'           => MWB_CF7_INTEGRATION_WITH_ENGAGEBAY_URL . 'admin/images/api.png',
				'get_api_title'           => __( 'How to get the API keys?', 'mwb-cf7-integration-with-engagebay' ),
				'sweetalert_button_text'  => __( 'Ok', 'mwb-cf7-integration-with-engagebay' ),
				'required_fields'         => __( 'All fields are required.', 'mwb-cf7-integration-with-engagebay' ),
				'valid_form'              => __( 'Select a valid CF7 form!!', 'mwb-cf7-integration-with-engagebay' ),
				'filter_valid_form'       => __( 'Please select a valid CF7 form', 'mwb-cf7-integration-with-engagebay' ),
				'valid_object_field'      => __( 'Select a valid Engagebay object and its corresponding field', 'mwb-cf7-integration-with-engagebay' ),
				'select_form'             => __( 'Please select a form first', 'mwb-cf7-integration-with-engagebay' ),
				'popup_process_title'     => __( 'Process Complete', 'mwb-cf7-integration-with-engagebay' ),
				'popup_error_title'       => __( 'Opps...!', 'mwb-cf7-integration-with-engagebay' ),
				'alert_title'             => __( 'Alert', 'mwb-cf7-integration-with-engagebay' ),
				'popup_error_msg'         => __( 'Something went wrong', 'mwb-cf7-integration-with-engagebay' ),
				'integer_value'           => __( 'Value must be Integer', 'mwb-cf7-integration-with-engagebay' ),
				'ajax_loading'            => __( 'Loading...', 'mwb-cf7-integration-with-engagebay' ),
				'download_text'           => __( 'Download', 'mwb-cf7-integration-with-engagebay' ),
				'clear_log'               => __( 'Clear Log', 'mwb-cf7-integration-with-engagebay' ),
				'condition_filter_value'  => __( 'Enter value', 'mwb-cf7-integration-with-engagebay' ),
				'feed_active_text'        => __( 'Active', 'mwb-cf7-integration-with-engagebay' ),
				'sandbox_text'            => __( 'Sandbox', 'mwb-cf7-integration-with-engagebay' ),
				'feed_title_empty'        => __( '*Title field cant\'t be empty', 'mwb-cf7-integration-with-engagebay' ),
				'select_field_filter'     => __( 'Select Field', 'mwb-cf7-integration-with-engagebay' ),
				'select_condition_filter' => __( 'Select Condition', 'mwb-cf7-integration-with-engagebay' ),
				'rows_per_page'           => __( 'Rows per page', 'mwb-cf7-integration-with-engagebay' ),
			);

			wp_localize_script( $this->plugin_name, 'mwb_cf7_integration_ajax_data', $ajax_data );
		}
	}

	/**
	 * Check for the screens provided by the plugin.
	 *
	 * @since     1.0.0
	 * @return    bool
	 */
	public function is_valid_screen() {

		$result = false;

		$valid_screens = array(
			'mwb_' . $this->crm_slug . '_cf7_page',
			'mwb_' . $this->crm_slug . '_cf7',
		);

		$screen = get_current_screen();

		if ( ! empty( $screen->id ) ) {

			$pages = $screen->id;

			foreach ( $valid_screens as $screen ) {
				if ( false !== strpos( $pages, $screen ) ) { // phpcs:ignore
					$result = true;
				}
			}
		}

		return $result;
	}

	/**
	 * Add Engagebay submenu to Contact menu.
	 *
	 * @since     1.0.0
	 * @return    void
	 */
	public function mwb_cf7_integration_submenu() {
		add_submenu_page(
			'wpcf7',
			'Engagebay',
			'Engagebay',
			'manage_options',
			'mwb_' . $this->crm_slug . '_cf7_page',
			array( $this, 'mwb_cf7_integration_submenu_cb' ),
			4
		);
	}

	/**
	 * Engagebay sub-menu callback function.
	 *
	 * @since     1.0.0
	 * @return    void
	 */
	public function mwb_cf7_integration_submenu_cb() {
		require_once MWB_CF7_INTEGRATION_WITH_ENGAGEBAY_DIRPATH . 'admin/partials/mwb-cf7-integration-with-' . $this->crm_slug . '-admin-display.php';
	}

	/**
	 * Function to run at admin intitialization.
	 *
	 * @since     1.0.0
	 * @return    bool
	 */
	public function mwb_cf7_integration_admin_init_process() {

		if ( 'engagebay' != $this->crm_slug ) { // phpcs:ignore
			return;
		}

		if ( ! empty( $_GET['mwb-cf7-engagebay-integration-perform-auth'] ) ) {
			if ( isset( $_GET['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) ) ) {

				$api_key  = ! empty( $_GET['api_key'] ) ? sanitize_text_field( wp_unslash( $_GET['api_key'] ) ) : false;
				$base_url = ! empty( $_GET['base_url'] ) ? sanitize_text_field( wp_unslash( $_GET['base_url'] ) ) : false;

				update_option( 'mwb-cf7-' . $this->crm_slug . '-api-key', $api_key );
				update_option( 'mwb-cf7-' . $this->crm_slug . '-base-url', $base_url );
				update_option( 'mwb-cf7-' . $this->crm_slug . '-crm-connected', true );

				wp_redirect( admin_url( 'admin.php?page=mwb_' . $this->crm_slug . '_cf7_page&mwb_auth=1' ) ); // phpcs:ignore
				exit;

			}
		} elseif ( ! empty( $_GET['mwb-cf7-engagebay-integration-perform-reauth'] ) ) {
			if ( isset( $_GET['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) ) ) {

				$api_key = ! empty( $_GET['api_key'] ) ? sanitize_text_field( wp_unslash( $_GET['api_key'] ) ) : false;

				update_option( 'mwb-cf7-' . $this->crm_slug . '-api-key', $api_key );
				update_option( 'mwb-cf7-' . $this->crm_slug . '-crm-connected', true );

				wp_redirect( admin_url( 'admin.php?page=mwb_' . $this->crm_slug . '_cf7_page&mwb_reauth=1' ) ); // phpcs:ignore
				exit;

			}
		} elseif ( ! empty( $_GET['mwb-cf7-perform-refresh'] ) ) { // Perform refresh token.

			$crm_class      = 'Mwb_Cf7_Integration_' . $this->crm_name . '_Api';
			$crm_api_module = $crm_class::get_instance();
			$crm_api_module->renew_access_token();
			wp_redirect( admin_url( 'admin.php?page=mwb_' . $this->crm_slug . '_cf7_page' ) ); // phpcs:ignore
			exit;

		}

		/* Download log file */
		if ( ! empty( $_GET['mwb_contact_form_download'] ) ) { // Perform log file download.
			$filename = WP_CONTENT_DIR . '/uploads/mwb-' . $this->crm_slug . '-cf7-logs/mwb-' . $this->crm_slug . '-cf7-sync-log.log';
			header( 'Content-type: text/plain' );
			header( 'Content-Disposition: attachment; filename="' . basename( $filename ) . '"' );
			readfile( $filename ); // phpcs:ignore
			exit;
		}

	}

	/**
	 * Get plugin name and version.
	 *
	 * @since    1.0.0
	 * @return   array
	 */
	public function add_plugin_headings() {

		$headings = array(
			'name'    => esc_html__( 'MWB CF7 INTEGRATION WITH ENGAGEBAY', 'mwb-cf7-integration-with-engagebay' ),
			'version' => MWB_CF7_INTEGRATION_WITH_ENGAGEBAY_VERSION,
		);

		return apply_filters( 'mwb_' . $this->crm_slug . '_cf7_plugin_headings', $headings );
	}

	/**
	 * Tooltip icon and tooltip data.
	 *
	 * @param     string $tip Tip to display.
	 * @since     1.0.0
	 * @return    void
	 */
	public static function mwb_cf7_integration_tooltip( $tip ) {
		$crm_slug = Mwb_Cf7_Integration_With_Engagebay::get_current_crm( 'slug' );
		?>
			<i class="mwb_<?php echo esc_attr( $crm_slug ); ?>_cf7_tips" data-tip="<?php echo esc_html( $tip ); ?>"><span class="dashicons dashicons-editor-help"></span></i> 
		<?php

	}

	/**
	 * Clear sync log callback.
	 *
	 * @since     1.0.0
	 * @return    void
	 */
	public function mwb_cf7_integration_clear_sync_log() {
		$connect          = 'Mwb_Cf7_Integration_Connect_' . $this->crm_slug . '_Framework';
		$connect_manager  = $connect::get_instance();
		$delete_duration  = $connect_manager->get_settings_details( 'delete_logs' );
		$delete_timestamp = time() - ( $delete_duration * 24 * 60 * 60 );
		$connect_manager->mwb_cf7_engagebay_clear_log_data( $delete_timestamp );
	}

	/**
	 * Get all valid screens to add scripts and templates.
	 *
	 * @param     array $valid_screens An array of plugin scrrens.
	 * @since     1.0.0
	 * @return    array
	 */
	public function mwb_cf7_integration_add_frontend_screens( $valid_screens = array() ) {

		if ( is_array( $valid_screens ) ) {

			// Push your screen here.
			array_push( $valid_screens, 'contact_page_mwb_' . $this->crm_slug . '_cf7_page' );
		}

		return $valid_screens;
	}

	/**
	 * Get all valid slugs to add deactivate popup.
	 *
	 * @param     array $valid_screens An array of plugin scrrens.
	 * @since     1.0.0
	 * @return    array
	 */
	public function mwb_cf7_integration_add_deactivation_screens( $valid_screens = array() ) {

		if ( is_array( $valid_screens ) ) {

			// Push your screen here.
			array_push( $valid_screens, 'mwb-cf7-integration-with-' . $this->crm_slug );
		}

		return $valid_screens;
	}

	/**
	 * Returns if pro plugin is active or not.
	 *
	 * @since      1.0.0
	 * @return     bool
	 */
	public static function pro_dependency_check() {

		// Check if pro plugin exists.
		if ( mwb_eb_cf7_is_plugin_active( 'cf7-integration-with-engagebay/cf7-integration-with-engagebay.php' ) ) {

			if ( class_exists( 'Cf7_Integration_With_Engagebay_Admin' ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Checks Whether Pro version is compatible or not.
	 *
	 * @since      1.0.0
	 * @return     bool|string
	 */
	public static function version_compatibility_check() {

		if ( self::pro_dependency_check() ) {

			// When Pro plugin is outdated.
			if ( defined( 'CF7_INTEGRATION_WITH_ENGAGEBAY_VERSION' ) && version_compare( CF7_INTEGRATION_WITH_ENGAGEBAY_VERSION, '1.0.0' ) < 0 ) {

				return 'incompatible';
			} else {

				return 'compatible';
			}
		}

		return false;
	}

	/**
	 * Validate Pro version compatibility.
	 *
	 * @since     1.0.0
	 * @return    void
	 */
	public function mwb_cf7_integration_version_compatibility() {

		// When Pro version in incompatible.
		if ( 'incompatible' == self::version_compatibility_check() ) { // phpcs:ignore

			set_transient( 'mwb_' . $this->crm_slug . '_cf7_pro_version_incompatible', 'true' );

			// Deactivate Pro Plugin.
			add_action( 'admin_init', array( $this, 'mwb_cf7_integration_deactivate_pro_plugin' ) );

		} elseif ( 'compatible' == self::version_compatibility_check() && 'true' == get_transient( 'mwb_' . $this->crm_slug . '_cf7_pro_version_incompatible' ) ) {  // phpcs:ignore
			// When Pro version in compatible and transient is set.
			delete_transient( 'mwb_' . $this->crm_slug . '_cf7_pro_version_incompatible' );
		}

		if ( 'true' == get_transient( 'mwb_' . $this->crm_slug . '_cf7_pro_version_incompatible' ) ) { // phpcs:ignore

			// Deactivate Pro Plugin admin notice.
			add_action( 'admin_notices', array( $this, 'mwb_cf7_integration_deactivate_pro_admin_notice' ) );
		}
	}

	/**
	 * Deactivate Pro Plugin.
	 *
	 * @since     1.0.0
	 * @return    void
	 */
	public function mwb_cf7_integration_deactivate_pro_plugin() {

		// To hide Plugin activated notice.
		if ( ! empty( $_GET['activate'] ) ) { //phpcs:ignore

			unset( $_GET['activate'] ); //phpcs:ignore
		}

		deactivate_plugins( 'cf7-integration-with-engagebay/cf7-integration-with-engagebay.php' );
	}

	/**
	 * Deactivate Pro Plugin admin notice.
	 *
	 * @since     1.0.0
	 * @return    void
	 */
	public function mwb_cf7_integration_deactivate_pro_admin_notice() {

		$screen = get_current_screen();

		$valid_screens = array(
			'mwb_' . $this->crm_slug . '_cf7_page',
			'plugins',
		);

		if ( ! empty( $screen->id ) && in_array( $screen->id, $valid_screens ) ) { // phpcs:ignore
			?>

			<div class="notice notice-error is-dismissible mwb-notice">
				<p><strong><?php esc_html_e( 'CF7 INTEGRATION WITH ENGAGEBAY', 'mwb-cf7-integration-with-engagebay' ); ?></strong> <?php esc_html_e( 'is deactivated, Please Update the PRO version as this version is outdated and will not work with the current', 'mwb-cf7-integration-with-engagebay' ); ?><strong><?php esc_html_e( 'MWB CF7 INTEGRATION WITH ENGAGEBAY', 'mwb-cf7-integration-with-engagebay' ); ?></strong> <?php esc_html_e( 'Org version.', 'mwb-cf7-integration-with-engagebay' ); ?></p>
			</div>

			<?php
		}
	}

	/**
	 * Check if pro plugin active and trail not expired.
	 *
	 * @since    1.0.0
	 * @return   bool
	 */
	public static function is_pro_available_and_active() {
		$result   = false;
		$crm_name = Mwb_Cf7_Integration_With_Engagebay::get_current_crm();
		$pro_main = 'Cf7_Integration_With_' . $crm_name;
		if ( self::pro_dependency_check() ) {

			$license    = $pro_main::$mwb_cf7_pro_license_cb;
			$ini_days   = $pro_main::$mwb_cf7_pro_ini_license_cb;
			$days_count = $pro_main::$ini_days();

			if ( ! $pro_main::$license() && 0 > $days_count ) {
				$result = true;
			}
		} elseif ( false === self::pro_dependency_check() ) {
			$result = true;
		}
		return $result;
	}

}
