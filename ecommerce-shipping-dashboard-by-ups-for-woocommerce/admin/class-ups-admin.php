<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.ups.com
 * @since      1.0.1
 *
 * @package    UPSESD
 * @subpackage UPSESD/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    UPSESD
 * @subpackage UPSESD/admin
 * @author UPS eCommerce Shipping Dashboard
 */
class UPSESD_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.1
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.1
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.1
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
     */

    /**
     * The current instance id of itembase.
     *
     * @since    1.0.1
     * @access   protected
     * @var      string    $item_base_instance_id    The current instance id of itembase.
     */
    protected $item_base_instance_id;

    /**
     * The current itembase webhook url domain.
     *
     * @since    1.0.1
     * @access   protected
     * @var      string    $item_base_web_hook_url_domain    The current itembase webhook url domain.
     */
    protected $item_base_web_hook_url_domain;

    /**
     * The current itembase signup URL.
     *
     * @since    1.0.1
     * @access   protected
     * @var      string    $item_base_signup_url    The current itembase signup URL.
     */
    protected $item_base_signup_url;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.1
     * @param mixed $plugin_name get plugin name.
     * @param mixed $version get plugin version.
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name                   = $plugin_name;
        $this->version                       = $version;
        $this->item_base_instance_id         = UPS_ITEMBASE_INSTANCE_ID;
        $this->item_base_web_hook_url_domain = UPS_ITEMBASE_WEB_HOOK_URL_DOMAIN;
        $this->item_base_signup_url          = UPS_ITEMBASE_SIGN_UP_URL;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.1
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in UPSESD_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The UPSESD_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style(
            $this->plugin_name,
            plugin_dir_url(__FILE__) . 'css/ups-admin.css',
            array(),
            $this->version,
            'all'
        );
        wp_enqueue_style('postbox');
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.1
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in UPSESD_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The UPSESD_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script(
            $this->plugin_name,
            plugin_dir_url(__FILE__) . 'js/ups-admin.js',
            array('jquery'),
            $this->version,
            false
        );
        wp_localize_script(
            $this->plugin_name,
            'upsAjax',
            array('ajaxurl' => admin_url('admin-ajax.php'))
        );
    }

    /**
     * Register addmin menu on plugin activation
     *
     * @return void
     */
    public function register_ups_admin_menu_page()
    {
        add_menu_page(
            __('UPS', 'ecommerce-shipping-dashboard-by-ups-for-woocommerce'),
            __('UPS', 'ecommerce-shipping-dashboard-by-ups-for-woocommerce'),
            'manage_options',
            'ups-starting-here-page',
            array($this, 'ups_starting_here_page'),
            'dashicons-store',
            '50'
        );

        add_submenu_page(
            'ups-starting-here-page',
            __('UPS', 'ecommerce-shipping-dashboard-by-ups-for-woocommerce'),
            __('Start Here', 'ecommerce-shipping-dashboard-by-ups-for-woocommerce'),
            'manage_options',
            'ups-starting-here-page',
            '',
            0
        );
    }

    /**
     * Method ups_starting_here_page
     *
     * @return void
     */
    public function ups_starting_here_page()
    {
        if (class_exists('WC_Integration')) {
            self::add_new_api_integration();
        }
    }

    /**
     * Include file with varables
     *
     * @param string $file_path get file path.
     * @param array  $variables variables arry.
     * @param mixed  $print output print.
     *
     * @return mixed
     */
    public function include_with_variables($file_path, $variables = array(), $print = true)
    {
        extract($variables);
        ob_start();
        include_once $file_path;
        $output = ob_get_clean();
        if (! $print) {
            return $output;
        }
        echo $output;
    }

    /**
     * Returns consumer key for the current instance
     *
     * @return string
     */
    public function get_exisitng_consumer_key()
    {
        return 'ck_' . $this->item_base_instance_id;
    }

    /**
     * Returns exisitng API credentials associated with the current instance.
     *
     * @return array|null
     */
    public function get_existing_api_credentials()
    {
        global $wpdb;

        // Get the existing consumer key
        $data = $this->get_exisitng_consumer_key();

        // Define a cache key based on the consumer key
        $cache_key = 'existing_api_credentials_' . md5($data);

        // Try to get the cached result
        $cached_credentials = wp_cache_get($cache_key, 'api_credentials');

        if ($cached_credentials !== false) {
            return $cached_credentials; // Return cached credentials if available
        }

        // Fetch API credentials from the database
        $api_credentials = $wpdb->get_results( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
            $wpdb->prepare(
                'SELECT * FROM ' . $wpdb->prefix . 'woocommerce_api_keys WHERE consumer_key = %s',
                wc_api_hash($data)
            )
        );

        if (empty($api_credentials) || empty($api_credentials[0])) {
            return null;
        }

        // Cache the results for future use
        wp_cache_set($cache_key, (array) $api_credentials[0], 'api_credentials', HOUR_IN_SECONDS); // Cache for 1 hour
        return (array) $api_credentials[0];
    }

    /**
     * Add API keys and other integration of UPS to woocommerce
     */
    public function add_new_api_integration()
    {
        global $wpdb;
        global $wp_version;
        global $woocommerce;

        if (is_multisite()) {

            $id = get_current_blog_id();

            if (get_blog_option($id, 'permalink_structure') === '') {
                update_blog_option($id, 'permalink_structure', '/%postname%/');
            }

            /**
             * Enable WooCommerce API in case it's not enabled.
             */
            if (get_blog_option($id, 'woocommerce_api_enabled') !== 'yes') {
                update_blog_option($id, 'woocommerce_api_enabled', 'yes');
            }
        } else {

            if (get_option('permalink_structure') === '') {
                update_option('permalink_structure', '/%postname%/');
            }

            /**
             * Enable WooCommerce API in case it's not enabled.
             */
            if (get_option('woocommerce_api_enabled') !== 'yes') {
                update_option('woocommerce_api_enabled', 'yes');
            }
        }

        /**
         * Check if extension created already API consumer key
         */
        $api_credentials = $this->get_existing_api_credentials();

        /**
         * If API credentials were not created
         */
        if (empty($api_credentials)) {
            /**
             * Add api keys and its description to woocommerce REST API section.
             */

            /* translators: %1$s: is the API name, %2$s: is the permission type (e.g., 'read_write'), %3$s: is the creation date, and %4$s: is the creation time */
            $translated_string = __('%1$s - API %2$s (created on %3$s at %4$s).', 'ecommerce-shipping-dashboard-by-ups-for-woocommerce');
            if (is_multisite()) {
                $id                  = get_current_blog_id();
                $apiname             = 'UPSESD-' . $id;
                $api_key_description = sprintf(
                    $translated_string,
                    $apiname,
                    'read_write',
                    date_i18n(wc_date_format()),
                    date_i18n(wc_time_format())
                );
            } else {
                $apiname             = 'UPSESD';
                $api_key_description = sprintf(
                    $translated_string,
                    $apiname,
                    'read_write',
                    date_i18n(wc_date_format()),
                    date_i18n(wc_time_format())
                );
            }

            $current_user    = wp_get_current_user();
            $permissions     = 'read_write';
            $consumer_key    = $this->get_exisitng_consumer_key();
            $consumer_secret = 'cs_' . wc_rand_hash();

            $data = $wpdb->insert( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
                $wpdb->prefix . 'woocommerce_api_keys',
                array(
                    'user_id'         => $current_user->ID,
                    'description'     => $api_key_description,
                    'permissions'     => $permissions,
                    'consumer_key'    => wc_api_hash($consumer_key),
                    'consumer_secret' => $consumer_secret,
                    'truncated_key'   => substr($consumer_key, -7),
                ),
                array('%d', '%s', '%s', '%s', '%s', '%s')
            );

            if ($data) {
                $api_credentials = $this->get_existing_api_credentials();
            } else {
                echo esc_html(__('Please re-load page there is some error to configure plugin', 'ecommerce-shipping-dashboard-by-ups-for-woocommerce'));
            }
        } else {
            $api_credentials['consumer_key'] = $this->get_exisitng_consumer_key();
        }

        if (empty($api_credentials['last_access'])) {
            $module         = 'ups_module';
            $module_version = '1.0.6';
            $signup_url     = $this->item_base_signup_url;
            $site_url       = get_option('siteurl');

            $consumer_key = esc_attr($this->get_exisitng_consumer_key());

            $consumer_secret = esc_attr($api_credentials['consumer_secret']);

            // Include WordPress Filesystem functions
            require_once ABSPATH . 'wp-admin/includes/file.php';

            // Initialize the WordPress filesystem
            WP_Filesystem();

            // Define the path to your file
            $file_path = UPS_PLUGIN_DIR . 'terms-and-conditions.txt';

            // Read the file contents
            if (file_exists($file_path)) {
                $termsfile = $GLOBALS['wp_filesystem']->get_contents($file_path);
            } else {
                // Handle the case where the file does not exist
                $termsfile = new WP_Error('file_not_found', __('The terms and conditions file does not exist.', 'ecommerce-shipping-dashboard-by-ups-for-woocommerce'));
                $termsfile = ''; // Set a default value or handle the error accordingly
            }

            $timezone = get_option('timezone_string');
            if (! $timezone) {
                $gmt_offset = get_option('gmt_offset');
                $timezone   = sprintf('UTC%+d', $gmt_offset);
            }

            // Place each line of $termsfile into array.
            $terms_condtions_data = array();
            if (! empty($termsfile)) {
                $terms_condtions_data = explode("\n", $termsfile);
            }

            $this->include_with_variables(
                UPS_PLUGIN_DIR . 'admin/partials/ups-admin-starting-here-page.php',
                array(
                    'module'              => $module,
                    'module_version'      => $module_version,
                    'signup_url'          => $signup_url,
                    'site_url'            => $site_url,
                    'consumer_key'        => $consumer_key,
                    'consumer_secret'     => $consumer_secret,
                    'wp_version'          => $wp_version,
                    'woocommerce_version' => $woocommerce->version,
                    'terms_condtions'     => $terms_condtions_data,
                    'wp_timezone'         => $timezone,
                )
            );
        } else {
            $key_id = esc_attr($api_credentials['key_id']);
            $this->include_with_variables(
                UPS_PLUGIN_DIR . 'admin/partials/ups-admin-starting-here-page.php',
                array(
                    'key_id' => $key_id,
                )
            );
        }
    }

    /**
     * Checks if the current request is about to revoke API key.
     */
    public function is_revoke_key_request()
    {
        if (isset($_REQUEST['action'])) {
            $action = sanitize_text_field(wp_unslash($_REQUEST['action']));
            $keys   = isset($_REQUEST['key']) ? array_map('absint', (array) $_REQUEST['key']) : array();

            if ('revoke' === $action) {
                $api_credentials = self::get_existing_api_credentials();

                if (empty($api_credentials)) {
                    return;
                }

                foreach ($keys as $key_id) {
                    if ($api_credentials['key_id'] == $key_id) {
                        self::revoke_key($api_credentials);
                    }
                }
            }
        }

        if (! empty($_REQUEST['revoke-key'])) {
            $key_id          = absint($_REQUEST['revoke-key']);
            $api_credentials = self::get_existing_api_credentials();

            if ($api_credentials['key_id'] == $key_id) {
                self::revoke_key($api_credentials);
            }
        }
    }

    /**
     * Remove api keys and webhooks
     *
     * @param mixed $api_credentials Get API credentials.
     */
    public function revoke_key($api_credentials)
    {
        if (! extension_loaded('curl') || ! extension_loaded('json')) {
            return;
        }

        $http_args = array(
            'method'      => 'POST',
            'timeout'     => MINUTE_IN_SECONDS,
            'redirection' => 0,
            'httpversion' => '1.1',
            'blocking'    => true,
            'user-agent'  => sprintf(
                'WooCommerce/%s Hookshot (WordPress/%s)',
                WC_VERSION,
                $GLOBALS['wp_version']
            ),
            'body'        => '{}',
            'cookies'     => array(),
            'headers'     => array('Content-Type' => 'application/json'),
        );

        $http_args = apply_filters('woocommerce_webhook_http_args', $http_args, array(), 0);

        $wcwh = new WC_Webhook();
        $wcwh->set_secret(base64_encode(md5(self::get_exisitng_consumer_key() . ':access.revoke', true)));

        $http_args['headers']['X-WC-Webhook-Source']      = home_url('/');
        $http_args['headers']['X-WC-Webhook-Topic']       = 'access.revoke';
        $http_args['headers']['X-WC-Webhook-Resource']    = 'store';
        $http_args['headers']['X-WC-Webhook-Event']       = 'disconnected';
        $http_args['headers']['X-WC-Webhook-Signature']   = $wcwh->generate_signature($http_args['body']);
        $http_args['headers']['X-WC-Webhook-ID']          = 0;
        $http_args['headers']['X-WC-Webhook-Delivery-ID'] = 0;

        $web_hook_url = UPS_ITEMBASE_WEB_HOOK_URL;

        if (empty($web_hook_url)) {
            return;
        }
        wp_safe_remote_request($web_hook_url, $http_args);
    }

    /**
     * Method ups_settings_link
     *
     * @param array $links This is settings action.
     *
     * @return array
     */
    public function ups_settings_link(array $links)
    {
        $mylinks = array(
            '<a href="' . esc_url(get_admin_url(null, 'admin.php?page=ups-starting-here-page')) . '">Settings</a>',
        );

        $links = array_merge($links, $mylinks);
        return $links;
    }

    /**
     * Put request add for version 1.0.3
     *
     * @since 1.0.3
     * @return void
     */
    public function ups_core_check_for_plugin_version_change()
    {
        $option_name           = 'ups_core_update_version';
        $existing_option_value = get_option($option_name);
        if (is_admin() && false === $existing_option_value) {
            $stored_version  = '1.0.2';
            $current_version = UPS_VERSION;
            if ($stored_version && $stored_version !== $current_version && '1.0.6' === $current_version) {
                update_option($option_name, '1.0.6');

                $api_credentials = $this->get_existing_api_credentials();

                if (! empty($api_credentials['last_access'])) {

                    $consumer_key    = esc_attr($this->get_exisitng_consumer_key());
                    $consumer_secret = esc_attr($api_credentials['consumer_secret']);
                    $site_url        = get_option('siteurl');
                    $timezone        = get_option('timezone_string');
                    if (! $timezone) {
                        $gmt_offset = get_option('gmt_offset');
                        $timezone   = sprintf('UTC%+d', $gmt_offset);
                    }
                    $url = UPS_ITEMBASE_SIGN_UP_URL;
                    $body = array(
                        'wp_url'          => $site_url,
                        'wp_timezone'     => $timezone,
                        'consumer_key'    => $consumer_key,
                        'consumer_secret' => $consumer_secret,
                    );

                    $args = array(
                        'method'  => 'PUT',
                        'body'    => wp_json_encode($body),
                        'headers' => array(
                            'Content-Type' => 'application/json',
                        ),
                    );
                    wp_remote_request($url, $args);
                }
            }
        }
    }
}
