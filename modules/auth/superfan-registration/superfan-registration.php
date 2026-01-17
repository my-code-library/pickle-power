<?php
/**
 * Module: Super Fan Registration Enhancer
 * Description: Adds branded messaging and styling to the registration page.
 */

if (!defined('ABSPATH')) exit;

class PicklePower_SuperFan_Registration {

    public function __construct() {
        add_action('register_form', [$this, 'render_superfan_message']);
        add_action('login_enqueue_scripts', [$this, 'enqueue_styles']);
    }

    /**
     * Inject branded Super Fan messaging above the registration form.
     */
    public function render_superfan_message() {
        ?>
        <div class="pickle-superfan-intro">
            <h2 class="pickle-superfan-title">Become a Pickle Juice Super Fan</h2>
            <p class="pickle-superfan-tagline">
                Join the inner circle for early releases, exclusive drops, and behind‑the‑scenes access.
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
     * Load CSS only on the registration page.
     */
    public function enqueue_styles() {
        if (isset($_GET['action']) && $_GET['action'] === 'register') {
            wp_enqueue_style(
                'pickle-superfan-css',
                plugin_dir_url(__FILE__) . 'assets/css/superfan-registration.css',
                [],
                '1.0.0'
            );
        }
    }
}

new PicklePower_SuperFan_Registration();
