<?php
if (!defined('ABSPATH')) exit;

if (defined('PJ_PASSWORDLESS_TOGGLE_LOADED')) return;
define('PJ_PASSWORDLESS_TOGGLE_LOADED', true);

/**
 * --------------------------------------------------
 * Hide Password Field When Password Login Is Disabled
 * --------------------------------------------------
 */

add_action('login_form', function() {

    if (get_option('pj_disable_password_login') !== '1') {
        return;
    }

    echo '<style>
        #user_pass,
        label[for="user_pass"],
        #loginform p:has(#user_pass) {
            display: none !important;
        }
    </style>';

    echo '<p style="margin-top:10px; font-weight:bold;">
        Password login is disabled. Use the magic link button below.
    </p>';
});


add_filter('authenticate', function($user, $username, $password) {

    $disabled = get_option('pj_disable_password_login', '0');

    if ($disabled !== '1') {
        return $user; // Password login allowed
    }

    // If user tries to log in with a password, block it
    if (!empty($password)) {
        return new WP_Error(
            'password_disabled',
            __('Password login is disabled. Please use a magic link.')
        );
    }

    return $user;

}, 5, 3);
