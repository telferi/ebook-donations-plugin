<?php
/**
 * Plugin Name: EBook & Donation Plugin
 * Description: Egyszerű eBook értékesítő és támogatói (Donation) plugin Stripe fizetéssel, új felhasználói szerepkörökkel és testreszabott bejelentkezési/ regisztrációs oldalakkal.
 * Version: 1.1
 * Author: Frank Smith
 * Text Domain: ebook-donation
 */

// Közvetlen meghívás tiltása
if (!defined('ABSPATH')) {
    exit;
}

// Plugin konstansok
define('EBOOK_DONATION_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('EBOOK_DONATION_PLUGIN_URL', plugin_dir_url(__FILE__));

// Fájlok betöltése
require_once EBOOK_DONATION_PLUGIN_DIR . 'includes/class-admin.php';
require_once EBOOK_DONATION_PLUGIN_DIR . 'includes/class-stripe.php';
require_once EBOOK_DONATION_PLUGIN_DIR . 'includes/class-ebook-cpt.php';
require_once EBOOK_DONATION_PLUGIN_DIR . 'includes/class-membership.php';
require_once EBOOK_DONATION_PLUGIN_DIR . 'includes/class-shortcodes.php';

// Admin init
add_action('admin_init', ['EBook_Donation_Admin', 'init']);

// Aktiválási hook
function ebook_donation_plugin_activate() {
    // Új szerepkörök hozzáadása
    add_role('silver_member', 'Silver Member', ['read' => true]);
    add_role('gold_member', 'Gold Member', ['read' => true]);
    add_role('diamond_member', 'Diamond Member', ['read' => true]);

    // Custom post type regisztráció (eBook) – futtatjuk egyszer az aktiváláskor
    EBook_CPT::register_ebook_cpt();

    // Permalink frissítés
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'ebook_donation_plugin_activate');

// Deaktiválási hook
function ebook_donation_plugin_deactivate() {
    // Szerepkörök törlése példa kedvéért (opcionális: érdemes átgondolni, töröljük-e)
    remove_role('silver_member');
    remove_role('gold_member');
    remove_role('diamond_member');
    
    // Permalink frissítés
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'ebook_donation_plugin_deactivate');

// Plugin inicializálása
function ebook_donation_plugin_init() {
    // Custom post type regisztrálása eBook-hoz
    EBook_CPT::register_ebook_cpt();

    // Shortcodes regisztrálása
    EBook_Donation_Shortcodes::init();

    // Scriptek és stílusok betöltése
    add_action('wp_enqueue_scripts', 'ebook_donation_enqueue_scripts');
}
add_action('init', 'ebook_donation_plugin_init');

/**
 * Publikus oldalon betöltendő stílusok és scriptek
 */
function ebook_donation_enqueue_scripts() {
    wp_enqueue_style('ebook-donation-style', EBOOK_DONATION_PLUGIN_URL . 'assets/css/style.css', [], '1.0');
    wp_enqueue_script('ebook-donation-script', EBOOK_DONATION_PLUGIN_URL . 'assets/js/script.js', ['jquery'], '1.0', true);

    // Példa: Stripe kulcs átadása JS-nek
    $stripe_public_key = 'STRIPE_PUBLIC_KEY_IDE';
    wp_localize_script('ebook-donation-script', 'ebookDonationVars', [
        'stripePublicKey' => $stripe_public_key
    ]);
}
