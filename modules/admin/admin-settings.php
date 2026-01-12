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

        /* ------------------------------
         * TURNSTILE SETTINGS
         * ------------------------------ */

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
                echo '<input type="password" id="pj_turnstile_site_key" name="pj_turnstile_site_key" value="' . $value . '" class="regular-text" autocomplete="new-password" />';
                echo ' <button type="button" class="button pj-toggle-key" data-target="pj_turnstile_site_key">Show</button>';
            },
            'pj-settings',
            'pj_turnstile_section'
        );

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


        /* ------------------------------
         * PASSWORDLESS LOGIN
         * ------------------------------ */

        register_setting('pj_settings_group', 'pj_enable_magic_link_login', [
            'type' => 'boolean',
            'default' => 0,
        ]);

        add_settings_section(
            'pj_passwordless_section',
            'Passwordless Login',
            function() {
                echo '<p>Choose password or magic link login.</p>';
            },
            'pj-settings'
        );

        add_settings_field(
            'pj_enable_magic_link_login',
            'Enable Magic Login Links',
            function() {
                $value = get_option('pj_enable_magic_link_login', '0');
                echo '<label>';
                echo '<input type="checkbox" name="pj_enable_magic_link_login" value="1" ' . checked($value, '1', false) . ' />';
                echo 'When enabled, a user can login with an email link instead of a password.';
                echo '</label>';
            },
            'pj-settings',
            'pj_passwordless_section'
        );


        /* ------------------------------
         * CUSTOM LOGIN URL
         * ------------------------------ */

        register_setting('pj_settings_group', 'pj_enable_custom_login_url', [
            'type'    => 'boolean',
            'default' => 1,
        ]);

        add_settings_field(
            'pj_enable_custom_login_url',
            'Enable Custom Login URL',
            function () {
                $value = get_option('pj_enable_custom_login_url', 1);
                echo '<label>';
                echo '<input type="checkbox" id="pj_enable_custom_login_url" name="pj_enable_custom_login_url" value="1" ' . checked($value, 1, false) . ' />';
                echo 'Enable the Custom Login URL module';
                echo '</label>';
            },
            'pj-settings',
            'pj_passwordless_section'
        );

        register_setting('pj_settings_group', 'pj_custom_login_slug');

        add_settings_field(
            'pj_custom_login_slug',
            'Custom Login URL',
            function () {
                $value = get_option('pj_custom_login_slug', 'login');
                echo '<div id="pj_custom_login_slug_wrapper">';
                echo '<input type="text" name="pj_custom_login_slug" value="' . esc_attr($value) . '" class="regular-text" />';
                echo '<p class="description">This slug replaces wp-login.php. Example: /login</p>';
                echo '</div>';
            },
            'pj-settings',
            'pj_passwordless_section'
        );


        /* ------------------------------
         * ADMIN DEBRANDING
         * ------------------------------ */

        register_setting('pj_settings_group', 'pj_disable_wp_org_menu');

        add_settings_field(
            'pj_disable_wp_org_menu',
            'Admin Bar & Footer Debranding',
            function () {
                $value = get_option('pj_disable_wp_org_menu', 0);

                echo '<label>';
                echo '<input type="checkbox" id="pj_disable_wp_org_menu" name="pj_disable_wp_org_menu" value="1" ' . checked($value, 1, false) . ' />';
                echo ' Remove WordPress.org admin bar menu and replace footer text';
                echo '</label>';

                echo '<p class="description">Hides the WordPress logo menu and replace the admin footer text.</p>';
            },
            'pj-settings',
            'pj_passwordless_section'
        );

        register_setting('pj_settings_group', 'pj_custom_admin_footer_text');

        add_settings_field(
            'pj_custom_admin_footer_text',
            'Custom Footer Text',
            function () {
                $value = esc_attr(get_option('pj_custom_admin_footer_text', ''));
                echo '<div id="pj_footer_text_wrapper">';
                echo '<input type="text" id="pj_custom_admin_footer_text" name="pj_custom_admin_footer_text" value="' . $value . '" class="regular-text" />';
                echo '<p class="description">Example: “Running on Pickle Power”</p>';
                echo '</div>';
            },
            'pj-settings',
            'pj_passwordless_section'
        );
        
        // Enable/disable Spotify bar
        register_setting(
            'pj_settings_group',            // Your existing settings group
            'pj_enable_spotify_bar',        // New option name
            [
                'type'              => 'boolean',
                'sanitize_callback' => function ( $value ) {
                    return $value ? 1 : 0;
                },
                'default'           => 0,
            ]
        );
        
        // Spotify URL
        register_setting(
            'pj_settings_group',
            'pj_spotify_url',
            [
                'type'              => 'string',
                'sanitize_callback' => 'esc_url_raw',
                'default'           => '',
            ]
        );
        
        add_settings_section(
            'pj_spotify_section',
            'Spotify Notification Bar',
            function() {
                echo '<p>Display a notification bar linking to your latest Spotify release.</p>';
            },
            'pj-settings'
        );

        // Checkbox: enable/disable bar
        add_settings_field(
            'pj_enable_spotify_bar',
            'Enable Spotify Notification Bar',
            function () {
                $value = get_option( 'pj_enable_spotify_bar', 0 );
                ?>
                <label>
                    <input type="checkbox"
                           name="pj_enable_spotify_bar"
                           value="1"
                        <?php checked( 1, $value ); ?> />
                    Show Spotify notification bar on the site
                </label>
                <?php
            },
            'pj-settings',          // 🔁 Replace with your actual settings page slug if needed
            'pj_spotify_section'   // 🔁 Replace with your existing section ID if needed
        );
        
        // Text input: Spotify URL
        add_settings_field(
            'pj_spotify_url',
            'Spotify Release URL',
            function () {
                $value = esc_url( get_option( 'pj_spotify_url', '' ) );
                ?>
                <input type="text"
                       name="pj_spotify_url"
                       value="<?php echo esc_attr( $value ); ?>"
                       style="width: 100%; max-width: 600px;"
                       placeholder="https://open.spotify.com/track/... or /album/...">
                <p class="description">
                    Paste the URL of your latest Spotify track, EP, or album.
                </p>
                <?php
            },
            'pj-settings',          // 🔁 Same as above
            'pj_spotify_section'   // 🔁 Same as above
        );

        /* ------------------------------
         * SERVER-SIDE SAFETY NET
         * ------------------------------ */

        add_filter('pre_update_option_pj_disable_wp_org_menu', function ($new, $old) {
            if (!$new) {
                update_option('pj_custom_admin_footer_text', '');
            }
            return $new;
        }, 10, 2);
    }


    /* ------------------------------
     * RENDER SETTINGS PAGE
     * ------------------------------ */

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

        <!-- Toggle password visibility -->
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

        <!-- Show/hide custom login slug -->
        <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggle = document.getElementById('pj_enable_custom_login_url');
            const slugField = document.getElementById('pj_custom_login_slug_wrapper');

            function updateVisibility() {
                slugField.style.display = toggle.checked ? '' : 'none';
            }

            toggle.addEventListener('change', updateVisibility);
            updateVisibility();
        });
        </script>

        <!-- Show/hide + clear footer text -->
        <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggle = document.getElementById('pj_disable_wp_org_menu');
            const wrapper = document.getElementById('pj_footer_text_wrapper');
            const textField = document.getElementById('pj_custom_admin_footer_text');

            function updateVisibility() {
                if (toggle.checked) {
                    wrapper.style.display = 'block';
                } else {
                    wrapper.style.display = 'none';
                    textField.value = '';
                }
            }

            toggle.addEventListener('change', updateVisibility);
            updateVisibility();
        });
        </script>

        </div>
        <?php
    }
}

PJ_Settings_Page::init();
