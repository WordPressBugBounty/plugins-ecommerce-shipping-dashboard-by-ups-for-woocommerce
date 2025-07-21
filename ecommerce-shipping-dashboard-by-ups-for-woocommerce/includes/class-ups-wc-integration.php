<?php

/**
 * UPS Integration.
 *
 * @category Integration
 * @author   UPS
 * @package    UPSESD
 * @subpackage UPSESD/includes
 */

/**
 * {@inheritdoc}
 */
class UPSESD_WC_Integration extends WC_Integration
{
    /**
     * Init and hook in the integration.
     */
    public function __construct()
    {
        $this->id           = 'ecommerce-shipping-dashboard-by-ups-for-woocommerce';
        $this->method_title = __('UPS Shipping Dashboard Integration', 'ecommerce-shipping-dashboard-by-ups-for-woocommerce');
    }

    /**
     * {@inheritdoc}
     */
    public function get_method_description()
    {
        $siteurl                  = get_site_url();
        $this->method_description = '<p>Thank you for installing UPS Shipping Dashboard plugin! Please proceed to
		<a href="' . $siteurl . '/wp-admin/admin.php?page=ups-starting-here-page">integration page</a>.</p>';

        return $this->method_description;
    }
}
