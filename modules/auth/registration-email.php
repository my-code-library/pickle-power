<?php
if (!defined('ABSPATH')) exit;

if (defined('PJ_REG_EMAIL_LOADED')) return;
define('PJ_REG_EMAIL_LOADED', true);

/**
 * --------------------------------------------------
 * Customize WordPress Registration Email
 * --------------------------------------------------
 */

add_filter('wp_new_user_notification_email', function($email_data, $user, $blogname) {

    $email = $user->user_email;

    // Check if password login is disabled
    $passwordless = get_option('pj_disable_password_login') === '1';

    if ($passwordless) {

        // Generate a magic link token
        $token = wp_generate_password(32, false);
        $expires = time() + 600; // 10 minutes

        update_user_meta($user->ID, 'pj_magic_token', $token);
        update_user_meta($user->ID, 'pj_magic_expires', $expires);

        $magic_url = add_query_arg([
            'pj_magic' => 1,
            'uid'      => $user->ID,
            'token'    => $token,
        ], site_url('/'));

        // Build magic-link-only email
        $email_data['subject'] = sprintf(__('Welcome to %s!'), $blogname);

        $email_data['message'] =
            "Welcome to $blogname!\n\n" .
            "Your account has been created.\n\n" .
            "Your username is: $email\n\n" .
            "Click below to log in instantly:\n$magic_url\n\n" .
            "This link expires in 10 minutes.\n";

        return $email_data;
    }

    // -------------------------------
    // Password login is enabled
    // Send normal reset-link email
    // -------------------------------

    $reset_key = get_password_reset_key($user);
    $reset_url = wp_login_url() . "?action=rp&key=$reset_key&login=" . rawurlencode($user->user_login);

    $email_data['subject'] = sprintf(__('Welcome to %s!'), $blogname);

    $email_data['message'] =
        "Welcome to $blogname!\n\n" .
        "Your account has been created.\n\n" .
        "Your username is: $email\n\n" .
        "To set your password, click the link below:\n$reset_url\n\n" .
        "If you didn't request this, you can ignore this email.\n";

    return $email_data;

}, 10, 3);
