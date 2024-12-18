<?php
/**
 * Plugin Name: Elementor Product Count Widget
 * Description: یک ویجت برای نمایش تعداد محصولات در یک دسته‌بندی خاص.
 * Version: 1.2
 * Author: mhmdq
 * Author URI: https://mhmdq.ir
 * License: GPL2
 */

if ( ! defined( 'ABSPATH' ) ) {// جلوگیری از دسترسی مستقیم
}

final class Elementor_Product_Count_Widget_Plugin {

    const VERSION = '1.2';
    const MINIMUM_ELEMENTOR_VERSION = '3.0.0';
    const MINIMUM_PHP_VERSION = '7.4';

    private static $_instance = null;

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        add_action( 'plugins_loaded', [ $this, 'on_plugins_loaded' ] );
    }

    public function on_plugins_loaded() {
        if ( ! did_action( 'elementor/loaded' ) ) {
            add_action( 'admin_notices', [ $this, 'elementor_not_loaded' ] );
            return;
        }

        if ( ! version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '>=' ) ) {
            add_action( 'admin_notices', [ $this, 'php_version_not_supported' ] );
            return;
        }

        add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
    }

    public function elementor_not_loaded() {
        echo '<div class="notice notice-warning"><p>' . esc_html__( 'Elementor فعال نیست. لطفاً افزونه Elementor را نصب و فعال کنید.', 'epcw' ) . '</p></div>';
    }

    public function php_version_not_supported() {
        echo '<div class="notice notice-warning"><p>' . esc_html__( 'نسخه PHP پشتیبانی نمی‌شود. لطفاً نسخه PHP خود را به‌روز کنید.', 'epcw' ) . '</p></div>';
    }

    public function register_widgets( $widgets_manager ) {
        require_once( __DIR__ . '/includes/class-widget.php' );
        $widgets_manager->register( new \Elementor\EPCW_Product_Count_Widget() );
    }
}

Elementor_Product_Count_Widget_Plugin::instance();
