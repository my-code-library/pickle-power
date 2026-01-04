<?php
/**
 * Plugin Name: Pickle Juice
 * Description: Email only registration-login, Google & Microsoft analytics/webmaster tools, custom login branding, Cloudflare Turnstile support
 * Author: Pickle Juice
 * Version: 1.0.4
 * Text Domain: pickle-juice
 * Domain Path: /languages
 */

// No direct access
if (!defined('ABSPATH')) {
    exit;
}

// Include the module loader
require_once plugin_dir_path(__FILE__) . 'includes/module-loader.php';

// Include admin settings page
require_once plugin_dir_pat(__FILE__) . 'includes/admin-settings.php';

// Turnstile support
function pj_get_turnstile_keys() {
    return [
        'site'   => get_option('pj_turnstile_site_key', ''),
        'secret' => get_option('pj_turnstile_secret_key', ''),
    ];
}
