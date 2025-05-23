<?php
namespace Hundskram;

class Theme {
    public function __construct() {
        add_action('after_setup_theme', [$this, 'setup']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue']);
    }

    public function setup() {
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
        add_theme_support('woocommerce');
        add_theme_support('editor-styles');
        add_theme_support('wp-block-styles');
        add_theme_support('responsive-embeds');
        add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption']);
    }

    public function enqueue() {
        wp_enqueue_style('theme-style', get_stylesheet_uri());
        wp_enqueue_style('theme-assets-style', get_template_directory_uri() . '/assets/css/styles.css');
        wp_enqueue_script('theme-script', get_template_directory_uri() . '/assets/js/main.js', [], false, true);
    }
}
