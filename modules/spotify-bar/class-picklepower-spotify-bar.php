<?php

if ( ! class_exists( 'PicklePower_Spotify_Bar' ) ) {

    class PicklePower_Spotify_Bar {

        public function __construct() {
            add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );
            add_action('wp_body_open', [ $this, 'render_bar' ]);
            add_action('wp_footer', [ $this, 'render_bar' ]);
        }

        /**
         * Enqueue CSS for the Spotify bar.
         */
        public function enqueue_assets() {
            // Only enqueue if bar is enabled and URL exists
            $enabled = get_option( 'pj_enable_spotify_bar', 0 );
            $url     = get_option( 'pj_spotify_url', '' );

            if ( ! $enabled || empty( $url ) ) {
                return;
            }

            wp_enqueue_style(
                'picklepower-spotify-bar',
                plugin_dir_url( __FILE__ ) . 'assets/css/spotify-bar.css',
                [],
                '1.0'
            );
        }

        /**
         * Render the notification bar in the footer.
         */
        public function render_bar() {
            $enabled = get_option( 'pj_enable_spotify_bar', 0 );
            $url     = esc_url( get_option( 'pj_spotify_url', '' ) );

            if ( ! $enabled || empty( $url ) ) {
                return;
            }

            /**
             * Allow template override from theme:
             * wp-content/themes/your-theme/picklepower/spotify-bar/bar.php
             */
            $template = locate_template( 'picklepower/spotify-bar/bar.php' );

            if ( ! $template ) {
                $template = __DIR__ . '/views/bar.php';
            }

            // Make URL available to the template
            $spotify_url = $url;

            include $template;
        }
    }

    new PicklePower_Spotify_Bar();
}
