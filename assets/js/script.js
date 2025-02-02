// script.js
jQuery(document).ready(function($) {
    // Példa Stripe integráció
    $('#ebook-donation-stripe-btn').on('click', function(e) {
        e.preventDefault();
        const amount = $(this).data('amount');
        // Itt kell meghívni a Stripe fizetési ablakát vagy PaymentIntent logic-ot
        alert('Stripe fizetés indulna: ' + amount + ' USD');
    });
});
