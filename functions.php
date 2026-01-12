<?php
/**
 * Plugin Name: Pickle Power
 * Description: Email only (no usernames) registration and login, Google & Microsoft analytics/webmaster tools, custom login logo, Cloudflare Turnstile support, magic login link support, custom login url support, Wordpress debranding support
 * Author: Pickle Juice
 * Version: 0.0.0
 * Text Domain: pickle-juice
 * Domain Path: /languages
 */

// No direct access
if (!defined('ABSPATH')) {
    exit;
}

// Adds settings link support
add_filter('plugin_action_links_' . plugin_basename(__FILE__), function($links) {
    $settings_url = admin_url('admin.php?page=pj-settings');
    $settings_link = '<a href="' . esc_url($settings_url) . '">Settings</a>';
    array_unshift($links, $settings_link);
    return $links;
});

// Include the module loader
require_once plugin_dir_path(__FILE__) . 'includes/module-loader.php';




