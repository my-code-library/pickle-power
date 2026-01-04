<?php
if (!defined('ABSPATH')) exit;

class PJ_Settings_Page {

    public static function init() {
        add_action('admin_menu', [__CLASS__, 'add_menu']);
        add_action('admin_init', [__CLASS__, 'register_settings']);
    }

    public static function add_menu() {
        add_menu_page(
            'Pickle Juice Settings',
            'Pickle Juice',
            'manage_options',
            'pj-settings',
            [__CLASS__, 'render_page'],
            'dashicons-admin-generic',
            80
        );
    }

    public static function register_settings() {

        // Register Turnstile keys
        register_setting('pj_settings_group', 'pj_turnstile_site_key');
        register_setting('pj_settings_group', 'pj_turnstile_secret_key');

        add_settings_section(
            'pj_turnstile_section',
            'Cloudflare Turnstile',
            function() {
                echo '<p>Protect login and registration with Cloudflare Turnstile.</p>';
            },
            'pj-settings'
        );

        add_settings_field(
            'pj_turnstile_site_key',
            'Turnstile Site Key',
            function() {
                $value = esc_attr(get_option('pj_turnstile_site_key', ''));
                echo '<input type="text" name="pj_turnstile_site_key" value="' . $value . '" class="regular-text">';
            },
            'pj-settings',
            'pj_turnstile_section'
        );

        add_settings_field(
            'pj_turnstile_secret_key',
            'Turnstile Secret Key',
            function() {
                $value = esc_attr(get_option('pj_turnstile_secret_key', ''));
                echo '<input type="text" name="pj_turnstile_secret_key" value="' . $value . '" class="regular-text">';
            },
            'pj-settings',
            'pj_turnstile_section'
        );
    }

    public static function render_page() {
        ?>
        <div class="wrap">
            <h1>Pickle Juice Settings</h1>

            <form method="post" action="options.php">
                <?php
                settings_fields('pj_settings_group');
                do_settings_sections('pj-settings');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
}

PJ_Settings_Page::init();
