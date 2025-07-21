<?php

/**
 * Plugin Name:       eCommerce Shipping Dashboard by UPS for WooCommerce
 * Plugin URI:        https://ups.dashboardlink.com
 * Description:       UPS eCommerce Shipping Dashboard WooCommerce integration extension
 * Tags:              woocommerce, UPS, fulfillment, paperless invoice, bulk label
 * Version:           1.0.6
 * Author:            UPS eCommerce Shipping Dashboard
 * Author URI:        https://www.ups.com
 * Domain Path:       /languages
 * Requires at least: 4.7
 * Tested up to:      6.7.1
 * Requires PHP:      7.4
 * WC requires at least: 3.0
 * WC tested up to: 9.5.2
 * Text Domain:       ecommerce-shipping-dashboard-by-ups-for-woocommerce
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package UPSESD
 * @category Core
 * @author UPS eCommerce Shipping Dashboard
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'UPS_VERSION', '1.0.6' );

/*
* Define global vraibles
*/
define( 'UPS_TEXTDOMAIN', 'ecommerce-shipping-dashboard-by-ups-for-woocommerce' );
define( 'UPS_PLUGIN_DIR', plugin_dir_path( __DIR__ . '/ecommerce-shipping-dashboard-by-ups-for-woocommerce/' ) );
define( 'UPS_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
/**
 * Define itembase varaibles
 */
define( 'UPS_ITEMBASE_INSTANCE_ID', 'd9539ace-b659-43fc-bc1e-041d5ce95fe6' );
define( 'UPS_ITEMBASE_WEB_HOOK_URL_DOMAIN', 'api.itembase.com' );
define( 'UPS_ITEMBASE_WEB_HOOK_URL', 'https://' . UPS_ITEMBASE_WEB_HOOK_URL_DOMAIN );
define(
	'UPS_ITEMBASE_SIGN_UP_URL',
	'https://' . UPS_ITEMBASE_WEB_HOOK_URL_DOMAIN . '/connectivity/instances/' . UPS_ITEMBASE_INSTANCE_ID . '/connect/complete'
);

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ups-activator.php
 */
function activate_ups() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ups-activator.php';
	UPSESD_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ups-deactivator.php
 */
function deactivate_ups() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ups-deactivator.php';
	UPSESD_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_ups' );
register_deactivation_hook( __FILE__, 'deactivate_ups' );

/**
 * Summary of ups_live_rate_before_woocommerce_init this function check init compatibility.
 *
 * @since 1.0.1
 * @return void
 */
function ups_before_woocommerce_init() {
	if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'cart_checkout_blocks', __FILE__, true );
	}
}
add_action( 'before_woocommerce_init', 'ups_before_woocommerce_init' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-ups.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.1
 */
function run_ups() {
	$plugin = new UPSESD();
	$plugin->run();
}
run_ups();
