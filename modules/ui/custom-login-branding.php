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
            width: 120px !important;
            height: 120px !important;
            border-radius: 100%;
        }
        body.login div#login {
            width: 375px;
        }
        /* Pickle Power – Enhanced Registration Submit Button */
        #registerform p.submit input[type="submit"] {
            width: 100%;
            padding: 14px 18px;
            background: linear-gradient(135deg, #00d084, #00a86b);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.25s ease;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
        }
        
        #registerform p.submit input[type="submit"]:hover {
            background: linear-gradient(135deg, #00e699, #00b97a);
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.22);
        }
        
        #registerform p.submit input[type="submit"]:active {
            transform: translateY(0);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.18);
        }
        @media (max-width: 480px) {
            #registerform p.submit input[type="submit"] {
                font-size: 15px;
                padding: 12px 16px;
                width: 100%;
                box-sizing: border-box;
            }
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
