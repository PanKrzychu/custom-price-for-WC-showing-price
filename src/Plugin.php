<?php

/**
 * Plugin main class.
 *
 * @package InvoicesWooCommerce
 */

namespace WPDesk\WPDeskCPWFree;

use CPWFreeVendor\WPDesk\Library\CustomPrice\Admin\Install;
use CPWFreeVendor\WPDesk\Library\CustomPrice\Integration;
use CPWFreeVendor\WPDesk\PluginBuilder\Plugin\Activateable;
use CPWFreeVendor\WPDesk\PluginBuilder\Plugin\HookableCollection;
use CPWFreeVendor\WPDesk\PluginBuilder\Plugin\HookableParent;
use CPWFreeVendor\WPDesk\PluginBuilder\Plugin\AbstractPlugin;
use CPWFreeVendor\WPDesk_Plugin_Info;

/**
 * Main plugin class. The most important flow decisions are made here.
 */
class Plugin extends AbstractPlugin implements HookableCollection, Activateable {

	use HookableParent;

	/**
	 * @param WPDesk_Plugin_Info $plugin_info Plugin data.
	 */
	public function __construct( $plugin_info ) {
		$this->plugin_info = $plugin_info;
		parent::__construct( $this->plugin_info );

		$this->docs_url     = get_locale() === 'pl_PL' ? 'https://www.wpdesk.pl/docs/wlasna-cena-produktu-woocommerce' : 'https://www.wpdesk.net/docs/docs-custom-price-for-woocommerce';
		$this->support_url  = get_locale() === 'pl_PL' ? 'https://www.wpdesk.pl/support/' : 'https://www.wpdesk.net/support/';
		$this->settings_url = admin_url( 'admin.php?page=wc-settings&tab=products&section=cpw' );
	}

	/**
	 * Integrate with WordPress and with other plugins using action/filter system.
	 *
	 * @return void
	 */
	public function hooks() {
		parent::hooks();
		$this->add_hookable( new Integration() );
		$this->hooks_on_hookable_objects();
	}

	public function activate() {
		( new Install() )->add_settings();
	}
}
