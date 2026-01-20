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
        if ( $hook !== 'toplevel_page_pj-settings' ) {
            return;
        }

        $base_url = plugin_dir_url( dirname( __FILE__ ) );

        wp_enqueue_style(
            'picklepower-admin',
            $base_url . 'admin/assets/css/picklepower-admin.css',
            [],
            '1.0'
        );

        wp_enqueue_script(
            'picklepower-admin',
            $base_url . 'admin/assets/js/picklepower-admin.js',
            [ 'jquery' ],
            '1.0',
            true
        );
    }
}
