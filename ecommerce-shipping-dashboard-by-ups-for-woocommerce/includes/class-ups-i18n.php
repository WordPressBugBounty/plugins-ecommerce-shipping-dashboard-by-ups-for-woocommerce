<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.ups.com
 * @since      1.0.1
 *
 * @package    UPSESD
 * @subpackage UPSESD/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.1
 * @package    UPSESD
 * @subpackage UPSESD/includes
 * @author UPS eCommerce Shipping Dashboard
 */
class UPSESD_i18n
{


    /**
     * Load the plugin text domain for translation.
     *
     * @since    1.0.1
     */
    public function load_plugin_textdomain()
    {

        load_plugin_textdomain(
            'ecommerce-shipping-dashboard-by-ups-for-woocommerce',
            false,
            dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
        );
    }
}
