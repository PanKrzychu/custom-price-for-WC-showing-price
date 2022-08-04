<?php

/**
 * WooCommerce Custom Price Settings
 *
 * @package     Admin
 * @version     3.0
 */
namespace CPWFreeVendor\WPDesk\Library\CustomPrice\Admin;

use CPWFreeVendor\WPDesk\Library\CustomPrice\Helper;
use CPWFreeVendor\WPDesk\Library\CustomPrice\Integration;
use CPWFreeVendor\WPDesk\PluginBuilder\Plugin\Hookable;
/**
 * WC_NYP_Admin_Settings
 */
class SettingsTab implements \CPWFreeVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    /**
     * @var string
     */
    private $label;
    /**
     * Constructor.
     */
    public function hooks()
    {
        $this->id = 'cpw';
        $this->label = \__('Custom Price', 'custom-price-for-woocommerce');
        \add_filter('woocommerce_get_sections_products', function ($sections) {
            $sections['cpw'] = $this->label;
            return $sections;
        });
        \add_filter('woocommerce_get_settings_products', [$this, 'add_fields'], 10, 2);
    }
    /**
     * Get settings array
     *
     * @return array
     */
    public function add_fields($settings, $current_section)
    {
        if ($current_section === 'cpw') {
            $cpw_settings[] = ['title' => \__('Custom Price', 'custom-price-for-woocommerce'), 'type' => 'title', 'id' => 'woocommerce_cpw_options'];
            $cpw_settings[] = ['title' => \__('Price Label', 'custom-price-for-woocommerce'), 'desc' => \__('This is the text that appears above the Custom Price input field.', 'custom-price-for-woocommerce'), 'id' => 'woocommerce_cpw_label_text', 'type' => 'text', 'css' => 'min-width:300px;', 'default' => \__('Price', 'custom-price-for-woocommerce'), 'desc_tip' => \true];
            $cpw_settings[] = ['title' => \__('Suggested Price Text', 'custom-price-for-woocommerce'), 'desc' => \__('This is the text to display before the suggested price. You can use the placeholder %price% to display the suggested price.', 'custom-price-for-woocommerce'), 'id' => 'woocommerce_cpw_suggested_text', 'type' => 'text', 'css' => 'min-width:300px;', 'custom_attributes' => \CPWFreeVendor\WPDesk\Library\CustomPrice\Helper::get_custom_attributes(), 'default' => \__('Suggested price: %price%', 'custom-price-for-woocommerce'), 'desc_tip' => \true];
            $cpw_settings[] = ['title' => \__('Minimum Price Text', 'custom-price-for-woocommerce'), 'desc' => \__('This is the text to display before the minimum accepted price. You can use the placeholder %price% to display the minimum price.', 'custom-price-for-woocommerce'), 'id' => 'woocommerce_cpw_minimum_text', 'type' => 'text', 'css' => 'min-width:300px;', 'custom_attributes' => \CPWFreeVendor\WPDesk\Library\CustomPrice\Helper::get_custom_attributes(), 'default' => \__('Minimum price: %price%', 'custom-price-for-woocommerce'), 'desc_tip' => \true];
            $cpw_settings[] = ['title' => \__('Maximum Price Text', 'custom-price-for-woocommerce'), 'desc' => \__('This is the text to display before the maximum accepted price. You can use the placeholder %price% to display the maximum price.', 'custom-price-for-woocommerce'), 'id' => 'woocommerce_cpw_maximum_text', 'type' => 'text', 'css' => 'min-width:300px;', 'custom_attributes' => \CPWFreeVendor\WPDesk\Library\CustomPrice\Helper::get_custom_attributes(), 'default' => \__('Maximum price: %price%', 'custom-price-for-woocommerce'), 'desc_tip' => \true];
            $cpw_settings[] = ['title' => \__('Add to Cart Button Text for Shop', 'custom-price-for-woocommerce'), 'desc' => \__('This is the text that appears on the Add to Cart buttons on the Shop Pages.', 'custom-price-for-woocommerce'), 'id' => 'woocommerce_cpw_button_text', 'type' => 'text', 'css' => 'min-width:300px;', 'custom_attributes' => \CPWFreeVendor\WPDesk\Library\CustomPrice\Helper::get_custom_attributes(), 'default' => \__('Choose price', 'custom-price-for-woocommerce'), 'placeholder' => \__('Choose price', 'custom-price-for-woocommerce'), 'desc_tip' => \true];
            $cpw_settings[] = ['title' => \__('Add to Cart Button Text for Single Product', 'custom-price-for-woocommerce'), 'desc' => \__('This is the text that appears on the Add to Cart buttons on the Single Product Pages. Leave blank to inherit the default add to cart text.', 'custom-price-for-woocommerce'), 'id' => 'woocommerce_cpw_button_text_single', 'type' => 'text', 'css' => 'min-width:300px;', 'custom_attributes' => \CPWFreeVendor\WPDesk\Library\CustomPrice\Helper::get_custom_attributes(), 'default' => '', 'desc_tip' => \true];
            $cpw_settings[] = ['type' => 'sectionend', 'id' => 'woocommerce_cpw_style_options'];
            return $cpw_settings;
        }
        return $settings;
    }
}
