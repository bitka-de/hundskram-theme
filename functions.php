<?php
require_once get_template_directory() . '/classes/Theme.php';
require_once get_template_directory() . '/classes/WooSupport.php';
require_once get_template_directory() . '/classes/Header.php';

new \Hundskram\Theme();
new \Hundskram\WooSupport();



add_action('wp_ajax_hundskram_live_search', function () {
    $query = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
    $type = isset($_GET['type']) ? sanitize_text_field($_GET['type']) : '';
    $results = [];
    if (strlen($query) >= 3) {
        if ($type === 'product') {
            // Produkte (WooCommerce)
            $product_args = [
                'post_type' => 'product',
                'posts_per_page' => 10,
                's' => $query,
                'post_status' => 'publish',
            ];
            $product_query = new WP_Query($product_args);
            foreach ($product_query->posts as $post) {
                $thumbnail = get_the_post_thumbnail_url($post, 'woocommerce_thumbnail');
                if (!$thumbnail) {
                    $thumbnail = wc_placeholder_img_src('woocommerce_thumbnail');
                }
                $results[] = [
                    'title' => get_the_title($post),
                    'url' => get_permalink($post),
                    'type' => 'product',
                    'thumbnail' => $thumbnail,
                ];
            }
        } else {
            // Inhalte (Seiten/Beiträge)
            $content_args = [
                'post_type' => ['post', 'page'],
                'posts_per_page' => 10,
                's' => $query,
                'post_status' => 'publish',
            ];
            $content_query = new WP_Query($content_args);
            foreach ($content_query->posts as $post) {
                $results[] = [
                    'title' => get_the_title($post),
                    'url' => get_permalink($post),
                    'type' => get_post_type($post),
                ];
            }
        }
    }
    wp_send_json($results);
});
add_action('wp_ajax_nopriv_hundskram_live_search', function () {
    $query = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
    $type = isset($_GET['type']) ? sanitize_text_field($_GET['type']) : '';
    $results = [];
    if (strlen($query) >= 3) {
        if ($type === 'product') {
            // Produkte (WooCommerce)
            $product_args = [
                'post_type' => 'product',
                'posts_per_page' => 10,
                's' => $query,
                'post_status' => 'publish',
            ];
            $product_query = new WP_Query($product_args);
            foreach ($product_query->posts as $post) {
                $thumbnail = get_the_post_thumbnail_url($post, 'woocommerce_thumbnail');
                if (!$thumbnail) {
                    $thumbnail = wc_placeholder_img_src('woocommerce_thumbnail');
                }
                $results[] = [
                    'title' => get_the_title($post),
                    'url' => get_permalink($post),
                    'type' => 'product',
                    'thumbnail' => $thumbnail,
                ];
            }
        } else {
            // Inhalte (Seiten/Beiträge)
            $content_args = [
                'post_type' => ['post', 'page'],
                'posts_per_page' => 10,
                's' => $query,
                'post_status' => 'publish',
            ];
            $content_query = new WP_Query($content_args);
            foreach ($content_query->posts as $post) {
                $results[] = [
                    'title' => get_the_title($post),
                    'url' => get_permalink($post),
                    'type' => get_post_type($post),
                ];
            }
        }
    }
    wp_send_json($results);
});


function hk_footer_logo()
{
    $logo = get_theme_mod('hundskram_logo');
?>
    <a href="<?php echo esc_url(home_url('/')); ?>">
        <?php if ($logo) : ?>
            <img src="<?php echo esc_url($logo); ?>" alt="<?php bloginfo('name'); ?> Logo" class="hk-footer__logo max-h-9 h-full max-w-full w-auto object-contain opacity-70 grayscale filter">
        <?php else : ?>
            <?php bloginfo('name'); ?>
        <?php endif; ?>
    </a>
<?php
}


// === Customizer: Footer Beliebte Kategorien Links als auswählbare/sortierbare Links ===
add_action('customize_register', function ($wp_customize) {
    $wp_customize->add_section('hundskram_footer_section', [
        'title' => __('Footer: Beliebte Kategorien', 'hundskram'),
        'priority' => 160,
    ]);
    // Hole alle Produktkategorien
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
    $wp_customize->add_control(new WP_Customize_Control(
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
    // === Customizer: Footer Beliebte Kategorien als bis zu 5 Seiten-Links ===
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
});

// === Customizer: Footer-Spalten (frei befüllbar, ab Spalte 2 Linkauswahl, als Subregister unterhalb Footer) ===
add_action('customize_register', function ($wp_customize) {
    // Hauptbereich für Footer
    $wp_customize->add_panel('hundskram_footer_panel', [
        'title' => __('Footer', 'hundskram'),
        'priority' => 170,
    ]);
    // Spalte 1: Logo/Slogan (frei)
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
    // Spalten 2-4: Überschrift + bis zu 5 Link-Auswahlen
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
    // Social Media Links (Footer Panel)
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
});



function get_social_network_icon($network)
{
    include get_template_directory() . "/assets/social-icons/{$network}.svg";
}
