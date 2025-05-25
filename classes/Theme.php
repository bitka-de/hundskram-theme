<?php

namespace Hundskram;

class Theme
{
    public function __construct()
    {
        add_action('after_setup_theme', [$this, 'setup']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue']);
        add_action('customize_register', [__CLASS__, 'add_logo_customizer']);
        add_action('after_setup_theme', function() { add_filter('show_admin_bar', '__return_false'); });
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
        ]);
        // SVG & WebP Upload erlauben
        add_filter('upload_mimes', [__CLASS__, 'allow_svg_webp_uploads']);
    }

    /**
     * Erlaubt SVG und WebP Uploads
     */
    public static function allow_svg_webp_uploads($mimes)
    {
        $mimes['svg'] = 'image/svg+xml';
        $mimes['webp'] = 'image/webp';
        return $mimes;
    }

    public function enqueue()
    {
        wp_enqueue_style('theme-style', get_stylesheet_uri());
        wp_enqueue_style('theme-assets-style', get_template_directory_uri() . '/assets/css/styles.css');
        wp_enqueue_script('theme-script', get_template_directory_uri() . '/assets/js/main.js', [], false, true);
    }

    /**
     * Fügt dem Customizer die Möglichkeit hinzu, ein Logo (PNG, WEBP, JPEG, SVG) hochzuladen
     */
    public static function add_logo_customizer($wp_customize)
    {
        $wp_customize->add_setting('hundskram_logo', [
            'default' => '',
            'sanitize_callback' => function($input) {
                $filetype = wp_check_filetype($input);
                $allowed = ['image/png', 'image/jpeg', 'image/webp', 'image/svg+xml'];
                return in_array($filetype['type'], $allowed) ? $input : '';
            },
        ]);
        $wp_customize->add_control(new \WP_Customize_Image_Control($wp_customize, 'hundskram_logo', [
            'label'    => __('Logo (PNG, WEBP, JPEG, SVG)', 'hundskram'),
            'section'  => 'title_tagline',
            'settings' => 'hundskram_logo',
            'mime_type' => 'image',
        ]));
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
