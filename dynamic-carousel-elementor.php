<?php
/**
 * Plugin Name: Dynamic Carousel Widget for Elementor
 * Description: A fully customizable carousel widget supporting images, videos, and Elementor templates with dynamic widths
 * Version: 1.0.0
 * Author: Robb Developer
 * Text Domain: elementor-custom-widgets
 * Requires PHP: 7.0
 * Requires at least: 5.0
 */

if (!defined('ABSPATH')) exit;

final class Dynamic_Carousel_Elementor {

    const VERSION = '1.0.0';
    const MINIMUM_ELEMENTOR_VERSION = '3.0.0';
    const MINIMUM_PHP_VERSION = '7.0';

    private static $_instance = null;

    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        add_action('plugins_loaded', [$this, 'init']);
    }

    public function init() {
        // Check if Elementor installed and activated
        if (!did_action('elementor/loaded')) {
            add_action('admin_notices', [$this, 'admin_notice_missing_main_plugin']);
            return;
        }

        // Check for required Elementor version
        if (!version_compare(ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=')) {
            add_action('admin_notices', [$this, 'admin_notice_minimum_elementor_version']);
            return;
        }

        // Check for required PHP version
        if (version_compare(PHP_VERSION, self::MINIMUM_PHP_VERSION, '<')) {
            add_action('admin_notices', [$this, 'admin_notice_minimum_php_version']);
            return;
        }

        // Register Widget
        add_action('elementor/widgets/register', [$this, 'register_widgets']);

        // Register Widget Scripts
        add_action('elementor/frontend/after_register_scripts', [$this, 'register_frontend_scripts']);
        
        // Register Widget Styles
        add_action('elementor/frontend/after_register_styles', [$this, 'register_frontend_styles']);
    }

    public function admin_notice_missing_main_plugin() {
        if (isset($_GET['activate'])) unset($_GET['activate']);

        $message = sprintf(
            esc_html__('"%1$s" requires "%2$s" to be installed and activated.', 'elementor-custom-widgets'),
            '<strong>' . esc_html__('Dynamic Carousel Widget', 'elementor-custom-widgets') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'elementor-custom-widgets') . '</strong>'
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    public function admin_notice_minimum_elementor_version() {
        if (isset($_GET['activate'])) unset($_GET['activate']);

        $message = sprintf(
            esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-custom-widgets'),
            '<strong>' . esc_html__('Dynamic Carousel Widget', 'elementor-custom-widgets') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'elementor-custom-widgets') . '</strong>',
            self::MINIMUM_ELEMENTOR_VERSION
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    public function admin_notice_minimum_php_version() {
        if (isset($_GET['activate'])) unset($_GET['activate']);

        $message = sprintf(
            esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-custom-widgets'),
            '<strong>' . esc_html__('Dynamic Carousel Widget', 'elementor-custom-widgets') . '</strong>',
            '<strong>' . esc_html__('PHP', 'elementor-custom-widgets') . '</strong>',
            self::MINIMUM_PHP_VERSION
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    public function register_widgets($widgets_manager) {
        require_once(__DIR__ . '/widgets/dynamic-carousel-widget.php');
        $widgets_manager->register(new \ElementorCustomWidgets\Widgets\Dynamic_Carousel_Widget());
    }

    public function register_frontend_scripts() {
        wp_register_script(
            'dynamic-carousel-script',
            plugins_url('/assets/js/dynamic-carousel-script.js', __FILE__),
            ['jquery'],
            self::VERSION,
            true
        );
    }

    public function register_frontend_styles() {
        wp_register_style(
            'dynamic-carousel-style',
            plugins_url('/assets/css/dynamic-carousel-style.css', __FILE__),
            [],
            self::VERSION
        );
    }
}

Dynamic_Carousel_Elementor::instance();