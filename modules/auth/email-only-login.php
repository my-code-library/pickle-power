<?php
if (!defined('ABSPATH')) exit;

// Prevent double-loading even if something else includes this file.
if (defined('PJ_EMAIL_ONLY_LOGIN_LOADED')) return;
define('PJ_EMAIL_ONLY_LOGIN_LOADED', true);

/**
 * Email-only registration (no username field shown to user).
 *
 * - Hides the username field on the registration form
 * - Removes "empty username" errors
 * - Requires email
 * - Auto-generates a unique username from the email
 */

// Hide username field on registration form.
add_action('register_form', function() {
    echo '<style>#user_login { display:none !important; }</style>';
});

// Remove "empty username" error and require email.
add_filter('registration_errors', function($errors, $sanitized_user_login, $user_email) {

    // WordPress may complain about empty username; we handle it ourselves.
    if (isset($errors->errors['empty_username'])) {
        unset($errors->errors['empty_username']);
    }

    if (empty($user_email)) {
        $errors->add('email_required', __('Please enter your email address.', 'pickle-juice'));
    }

    return $errors;
}, 10, 3);

// Remove username label
add_action('register_form', function() {
    echo '<style>
        #user_login,
        label[for="user_login"] {
            display: none !important;
        }
    </style>';
});

// Auto-generate username from email (no DB loops, no recursion).
add_filter('pre_user_login', function($login) {

    // If WordPress already has a username, leave it alone.
    if (!empty($login)) {
        return $login;
    }

    if (!empty($_POST['user_email'])) {
        $email = sanitize_email(wp_unslash($_POST['user_email']));
        $base  = strstr($email, '@', true);

        if (empty($base)) {
            $base = 'user';
        }

        // Generate a pseudo-unique username based on email.
        // Example: scotty_a1b2c3d4
        $unique = $base . '_' . wp_generate_password(8, false);

        return sanitize_user($unique, true);
    }

    // Fallback if somehow no email is present.
    return 'user_' . wp_generate_password(8, false);
});
