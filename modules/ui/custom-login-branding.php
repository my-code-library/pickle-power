<?php
if (!defined('ABSPATH')) exit;

// Prevent double-loading
if (defined('PJ_CUSTOM_LOGIN_BRANDING_LOADED')) return;
define('PJ_CUSTOM_LOGIN_BRANDING_LOADED', true);

/**
 * Custom Login Branding
 *
 * - Replaces the WordPress logo on wp-login.php
 * - Makes the logo link to your homepage
 * - Sets the logo title attribute
 */

// 1. Replace the login logo with your custom image
add_action('login_enqueue_scripts', function() {

    // Update this URL to your actual logo
    $logo_url = wp_get_attachment_url(320); // replace 320 with your media ID
    
    echo '<style>
        body.login div#login h1 a {
            background-image: url(' . esc_url($logo_url) . ') !important;
            background-size: contain !important;
            width: 240px !important;
            height: 120px !important;
        }
    </style>';
});

// 2. Make the logo link to your homepage
add_filter('login_headerurl', function() {
    return home_url('/');
});

// 3. Change the logo title text
add_filter('login_headertext', function() {
    return get_bloginfo('name');
});
