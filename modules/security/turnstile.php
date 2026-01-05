<?php
if (!defined('ABSPATH')) exit;

if (defined('PJ_TURNSTILE_LOADED')) return;
define('PJ_TURNSTILE_LOADED', true);

/**
 * --------------------------------------------------
 * Cloudflare Turnstile (Login + Registration)
 * --------------------------------------------------
 */

// Pull keys from settings
$turnstile_site_key   = get_option('pj_turnstile_site_key', '');
$turnstile_secret_key = get_option('pj_turnstile_secret_key', '');

// 1. Render widget on login + registration
function pj_turnstile_render() {
    $site_key = get_option('pj_turnstile_site_key', '');

    if (empty($site_key)) {
        return; // No widget if not configured
    }

    echo '<div>
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
        <div class="cf-turnstile" data-sitekey="' . esc_attr($site_key) . '" style="margin: 20px 0;"></div>
    </div>';
}

add_action('login_form', 'pj_turnstile_render');
add_action('register_form', 'pj_turnstile_render');

// 2. Validate Turnstile (shared)
function pj_turnstile_validate($user) {
    $secret = get_option('pj_turnstile_secret_key', '');

    if (empty($secret)) {
        return $user; // Skip validation if not configured
    }

    if (isset($user) && $user instanceof WP_User) {
        return $user; // Already authenticated
    }

    if (empty($_POST['cf-turnstile-response'])) {
        return new WP_Error('turnstile_missing', __('Please verify you are human.'));
    }

    $response = sanitize_text_field($_POST['cf-turnstile-response']);

    $verify = wp_remote_post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
        'body' => [
            'secret'   => $secret,
            'response' => $response,
            'remoteip' => $_SERVER['REMOTE_ADDR'] ?? '',
        ]
    ]);

    if (is_wp_error($verify)) {
        return new WP_Error('turnstile_failed', __('Turnstile verification failed.'));
    }

    $data = json_decode(wp_remote_retrieve_body($verify), true);

    if (empty($data['success'])) {
        return new WP_Error('turnstile_invalid', __('Turnstile verification failed.'));
    }

    return $user;
}

// 3. Apply validation to login + registration
add_filter('authenticate', 'pj_turnstile_validate', 5, 1);
add_filter('registration_errors', function($errors) {
    $result = pj_turnstile_validate(null);
    if (is_wp_error($result)) {
        $errors->add('turnstile_error', $result->get_error_message());
    }
    return $errors;
}, 5);
