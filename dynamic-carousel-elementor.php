<?php
/**
 * Plugin Name: Dynamic Carousel Widget for Elementor
 * Description: A fully customizable carousel widget supporting images, videos, and Elementor templates with dynamic widths
 * Version: 1.0.4
 * Author: Robb Developer
 * Text Domain: elementor-custom-widgets
 * Requires PHP: 7.0
 * Requires at least: 5.0
 */

if (!defined('ABSPATH')) exit;

final class Dynamic_Carousel_Elementor {

    const VERSION = '1.0.4';
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

        // Register Widget Styles - use wp_enqueue_scripts to load in head
        add_action('wp_enqueue_scripts', [$this, 'register_frontend_styles'], 5);

        // AJAX handlers for video posters
        add_action('wp_ajax_carousel_upload_video_poster', [$this, 'ajax_upload_video_poster']);
        add_action('wp_ajax_nopriv_carousel_upload_video_poster', [$this, 'ajax_upload_video_poster']);
        add_action('wp_ajax_carousel_check_video_poster', [$this, 'ajax_check_video_poster']);
        add_action('wp_ajax_nopriv_carousel_check_video_poster', [$this, 'ajax_check_video_poster']);
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

        // Localize script with AJAX URL and nonce
        wp_localize_script('dynamic-carousel-script', 'carouselAjax', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('carousel_video_poster_nonce')
        ]);
    }

    public function register_frontend_styles() {
        wp_register_style(
            'dynamic-carousel-style',
            plugins_url('/assets/css/dynamic-carousel-style.css', __FILE__),
            [],
            self::VERSION
        );
    }

    public function ajax_upload_video_poster() {
        // Verify nonce
        check_ajax_referer('carousel_video_poster_nonce', 'nonce');

        // Check if file was uploaded
        if (!isset($_FILES['poster_image'])) {
            wp_send_json_error(['message' => 'No image file uploaded']);
            return;
        }

        $video_slug = sanitize_title($_POST['video_slug'] ?? '');
        if (empty($video_slug)) {
            wp_send_json_error(['message' => 'Invalid video slug']);
            return;
        }

        // FIRST: Check if poster already exists - prevent duplicates
        global $wpdb;
        $existing_check = $wpdb->prepare(
            "SELECT ID FROM {$wpdb->posts}
            WHERE post_type = 'attachment'
            AND post_name = %s
            LIMIT 1",
            $video_slug . '-poster'
        );

        $existing_id = $wpdb->get_var($existing_check);
        if ($existing_id) {
            $existing_url = wp_get_attachment_url($existing_id);
            if ($existing_url) {
                // Poster already exists, return it instead of uploading again
                wp_send_json_success([
                    'url' => $existing_url,
                    'attachment_id' => $existing_id,
                    'already_existed' => true
                ]);
                return;
            }
        }

        // Handle file upload
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');

        $file = $_FILES['poster_image'];
        $upload_overrides = [
            'test_form' => false,
            'test_type' => false
        ];

        // Move uploaded file
        $movefile = wp_handle_upload($file, $upload_overrides);

        if (isset($movefile['error'])) {
            wp_send_json_error(['message' => $movefile['error']]);
            return;
        }

        // Prepare attachment data
        $filename = $video_slug . '-poster.webp';
        $attachment = [
            'guid' => $movefile['url'],
            'post_mime_type' => $movefile['type'],
            'post_title' => sanitize_file_name($video_slug) . ' Poster',
            'post_content' => '',
            'post_status' => 'inherit',
            'post_name' => $video_slug . '-poster'
        ];

        // Insert attachment
        $attachment_id = wp_insert_attachment($attachment, $movefile['file']);

        if (is_wp_error($attachment_id)) {
            wp_send_json_error(['message' => 'Failed to create attachment']);
            return;
        }

        // Generate metadata
        $attach_data = wp_generate_attachment_metadata($attachment_id, $movefile['file']);
        wp_update_attachment_metadata($attachment_id, $attach_data);

        $poster_url = wp_get_attachment_url($attachment_id);

        wp_send_json_success([
            'url' => $poster_url,
            'attachment_id' => $attachment_id
        ]);
    }

    public function ajax_check_video_poster() {
        // Verify nonce
        check_ajax_referer('carousel_video_poster_nonce', 'nonce');

        $video_slug = sanitize_title($_POST['video_slug'] ?? '');
        if (empty($video_slug)) {
            wp_send_json_error(['message' => 'Invalid video slug']);
            return;
        }

        // Search for existing poster in media library (match the find_existing_poster logic)
        global $wpdb;
        $query = $wpdb->prepare(
            "SELECT ID FROM {$wpdb->posts}
            WHERE post_type = 'attachment'
            AND (post_mime_type = 'image/webp' OR post_mime_type = 'image/jpeg' OR post_mime_type = 'image/png')
            AND (post_name = %s OR post_name = %s)
            ORDER BY ID DESC
            LIMIT 1",
            $video_slug . '-poster',
            $video_slug
        );

        $attachment_id = $wpdb->get_var($query);

        if ($attachment_id) {
            $poster_url = wp_get_attachment_url($attachment_id);
            if ($poster_url) {
                wp_send_json_success([
                    'poster_url' => $poster_url,
                    'attachment_id' => $attachment_id
                ]);
                return;
            }
        }

        // Also try searching by title as fallback
        $query = $wpdb->prepare(
            "SELECT ID FROM {$wpdb->posts}
            WHERE post_type = 'attachment'
            AND (post_mime_type LIKE 'image/%')
            AND post_title LIKE %s
            ORDER BY ID DESC
            LIMIT 1",
            '%' . $wpdb->esc_like($video_slug) . '%Poster%'
        );

        $attachment_id = $wpdb->get_var($query);

        if ($attachment_id) {
            $poster_url = wp_get_attachment_url($attachment_id);
            if ($poster_url) {
                wp_send_json_success([
                    'poster_url' => $poster_url,
                    'attachment_id' => $attachment_id
                ]);
                return;
            }
        }

        // Third fallback: Search by GUID (full URL path)
        $query = $wpdb->prepare(
            "SELECT ID FROM {$wpdb->posts}
            WHERE post_type = 'attachment'
            AND (post_mime_type LIKE 'image/%')
            AND guid LIKE %s
            ORDER BY ID DESC
            LIMIT 1",
            '%' . $wpdb->esc_like($video_slug) . '%poster%'
        );

        $attachment_id = $wpdb->get_var($query);

        if ($attachment_id) {
            $poster_url = wp_get_attachment_url($attachment_id);
            if ($poster_url) {
                wp_send_json_success([
                    'poster_url' => $poster_url,
                    'attachment_id' => $attachment_id
                ]);
                return;
            }
        }

        // Fourth fallback: Try using attachment_metadata to search filename
        $query = $wpdb->prepare(
            "SELECT post_id FROM {$wpdb->postmeta}
            WHERE meta_key = '_wp_attached_file'
            AND meta_value LIKE %s
            LIMIT 1",
            '%' . $wpdb->esc_like($video_slug) . '%poster%'
        );

        $attachment_id = $wpdb->get_var($query);

        if ($attachment_id) {
            $poster_url = wp_get_attachment_url($attachment_id);
            if ($poster_url) {
                wp_send_json_success([
                    'poster_url' => $poster_url,
                    'attachment_id' => $attachment_id
                ]);
                return;
            }
        }

        wp_send_json_error(['message' => 'Poster not found']);
    }
}

Dynamic_Carousel_Elementor::instance();