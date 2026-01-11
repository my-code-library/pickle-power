<?php
if (!defined('ABSPATH')) exit;

if (defined('PJ_CUSTOM_LOGIN_LOADED')) return;
define('PJ_CUSTOM_LOGIN_LOADED', true);

/**
 * --------------------------------------------------
 * Pickle Juice – Custom Login URL Module
 * --------------------------------------------------
 *
 * - Replaces wp-login.php with a custom slug
 * - Prevents redirect loops (even when WP internally loads wp-login.php)
 * - Allows magic links, resets, logout, lost password, etc.
 * - Filters site_url() so plugins stop linking to wp-login.php
 * - Minimal, predictable, override-safe
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

    $uri = $_SERVER['REQUEST_URI'] ?? '';

    /**
     * --------------------------------------------------
     * 1. If the PUBLIC URL already IS the custom slug,
     *    do nothing.
     *
     * This is the key to preventing redirect loops.
     * --------------------------------------------------
     */
    if (preg_match("#^/{$slug}(/|$)#", $uri)) {
        return;
    }

    /**
     * --------------------------------------------------
     * 2. Allow magic link flows
     * --------------------------------------------------
     */
    if (isset($_GET['pj_magic'])) return;
    if (!empty($_POST['pj_magic_request'])) return;

    /**
     * --------------------------------------------------
     * 3. Allow classic login POSTs (until removed)
     * --------------------------------------------------
     */
    if (!empty($_POST['log'])) return;

    /**
     * --------------------------------------------------
     * 4. Allow all core WP login actions
     * --------------------------------------------------
     */
    $allowed_actions = [
        'login',
        'logout',
        'lostpassword',
        'rp',
        'resetpass',
        'register',
        'postpass',
    ];

    if (isset($_GET['action']) && in_array($_GET['action'], $allowed_actions, true)) {
        return;
    }

    /**
     * --------------------------------------------------
     * 5. If the internal script is wp-login.php,
     *    redirect to custom slug
     * --------------------------------------------------
     */
    $path = parse_url($uri, PHP_URL_PATH);
    $script = basename($path);
    /**
     * --------------------------------------------------
     * Bluehost-proof override:
     * If WordPress is internally loading wp-login.php,
     * but the public URL is NOT your custom slug,
     * force redirect to your slug.
     * --------------------------------------------------
     */
    if (
        isset($GLOBALS['pagenow']) &&
        $GLOBALS['pagenow'] === 'wp-login.php' &&
        !preg_match("#^/{$slug}(/|$)#", $uri)
    ) {
        wp_redirect(home_url("/{$slug}/"));
        exit;
    }

    if (strcasecmp($script, 'wp-login.php') === 0) {
        wp_redirect(home_url("/{$slug}/"));
        exit;
    }
});
