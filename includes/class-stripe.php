<?php
/**
 * Stripe integráció (hivatalos könyvtárral) – Példakód
 */

if (!defined('ABSPATH')) {
    exit;
}

use Stripe\Stripe;
use Stripe\PaymentIntent;

class EBook_Donation_Stripe {

    /**
     * Példa: Stripe fizetés feldolgozása PaymentIntent segítségével.
     *
     * @param float  $amount   Fizetési összeg (pl. 10.00)
     * @param string $currency Az ISO pénznemkód, pl. 'usd', 'huf'
     * @param array  $paymentData Egyéb PaymentIntent paraméterek
     *
     * @return \Stripe\PaymentIntent|\WP_Error
     */
    public static function process_stripe_payment($amount, $currency = 'usd', $paymentData = []) {
        try {
            // Olvassuk ki az adminbeállításokat (publishable és secret key)
            $options = get_option('ebook_donation_stripe_keys', []);
            $secretKey = isset($options['secret_key']) ? $options['secret_key'] : '';
            if (empty($secretKey)) {
                return new \WP_Error('stripe_no_key', __('Nincs megadva Stripe Secret Key!', 'ebook-donation'));
            }

            // Stripe inicializálása a Secret Key-vel
            Stripe::setApiKey($secretKey);

            // PaymentIntent létrehozása
            $paymentIntent = PaymentIntent::create([
                'amount' => self::convertToCents($amount),
                'currency' => $currency,
            ] + $paymentData);

            return $paymentIntent;

        } catch (\Exception $e) {
            return new \WP_Error('stripe_error', $e->getMessage());
        }
    }

    /**
     * Egyszerű segédfüggvény a $amount (float) konverziójához centre
     */
    private static function convertToCents($amount) {
        // Példa: 10.00 -> 1000
        return (int) round($amount * 100);
    }
}
