<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.ups.com
 * @since      1.0.1
 *
 * @package    UPSESD
 * @subpackage UPSESD/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.1
 * @package    UPSESD
 * @subpackage UPSESD/includes
 * @author UPS eCommerce Shipping Dashboard
 */
class UPSESD_Activator
{

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.1
     */
    public static function activate()
    {

        $need    = false; // do we need Woo?
        $network = false; // is plugin activated at network level?

        if (! function_exists('is_plugin_active_for_network')) {
            require_once ABSPATH . '/wp-admin/includes/plugin.php';
        }

        // Multisite && this plugin is network activated - Woo must be network activated
        if (is_multisite() && is_plugin_active_for_network(plugin_basename(__FILE__))) {
            $need    = is_plugin_active_for_network('woocommerce/woocommerce.php') ? false : true;
            $network = true;
            // This plugin runs on a single site || is locally activated
        } else {
            $need = is_plugin_active('woocommerce/woocommerce.php') ? false : true;
        }

        if ($need === true) {
            wp_die(
                '<h1>WooCommerce required</h1><p>WooCommerce extension must be installed and activated first!</p>',
                'eCommerce Shipping Dashboard by UPS for WooCommerce - Plugin Activation Error',
                array(
                    'response'  => 500,
                    'back_link' => true,
                )
            );
        }
    }
}
