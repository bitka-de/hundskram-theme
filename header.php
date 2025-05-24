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


            <!-- Center: Links + Search (Desktop) -->
            <div class="flex-1 flex justify-center items-center gap-2">
                <nav class="hidden md:block">
                    <?php if (has_nav_menu('primary')) {
                        wp_nav_menu([
                            'theme_location' => 'primary',
                            'container' => false,
                            'menu_class' => 'flex gap-4',
                            'fallback_cb' => false
                        ]);
                    } else {
                        echo '<a href="' . esc_url(admin_url('nav-menus.php')) . '" class="text-blue-600 underline">{{Menü erstellen}}</a>';
                    } ?>
                </nav>
            </div>
            <!-- Right: Navigation, Cart, User, Search -->
            <div class="flex-1 flex items-center justify-end gap-4">
                <!-- Navigation -->

                <!-- Aktueller Preis vor Cart Button -->
                <span class="cart-total text-sm font-semibold mr-2">
                    <?php echo wc_price(WC()->cart->get_cart_total()); ?>
                </span>

                <!-- Search Button (öffnet Popover) -->
                <button id="search-toggle" class="p-2 rounded bg-white border border-neutral-300 shadow-sm hover:bg-neutral-200 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-400" aria-label="Suche öffnen">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><circle cx="11" cy="11" r="7" stroke-width="2"/><path stroke-width="2" d="M21 21l-3.5-3.5"/></svg>
                </button>

                <!-- Overlay für Suche -->
                <div id="search-overlay" class="hidden fixed inset-0 z-[9998] bg-black/60 backdrop-blur-sm transition-opacity"></div>
                <!-- Such-Popover mittig -->
                <div id="search-popover" class="hidden fixed inset-0 z-[9999] flex items-center justify-center">
                    <div class="relative w-full max-w-md mx-auto bg-white rounded-2xl shadow-2xl border border-neutral-200 p-0 overflow-hidden animate-fade-in">
                        <form id="live-search-form" class="flex flex-col gap-3 p-6 border-b bg-neutral-50">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="text-xs font-medium text-neutral-500">Suche:</span>
                                <!-- Moderner Toggle-Switch (verbessertes Design, Bar als Hintergrund, Labels klarer) -->
                                <button type="button" id="search-type-toggle" class="relative flex items-center h-9 w-40 rounded-full px-1 bg-neutral-100 border border-neutral-200 shadow-inner transition-colors focus:outline-none focus:ring-2 focus:ring-blue-400 group overflow-hidden">
                                    <!-- Indicator Bar -->
                                    <span id="search-type-indicator" class="absolute left-1 top-1 h-7 w-[calc(50%-0.25rem)] bg-gradient-to-r from-blue-500 to-blue-700 rounded-full shadow-lg transition-transform duration-300 pointer-events-none"></span>
                                    <!-- Labels -->
                                    <span id="search-type-product" class="relative flex-1 text-center text-sm font-semibold transition-colors duration-200 select-none py-1 z-10">Produkte</span>
                                    <span id="search-type-content" class="relative flex-1 text-center text-sm font-semibold transition-colors duration-200 select-none py-1 z-10">Inhalte</span>
                                </button>
                            </div>
                            <div class="relative">
                                <input type="search" id="live-search-input" name="s" class="w-full border border-neutral-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400 text-base bg-white shadow-sm pr-12" placeholder="Suchen …" autocomplete="off">
                                <svg class="absolute right-3 top-1/2 -translate-y-1/2 text-neutral-400 pointer-events-none" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="M21 21l-3.5-3.5"/></svg>
                            </div>
                        </form>
                        <div id="live-search-results" class="max-h-96 overflow-y-auto bg-white"></div>
                        <!-- Close Button oben rechts -->
                        <button type="button" id="close-search-popover" class="absolute top-3 right-3 p-2 rounded-full bg-neutral-100 border border-neutral-200 text-blue-600 hover:bg-neutral-200 transition" aria-label="Schließen">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

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
                <div id="cart-overlay" class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-sm transition-opacity"></div>
                <aside id="cart-panel" class="fixed top-0 right-0 h-full w-full max-w-md bg-white shadow-2xl z-50 translate-x-full transition-transform flex flex-col border-l border-neutral-200 sm:rounded-l-xl sm:top-4 sm:bottom-4 sm:right-4 sm:h-auto sm:max-h-[90vh] sm:w-[95vw] md:w-[32rem]">
                    <div class="flex items-center justify-between p-4 border-b bg-neutral-50 sticky top-0 z-10">
                        <h2 class="text-lg font-bold">Warenkorb</h2>
                        <button id="close-cart-panel" class="text-2xl leading-none text-neutral-500 hover:text-neutral-800 bg-neutral-100 rounded-full w-9 h-9 flex items-center justify-center transition" aria-label="Schließen">&times;</button>
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
                    <div class="p-4 border-t bg-white sticky bottom-0 z-10">
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
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Overlay-Logik
            var searchToggle = document.getElementById('search-toggle');
            var searchPopover = document.getElementById('search-popover');
            var searchOverlay = document.getElementById('search-overlay');
            var searchInput = document.getElementById('live-search-input');
            var searchResults = document.getElementById('live-search-results');
            var searchForm = document.getElementById('live-search-form');
            var searchTypeToggle = document.getElementById('search-type-toggle');
            var searchTypeIndicator = document.getElementById('search-type-indicator');
            var searchTypeProduct = document.getElementById('search-type-product');
            var searchTypeContent = document.getElementById('search-type-content');
            var closeSearchPopover = document.getElementById('close-search-popover');
            let searchTimeout;
            let searchType = 'product';
            function closePopover() {
                if (searchPopover) searchPopover.classList.add('hidden');
                if (searchOverlay) searchOverlay.classList.add('hidden');
                if (searchResults) searchResults.innerHTML = '';
                if (searchInput) searchInput.value = '';
            }
            function openPopover() {
                if (searchPopover) searchPopover.classList.remove('hidden');
                if (searchOverlay) searchOverlay.classList.remove('hidden');
                setTimeout(function() { if (searchInput) searchInput.focus(); }, 100);
            }
            function setSearchType(type) {
                searchType = type;
                if (searchTypeIndicator) {
                    searchTypeIndicator.style.transform = (type === 'product') ? 'translateX(0)' : 'translateX(100%)';
                }
                if (searchTypeProduct && searchTypeContent) {
                    if (type === 'product') {
                        searchTypeProduct.classList.add('text-white');
                        searchTypeProduct.classList.remove('text-blue-600');
                        searchTypeContent.classList.remove('text-white');
                        searchTypeContent.classList.add('text-blue-600');
                    } else {
                        searchTypeProduct.classList.remove('text-white');
                        searchTypeProduct.classList.add('text-blue-600');
                        searchTypeContent.classList.add('text-white');
                        searchTypeContent.classList.remove('text-blue-600');
                    }
                }
                if (searchInput) {
                    searchInput.placeholder = (type === 'product') ? 'Produkte durchsuchen …' : 'Inhalte durchsuchen …';
                }
                if (searchInput && searchInput.value.length >= 3) {
                    searchInput.dispatchEvent(new Event('input'));
                }
            }
            if (searchTypeToggle) {
                searchTypeToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    setSearchType(searchType === 'product' ? 'content' : 'product');
                });
                setSearchType('product');
            }
            if (searchToggle && searchPopover && searchOverlay) {
                searchToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    openPopover();
                });
                searchOverlay.addEventListener('click', closePopover);
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') closePopover();
                });
            }
            if (closeSearchPopover) {
                closeSearchPopover.addEventListener('click', closePopover);
            }
            if (searchInput && searchResults) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    const query = searchInput.value.trim();
                    if (query.length < 3) {
                        searchResults.innerHTML = '<div class="p-6 text-gray-400 text-base text-center">Bitte mindestens 3 Buchstaben eingeben …</div>';
                        return;
                    }
                    // Loading Spinner
                    searchResults.innerHTML = '<div class="flex flex-col items-center justify-center py-10"><span class="loader mb-3"></span><span class="text-blue-600 font-semibold">Suche läuft …</span></div>';
                    searchTimeout = setTimeout(function() {
                        fetch('<?php echo admin_url('admin-ajax.php'); ?>?action=hundskram_live_search&s=' + encodeURIComponent(query) + '&type=' + encodeURIComponent(searchType))
                            .then(res => res.json())
                            .then(data => {
                                if (data && data.length > 0) {
                                    if (searchType === 'product') {
                                        const products = data.filter(item => item.type === 'product');
                                        if (products.length === 0) {
                                            searchResults.innerHTML = '<div class="p-6 text-gray-400 text-base text-center">Keine Produkte gefunden.</div>';
                                            return;
                                        }
                                        searchResults.innerHTML = products.map(item =>
                                            `<a href="${item.url}" class="group flex items-center gap-4 px-4 py-3 border-b border-neutral-100 hover:bg-blue-50 transition rounded-lg">
                                                <img src="${item.thumbnail || '/wp-content/plugins/woocommerce/assets/images/placeholder.png'}" alt="${item.title}" class="w-16 h-16 object-contain rounded bg-neutral-100 border border-neutral-200 flex-shrink-0" loading="lazy">
                                                <div class="flex-1 min-w-0">
                                                    <div class="font-semibold text-base text-blue-900 group-hover:text-blue-700 truncate">${item.title}</div>
                                                    <span class="inline-block mt-1 px-2 py-0.5 text-xs rounded bg-blue-100 text-blue-700 font-semibold">Produkt</span>
                                                </div>
                                            </a>`
                                        ).join('');
                                    } else {
                                        const contents = data.filter(item => item.type === 'page' || item.type === 'post');
                                        if (contents.length === 0) {
                                            searchResults.innerHTML = '<div class="p-6 text-gray-400 text-base text-center">Keine Inhalte gefunden.</div>';
                                            return;
                                        }
                                        searchResults.innerHTML = contents.map(item =>
                                            `<a href="${item.url}" class="group block px-4 py-3 border-b border-neutral-100 hover:bg-blue-50 transition rounded-lg">
                                                <div class="font-semibold text-base text-blue-900 group-hover:text-blue-700 truncate">${item.title}</div>
                                                <span class="inline-block mt-1 px-2 py-0.5 text-xs rounded ${item.type === 'page' ? 'bg-neutral-200 text-neutral-700' : 'bg-blue-100 text-blue-700'} font-semibold">${item.type === 'page' ? 'Seite' : 'Beitrag'}</span>
                                            </a>`
                                        ).join('');
                                    }
                                } else {
                                    searchResults.innerHTML = '<div class="p-6 text-gray-400 text-base text-center">Keine Ergebnisse gefunden.</div>';
                                }
                            })
                            .catch(() => {
                                searchResults.innerHTML = '<div class="p-6 text-red-400 text-base text-center">Fehler bei der Suche.</div>';
                            });
                    }, 300);
                });
                searchForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const first = searchResults.querySelector('a');
                    if (first) first.click();
                });
            }
        });
        </script>
        <style>
        /* Overlay Animation */
        #search-overlay { opacity: 0; transition: opacity 0.2s; }
        #search-overlay:not(.hidden) { opacity: 1; }
        /* Popover Animation */
        @keyframes fade-in { from { opacity: 0; transform: scale(0.98); } to { opacity: 1; transform: scale(1); } }
        .animate-fade-in { animation: fade-in 0.25s cubic-bezier(.4,0,.2,1); }
        /* Toggle Switch */
        #search-type-toggle { box-shadow: 0 1px 4px 0 rgba(60,72,88,0.08); }
        #search-type-indicator { box-shadow: 0 2px 8px 0 rgba(60,72,88,0.10); }
        #search-type-toggle span { z-index: 2; }
        #search-type-toggle:focus { outline: 2px solid var(--color-blue-400); outline-offset: 2px; }
        /* Loader Spinner */
        .loader {
          width: 2.5rem; height: 2.5rem; border: 4px solid #e5e7eb; border-top: 4px solid #2563eb; border-radius: 50%; animation: spin 1s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
        </style>