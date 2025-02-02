<?php
if (!defined('ABSPATH')) {
    exit;
}

class EBook_Donation_Shortcodes {

    public static function init() {
        add_shortcode('ebook_donation_login', [__CLASS__, 'ebook_donation_login_shortcode']);
        add_shortcode('ebook_donation_register', [__CLASS__, 'ebook_donation_register_shortcode']);
        add_shortcode('ebook_donation_donate', [__CLASS__, 'ebook_donation_donate_shortcode']);
    }

    // Bejelentkezési űrlap shortcode
    public static function ebook_donation_login_shortcode($atts = []) {
        // Feldolgozás
        EBook_Donation_Membership::process_login_form();
        // Megjelenítés
        return EBook_Donation_Membership::render_login_form();
    }

    // Regisztrációs űrlap shortcode
    public static function ebook_donation_register_shortcode($atts = []) {
        // Feldolgozás
        EBook_Donation_Membership::process_registration_form();
        // Megjelenítés
        return EBook_Donation_Membership::render_registration_form();
    }

    // Donation (adományozás) shortcode – Stripe gomb
    public static function ebook_donation_donate_shortcode($atts = []) {
        $atts = shortcode_atts([
            'amount' => '10', // Alapértelmezett adomány összeg
        ], $atts);

        ob_start(); 
        ?>
        <div class="ebook-donation-donate-box">
            <p>Köszönjük a támogatást! Összeg: <?php echo esc_html($atts['amount']); ?> USD</p>
            <button id="ebook-donation-stripe-btn" data-amount="<?php echo esc_attr($atts['amount']); ?>">
                Fizetés Stripe-on keresztül
            </button>
        </div>
        <?php
        return ob_get_clean();
    }
}
