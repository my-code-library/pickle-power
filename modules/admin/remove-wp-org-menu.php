<?php
if (!defined('ABSPATH')) exit;

class PJ_Remove_WP_Org_Menu {
    public static function init() {
        // Remove WordPress.org links from the admin bar
        add_action('admin_bar_menu', [__CLASS__, 'remove_wp_org_admin_bar'], 999);
    }

    public static function remove_wp_org_admin_bar($wp_admin_bar) {
        // Remove the WordPress logo menu entirely
        $wp_admin_bar->remove_node('wp-logo');

        // If you want to remove only specific sub-items instead of the whole menu:
        // $wp_admin_bar->remove_node('about');
        // $wp_admin_bar->remove_node('wporg');
        // $wp_admin_bar->remove_node('documentation');
        // $wp_admin_bar->remove_node('support-forums');
        // $wp_admin_bar->remove_node('feedback');
    }
}

PJ_Remove_WP_Org_Menu::init();
