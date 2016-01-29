<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the dashboard.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    HBI_Premium_Ads
 * @subpackage HBI_Premium_Ads/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, dashboard-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    HBI_Premium_Ads
 * @subpackage HBI_Premium_Ads/includes
 * @author     Marc Palmer <mapalmer@hbi.com>
 */
class HBI_Premium_Ads {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      HBI_Premium_Ads_Loader    $loader    Maintains and registers all hooks for the plugin.
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
	 * Load the dependencies, define the locale, and set the hooks for the Dashboard and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'hbi-premium-ads';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - HBI_Premium_Ads_Loader. Orchestrates the hooks of the plugin.
	 * - HBI_Premium_Ads_i18n. Defines internationalization functionality.
	 * - HBI_Premium_Ads_Admin. Defines all hooks for the dashboard.
	 * - HBI_Premium_Ads_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-hbi-premium-ads-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-hbi-premium-ads-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the Dashboard.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-hbi-premium-ads-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-hbi-premium-ads-public.php';
        
		$this->loader = new HBI_Premium_Ads_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the HBI_Premium_Ads_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new HBI_Premium_Ads_i18n();
		$plugin_i18n->set_domain( $this->get_plugin_name() );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the dashboard functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new HBI_Premium_Ads_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
        $this->loader->add_action( 'admin_init', $plugin_admin, 'register_hbi_premium_ads_display_settings' );
        $this->loader->add_action( 'admin_init', $plugin_admin, 'register_hbi_premium_ads_wrap_ad_settings' );
        $this->loader->add_action( 'admin_init', $plugin_admin, 'register_hbi_premium_ads_expanding_ad_settings' );
        $this->loader->add_action( 'admin_init', $plugin_admin, 'register_hbi_premium_ads_leaderboard_ad_settings' );
        $this->loader->add_action( 'init', $plugin_admin, 'register_takeover_post_type' );
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'hbi_premium_ads_options_page' );
        
        /**
         * Handle the meta box for on-posttype customization
         */
        $this->loader->add_action( 'load-post.php', $plugin_admin, 'register_hbi_premium_ads_metabox' );
        $this->loader->add_action( 'load-post-new.php', $plugin_admin, 'register_hbi_premium_ads_metabox' );
        $this->loader->add_action( 'save_post', $plugin_admin, 'validate_metaboxes' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		$plugin_public = new HBI_Premium_Ads_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
        $this->loader->add_action( 'display_expanding_ad', $plugin_public, 'display_expanding_ad' );
        $this->loader->add_action( 'display_wrap_ads', $plugin_public, 'display_wrap_ads' );
        $this->loader->add_action( 'display_leaderboard_ad', $plugin_public, 'display_leaderboard_ad' );
        $this->loader->add_action( 'wp_head', $plugin_public, 'add_takeover_background' );
        $this->loader->add_filter( 'body_class', $plugin_public, 'add_body_classes' );
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
	 * @return    HBI_Premium_Ads_Loader    Orchestrates the hooks of the plugin.
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
}
