<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_Cf7_Integration_With_Engagebay
 * @subpackage Mwb_Cf7_Integration_With_Engagebay/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Mwb_Cf7_Integration_With_Engagebay
 * @subpackage Mwb_Cf7_Integration_With_Engagebay/includes
 * @author     MakeWebBetter <https://makewebbetter.com/>
 */
class Mwb_Cf7_Integration_With_Engagebay {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Mwb_Cf7_Integration_With_Engagebay_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'MWB_CF7_INTEGRATION_WITH_ENGAGEBAY_VERSION' ) ) {
			$this->version = MWB_CF7_INTEGRATION_WITH_ENGAGEBAY_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'mwb-cf7-integration-with-engagebay';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_template_hooks();
		$this->define_feed_cpt_hooks();
		$this->define_ajax_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Mwb_Cf7_Integration_With_Engagebay_Loader. Orchestrates the hooks of the plugin.
	 * - Mwb_Cf7_Integration_With_Engagebay_I18n. Defines internationalization functionality.
	 * - Mwb_Cf7_Integration_With_Engagebay_Admin. Defines all hooks for the admin area.
	 * - Mwb_Cf7_Integration_With_Engagebay_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mwb-cf7-integration-with-engagebay-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mwb-cf7-integration-with-engagebay-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-mwb-cf7-integration-with-engagebay-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-mwb-cf7-integration-with-engagebay-public.php';

		/**
		 * The class responsible for handling ajax requests.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mwb-cf7-integration-with-engagebay-ajax-handler.php';

		/**
		 * The class responsible for all base api definitions of Engagebay crm in the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'mwb-crm-fw/api/class-mwb-cf7-integration-engagebay-api-base.php';

		/**
		 * The class responsible for all Engagebay api definitions in the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'mwb-crm-fw/api/class-mwb-cf7-integration-engagebay-api.php';

		/**
		 * The class responsible for handling of feeds module.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'mwb-crm-fw/framework/class-mwb-cf7-integration-engagebay-feed-module.php';

		/**
		 * The class responsible for defining all the templates that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'mwb-crm-fw/framework/class-mwb-cf7-integration-engagebay-template-manager.php';

		/**
		 * The class responsible for handling of connect framework.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'mwb-crm-fw/framework/class-mwb-cf7-integration-engagebay-framework.php';

		/**
		 * The class reponsible for handling Engagebay connect framework.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'mwb-crm-fw/framework/class-mwb-cf7-integration-connect-engagebay-framework.php';

		/**
		 * The class responsible for handling request module.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'mwb-crm-fw/framework/class-mwb-cf7-integration-engagebay-request-module.php';

		/**
		 * The class responsible for defining all actions that occur in the onboarding the site data
		 * in the admin side of the site.
		 */
		! class_exists( 'Mwb_Cf7_Integration_With_Engagebay_Onboarding' ) && require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mwb-cf7-integration-with-engagebay-onboarding.php';
		$this->onboard = new Mwb_Cf7_Integration_With_Engagebay_Onboarding();

		$this->loader = new Mwb_Cf7_Integration_With_Engagebay_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Mwb_Cf7_Integration_With_Engagebay_I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Mwb_Cf7_Integration_With_Engagebay_I18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Mwb_Cf7_Integration_With_Engagebay_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		// Add submenu.
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'mwb_cf7_integration_submenu' );
		// Admin init process.
		$this->loader->add_action( 'admin_init', $plugin_admin, 'mwb_cf7_integration_admin_init_process' );
		// Clear log callback.
		$this->loader->add_action( 'mwb_' . self::get_current_crm( 'slug' ) . '_cf7_clear_log', $plugin_admin, 'mwb_cf7_integration_clear_sync_log' );
		// Add onboarding screens.
		$this->loader->add_filter( 'mwb_helper_valid_frontend_screens', $plugin_admin, 'mwb_cf7_integration_add_frontend_screens' );
		// Add Deactivation screen.
		$this->loader->add_filter( 'mwb_deactivation_supported_slug', $plugin_admin, 'mwb_cf7_integration_add_deactivation_screens' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Mwb_Cf7_Integration_With_Engagebay_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		// Grab form data after validation.
		$this->loader->add_filter( 'wpcf7_before_send_mail', $plugin_public, 'mwb_cf7_integration_fetch_input_data', 99, 1 );

		// Get user data.
		$this->loader->add_action( 'wp_loaded', $plugin_public, 'mwb_cf7_integration_logged_user_info' );

	}

	/**
	 * Register all the hooks related to the template manager of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_template_hooks() {

		$plugin_template = new Mwb_Cf7_Integration_Engagebay_Template_Manager();

		$this->loader->add_action( 'mwb_' . self::get_current_crm( 'slug' ) . '_cf7_nav_tab', $plugin_template, 'render_navigation_tab' );
		$this->loader->add_action( 'mwb_' . self::get_current_crm( 'slug' ) . '_cf7_auth_screen', $plugin_template, 'render_authorisation_screen' );
	}

	/**
	 * Register all hooks related to the Feeds cpt of the plugin.
	 *
	 * @since     1.0.0
	 * @access    private
	 */
	private function define_feed_cpt_hooks() {

		$feed_module = new Mwb_Cf7_Integration_Engagebay_Feed_Module();

		// Register custom post type.
		$this->loader->add_action( 'init', $feed_module, 'register_feeds_post_type' );
		// Save metadata.
		$this->loader->add_action( 'save_post', $feed_module, 'save_feeds_data' );

	}

	/**
	 * Register all hooks related to ajax request of the plugin.
	 *
	 * @since     1.0.0
	 * @access    private
	 */
	private function define_ajax_hooks() {

		$plugin_ajax = new Mwb_Cf7_Integration_With_Engagebay_Ajax_Handler();

		// All ajax callbacks.
		$this->loader->add_action( 'wp_ajax_mwb_cf7_' . self::get_current_crm( 'slug' ) . '_ajax_request', $plugin_ajax, 'mwb_cf7_integration_ajax_callback' );
		// Data table callback.
		$this->loader->add_action( 'wp_ajax_get_datatable_logs', $plugin_ajax, 'get_datatable_data_cb' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Mwb_Cf7_Integration_With_Engagebay_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Returns current CRM name & slug.
	 *
	 * @param    string $get     Value to retrieve i.e slug or name.
	 * @since    1.0.0
	 * @return   string
	 */
	public static function get_current_crm( $get = '' ) {
		$slug = 'engagebay';
		if ( 'slug' == $get ) { // phpcs:ignore
			return esc_html( ( $slug ) );
		} else {
			return esc_html( ucwords( $slug ) );
		}
	}

}
