<?php
if (!defined('ABSPATH')) {
    exit;
}

class EBook_Donation_Admin {

    // Adatbázisban tárolt opció neve
    const OPTION_NAME = 'ebook_donation_stripe_keys';

    /**
     * Admin inicializálás: menüpont és beállítások regisztrálása
     */
    public static function init() {
        add_action('admin_menu', [__CLASS__, 'add_plugin_settings_page']);
        add_action('admin_init', [__CLASS__, 'register_plugin_settings']);
    }

    /**
     * Beállítási oldal a "Beállítások" menüpont alatt
     */
    public static function add_plugin_settings_page() {
        add_options_page(
            __('EBook Donation beállítások', 'ebook-donation'),
            __('EBook Donation', 'ebook-donation'),
            'manage_options',
            'ebook-donation-settings',
            [__CLASS__, 'render_settings_page']
        );
    }

    /**
     * Beállítási mezők regisztrálása
     */
    public static function register_plugin_settings() {
        register_setting(
            'ebook_donation_stripe_keys_group', 
            self::OPTION_NAME,
            [
                'sanitize_callback' => [__CLASS__, 'sanitize_keys']
            ]
        );

        // Szekció
        add_settings_section(
            'ebook_donation_stripe_section',
            __('Stripe kulcsok beállítása', 'ebook-donation'),
            '__return_false', // nincs külön leíró függvény
            'ebook-donation-settings'
        );

        // Publishable key mező
        add_settings_field(
            'ebook_donation_publishable_key',
            __('Stripe Publishable Key', 'ebook-donation'),
            [__CLASS__, 'render_publishable_key_field'],
            'ebook-donation-settings',
            'ebook_donation_stripe_section'
        );

        // Secret key mező
        add_settings_field(
            'ebook_donation_secret_key',
            __('Stripe Secret Key', 'ebook-donation'),
            [__CLASS__, 'render_secret_key_field'],
            'ebook-donation-settings',
            'ebook_donation_stripe_section'
        );
    }

    /**
     * Kiírja a Publishable Key mezőt
     */
    public static function render_publishable_key_field() {
        $options = get_option(self::OPTION_NAME);
        $publishableKey = isset($options['publishable_key']) ? esc_attr($options['publishable_key']) : '';
        echo '<input type="text" name="' . self::OPTION_NAME . '[publishable_key]" value="' . $publishableKey . '" size="50" />';
    }

    /**
     * Kiírja a Secret Key mezőt
     */
    public static function render_secret_key_field() {
        $options = get_option(self::OPTION_NAME);
        $secretKey = isset($options['secret_key']) ? esc_attr($options['secret_key']) : '';
        echo '<input type="text" name="' . self::OPTION_NAME . '[secret_key]" value="' . $secretKey . '" size="50" />';
    }

    /**
     * Kimenet a beállítási oldalon
     */
    public static function render_settings_page() { 
        ?>
        <div class="wrap">
            <h1><?php _e('EBook Donation Plugin beállítások', 'ebook-donation'); ?></h1>
            <form method="post" action="options.php">
                <?php
                    settings_fields('ebook_donation_stripe_keys_group');
                    do_settings_sections('ebook-donation-settings');
                    submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Kulcsok szűrése és tisztítása
     */
    public static function sanitize_keys($input) {
        $output = [];
        $output['publishable_key'] = sanitize_text_field($input['publishable_key'] ?? '');
        $output['secret_key']      = sanitize_text_field($input['secret_key'] ?? '');
        return $output;
    }
}
