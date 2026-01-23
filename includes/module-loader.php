<?php
if (!defined('ABSPATH')) exit;

class PJ_Module_Loader {
    public static function load() {

        // Absolute path to plugin root
        $plugin_root = dirname(plugin_dir_path(__FILE__));

        // Absolute path to modules directory
        $base_dir = $plugin_root . '/modules/';

        $modules = [
            // Always-loaded modules
            'admin/admin-settings.php',
            'auth/email-only-registration.php',
            'auth/email-only-auth.php',
            'auth/registration-email.php',
            'security/turnstile.php',
            'trackers/analytics.php',
            'ui/custom-login-branding.php',
            'ui/passwordless-toggle.php',

        ];

        /**
         * Conditionally loaded modules
         * --------------------------------
         * These modules depend on admin settings.
         */

        // Custom Login URL module
        if ( get_option('pj_enable_custom_login_url', 1) ) {
            $modules[] = 'auth/custom-login-url.php';
        }

        if ( get_option('pj_enable_magic_link_login', '1') ) {
            $modules[] = 'auth/magic-links.php';
        }

        // Disable WordPress.org admin bar menu
        if ( get_option('pj_disable_wp_org_menu') ) {
            $modules[] = 'ui/remove-wp-org-menu.php';
        }


        //Enable Spotify Release Notification Bar v2
        if( get_option( 'pj_enable_spotify_bar', 0 ) ) {
            $modules[] = 'spotify-bar/class-picklepower-spotify-bar.php';
        }
        /**
         *
        //Enable Spotify release notification bar
        $pj_enable_spotify_bar = get_option( 'pj_enable_spotify_bar', 0 );

            if ( $pj_enable_spotify_bar ) {
            $spotify_bar_module = $base_dir . 'spotify-bar/class-picklepower-spotify-bar.php';
        
            if ( file_exists( $spotify_bar_module ) ) {
                require_once $spotify_bar_module;
            }
        }
        *
        */
        
        // Superfan registration
        if (get_option('pj_superfan_enabled')) {
            $modules[] = 'auth/superfan-registration/superfan-registration.php';
        }

        // Loop through and load modules
        foreach ($modules as $module) {
            $path = $base_dir . $module;

            if (file_exists($path)) {
                include_once $path;
            }
        }
        
        if ( is_admin() ) { 
            $admin_class = $base_dir . 'admin/class-picklepower-admin.php'; 
            if ( file_exists( $admin_class ) ) { 
                require_once $admin_class; 
                new PicklePower_Admin(); 
            } 
        }
        
    }
}

add_action('plugins_loaded', ['PJ_Module_Loader', 'load']);








