<?php

namespace Hundskram\Customizer;

class Header
{

    public function header_options($wp_customize)
    {
        $wp_customize->add_section('hundskram_header', [
            'title' => __('HK Header', 'hundskram'),
            'priority' => 10,
        ]);

        $wp_customize->add_setting('hundskram_header_show_cart_total', [
            'default' => true,
            'sanitize_callback' => 'sanitize_text_field',
        ]);
        $wp_customize->add_control('hundskram_header_show_cart_total', [
            'label' => __('Warenkorb-Gesamtbetrag anzeigen', 'hundskram'),
            'section' => 'hundskram_header',
            'type' => 'checkbox',
        ]);
    }
}
