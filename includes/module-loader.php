<?php
if (!defined('ABSPATH')) exit;

class PJ_Module_Loader {
    public static function load() {

        // Absolute path to plugin root
        $plugin_root = dirname(plugin_dir_path(__FILE__));

        // Absolute path to modules directory
        $base_dir = $plugin_root . '/modules/';

        $modules = [
		// Add modules here
            'admin/admin-settings.php',
			'auth/email-only-registration.php',
			'auth/email-only-auth.php',
			'security/turnstile.php',
			'trackers/analytics.php',
			'ui/custom-login-branding.php',
			'auth/magic-links.php',

        ];

        foreach ($modules as $module) {
            $path = $base_dir . $module;

            if (file_exists($path)) {
                include_once $path;
            }
        }
    }
}


add_action('plugins_loaded', ['PJ_Module_Loader', 'load']);




