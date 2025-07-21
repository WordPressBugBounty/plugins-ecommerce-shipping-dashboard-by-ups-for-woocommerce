<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.ups.com
 * @since      1.0.1
 *
 * @package    UPSESD
 * @subpackage UPSESD/includes
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
 * @since      1.0.1
 * @package    UPSESD
 * @subpackage UPSESD/includes
 * @author UPS eCommerce Shipping Dashboard
 */
class UPSESD
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.1
     * @access   protected
     * @var      UPSESD_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.1
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.1
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
     * @since    1.0.1
     */
    public function __construct()
    {
        if (defined('UPS_VERSION')) {
            $this->version = UPS_VERSION;
        } else {
            $this->version = '1.0.6';
        }
        $this->plugin_name = 'ecommerce-shipping-dashboard-by-ups-for-woocommerce';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
        if ( is_multisite() ) {
			add_action( 'plugins_loaded', array( $this, 'add_wc_integrations_ups_for_nw' ) );
		}else{
			add_action( 'plugins_loaded', array( $this, 'add_wc_integrations_ups' ) );
		}
		add_filter( 'woocommerce_integrations', array( $this, 'add_ups_wc_integration_method' ) );
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - UPSESD_Loader. Orchestrates the hooks of the plugin.
     * - UPSESD_i18n. Defines internationalization functionality.
     * - UPSESD_Admin. Defines all hooks for the admin area.
     * - UPSESD_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.1
     * @access   private
     */
    private function load_dependencies()
    {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(__DIR__) . 'includes/class-ups-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(__DIR__) . 'includes/class-ups-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(__DIR__) . 'admin/class-ups-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(__DIR__) . 'public/class-ups-public.php';

        $this->loader = new UPSESD_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the UPSESD_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.1
     * @access   private
     */
    private function set_locale()
    {
        $plugin_i18n = new UPSESD_i18n();
        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.1
     * @access   private
     */
    private function define_admin_hooks()
    {

        $plugin_admin = new UPSESD_Admin($this->get_plugin_name(), $this->get_version());
        $this->loader->add_action('admin_init', $plugin_admin, 'ups_core_check_for_plugin_version_change');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        $this->loader->add_action('admin_menu', $plugin_admin, 'register_ups_admin_menu_page');
        $this->loader->add_action('admin_init', $plugin_admin, 'is_revoke_key_request');
        $this->loader->add_filter('plugin_action_links_' . UPS_PLUGIN_BASENAME, $plugin_admin, 'ups_settings_link');
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.1
     * @access   private
     */
    private function define_public_hooks()
    {

        $plugin_public = new UPSESD_Public($this->get_plugin_name(), $this->get_version());
        /* $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts'); */
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.1
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.1
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.1
     * @return    UPSESD_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.1
     * @return    string    The version number of the plugin.
     */
    public function get_version()
    {
        return $this->version;
    }

    /**
     * Include your integration class file.
     */
    public function add_wc_integrations_ups()
    {
        if (class_exists('WC_Integration')) {
            require_once plugin_dir_path(__DIR__) . 'includes/class-ups-wc-integration.php';
        }
    }

    /**
     * Include your integration class file for network.
     */
    public function add_wc_integrations_ups_for_nw($network_plugin)
    {
        if (class_exists('WC_Integration')) {
            require_once plugin_dir_path(__DIR__) . 'includes/class-ups-wc-integration.php';
        }
    }

    /**
     * Add a new ups integration to WooCommerce.
     *
     * @param array $integrations Get woocommerce integration.
     *
     * @return array
     */
    public function add_ups_wc_integration_method($integrations)
    {
        $integrations[] = 'UPSESD_WC_Integration';
        return $integrations;
    }
}
