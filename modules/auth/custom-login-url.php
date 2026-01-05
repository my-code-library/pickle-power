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

add_action('login_init', function() {

    $slug = trim(get_option('pj_custom_login_slug', ''), '/');
    if (!$slug) return;

    // The actual URL the user requested
    $request_uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

    // 1. If user is already on the custom login URL, allow
    if ($request_uri === $slug) {
        return;
    }

    // 2. Allow magic links
    if (isset($_GET['pj_magic'])) {
        return;
    }

    // 3. Allow password reset flows
    if (isset($_GET['action']) && in_array($_GET['action'], ['rp', 'resetpass'], true)) {
        return;
    }

    // 4. Allow login POST (password or magic link)
    if (!empty($_POST['log']) || !empty($_POST['pj_magic_request'])) {
        return;
    }

    // 5. If the request is NOT wp-login.php, do nothing
    // This prevents redirect loops
    $script = basename($_SERVER['SCRIPT_NAME']);
    if ($script !== 'wp-login.php') {
        return;
    }

    // 6. Redirect ONLY wp-login.php to the custom slug
    wp_redirect(home_url("/{$slug}/"));
    exit;
});
