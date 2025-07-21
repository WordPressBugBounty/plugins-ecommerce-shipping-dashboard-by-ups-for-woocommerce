<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * This script performs cleanup actions such as deleting plugin options,
 * removing API keys, and sending webhook notifications when the plugin is uninstalled.
 *
 * @link       https://www.ups.com
 * @since      1.0.1
 *
 * @package    UPSESD
 */

// If uninstall not called from WordPress, then exit.
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Delete the option related to the plugin version
$option_name = 'ups_core_update_version';
delete_option($option_name);

// Include the main plugin file to access constants and functions
require_once WP_PLUGIN_DIR . '/ecommerce-shipping-dashboard-by-ups-for-woocommerce/ecommerce-shipping-dashboard-by-ups-for-woocommerce.php';

global $wpdb;

// Construct the consumer key and hash it
$consumer_key = 'ck_' . UPS_ITEMBASE_INSTANCE_ID;
$key          = wc_api_hash($consumer_key);

if (is_multisite()) {
    global $wpdb;
    $blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");

    if ($blog_ids) {
        foreach ($blog_ids as $blog_id) {
            switch_to_blog($blog_id);

            // Delete the API key from the database
            delete_api_key($key);

            // Send a webhook notification about the key revocation
            send_webhook_delete_connection($key);

            restore_current_blog();
        }
    }
} else {

    // Delete the API key from the database
    delete_api_key($key);

    // Send a webhook notification about the key revocation
    send_webhook_delete_connection($key);
}

/**
 * Deletes an API key from the database based on the consumer key.
 *
 * @param string $key The consumer key of the API key to be deleted.
 * @return void
 */
function delete_api_key($key)
{
    global $wpdb;

    // Prepare the consumer key for deletion
    $consumer_key = sanitize_text_field($key); // Sanitize input

    // Delete the API key using prepared statement
    $result = $wpdb->delete( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
        $wpdb->prefix . 'woocommerce_api_keys',
        array('consumer_key' => $consumer_key), // Data to delete
        array('%s') // Format
    );

    if ($result === false) {
        // Log error if the delete operation fails
        wp_trigger_error('Failed to delete API key: ' . $key, E_USER_WARNING);
    }
}

/**
 * Sends a webhook notification about the API key revocation.
 *
 * @param string $key The consumer key being revoked.
 * @return void
 */
function send_webhook_delete_connection($key)
{
    $delivery_url = UPS_ITEMBASE_WEB_HOOK_URL;
    $wc_version   = get_option('woocommerce_version');

    // Prepare HTTP arguments for the webhook request
    $http_args = array(
        'method'      => 'POST',
        'timeout'     => MINUTE_IN_SECONDS,
        'redirection' => 0,
        'httpversion' => '1.1',
        'blocking'    => true,
        'user-agent'  => sprintf(
            'WooCommerce/%s Hookshot (WordPress/%s)',
            $wc_version,
            $GLOBALS['wp_version']
        ),
        'body'        => wp_json_encode(array()), // Send empty JSON body
        'cookies'     => array(),
        'headers'     => array('Content-Type' => 'application/json'),
    );

    // Apply filters to modify HTTP arguments
    $http_args = apply_filters('woocommerce_webhook_http_args', $http_args, array(), 0);

    // Initialize the webhook class
    $wcwh = new WC_Webhook();
    $wcwh->set_secret(base64_encode(md5($key . ':access.revoke', true)));

    // Set additional headers for the webhook request
    $http_args['headers']['X-WC-Webhook-Source']      = home_url('/');
    $http_args['headers']['X-WC-Webhook-Topic']       = 'access.revoke';
    $http_args['headers']['X-WC-Webhook-Resource']    = 'store';
    $http_args['headers']['X-WC-Webhook-Event']       = 'disconnected';
    $http_args['headers']['X-WC-Webhook-Signature']   = $wcwh->generate_signature($http_args['body']);
    $http_args['headers']['X-WC-Webhook-ID']          = 0; // Optionally set webhook ID if applicable
    $http_args['headers']['X-WC-Webhook-Delivery-ID'] = 0; // Optionally set delivery ID if applicable

    // Send the webhook notification
    $response = wp_safe_remote_request($delivery_url, $http_args);

    // Check for errors in the HTTP request
    if (is_wp_error($response)) {
        // Trigger an error for failed webhook request
        wp_trigger_error('Webhook for delete connection failed: ' . $response->get_error_message(), E_USER_WARNING);
    }
}
