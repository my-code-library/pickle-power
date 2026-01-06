<?php
if (!defined('ABSPATH')) exit;

if (defined('PJ_CUSTOM_LOGIN_LOADED')) return;
define('PJ_CUSTOM_LOGIN_LOADED', true);

/**
 * --------------------------------------------------
 * Custom Login URL Module (Pickle Juice)
 * --------------------------------------------------
 *
 * - Replaces wp-login.php with a custom slug
 * - Avoids redirect loops
 * - Respects magic links, password resets, and core actions
 * - Plays nice with POST-based flows
 *
 * Option: pj_custom_login_slug
 */

// --------------------------------------------------
// Flush rewrite rules when slug changes
// --------------------------------------------------
add_action('update_option_pj_custom_login_slug', function() {
    flush_rewrite_rules();
});

// --------------------------------------------------
// Register rewrite rule for custom login slug
// --------------------------------------------------
add_action('init', function() {
    $slug = trim(get_option('pj_custom_login_slug', ''), '/');
    if (!$slug) return;

    // /slug/ → wp-login.php
    add_rewrite_rule("^{$slug}/?$", 'wp-login.php', 'top');
});

// --------------------------------------------------
// Filter site_url() so wp-login.php links use custom slug
// --------------------------------------------------
add_filter('site_url', function($url, $path, $scheme, $blog_id) {
    $slug = trim(get_option('pj_custom_login_slug', ''), '/');
    if (!$slug) return $url;

    if ($path === 'wp-login.php' || $path === '/wp-login.php') {
        return home_url("/{$slug}/");
    }

    return $url;
}, 10, 4);

// --------------------------------------------------
// Redirect direct wp-login.php hits to custom slug
// --------------------------------------------------
add_action('login_init', function() {

    $slug = trim(get_option('pj_custom_login_slug', ''), '/');
    if (!$slug) return;

    // Current request path (no query string)
    $request_path = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
    $request_path = trim($request_path, '/');

    // If we're already at the custom slug, do nothing
    if ($request_path === $slug) {
        return;
    }

    // Allow magic link processing
    if (isset($_GET['pj_magic'])) {
        return;
    }

    // Allow magic link request POSTs
    if (!empty($_POST['pj_magic_request'])) {
        return;
    }

    // If you're still supporting classic username login, allow login POSTs
    // Once removed, you can safely delete this block.
    if (!empty($_POST['log'])) {
        return;
    }

    // Allow all core WP login actions (prevents loops)
    $allowed_actions = [
        'login',
        'logout',
        'lostpassword',
        'rp',
        'resetpass',
        'register',
        'postpass',
    ];

    if (isset($_GET['action']) && $_GET['action'] !== '') {
        $action = sanitize_key(wp_unslash($_GET['action']));
        if (in_array($action, $allowed_actions, true)) {
            return;
        }
    }

    // Only intercept direct wp-login.php hits
    $requested_file = basename($request_path);
    if (strcasecmp($requested_file, 'wp-login.php') !== 0) {
        return;
    }

    // Redirect to custom login slug
    wp_redirect(home_url("/{$slug}/"));
    exit;
});
