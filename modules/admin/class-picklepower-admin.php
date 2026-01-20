<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class PicklePower_Admin {

    public function __construct() {
        add_action( 'admin_enqueue_scripts', [ $this, 'picklepower_enqueue_admin_assets' ] );
    }

    /**
     * Enqueue admin CSS/JS for the Pickle Power settings page only.
     */
    public function picklepower_enqueue_admin_assets( $hook ) {

        // Match your settings page slug exactly:
        // settings_page_{menu_slug}
        if ( $hook !== 'settings_page_picklepower-settings' ) {
            return;
        }

        $base_url = plugin_dir_url( dirname( __FILE__ ) );

        wp_enqueue_style(
            'picklepower-admin',
            $base_url . 'assets/css/picklepower-admin.css',
            [],
            PICKLEPOWER_VERSION
        );

        wp_enqueue_script(
            'picklepower-admin',
            $base_url . 'assets/js/picklepower-admin.js',
            [ 'jquery' ],
            PICKLEPOWER_VERSION,
            true
        );
    }
}
