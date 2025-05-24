<?php
$hk_header = new \Hundskram\Header();
$hk_header_logo = $hk_header->render_logo();
$hk_header_nav = $hk_header->render_navigation();

$hk_header_cart_total = $hk_header::render_cart_total(); #Search Button (öffnet Popover)
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
    <header class="bg-neutral-100">
        <div class="boxed flex items-center justify-between py-4">
            <?= $hk_header_logo; ?>


            <!-- Center: Links + Search (Desktop) -->
            <div class="flex-1 flex justify-center items-center gap-2">
                <?= $hk_header_nav; ?>
            </div>


            <!-- Right: Navigation, Cart, User, Search -->
            <div class="flex-1 flex items-center justify-end gap-4">
                <?= $hk_header_cart_total; ?>
                <?= $hk_header_cart_button; ?>
                <?= $hk_header_user_dropdown; ?>
                <?= $hk_header_search_button; ?>
                <?= $hk_header_search_popover; ?>
                <?= $hk_header_cart_panel; ?>
            </div>
        </div>
    </header>
    <main class="grow">
        <?php include_once get_template_directory() . '/src/js/header.php'; ?>