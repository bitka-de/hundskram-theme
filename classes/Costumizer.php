<?php

namespace Hundskram;

class Costumizer
{
    public function __construct()
    {
        add_action('customize_register', [$this, 'register_footer_categories']);
        add_action('customize_register', [$this, 'register_footer_columns']);
        add_action('customize_register', [$this, 'register_header_options']);
    }

    public function register_footer_categories($wp_customize)
    {
        $wp_customize->add_section('hundskram_footer_section', [
            'title' => __('Footer: Beliebte Kategorien', 'hundskram'),
            'priority' => 160,
        ]);
        $cats = get_terms([
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
        ]);
        $choices = [];
        if (!empty($cats) && !is_wp_error($cats)) {
            foreach ($cats as $cat) {
                $choices[$cat->term_id] = $cat->name;
            }
        }
        $wp_customize->add_setting('hundskram_footer_popular_cat_ids', [
            'default' => [],
            'type' => 'theme_mod',
            'sanitize_callback' => function ($input) {
                if (is_array($input)) {
                    return array_map('intval', $input);
                }
                return [];
            },
        ]);
        $wp_customize->add_control(new \WP_Customize_Control(
            $wp_customize,
            'hundskram_footer_popular_cat_ids',
            [
                'label' => __('Beliebte Kategorien (wähle & sortiere)', 'hundskram'),
                'section' => 'hundskram_footer_section',
                'type' => 'select',
                'choices' => $choices,
                'input_attrs' => [
                    'multiple' => 'multiple',
                    'style' => 'height: 200px;',
                ],
                'description' => __('Halte Cmd/Ctrl gedrückt, um mehrere Kategorien auszuwählen. Die Reihenfolge entspricht der Auswahlreihenfolge.', 'hundskram'),
            ]
        ));
        for ($i = 1; $i <= 5; $i++) {
            $wp_customize->add_setting("hundskram_footer_popular_page_$i", [
                'type' => 'theme_mod',
                'sanitize_callback' => 'absint',
                'default' => '',
            ]);
            $wp_customize->add_control("hundskram_footer_popular_page_$i", [
                'label' => sprintf(__('Beliebte Kategorie Link %d (Seite)', 'hundskram'), $i),
                'section' => 'hundskram_footer_section',
                'type' => 'dropdown-pages',
            ]);
        }
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
            'title' => __('Header', 'hundskram'),
            'priority' => 10,
        ]);
        $wp_customize->add_setting('hundskram_header_show_cart_total', [
            'type' => 'theme_mod',
            'default' => true,
            'sanitize_callback' => function($value) { return (bool)$value; },
        ]);
        $wp_customize->add_control('hundskram_header_show_cart_total', [
            'type' => 'checkbox',
            'section' => 'hundskram_header_section',
            'label' => __('Warenkorb-Gesamtpreis im Header anzeigen', 'hundskram'),
        ]);
    }
}
