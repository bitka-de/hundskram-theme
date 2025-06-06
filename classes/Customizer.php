<?php

namespace Hundskram;

use Hundskram\Customizer\Header;

class Customizer
{
    public function __construct()
    {
        // add_action('customize_register', [$this, 'register_footer_categories']);
        // add_action('customize_register', [$this, 'register_footer_columns']);
        // add_action('customize_register', [$this, 'register_header_options']);
        // add_action('customize_register', [$this, 'register_header_meta_text']);
        // add_action('customize_register', [$this, 'register_quick_deals']);

        // Header Customizer
        // add_action('customize_register', [Header::class, 'header_options']);

    }






    public function register_footer_columns($wp_customize)
    {
        $wp_customize->add_panel('hundskram_footer_panel', [
            'title' => __('Footer', 'hundskram'),
            'priority' => 170,
        ]);
        $wp_customize->add_section('hundskram_footer_col_1', [
            'title' => __('Footer Spalte 1', 'hundskram'),
            'priority' => 1,
            'panel' => 'hundskram_footer_panel',
        ]);
        $wp_customize->add_setting('hundskram_footer_col_title_1', [
            'type' => 'theme_mod',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => '',
        ]);
        $wp_customize->add_control('hundskram_footer_col_title_1', [
            'label' => __('Spalte 1: Überschrift', 'hundskram'),
            'section' => 'hundskram_footer_col_1',
            'type' => 'text',
        ]);
        $wp_customize->add_setting('hundskram_footer_col_content_1', [
            'type' => 'theme_mod',
            'sanitize_callback' => 'wp_kses_post',
            'default' => '<span class="max-w-md">Dein Shop für Hundezubehör &amp; mehr – mit Liebe ausgesucht.</span>',
        ]);
        $wp_customize->add_control('hundskram_footer_col_content_1', [
            'label' => __('Spalte 1: Inhalt (HTML erlaubt)', 'hundskram'),
            'section' => 'hundskram_footer_col_1',
            'type' => 'textarea',
        ]);
        for ($i = 2; $i <= 4; $i++) {
            $wp_customize->add_section("hundskram_footer_col_$i", [
                'title' => sprintf(__('Footer Spalte %d', 'hundskram'), $i),
                'priority' => $i,
                'panel' => 'hundskram_footer_panel',
            ]);
            $wp_customize->add_setting("hundskram_footer_col_title_$i", [
                'type' => 'theme_mod',
                'sanitize_callback' => 'sanitize_text_field',
                'default' => '',
            ]);
            $wp_customize->add_control("hundskram_footer_col_title_$i", [
                'label' => sprintf(__('Spalte %d: Überschrift', 'hundskram'), $i),
                'section' => "hundskram_footer_col_$i",
                'type' => 'text',
            ]);
            for ($j = 1; $j <= 5; $j++) {
                $wp_customize->add_setting("hundskram_footer_col_{$i}_link_$j", [
                    'type' => 'theme_mod',
                    'sanitize_callback' => 'absint',
                    'default' => '',
                ]);
                $wp_customize->add_control("hundskram_footer_col_{$i}_link_$j", [
                    'label' => sprintf(__('Link %d (Seite/Beitrag)', 'hundskram'), $j),
                    'section' => "hundskram_footer_col_$i",
                    'type' => 'dropdown-pages',
                ]);
            }
        }
        $wp_customize->add_section('hundskram_footer_social', [
            'title' => __('Footer Social Media', 'hundskram'),
            'priority' => 99,
            'panel' => 'hundskram_footer_panel',
        ]);
        for ($i = 1; $i <= 6; $i++) {
            $wp_customize->add_setting("hundskram_footer_social_link_url_$i", [
                'type' => 'theme_mod',
                'sanitize_callback' => 'esc_url_raw',
                'default' => '',
            ]);
            $wp_customize->add_control("hundskram_footer_social_link_url_$i", [
                'label' => sprintf(__('Social Link %d URL', 'hundskram'), $i),
                'section' => 'hundskram_footer_social',
                'type' => 'url',
            ]);
        }
    }

    public function register_header_options($wp_customize)
    {
        $wp_customize->add_section('hundskram_header_section', [
            'title' => __('Elemente anzeigen', 'hundskram'),
            'priority' => 10,
        ]);
        $wp_customize->add_setting('hundskram_header_show_cart_total', [
            'type' => 'theme_mod',
            'default' => true,
            'sanitize_callback' => function ($value) {
                return (bool)$value;
            },
        ]);
        $wp_customize->add_control('hundskram_header_show_cart_total', [
            'type' => 'checkbox',
            'section' => 'hundskram_header_section',
            'label' => __('Warenkorb-Gesamtpreis im Header anzeigen', 'hundskram'),
        ]);

        $wp_customize->add_setting('hundskram_header_show_search', [
            'type' => 'theme_mod',
            'default' => true,
            'sanitize_callback' => function ($value) {
                return (bool)$value;
            },
        ]);
        $wp_customize->add_control('hundskram_header_show_search', [
            'type' => 'checkbox',
            'section' => 'hundskram_header_section',
            'label' => __('Suchfeld im Header anzeigen', 'hundskram'),
        ]);
        // === Customizer: Header-Elemente Sortierung als Unterregister ===
        $wp_customize->add_panel('hundskram_header_panel', [
            'title' => __('Header', 'hundskram'),
            'priority' => 10,
        ]);
        // Hauptoptionen (z.B. Preis anzeigen)
        $wp_customize->get_section('hundskram_header_section')->panel = 'hundskram_header_panel';
        // Sortier-Subsection
        $wp_customize->add_section('hundskram_header_sort_section', [
            'title' => __('Elemente sortieren', 'hundskram'),
            'priority' => 20,
            'panel' => 'hundskram_header_panel',
        ]);
        $wp_customize->add_setting('hundskram_header_elements_order', [
            'type' => 'theme_mod',
            'default' => 'price,cart,user,search',
            'sanitize_callback' => function ($input) {
                $allowed = ['price', 'cart', 'user', 'search'];
                $arr = array_map('trim', explode(',', $input));
                $arr = array_values(array_intersect($arr, $allowed));
                return implode(',', $arr);
            },
        ]);
        $wp_customize->add_control('hundskram_header_elements_order', [
            'label' => __('Header-Elemente Reihenfolge', 'hundskram'),
            'section' => 'hundskram_header_sort_section',
            'type' => 'text',
            'description' => __('Kommagetrennte Reihenfolge, z.B.: price,cart,user,search', 'hundskram'),
        ]);
    }

    public function register_header_meta_text($wp_customize)
    {
        $wp_customize->add_section('hundskram_header_meta_section', [
            'title' => __('Meta Info', 'hundskram'),
            'priority' => 30,
            'panel' => 'hundskram_header_panel',
        ]);
        $wp_customize->add_setting('hundskram_header_meta_text', [
            'type' => 'theme_mod',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => '',
        ]);
        $wp_customize->add_control('hundskram_header_meta_text', [
            'label' => __('Inhalt', 'hundskram'),
            'section' => 'hundskram_header_meta_section',
            'type' => 'text',
        ]);
        $wp_customize->add_setting('hundskram_header_meta_active', [
            'type' => 'theme_mod',
            'default' => true,
            'sanitize_callback' => function ($value) {
                return (bool)$value;
            },
        ]);
        $wp_customize->add_control('hundskram_header_meta_active', [
            'label' => __('Meta Text zeigen', 'hundskram'),
            'section' => 'hundskram_header_meta_section',
            'type' => 'checkbox',
        ]);
    }

        public function register_quick_deals($wp_customize)
        {
            $wp_customize->add_section('hundskram_quick_deals_section', [
                'title'    => __('Quick Deals', 'hundskram'),
                'priority' => 180,
            ]);

            $products = get_posts([
                'post_type'      => 'product',
                'posts_per_page' => -1,
                'post_status'    => 'publish',
                'orderby'        => 'title',
                'order'          => 'ASC',
            ]);
            $choices = [];
            foreach ($products as $product) {
                $choices[$product->ID] = $product->post_title;
            }

            for ($i = 1; $i <= 5; $i++) {
                $wp_customize->add_setting("hundskram_quick_deal_product_$i", [
                    'type' => 'theme_mod',
                    'sanitize_callback' => 'absint',
                    'default' => '',
                ]);
                $wp_customize->add_control("hundskram_quick_deal_product_$i", [
                    'label' => sprintf(__('Quick Deal Produkt %d', 'hundskram'), $i),
                    'section' => 'hundskram_quick_deals_section',
                    'type' => 'select',
                    'choices' => ['' => __('Bitte wählen', 'hundskram')] + $choices,
                ]);
            }
        }
}
