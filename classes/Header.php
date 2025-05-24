<?php

namespace Hundskram;

class Header
{
    public function __construct() {}

    public static function render_logo(): string
    {
        $html = '<div class="flex-1">';
        $html .= '<a href="' . esc_url(home_url('/')) . '" class="font-bold text-lg flex items-center gap-2"';
        if (is_customize_preview()) {
            $html .= ' onclick="wp.customize.control(\'hundskram_logo\').focus(); return false;" style="cursor:pointer"';
        }
        $html .= '>';
        $logo = get_theme_mod('hundskram_logo');
        if ($logo) {
            $html .= '<img src="' . esc_url($logo) . '" alt="Logo" class="h-10 max-w-none w-auto object-contain" style="max-width:none;">';
        } else {
            $html .= get_bloginfo('name');
        }
        $html .= '</a></div>';
        return $html;
    }

    public static function render_navigation(string $location = 'primary'): string
    {
        if (has_nav_menu($location)) {
            // Buffer output of wp_nav_menu
            ob_start();
            wp_nav_menu([
                'theme_location' => $location,
                'container' => false,
                'menu_class' => 'flex gap-4',
                'fallback_cb' => false
            ]);
            $menu = ob_get_clean();
            return '<nav class="hidden md:block">' . $menu . '</nav>';
        } else {
            $link = '<a href="' . esc_url(admin_url('nav-menus.php')) . '" class="text-blue-600 underline">{{Menü erstellen}}</a>';
            return '<nav class="hidden md:block">' . $link . '</nav>';
        }
    }

    public static function render_cart_total(): string
    {
        if (!function_exists('WC') || !WC()->cart) return '';
        $total = wc_price(WC()->cart->get_cart_total());
        return '<span class="cart-total text-sm font-semibold mr-2">' . $total . '</span>';
    }

    public static function render_search_button(): string
    {
        return '<button id="search-toggle" class="p-2 rounded bg-white border border-neutral-300 shadow-sm hover:bg-neutral-200 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-400" aria-label="Suche öffnen">'
            . '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">'
            . '<circle cx="11" cy="11" r="7" stroke-width="2" />'
            . '<path stroke-width="2" d="M21 21l-3.5-3.5" />'
            . '</svg>'
            . '</button>';
    }

    public static function render_search_popover(): string
    {
        // Overlay + Popover (UI/UX modern, barrierearm, Toggle, Close, etc.)
        return '<div id="search-overlay" class="hidden fixed inset-0 z-[9998] bg-black/60 backdrop-blur-sm transition-opacity"></div>'
            . '<div id="search-popover" class="hidden fixed inset-0 z-[9999] flex items-center justify-center">'
            . '<div class="relative w-full max-w-md mx-auto bg-white rounded-2xl shadow-2xl border border-neutral-200 p-0 overflow-hidden animate-fade-in">'
            . '<form id="live-search-form" class="flex flex-col gap-3 p-6 border-b bg-neutral-50">'
            . '<div class="flex items-center gap-3 mb-2">'
            . '<span class="text-xs font-medium text-neutral-500">Suche:</span>'
            . '<button type="button" id="search-type-toggle" class="relative flex items-center h-9 w-40 rounded-full px-1 bg-neutral-100 border border-neutral-200 shadow-inner transition-colors focus:outline-none focus:ring-2 focus:ring-blue-400 group overflow-hidden">'
            . '<span id="search-type-indicator" class="absolute left-1 top-1 h-7 w-[calc(50%-0.25rem)] bg-gradient-to-r from-blue-500 to-blue-700 rounded-full shadow-lg transition-transform duration-300 pointer-events-none"></span>'
            . '<span id="search-type-product" class="relative flex-1 text-center text-sm font-semibold transition-colors duration-200 select-none py-1 z-10">Produkte</span>'
            . '<span id="search-type-content" class="relative flex-1 text-center text-sm font-semibold transition-colors duration-200 select-none py-1 z-10">Inhalte</span>'
            . '</button>'
            . '</div>'
            . '<div class="relative">'
            . '<input type="search" id="live-search-input" name="s" class="w-full border border-neutral-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400 text-base bg-white shadow-sm pr-12" placeholder="Suchen …" autocomplete="off">'
            . '<svg class="absolute right-3 top-1/2 -translate-y-1/2 text-neutral-400 pointer-events-none" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">'
            . '<circle cx="11" cy="11" r="7" />'
            . '<path d="M21 21l-3.5-3.5" />'
            . '</svg>'
            . '</div>'
            . '</form>'
            . '<div id="live-search-results" class="max-h-96 overflow-y-auto bg-white"></div>'
            . '<button type="button" id="close-search-popover" class="absolute top-3 right-3 p-2 rounded-full bg-neutral-100 border border-neutral-200 text-blue-600 hover:bg-neutral-200 transition" aria-label="Schließen">'
            . '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">'
            . '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />'
            . '</svg>'
            . '</button>'
            . '</div>'
            . '</div>';
    }

    public static function render_cart_button(): string
    {
        $count = function_exists('WC') && WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
        return '<a id="cart-button" href="#" class="inline-flex items-center gap-1 px-3 py-2 rounded bg-white border border-neutral-300 shadow-sm hover:bg-neutral-200 transition-colors relative">'
            . '<span class="material-icons">'
            . '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 256 256">'
            . '<path d="M216 40H40a16 16 0 0 0-16 16v144a16 16 0 0 0 16 16h176a16 16 0 0 0 16-16V56a16 16 0 0 0-16-16Zm0 160H40V56h176v144ZM176 88a48 48 0 0 1-96 0 8 8 0 0 1 16 0 32 32 0 0 0 64 0 8 8 0 0 1 16 0Z" />'
            . '</svg>'
            . '</span>'
            . '<span class="cart-count absolute -top-2 -right-2 bg-red-500 text-white rounded-full px-2 text-xs border border-white shadow">' . $count . '</span>'
            . '</a>';
    }

    public static function render_cart_panel(): string
    {
        $cart_url = function_exists('wc_get_cart_url') ? esc_url(wc_get_cart_url()) : '#';
        $mini_cart = '';
        if (function_exists('woocommerce_mini_cart')) {
            ob_start();
            woocommerce_mini_cart();
            $mini_cart = ob_get_clean();
        } else {
            $mini_cart = '<p>Warenkorb-Plugin nicht aktiv.</p>';
        }
        return '<div id="cart-overlay" class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-sm transition-opacity"></div>'
            . '<aside id="cart-panel" class="fixed top-0 right-0 h-full w-full max-w-md bg-white shadow-2xl z-50 translate-x-full transition-transform flex flex-col border-l border-neutral-200 sm:rounded-l-xl sm:top-4 sm:bottom-4 sm:right-4 sm:h-auto sm:max-h-[90vh] sm:w-[95vw] md:w-[32rem]">'
            . '<div class="flex items-center justify-between p-4 border-b bg-neutral-50 sticky top-0 z-10">'
            . '<h2 class="text-lg font-bold">Warenkorb</h2>'
            . '<button id="close-cart-panel" class="text-2xl leading-none text-neutral-500 hover:text-neutral-800 bg-neutral-100 rounded-full w-9 h-9 flex items-center justify-center transition" aria-label="Schließen">&times;</button>'
            . '</div>'
            . '<div class="flex-1 overflow-y-auto p-4" id="cart-panel-content">' . $mini_cart . '</div>'
            . '<div class="p-4 border-t bg-white sticky bottom-0 z-10">'
            . '<a href="' . $cart_url . '" class="w-full block text-center bg-blue-600 text-white font-bold py-3 rounded hover:bg-blue-700 transition">Zum Warenkorb</a>'
            . '</div>'
            . '</aside>';
    }

    public static function render_user_dropdown(): string
    {
        $html = '<div class="relative user-dropdown">';
        $html .= '<button type="button" class="inline-flex items-center justify-center w-10 h-10 rounded bg-white border border-neutral-300 shadow-sm hover:bg-neutral-200 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-400 user-dropdown-toggle" aria-haspopup="true" aria-expanded="false">';
        $html .= '<span class="sr-only">Mein Konto</span>';
        $html .= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 256 256">';
        $html .= '<path d="M230.92 212c-15.23-26.33-38.7-45.21-66.09-54.16a72 72 0 1 0-73.66 0c-27.39 8.94-50.86 27.82-66.09 54.16a8 8 0 1 0 13.85 8c18.84-32.56 52.14-52 89.07-52s70.23 19.44 89.07 52a8 8 0 1 0 13.85-8ZM72 96a56 56 0 1 1 56 56 56.06 56.06 0 0 1-56-56Z" />';
        $html .= '</svg>';
        $html .= '</button>';
        $html .= '<div class="user-dropdown-menu hidden absolute right-0 mt-2 w-48 bg-white border border-neutral-200 rounded shadow-lg z-50">';
        if (function_exists('wc_get_account_menu_items')) {
            $menu_items = wc_get_account_menu_items();
            foreach ($menu_items as $endpoint => $label) {
                $url = esc_url(wc_get_account_endpoint_url($endpoint));
                $html .= '<a href="' . $url . '" class="block px-4 py-2 hover:bg-neutral-100">' . esc_html($label) . '</a>';
            }
        }
        $html .= '</div></div>';
        return $html;
    }
}
