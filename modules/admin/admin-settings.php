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
            'Options',
            'Options',
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
        
        // Register password login toggle
        register_setting('pj_settings_group', 'pj_disable_password_login');
        
        // Section: Passwordless Login
        add_settings_section(
            'pj_passwordless_section',
            'Passwordless Login',
            function() {
                echo '<p>Control whether users can log in with a password or only via magic link.</p>';
            },
            'pj-settings'
        );
        
        // Field: Disable Password Login
        add_settings_field(
            'pj_disable_password_login',
            'Disable Password Login',
            function() {
                $value = get_option('pj_disable_password_login', '0');
                echo '<label>';
                echo '<input type="checkbox" name="pj_disable_password_login" value="1" ' . checked($value, '1', false) . ' />';
                echo ' Disable password login (magic link only)';
                echo '</label>';
            },
            'pj-settings',
            'pj_passwordless_section'
        );
        
        register_setting('pj_settings_group', 'pj_custom_login_slug');
        
        add_settings_field(
            'pj_custom_login_slug',
            'Custom Login URL',
            function() {
                $value = esc_attr(get_option('pj_custom_login_slug', ''));
                echo '<input type="text" name="pj_custom_login_slug" value="' . $value . '" placeholder="login" />';
                echo '<p class="description">Example: entering <strong>login</strong> makes your login URL <code>/login/</code></p>';
            },
            'pj-settings',
            'pj_passwordless_section'
        );

        register_setting(
            'pj_settings_group',
            'pj_enable_custom_login_url',
            [
                'type'    => 'boolean',
                'default' => 1,
            ]
        );

        add_settings_field(
            'pj_enable_custom_login_url',
            'Enable Custom Login URL',
            function () {
                $value = get_option('pj_enable_custom_login_url', 1);
        
                echo '<label>';
                echo '<input type="checkbox" name="pj_enable_custom_login_url" value="1" ' . checked($value, 1, false) . ' />';
                echo ' Activate the Custom Login URL module';
                echo '</label>';
        
                echo '<p class="description">Disabling this will completely deactivate the custom login URL module and restore the default <code>wp-login.php</code> behavior.</p>';
            },
            'pj-settings',
            'pj_passwordless_section'
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
