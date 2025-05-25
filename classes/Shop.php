<?php

namespace Hundskram;

class Shop
{
    public function __construct() {}

    /**
     * Gibt den aktuellen Warenkorb-Gesamtbetrag als formatierten String zurück (z.B. "34,99 €").
     */
    public function get_cart_total(): string
    {
        // Prüfe Customizer-Option
        $show = get_theme_mod('hundskram_header_show_cart_total', true);
        if (!$show) {
            return '';
        }
        if (!function_exists('WC') || !WC()->cart) {
            return '';
        }
        return '<div id="hk_cart_total">' . WC()->cart->get_cart_total() . '</div>';
    }
}
