<?php
if (!defined('ABSPATH')) exit;

if (defined('PJ_SETTINGS_PAGE_LOADED')) return;
define('PJ_SETTINGS_PAGE_LOADED', true);

class PJ_Settings_Page {

    public static function init() {
        add_action('admin_menu', [__CLASS__, 'add_menu']);
        add_action('admin_init', [__CLASS__, 'register_settings']);
    }

    public static function add_menu() {
        add_menu_page(
            'Additional Settings',
            'Additional Settings',
            'manage_options',
            'pj-settings',
            [__CLASS__, 'render_page'],
            'dashicons-admin-generic',
            80
        );
    }

    public static function register_settings() {

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

// Field: Site Key
add_settings_field(
    'pj_turnstile_site_key',
    'Turnstile Site Key',
    function() {
        $value = esc_attr(get_option('pj_turnstile_site_key', ''));
        echo '<input type="password" id="pj_turnstile_site_key" name="pj_turnstile_site_key" value="' . $value . '" class="regular-text" autocomplete="new-password" />';
        echo ' <button type="button" class="button pj-toggle-key" data-target="pj_turnstile_site_key">Show</button>';
    },
    'pj-settings',
    'pj_turnstile_section'
);

// Field: Secret Key
add_settings_field(
    'pj_turnstile_secret_key',
    'Turnstile Secret Key',
    function() {
        $value = esc_attr(get_option('pj_turnstile_secret_key', ''));
        echo '<input type="password" id="pj_turnstile_secret_key" name="pj_turnstile_secret_key" value="' . $value . '" class="regular-text" autocomplete="new-password" />';
        echo ' <button type="button" class="button pj-toggle-key" data-target="pj_turnstile_secret_key">Show</button>';
    },
    'pj-settings',
    'pj_turnstile_section'
);

    }

    public static function render_page() {
        ?>
        <div class="wrap">
            <h1></h1>

            <form method="post" action="options.php">
                <?php
                settings_fields('pj_settings_group');
                do_settings_sections('pj-settings');
                submit_button();
                ?>
            </form>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.pj-toggle-key').forEach(function(button) {
        button.addEventListener('click', function() {
            const input = document.getElementById(this.dataset.target);
            if (!input) return;

            if (input.type === 'password') {
                input.type = 'text';
                this.textContent = 'Hide';
            } else {
                input.type = 'password';
                this.textContent = 'Show';
            }
        });
    });
});
</script>

        </div>
        <?php
    }
}

PJ_Settings_Page::init();
