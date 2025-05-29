<?php
require_once get_template_directory() . '/classes/Theme.php';
require_once get_template_directory() . '/classes/WooSupport.php';
require_once get_template_directory() . '/classes/Header.php';
require_once get_template_directory() . '/classes/Shop.php';
require_once get_template_directory() . '/classes/Costumizer.php';

new \Hundskram\Theme();
new \Hundskram\WooSupport();
new \Hundskram\Header();
new \Hundskram\Shop();
new \Hundskram\Costumizer();

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
