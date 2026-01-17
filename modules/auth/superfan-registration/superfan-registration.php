<?php
/**
 * Module: Super Fan Registration Enhancer
 * Description: Branded messaging, custom button text, and removal of default WP notices.
 */

if (!defined('ABSPATH')) exit;

class PicklePower_SuperFan_Registration {

    public function __construct() {
        add_action('register_form', [$this, 'render_superfan_message']);
        add_filter('gettext', [$this, 'filter_register_text'], 10, 3);
        add_filter('login_message', [$this, 'replace_register_heading']);
        add_action('login_enqueue_scripts', [$this, 'enqueue_styles']);
    }

    /**
     * Inject branded Super Fan messaging above the registration form.
     */
    public function render_superfan_message() {
        ?>
        <div class="pickle-superfan-intro">
            <h2 class="pickle-superfan-title">Join the Pickle Juice Super Fan Club</h2>
            <p class="pickle-superfan-tagline">
                Unlock early releases, exclusive drops, and behind‑the‑scenes access.
            </p>

            <ul class="pickle-superfan-benefits">
                <li>🔥 Early access to new tracks</li>
                <li>🎧 Exclusive Super Fan‑only releases</li>
                <li>📬 Direct updates from the artist</li>
                <li>💬 Community perks as they roll out</li>
            </ul>
        </div>
        <?php
    }

    /**
     * Replace or remove default WP text:
     * - "Register For This Site"
     * - "A confirmation email will be sent..."
     * - Button text "Register"
     */
    public function filter_register_text($translated, $text, $domain) {

        // Replace the heading "Register For This Site"
        if ($text === 'Register For This Site') {
            return 'Become a Super Fan';
        }

        // Replace the notice above the button
        if ($text === 'A confirmation email will be sent to you.') {
            return 'You’re one step away from joining the inner circle.';
        }

        // Replace the button text "Register"
        if ($text === 'Register') {
            return 'Join the Super Fan Club';
        }

        return $translated;
    }

    /**
     * Remove the default login_message heading entirely if desired.
     */
    public function replace_register_heading($message) {
        // Only modify on the registration page
        if (isset($_GET['action']) && $_GET['action'] === 'register') {
            // Remove default WP message entirely
            return '';
        }
        return $message;
    }

    /**
     * Load CSS only on the registration page.
     */
    public function enqueue_styles() {
        if (isset($_GET['action']) && $_GET['action'] === 'register') {
            wp_enqueue_style(
                'pickle-superfan-css',
                plugin_dir_url(__FILE__) . 'assets/css/superfan-registration.css',
                [],
                '1.0.1'
            );
        }
    }
}

new PicklePower_SuperFan_Registration();
