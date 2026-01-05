<?php
if (!defined('ABSPATH')) exit;

if (defined('PJ_MAGIC_LINKS_LOADED')) return;
define('PJ_MAGIC_LINKS_LOADED', true);

/**
 * --------------------------------------------------
 * Pickle Juice Secure Link Login
 * --------------------------------------------------
 */

// 1. Add "Send Magic Link" button to login form
add_action('login_form', function() {

    // Only show magic link button when password login is disabled
    if (get_option('pj_disable_password_login') !== '1') {
        return;
    }

    echo '<p>
        <button type="submit" name="pj_magic_request" value="1" class="button button-primary" style="width:100%;margin: 20px 0;">
            Send me a magic login link
        </button>
    </p>';
});

// 2. Handle magic link request
add_action('login_form_login', function() {

    if (!isset($_POST['pj_magic_request'])) {
        return; // Not a magic link request
    }

    // --- Turnstile validation ---
    $secret = get_option('pj_turnstile_secret_key', '');

    if (!empty($secret)) {

        if (empty($_POST['cf-turnstile-response'])) {
            wp_die('Please verify you are human.');
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
            wp_die('Turnstile verification failed.');
        }

        $data = json_decode(wp_remote_retrieve_body($verify), true);

        if (empty($data['success'])) {
            wp_die('Turnstile verification failed.');
        }
    }
    // --- End Turnstile validation ---

    // Continue with magic link logic...
    $email = sanitize_email($_POST['log'] ?? '');

    if (empty($email) || !is_email($email)) {
        wp_die('Please enter a valid email address.');
    }

    $user = get_user_by('email', $email);

    if (!$user) {
        wp_die('No account found with that email address.');
    }

    // Generate token
    $token = wp_generate_password(32, false);
    $expires = time() + 600; // 10 minutes

    update_user_meta($user->ID, 'pj_magic_token', $token);
    update_user_meta($user->ID, 'pj_magic_expires', $expires);

    // Build magic link
    $url = add_query_arg([
        'pj_magic' => 1,
        'uid'      => $user->ID,
        'token'    => $token,
    ], site_url('/'));

    // Send email
    wp_mail(
        $email,
        'Your Pickle Juice Magic Login Link',
        "Click to log in:\n\n$url\n\nThis link expires in 10 minutes."
    );

    wp_die('A magic login link has been sent to your email.');
});


// 3. Process magic link login
add_action('init', function() {

    if (!isset($_GET['pj_magic'])) {
        return;
    }

    $uid   = intval($_GET['uid'] ?? 0);
    $token = sanitize_text_field($_GET['token'] ?? '');

    if (!$uid || !$token) {
        wp_die('Invalid magic link.');
    }

    $saved_token   = get_user_meta($uid, 'pj_magic_token', true);
    $saved_expires = intval(get_user_meta($uid, 'pj_magic_expires', true));

    if (!$saved_token || !$saved_expires) {
        wp_die('Magic link expired or invalid.');
    }

    if ($saved_token !== $token) {
        wp_die('Invalid magic link token.');
    }

    if (time() > $saved_expires) {
        wp_die('Magic link has expired.');
    }

    // Token is valid — log user in
    delete_user_meta($uid, 'pj_magic_token');
    delete_user_meta($uid, 'pj_magic_expires');

    wp_set_auth_cookie($uid, true);
    wp_redirect(admin_url());
    exit;
});
