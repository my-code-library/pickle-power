<?php
if (!defined('ABSPATH')) exit;

class PJ_Admin_Debranding {
    public static function init() {
        // Remove WP.org admin bar menu
        add_action('admin_bar_menu', [__CLASS__, 'remove_wp_org_admin_bar'], 999);

        // Replace footer text
        add_filter('admin_footer_text', [__CLASS__, 'replace_footer']);
    }

    public static function remove_wp_org_admin_bar($wp_admin_bar) {
        // Remove the WordPress logo menu entirely
        $wp_admin_bar->remove_node('wp-logo');
    }

    public static function replace_footer($text) {
        $enabled = get_option('pj_disable_wp_org_menu', 0);
        if (!$enabled) {
            return $text;
        }

        // Keep version number intact
        $version = get_bloginfo('version');

        // Custom footer text
        $custom = trim(get_option('pj_custom_admin_footer_text', ''));
        if ($custom === '') {
            $custom = 'Powered by Pickle Juice';
        }

        return esc_html($custom) . ' — WordPress ' . esc_html($version);
    }
}

PJ_Admin_Debranding::init();
