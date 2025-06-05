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

    /**
     * Gibt alle Produktkategorien mit Name, Beschreibung und Bild (URL) als Array zurück.
     *
     * @return array
     */
    public function get_all_categories(): array
    {
        $args = array(
            'taxonomy'   => 'product_cat',
            'hide_empty' => false,
        );
        $terms = get_terms($args);
        $categories = [];
        if (!empty($terms) && !is_wp_error($terms)) {
            foreach ($terms as $term) {
                $thumbnail_id = get_term_meta($term->term_id, 'thumbnail_id', true);
                $image_url = $thumbnail_id ? wp_get_attachment_url($thumbnail_id) : '';
                $categories[] = [
                    'id'          => $term->term_id,
                    'name'        => $term->name,
                    'description' => $term->description,
                    'image'       => $image_url,
                    'slug'        => $term->slug,
                    'count'       => $term->count,
                ];
            }
        }
        return $categories;
    }
}
