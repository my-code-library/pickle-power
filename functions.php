<?php
/**
 * Plugin Name: Pickle Power
 * Description: Email only (no usernames) registration and login, Google & Microsoft analytics/webmaster tools, custom login logo, Cloudflare Turnstile support, magic login links, custom login url, Wordpress debranding, superfan registration enhancements
 * Author: Pickle Juice
 * Version: 1.0.5
 * Text Domain: pickle-juice
 * Domain Path: /languages
 */

/**
Pickle Power – A modular WordPress plugin for branded login, security, and admin UX.
Copyright (C) 2026 Gold Coast Music/Pickle Juice

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
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












