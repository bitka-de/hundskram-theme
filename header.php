<?php
$hk_header = new \Hundskram\Header();
$hk_header_logo = $hk_header->render_logo();
$hk_header_nav = $hk_header->render_navigation();

$hk_shop = new \Hundskram\Shop();

$hk_header_cart_total = $hk_shop->get_cart_total(); #Search Button (öffnet Popover)
$hk_header_search_button = $hk_header::render_search_button();     #Overlay + Such-Popover
$hk_header_search_popover = $hk_header::render_search_popover();    #Cart Button
$hk_header_cart_button = $hk_header::render_cart_button();
$hk_header_cart_panel = $hk_header::render_cart_panel();    #Cart Panel Overlay & Drawer
$hk_header_user_dropdown = $hk_header::render_user_dropdown(); #User Login Dropdown -->





?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <?php wp_head(); ?>
</head>

<body <?php body_class('min-h-screen flex flex-col'); ?>>

    <?php if (get_theme_mod('hundskram_header_meta_active')) : ?>
        <section class="bg-gradient-to-tl from-brand-dark to-brand text-white">
            <div class="boxed flex text-sm relative items-center justify-center py-1.5">
                <?php
                $meta_text = get_theme_mod('hundskram_header_meta_text', 'Meta info');
                if (strlen($meta_text) < 3) {
                    $meta_text = 'Meta info ' . get_bloginfo('name');
                }
                echo esc_html($meta_text);
                ?>
                <button class="bg-black/10 opacity-60 hover:opacity-100 rounded absolute right-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class=" size-5 m-0.5" fill="currentColor" viewBox="0 0 256 256">
                        <path d="M205.66 194.34a8 8 0 0 1-11.32 11.32L128 139.31l-66.34 66.35a8 8 0 0 1-11.32-11.32L116.69 128 50.34 61.66a8 8 0 0 1 11.32-11.32L128 116.69l66.34-66.35a8 8 0 0 1 11.32 11.32L139.31 128Z" />
                    </svg>
                </button>
            </div>
        </section>
    <?php endif; ?>


    <header class="bg-neutral sticky top-0">
        <div class="boxed flex items-center justify-between py-4">
            <?= $hk_header_logo; ?>


            <!-- Center: Links + Search (Desktop) -->
            <div class="flex-1 flex justify-center items-center gap-2">
                <?= $hk_header_nav; ?>
            </div>


            <!-- Right: Navigation, Cart, User, Search -->
            <div class="flex-1 flex items-center justify-end gap-2">
                <?php
                $order = get_theme_mod('hundskram_header_elements_order', 'price,cart,user,search');
                $show_search = get_theme_mod('hundskram_header_show_search', true);

                // Wenn Suche deaktiviert ist, aus $order entfernen
                if (!$show_search) {
                    $order = str_replace(['search,', ',search', 'search'], '', $order);
                }
                $order = array_map('trim', explode(',', $order));
                $allowed = ['price', 'cart', 'user', 'search'];
                $order = array_values(array_intersect($order, $allowed));
                foreach ($order as $el) {
                    switch ($el) {
                        case 'price':
                            echo $hk_header_cart_total;
                            break;
                        case 'cart':
                            echo $hk_header_cart_button;
                            echo $hk_header_cart_panel;
                            break;
                        case 'user':
                            echo $hk_header_user_dropdown;
                            break;
                        case 'search':
                            echo $hk_header_search_button;
                            echo $hk_header_search_popover;
                            break;
                    }
                }
                ?>
            </div>
        </div>
    </header>
    <?php
    if (current_user_can('edit_posts')) {
        echo $hk_header::render_admin_shortcuts();
    }

    include_once get_template_directory() . '/src/js/header.php';
    ?>

    <main class="grow">