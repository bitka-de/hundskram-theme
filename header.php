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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>

<body <?php body_class('min-h-screen flex flex-col'); ?>>
    <header class="bg-neutral-100">
        <div class="boxed flex items-center justify-between py-4">
            <!-- Left: Logo/Link -->
            <div class="flex-1">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="font-bold text-lg flex items-center gap-2" <?php if (is_customize_preview()) { echo 'onclick="wp.customize.control(\'hundskram_logo\').focus(); return false;" style="cursor:pointer"'; } ?>>
                    <?php 
                    $logo = get_theme_mod('hundskram_logo');
                    if ($logo) {
                        echo '<img src="' . esc_url($logo) . '" alt="Logo" class="h-10 max-w-none w-auto object-contain" style="max-width:none;">';
                    } else {
                        bloginfo('name');
                    }
                    ?>
                </a>
            </div>
            <!-- Center: Links -->
            <div class="flex-1 flex justify-center">
                <?php 
                \Hundskram\Theme::render_links([
                    'navname' => 'primary',
                    'exclude_slugs' => ['mein-konto', 'kasse']
                ]); 
                ?>
            </div>




            <!-- Right: Navigation, Cart, User -->
            <div class="flex-1 flex items-center justify-end gap-4">
                <!-- Navigation -->
                <nav class="hidden md:block">
                    <?php if (has_nav_menu('primary')) {
                        wp_nav_menu([
                            'theme_location' => 'primary',
                            'container' => false,
                            'menu_class' => 'flex gap-4',
                            'fallback_cb' => false
                        ]);
                    } ?>
                </nav>
                <!-- Aktueller Preis vor Cart Button -->
                <span class="cart-total text-sm font-semibold mr-2">
                    <?php echo wc_price(WC()->cart->get_cart_total()); ?>
                </span>

                <!-- Cart Button -->
                <a id="cart-button" href="#" class="inline-flex items-center gap-1 px-3 py-2 rounded bg-white border border-neutral-300 shadow-sm hover:bg-neutral-200 transition-colors relative">
                    <span class="material-icons">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 256 256">
                            <path d="M216 40H40a16 16 0 0 0-16 16v144a16 16 0 0 0 16 16h176a16 16 0 0 0 16-16V56a16 16 0 0 0-16-16Zm0 160H40V56h176v144ZM176 88a48 48 0 0 1-96 0 8 8 0 0 1 16 0 32 32 0 0 0 64 0 8 8 0 0 1 16 0Z" />
                        </svg>
                    </span>
                    <span class="cart-count absolute -top-2 -right-2 bg-red-500 text-white rounded-full px-2 text-xs border border-white shadow">
                        <?php echo WC()->cart->get_cart_contents_count(); ?>
                    </span>
                </a>
                <!-- Cart Panel Overlay & Drawer -->
                <div id="cart-overlay" class="fixed inset-0 z-50 hidden bg-black/30 backdrop-blur-sm transition-opacity"></div>
                <aside id="cart-panel" class="fixed top-0 right-0 h-full w-full max-w-md bg-white shadow-2xl z-50 translate-x-full transition-transform flex flex-col">
                    <div class="flex items-center justify-between p-4 border-b">
                        <h2 class="text-lg font-bold">Warenkorb</h2>
                        <button id="close-cart-panel" class="text-2xl leading-none">&times;</button>
                    </div>
                    <div class="flex-1 overflow-y-auto p-4" id="cart-panel-content">
                        <?php
                        // Mini-Cart Inhalt (WooCommerce Template)
                        if (function_exists('woocommerce_mini_cart')) {
                            woocommerce_mini_cart();
                        } else {
                            echo '<p>Warenkorb-Plugin nicht aktiv.</p>';
                        }
                        ?>
                    </div>
                    <div class="p-4 border-t bg-white sticky bottom-0">
                        <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="w-full block text-center bg-blue-600 text-white font-bold py-3 rounded hover:bg-blue-700 transition">Zum Warenkorb</a>
                    </div>
                </aside>
                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var cartBtn = document.getElementById('cart-button');
                    var cartPanel = document.getElementById('cart-panel');
                    var cartOverlay = document.getElementById('cart-overlay');
                    var closeBtn = document.getElementById('close-cart-panel');
                    function openCartPanel(e) {
                        e.preventDefault();
                        cartPanel.classList.remove('translate-x-full');
                        cartOverlay.classList.remove('hidden');
                    }
                    function closeCartPanel() {
                        cartPanel.classList.add('translate-x-full');
                        cartOverlay.classList.add('hidden');
                    }
                    cartBtn.addEventListener('click', openCartPanel);
                    closeBtn.addEventListener('click', closeCartPanel);
                    cartOverlay.addEventListener('click', closeCartPanel);
                    document.addEventListener('keydown', function(e) {
                        if (e.key === 'Escape') closeCartPanel();
                    });
                });
                </script>
                <!-- User Login Dropdown (on click, ohne Alpine.js, nur mit JS) -->
                <div class="relative user-dropdown">
                    <button type="button" class="inline-flex items-center justify-center w-10 h-10 rounded bg-white border border-neutral-300 shadow-sm hover:bg-neutral-200 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-400 user-dropdown-toggle" aria-haspopup="true" aria-expanded="false">
                        <span class="sr-only">Mein Konto</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 256 256">
                            <path d="M230.92 212c-15.23-26.33-38.7-45.21-66.09-54.16a72 72 0 1 0-73.66 0c-27.39 8.94-50.86 27.82-66.09 54.16a8 8 0 1 0 13.85 8c18.84-32.56 52.14-52 89.07-52s70.23 19.44 89.07 52a8 8 0 1 0 13.85-8ZM72 96a56 56 0 1 1 56 56 56.06 56.06 0 0 1-56-56Z" />
                        </svg>
                    </button>
                    <div class="user-dropdown-menu hidden absolute right-0 mt-2 w-48 bg-white border border-neutral-200 rounded shadow-lg z-50">
                        <?php
                        if (function_exists('wc_get_account_menu_items')) {
                            $menu_items = wc_get_account_menu_items();
                            foreach ($menu_items as $endpoint => $label) {
                                $url = esc_url(wc_get_account_endpoint_url($endpoint));
                                echo '<a href="' . $url . '" class="block px-4 py-2 hover:bg-neutral-100">' . esc_html($label) . '</a>';
                            }
                        }
                        ?>
                    </div>
                </div>
                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var toggle = document.querySelector('.user-dropdown-toggle');
                    var menu = document.querySelector('.user-dropdown-menu');
                    if (toggle && menu) {
                        toggle.addEventListener('click', function(e) {
                            e.stopPropagation();
                            var expanded = toggle.getAttribute('aria-expanded') === 'true';
                            toggle.setAttribute('aria-expanded', !expanded);
                            menu.classList.toggle('hidden');
                        });
                        document.addEventListener('click', function(e) {
                            if (!menu.classList.contains('hidden')) {
                                menu.classList.add('hidden');
                                toggle.setAttribute('aria-expanded', 'false');
                            }
                        });
                        menu.addEventListener('click', function(e) {
                            e.stopPropagation();
                        });
                    }
                });
                </script>
            </div>
        </div>
    </header>
    <main class="grow">