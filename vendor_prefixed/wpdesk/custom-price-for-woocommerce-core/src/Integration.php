<?php

namespace CPWFreeVendor\WPDesk\Library\CustomPrice;

use Psr\Log\LoggerInterface;
use CPWFreeVendor\WPDesk\Library\CustomPrice\Admin\Product\ProductFields;
use CPWFreeVendor\WPDesk\Library\CustomPrice\Admin\Product\SaveProductMeta;
use CPWFreeVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use CPWFreeVendor\WPDesk\PluginBuilder\Plugin\HookableParent;
use CPWFreeVendor\WPDesk\View\Renderer\Renderer;
use CPWFreeVendor\WPDesk\Library\CustomPrice\Admin\Admin;
use CPWFreeVendor\WPDesk\Library\CustomPrice\Admin\SettingsTab;
use CPWFreeVendor\WPDesk\Library\CustomPrice\Compatibility\ExtensionSupport;
/**
 * Main class for integrate library with plugin.
 *
 * @package WPDesk\Library\CustomPrice
 */
class Integration implements \CPWFreeVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    use HookableParent;
    /**
     * @var Renderer
     */
    protected $renderer;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var bool
     */
    private static $is_super = \false;
    /**
     * @param bool $is_super
     */
    public function __construct(bool $is_super = \false)
    {
        self::$is_super = $is_super;
    }
    /**
     * @return bool
     */
    public static function is_super() : bool
    {
        return self::$is_super;
    }
    /**
     * @return string
     */
    protected final function get_library_url() : string
    {
        return \trailingslashit(\plugin_dir_url(\dirname(__FILE__)));
    }
    /**
     * @return string
     */
    protected final function get_library_path() : string
    {
        return \trailingslashit(\plugin_dir_path(\dirname(__FILE__)));
    }
    /**
     * Fire hooks.
     */
    public function hooks()
    {
        $display = new \CPWFreeVendor\WPDesk\Library\CustomPrice\Display($this->get_library_url(), $this->get_library_path());
        $cart = new \CPWFreeVendor\WPDesk\Library\CustomPrice\Cart();
        $this->add_hookable($display);
        $this->add_hookable($cart);
        $this->add_hookable(new \CPWFreeVendor\WPDesk\Library\CustomPrice\Order());
        $this->add_hookable(new \CPWFreeVendor\WPDesk\Library\CustomPrice\Compatibility\ExtensionSupport($cart, $display));
        $this->add_hookable(new \CPWFreeVendor\WPDesk\Library\CustomPrice\Admin\Admin($this->get_library_url(), $this->get_library_path()));
        $this->add_hookable(new \CPWFreeVendor\WPDesk\Library\CustomPrice\Admin\Product\ProductFields());
        $this->add_hookable(new \CPWFreeVendor\WPDesk\Library\CustomPrice\Admin\Product\SaveProductMeta());
        $this->add_hookable(new \CPWFreeVendor\WPDesk\Library\CustomPrice\Admin\SettingsTab());
        $this->hooks_on_hookable_objects();
    }
}
