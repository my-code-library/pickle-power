<?php
if (!defined('ABSPATH')) exit;

if (defined('PJ_CUSTOM_LOGIN_LOADED')) return;
define('PJ_CUSTOM_LOGIN_LOADED', true);

/**
 * --------------------------------------------------
 * Custom Login URL Module
 * --------------------------------------------------
 *
 * Allows replacing wp-login.php with a custom slug.
 * Prevents redirect loops.
 * Ensures magic links, password resets, and core
 * auth actions still work.
 *
 */

add_action('init', function() {

    $slug = trim(get_option('pj_custom_login_slug', ''), '/');

    if (!$slug) {
        return; // Feature disabled
    }

    // Register the custom login endpoint
    add_rewrite_rule("^{$slug}/?$", 'wp-login.php', 'top');
});

/**
 * Prevent direct access to wp-login.php unless:
 * - It's a magic link
 * - It's a password reset
 * - It's an authentication post
 * - It's an admin redirect
 */
add_action('login_init', function() {

    $slug = trim(get_option('pj_custom_login_slug', ''), '/');
    if (!$slug) return;

    $requested = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    // Allowed direct access cases
    $allowed = [
        'action=rp',  // password reset
        'action=resetpass',
        'pj_magic',   // magic link
    ];

    foreach ($allowed as $allow) {
        if (isset($_REQUEST[$allow])) {
            return; // allow access
        }
    }

    // If user is posting login credentials, allow
    if (!empty($_POST['log']) || !empty($_POST['pj_magic_request'])) {
        return;
    }

    // If already on custom login URL, allow
    if ($requested === $slug) {
        return;
    }

    // Otherwise redirect to custom login URL
    wp_redirect(home_url("/{$slug}/"));
    exit;
});
