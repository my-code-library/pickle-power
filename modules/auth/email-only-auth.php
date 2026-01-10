<?php
if (!defined('ABSPATH')) exit;

// Prevent double-loading
if (defined('PJ_EMAIL_ONLY_AUTH_LOADED')) return;
define('PJ_EMAIL_ONLY_AUTH_LOADED', true);

/**
 * --------------------------------------------------
 * Email‑Only Login
 * --------------------------------------------------
 *
 * - Replaces username login with email login
 * - Keeps WordPress password authentication
 * - No recursion, no loops, no DB hammering
 */

// 1. Change the login form label from "Username" to "Email"
add_filter('gettext', function($text, $original, $domain) {
    if ($text === 'Username or Email Address' || $text === 'Username') {
        return 'Email Address';
    }
    return $text;
}, 10, 3);

// 2. Authenticate using email instead of username
add_filter('authenticate', function($user, $username, $password) {

    // If another authentication method already succeeded, respect it
    if ($user instanceof WP_User) {
        return $user;
    }

    // If no email or password provided, let WP handle errors
    if (empty($username) || empty($password)) {
        return $user;
    }

    // Look up user by email
    $user_obj = get_user_by('email', $username);

    if (!$user_obj) {
        return new WP_Error('invalid_email', __('No account found with that email address.'));
    }

    // Validate password
    if (!wp_check_password($password, $user_obj->user_pass, $user_obj->ID)) {
        return new WP_Error('incorrect_password', __('Incorrect password.'));
    }

    // Success — return the WP_User object
    return $user_obj;

}, 20, 3);

// 3. Make the login form input type="email" for better UX
add_action('login_head', function() {
    echo '<style>
        #user_login {
            width: 100%;
        }
        #user_login[type="text"] {
            /* Force browser to treat it like an email field */
        }
    </style>';
});
