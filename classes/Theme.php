<?php

namespace Hundskram;

class Theme
{
    public function __construct()
    {
        add_action('after_setup_theme', [$this, 'setup']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue']);
    }

    public function setup()
    {
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
        add_theme_support('woocommerce');
        add_theme_support('editor-styles');
        add_theme_support('wp-block-styles');
        add_theme_support('responsive-embeds');
        add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption']);
        register_nav_menus([
            'primary' => __('Primäre Navigation', 'hundskram'),
            'footer' => __('Footer Navigation', 'hundskram'),
            // weitere Menüs nach Bedarf
        ]);
    }

    public function enqueue()
    {
        wp_enqueue_style('theme-style', get_stylesheet_uri());
        wp_enqueue_style('theme-assets-style', get_template_directory_uri() . '/assets/css/styles.css');
        wp_enqueue_script('theme-script', get_template_directory_uri() . '/assets/js/main.js', [], false, true);
    }

    /**
     * Gibt Links für Header, Footer oder andere Bereiche aus (entweder Menü oder alle Seiten, mit Ausschluss)
     * @param array $exclude_slugs Slugs, die ausgeschlossen werden sollen
     * @param string|null $navname Optionaler Menüname
     */
    public static function render_links(array $exclude_slugs = ['mein-konto', 'kasse'], ?string $navname = null)
    {

        if(!has_nav_menu($navname)) {
            
            $nav_menus = get_registered_nav_menus();
            echo '<ul class="text-sm text-gray-500">';
            foreach ($nav_menus as $location => $description) {
                echo '<li><strong>' . esc_html($location) . '</strong>: ' . esc_html($description) . '</li>';
            }
            echo '</ul>';
            return null;}

        if ($navname !== null && has_nav_menu($navname)) {
            wp_nav_menu([
                'theme_location' => $navname,
                'container' => false,
                'menu_class' => 'flex gap-4',
                'fallback_cb' => false
            ]);
        } else {
            $pages = get_pages([
                'sort_column' => 'menu_order,post_title',
                'sort_order'  => 'asc',
                'post_status' => ['publish', 'private', 'draft', 'pending', 'future', 'trash']
            ]);
            foreach ($pages as $page) {
                if (in_array($page->post_name, $exclude_slugs)) {
                    continue;
                }
                echo '<a 
                    href="' . esc_url(get_permalink($page->ID)) . '" 
                    class="block px-2 py-1 hover:underline"
                    data-id="' . esc_attr($page->ID) . '" 
                    data-slug="' . esc_attr($page->post_name) . '" 
                    data-status="' . esc_attr($page->post_status) . '"
                >' . esc_html($page->post_title) . '</a>';
            }
        }
    }
}
