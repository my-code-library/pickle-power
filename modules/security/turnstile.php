<?php
if (!defined('ABSPATH')) exit;

// Prevent double-loading
if (defined('PJ_TURNSTILE_LOADED')) return;
define('PJ_TURNSTILE_LOADED', true);

/**
 * --------------------------------------------------
 * Cloudflare Turnstile for Login + Registration
 * --------------------------------------------------
 *
 * - Adds Turnstile widget to wp-login.php (login + register)
 * - Validates Turnstile server-side
 * - Blocks bots before authentication
 */

// Your Turnstile keys (admin settings)
$keys = pj_get_turnstile_keys();
$turnstile_site_key   = $keys['site'];
$turnstile_secret_key = $keys['secret'];

// 1. Inject Turnstile widget into login + registration forms
add_action('login_form', 'pj_turnstile_render');
add_action('register_form', 'pj_turnstile_render');

function pj_turnstile_render() {
    global $turnstile_site_key;

    echo '<div style="margin: 20px 0;">
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
        <div class="cf-turnstile" data-sitekey="' . esc_attr($turnstile_site_key) . '"></div>
    </div>';
}

// 2. Validate Turnstile on login
add_filter('authenticate', function($user, $username, $password) {
    return pj_turnstile_validate($user);
}, 5, 3);

// 3. Validate Turnstile on registration
add_filter('registration_errors', function($errors) {
    $result = pj_turnstile_validate(null);

    if (is_wp_error($result)) {
        $errors->add('turnstile_error', $result->get_error_message());
    }

    return $errors;
}, 5);

// 4. Shared validation function
function pj_turnstile_validate($user) {
    global $turnstile_secret_key;

    // If another plugin already authenticated the user, skip validation
    if ($user instanceof WP_User) {
        return $user;
    }

    // Missing Turnstile response
    if (empty($_POST['cf-turnstile-response'])) {
        return new WP_Error('turnstile_missing', __('Please verify you are human.', 'pickle-juice'));
    }

    $response = sanitize_text_field($_POST['cf-turnstile-response']);

    // Verify with Cloudflare
    $verify = wp_remote_post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
        'body' => [
            'secret'   => $turnstile_secret_key,
            'response' => $response,
            'remoteip' => $_SERVER['REMOTE_ADDR'] ?? '',
        ]
    ]);

    if (is_wp_error($verify)) {
        return new WP_Error('turnstile_failed', __('Turnstile verification failed. Please try again.', 'pickle-juice'));
    }

    $data = json_decode(wp_remote_retrieve_body($verify), true);

    if (empty($data['success'])) {
        return new WP_Error('turnstile_invalid', __('Turnstile verification failed. Please try again.', 'pickle-juice'));
    }

    return $user; // Validation passed
}
