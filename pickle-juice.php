<?php
/**
 * Plugin Name: Pickle Juice
 * Description: Email only (no usernames) registration and login, Google & Microsoft analytics/webmaster tools, custom login logo, Cloudflare Turnstile support, magic login link support, custom login url support
 * Author: Pickle Juice
 * Version: 1.1.2
 * Text Domain: pickle-juice
 * Domain Path: /languages
 */

// No direct access
if (!defined('ABSPATH')) {
    exit;
}

// Include the module loader
require_once plugin_dir_path(__FILE__) . 'includes/module-loader.php';

