<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.ups.com
 * @since      1.0.1
 *
 * @package    UPSESD
 * @subpackage UPSESD/admin/partials
 */

?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<h1 class="wp-heading-inline">
    <?php esc_html_e("Let's get you started with the UPS® Shipping Dashboard", 'ecommerce-shipping-dashboard-by-ups-for-woocommerce'); ?>
</h1>
<p>
    <?php esc_html_e('UPS eCommerce Shipping Dashboard WooCommerce integration extension', 'ecommerce-shipping-dashboard-by-ups-for-woocommerce'); ?>
</p>
<div class="wrap disablenotice">
    <div id="dashboard-widgets-wrap">
        <div class="ups-overlay"></div>
        <div class="ups-spinner">
            <img
                src="<?php echo esc_url(includes_url() . 'js/tinymce/skins/lightgray/img/loader.gif'); ?>"
                alt="loader-image" />
        </div>
        <div id="dashboard-widgets" class="metabox-holder">
            <div id="postbox-container-1" class="postbox-container ups-container-custom" style="width: 70%;">
                <div id="normal-sortables" class="meta-box-sortables ui-sortable">
                    <?php
                    if (isset($key_id) && ! empty($key_id) && null !== $key_id) {
                    ?>
                        <div id="metabox" class="postbox">
                            <div class="postbox-header">
                                <h2 class="hndle"><?php esc_html_e('1. Activate the Plugin', 'ecommerce-shipping-dashboard-by-ups-for-woocommerce'); ?></h2>
                                <h2 class="right"><?php esc_html_e('Connected Successfully', 'ecommerce-shipping-dashboard-by-ups-for-woocommerce'); ?></h2>
                                <div class="handle-actions hide-if-no-js">
                                    <button type="button" class="handlediv" aria-expanded="true">
                                        <span class="dashicons dashicons-saved"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php
                    } else {
                    ?>
                        <div id="metabox" class="postbox">
                            <div class="postbox-header">
                                <h2 class="hndle ui-sortable-handle">
                                    <?php esc_html_e('1. Activate the Plugin', 'ecommerce-shipping-dashboard-by-ups-for-woocommerce'); ?>
                                </h2>
                                <div class="handle-actions hide-if-no-js">
                                    <button type="button" class="handlediv" aria-expanded="true">
                                        <span class="screen-reader-text">
                                            <?php esc_html_e('Toggle panel: Activate the Plugin', 'ecommerce-shipping-dashboard-by-ups-for-woocommerce'); ?>
                                        </span>
                                        <span class="toggle-indicator" aria-hidden="true"></span>
                                    </button>
                                </div>
                            </div>
                            <div class="inside">
                                <div class="custom-inside">
                                    <div class="main">
                                        <form name="post"
                                            action="<?php echo esc_attr($signup_url); ?>"
                                            method="post"
                                            class="initial-form hide-if-no-js"
                                            id="core-coonect-form">
                                            <input type="hidden" name="wp_url" value="<?php echo esc_attr($site_url); ?>" />
                                            <input type="hidden" name="wp_version" value="<?php echo esc_attr($wp_version); ?>" />
                                            <input type="hidden" name="wc_version" value="<?php echo esc_attr($woocommerce_version); ?>" />
                                            <input type="hidden" name="module_name" value="<?php echo esc_attr($module); ?>" />
                                            <input type="hidden" name="module_version" value="<?php echo esc_attr($module_version); ?>" />
                                            <input type="hidden" name="consumer_key" value="<?php echo esc_attr($consumer_key); ?>" />
                                            <input type="hidden" name="consumer_secret" value="<?php echo esc_attr($consumer_secret); ?>" />
                                            <input type="hidden" name="wp_timezone" value="<?php echo esc_attr($wp_timezone); ?>" />

                                            <div class="input-text-wrap" id="store_domain-wrap">
                                                <label for="store_domain">
                                                    <?php esc_html_e('Store Domain:', 'ecommerce-shipping-dashboard-by-ups-for-woocommerce'); ?>
                                                </label>
                                                <?php
                                                $url_parse    = wp_parse_url(get_site_url());
                                                $store_domain = $url_parse['host'];
                                                ?>
                                                <input type="text" name="store_domain" id="store_domain" autocomplete="off"
                                                    value="<?php echo esc_attr($store_domain); ?>" />
                                                <p class="storedomain-error-tag"
                                                    style="display: none;color: #ff0000;font-size: 12px;">
                                                    <?php esc_html_e("Please enter your store's domain name", 'ecommerce-shipping-dashboard-by-ups-for-woocommerce'); ?>
                                                </p>
                                            </div>

                                            <div class="input-text-wrap" izd="site_email-wrap">
                                                <label for="site_email">
                                                    <?php esc_html_e('Business Email:', 'ecommerce-shipping-dashboard-by-ups-for-woocommerce'); ?>
                                                </label>
                                                <input type="text" name="site_email" id="site_email" autocomplete="off" />
                                                <p class="businessmail-error-tag"
                                                    style="display: none;color: #ff0000;font-size: 12px;">
                                                    <?php esc_html_e('Please enter your business email address', 'ecommerce-shipping-dashboard-by-ups-for-woocommerce'); ?>
                                                </p>
                                            </div><br>

                                            <div class="checkbox-wrap" id="terms-wrap">
                                                <?php
                                                if (isset($terms_condtions)) {
                                                    foreach ($terms_condtions as $index => $checkbox) {
                                                        echo wp_kses(
                                                            '<label>'
                                                                . '<input type="checkbox" name="tc-checkbox-' . esc_attr($index) . '" id="tc-checkbox-' . esc_attr($index) . '" value="' . esc_attr($index) . '"/>'
                                                                . $checkbox
                                                                . '</label><br/>',
                                                            [
                                                                'label' => [],
                                                                'br' => [],
                                                                'input' => [
                                                                    'type' => [],
                                                                    'name' => [],
                                                                    'id' => [],
                                                                    'value' => []
                                                                ],
                                                                'a' => [
                                                                    'href' => [],
                                                                    'target' => []
                                                                ]
                                                            ]
                                                        );
                                                    }
                                                    echo '<br>';
                                                }
                                                ?>
                                                <p class="terms-error-tag"
                                                    style="color: #ff0000;font-size: 12px; display: none;">
                                                    <?php esc_html_e('Please accept all terms and conditions', 'ecommerce-shipping-dashboard-by-ups-for-woocommerce'); ?>
                                                </p>
                                            </div>
                                            <button type="submit" id="ups_integration_now_button" class="button-primary">
                                                <?php esc_html_e('Accept', 'ecommerce-shipping-dashboard-by-ups-for-woocommerce'); ?>
                                            </button>
                                        </form>
                                    </div>
                                    <div class="sub">
                                        <img
                                            src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'images/demo-image.png'); ?>"
                                            alt="<?php esc_html_e('UPS logo', 'ecommerce-shipping-dashboard-by-ups-for-woocommerce'); ?>"
                                            class="ups-image"
                                            width="400">
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                    <?php do_action('connect_ups_dashboard_form'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false); ?>