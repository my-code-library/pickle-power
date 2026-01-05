<?php
if (!defined('ABSPATH')) exit;

if (defined('PJ_PASSWORDLESS_TOGGLE_LOADED')) return;
define('PJ_PASSWORDLESS_TOGGLE_LOADED', true);

/**
 * --------------------------------------------------
 * Passwordless Mode (Hide Password + Disable Login Button)
 * --------------------------------------------------
 */

add_action('login_form', function() {

    // Only apply if password login is disabled
    if (get_option('pj_disable_password_login') !== '1') {
        return;
    }

    // Remove password field + login button using JS (safe + universal)
    echo '<script>
        document.addEventListener("DOMContentLoaded", function() {

            // Hide password field
            const passField = document.getElementById("user_pass");
            if (passField) {
                passField.closest("p").style.display = "none";
            }

            // Hide login button
            const loginBtn = document.getElementById("wp-submit");
            if (loginBtn) {
                loginBtn.style.display = "none";
            }

        });
    </script>';

    echo '<p style="margin-top:10px; font-weight:bold;">
        Password login is disabled. Use the magic link button below.
    </p>';
});
