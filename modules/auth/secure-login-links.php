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
    ?>
    <p style="margin-top:20px;">
        <button type="submit" name="pj_magic_request" value="1" class="button button-primary" style="width:100%;">
            Send me a secure login link
        </button>
    </p>
    <?php
});

// 2. Handle magic link request
add_action('login_form_login', function() {

    if (!isset($_POST['pj_magic_request'])) {
        return; // Not a magic link request
    }

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
