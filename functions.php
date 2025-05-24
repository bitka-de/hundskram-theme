<?php
require_once get_template_directory() . '/classes/Theme.php';
require_once get_template_directory() . '/classes/WooSupport.php';
require_once get_template_directory() . '/classes/Header.php';

new \Hundskram\Theme();
new \Hundskram\WooSupport();
new \Hundskram\Header();

add_action('wp_ajax_hundskram_live_search', function() {
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
add_action('wp_ajax_nopriv_hundskram_live_search', function() {
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
?>
