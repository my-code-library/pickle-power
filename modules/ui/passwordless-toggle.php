<?php
if (!defined('ABSPATH')) exit;

if (defined('PJ_PASSWORDLESS_TOGGLE_LOADED')) return;
define('PJ_PASSWORDLESS_TOGGLE_LOADED', true);

/**
 * --------------------------------------------------
 * Passwordless Mode (Hide Password + Disable Login Button)
 * --------------------------------------------------
 */

add_action('login_form', function() {

   if ( get_option('pj_enable_magic_link_login', '0') ) {
        return;
    }

    echo '<script>
document.addEventListener("DOMContentLoaded", function() {

    function removePasswordBlock() {
        // Remove password field
        const wrap = document.querySelector(".user-pass-wrap");
        if (wrap) wrap.remove();

        // Remove login button
        const loginBtn = document.getElementById("wp-submit");
        if (loginBtn) loginBtn.remove();

        // Remove "Remember Me"
        const remember = document.querySelector(".forgetmenot");
        if (remember) remember.remove();
    }

    // Run immediately
    removePasswordBlock();

    // Watch for late DOM changes
    const observer = new MutationObserver(removePasswordBlock);
    observer.observe(document.body, { childList: true, subtree: true });

});
</script>';

//    echo '<p style="margin-top:10px; font-weight:bold;">
//        Password login is disabled. Use the magic link button above.
//    </p>';
});



add_filter('authenticate', function($user, $username, $password) {

    if (get_option('pj_disable_password_login') !== '1') {
        return $user;
    }

    if (!empty($password)) {
        return new WP_Error(
            'password_disabled',
            __('Password login is disabled. Please use a magic link.')
        );
    }

    return $user;

}, 5, 3);


