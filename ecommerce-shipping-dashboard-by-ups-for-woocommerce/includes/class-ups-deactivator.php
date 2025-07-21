<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://www.ups.com
 * @since      1.0.1
 *
 * @package    UPSESD
 * @subpackage UPSESD/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.1
 * @package    UPSESD
 * @subpackage UPSESD/includes
 * @author UPS eCommerce Shipping Dashboard
 */
class UPSESD_Deactivator
{

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.1
     */
    public static function deactivate()
    {

        $active_plugins = apply_filters('active_plugins', get_option('active_plugins'));

        if (in_array('ups-glc/ups-glc.php', $active_plugins)) {
            wp_die(
                '<h1>UPS GLC Shipping Rate</h1>
				<p>The eCommerce Shipping Dashboard by UPS for WooCommerce extension is required for the UPS GLC Shipping Rate to function properly. Please deactivate the UPS GLC Shipping Rate extension and try again..!</p>',
                'eCommerce Shipping Dashboard by UPS for WooCommerce - Plugin deactivation Error',
                array(
                    'response'  => 500,
                    'back_link' => esc_url(admin_url('plugins.php')),
                )
            );
        }

        if (in_array('ups-live-rate/ups-live-rate.php', $active_plugins)) {
            wp_die(
                '<h1>UPS Live Shipping Rate</h1>
				<p>The eCommerce Shipping Dashboard by UPS for WooCommerce extension is required for the UPS Live Shipping Rate to function properly. Please deactivate the UPS Live Shipping Rate extension and try again..!</p>',
                'eCommerce Shipping Dashboard by UPS for WooCommerce - Plugin Deactivation Error',
                array(
                    'response'  => 500,
                    'back_link' => esc_url(admin_url('plugins.php')),
                )
            );
        }

        if (in_array('upsap/upsap.php', $active_plugins)) {
            wp_die(
                '<h1>UPS Access Points Shipping Rate</h1>
				<p>The eCommerce Shipping Dashboard by UPS for WooCommerce extension is required for the UPS Access Points Shipping Rate to function properly. Please deactivate the UPS Access Points Shipping Rate extension and try again..!</p>',
                'eCommerce Shipping Dashboard by UPS for WooCommerce - Plugin Deactivation Error',
                array(
                    'response'  => 500,
                    'back_link' => esc_url(admin_url('plugins.php')),
                )
            );
        }
    }
}
