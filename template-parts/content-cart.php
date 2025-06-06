<?php
/**
 * Template Name: Eigener Warenkorb
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<article class="custom-cart-page boxed">
    <h1 class="text-3xl font-bold mb-8 text-center tracking-tight">Warenkorb</h1>
    <?php if ( WC()->cart->is_empty() ) : ?>
        <div class="bg-white rounded-lg shadow p-8 text-center">
            <p class="text-lg text-gray-500 mb-4">Dein Warenkorb ist derzeit leer.</p>
            <a href="/shop" class="inline-block bg-brand text-white px-6 py-3 rounded font-semibold shadow hover:bg-brand-dark transition">Jetzt shoppen</a>
        </div>
    <?php else : ?>
    <div class="flex flex-col md:flex-row gap-8">
        <!-- Positionen (links) -->
        <div class="md:w-2/3 w-full order-1">
            <form id="cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post" class="bg-white rounded-lg shadow p-6">
  
                <table class="w-full mb-6 border-separate border-spacing-y-2">
                    <thead>
                        <tr class="bg-gradient-to-r from-brand/80 to-brand/60 text-white text-lg font-bold rounded-lg shadow-sm">
                            <th class="p-4 rounded-tl-lg tracking-wide text-left">Produkt</th>
                            <th class="p-4">Preis</th>
                            <th class="p-4">Menge</th>
                            <th class="p-4">Zwischensumme</th>
                            <th class="p-4 rounded-tr-lg"></th>
                        </tr>
                    </thead>
                    <tbody id="cart-items">
                        <?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
                            $_product   = $cart_item['data'];
                            if ( ! $_product || ! $_product->exists() ) continue;
                        ?>
                        <tr class="bg-gray-50 hover:bg-gray-100 rounded-lg" data-cart-item-key="<?php echo esc_attr($cart_item_key); ?>">
                            <td class="p-2 flex items-center gap-3">
                                <?php echo $_product->get_image('thumbnail', ['class' => 'w-16 h-16 rounded-lg shadow']); ?>
                                <span class="font-medium text-gray-800"><?php echo $_product->get_name(); ?></span>
                            </td>
                            <td class="p-2 font-semibold text-gray-700">
                                <?php echo wc_price( $_product->get_price() ); ?>
                            </td>
                            <td class="p-2">
                                <div class="flex items-center justify-center gap-2">
                                    <button type="button" class="cart-qty-btn bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-xl font-bold text-brand" data-action="decrement" data-cart-item-key="<?php echo esc_attr($cart_item_key); ?>">-</button>
                                    <input type="number" name="cart[<?php echo esc_attr($cart_item_key); ?>][qty]" value="<?php echo esc_attr($cart_item['quantity']); ?>" min="0" class="w-14 text-center border border-gray-300 rounded focus:ring-brand focus:border-brand cart-qty-input" data-cart-item-key="<?php echo esc_attr($cart_item_key); ?>" />
                                    <button type="button" class="cart-qty-btn bg-gray-200 hover:bg-gray-300 rounded-full w-8 h-8 flex items-center justify-center text-xl font-bold text-brand" data-action="increment" data-cart-item-key="<?php echo esc_attr($cart_item_key); ?>">+</button>
                                </div>
                            </td>
                            <td class="p-2 font-semibold text-gray-700 cart-line-subtotal">
                                <?php echo wc_price( $cart_item['line_subtotal'] ); ?>
                            </td>
                            <td class="p-2">
                                <button type="button" class="text-red-500 hover:underline text-sm remove-cart-item" data-cart-item-key="<?php echo esc_attr($cart_item_key); ?>">Entfernen</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </form>
        </div>
        <!-- Zusammenfassung (rechts) -->
        <div class="md:w-1/3 w-full order-2 md:order-2">
            <div class="bg-white rounded-xl shadow-lg p-6 sticky top-8">
                <h2 class="text-2xl font-extrabold mb-4 flex items-center gap-2 text-brand">
                    <svg class="w-7 h-7 text-brand" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3zm0 0V4m0 8v8"/></svg>
                    Zusammenfassung
                </h2>
                <div id="cart-summary" class="space-y-2">
                    <div class="flex justify-between mb-2">
                        <span>Zwischensumme:</span>
                        <span id="cart-subtotal"><?php echo WC()->cart->get_cart_subtotal(); ?></span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span>Versand:</span>
                        <span id="cart-shipping">-</span>
                    </div>
                    <div class="flex justify-between font-bold text-lg mt-4 border-t pt-4">
                        <span>Gesamtsumme:</span>
                        <span id="cart-total"><?php echo WC()->cart->get_cart_total(); ?></span>
                    </div>
                </div>
                <button type="button" class="w-full flex items-center justify-between mt-6 px-4 py-2 bg-gray-100 rounded-lg font-semibold text-brand hover:bg-gray-200 transition group" onclick="document.getElementById('coupon-collapse').classList.toggle('hidden')">
                    <span>Gutscheincode eingeben</span>
                    <svg class="w-5 h-5 ml-2 transition-transform group-[.open]:rotate-180" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div id="coupon-collapse" class="hidden mt-3 transition-all">
                    <form id="cart-coupon-form" class="flex flex-col gap-2" method="post" action="<?php echo esc_url( wc_get_cart_url() ); ?>">
                        <input type="text" name="coupon_code" id="coupon_code" class="border border-gray-300 rounded px-4 py-2 focus:ring-brand focus:border-brand w-full" placeholder="Gutscheincode eingeben" />
                        <button type="submit" name="apply_coupon" class="bg-brand text-white px-4 py-2 rounded font-semibold shadow hover:bg-brand-dark transition">Code einlösen</button>
                    </form>
                </div>
                <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="block mt-8 bg-brand text-white px-6 py-3 rounded font-bold shadow hover:bg-brand-dark transition text-center text-lg">Zur Kasse</a>
            </div>
        </div>
    </div>
    <?php endif; ?>
</article>

<script>
// Live-Aktualisierung der Mengen und Zusammenfassung
jQuery(function($){
    function updateCartSummary(data) {
        if(data && data.fragments) {
            $('#cart-summary').html($(data.fragments['div.widget_shopping_cart_content']).find('#cart-summary').html());
            $('#cart-total').text($(data.fragments['div.widget_shopping_cart_content']).find('#cart-total').text());
            $('#cart-subtotal').text($(data.fragments['div.widget_shopping_cart_content']).find('#cart-subtotal').text());
        }
    }
    $('.cart-qty-input').on('change', function(){
        var $input = $(this);
        var key = $input.data('cart-item-key');
        var qty = $input.val();
        var data = {
            action: 'woocommerce_update_cart_item',
            cart_item_key: key,
            quantity: qty
        };
        $.post(wc_cart_params.ajax_url, data, function(response){
            if(response && response.success) {
                location.reload(); // Optional: für echtes Live-Update Ajax-Handler in functions.php anlegen
            }
        });
    });
    $('.remove-cart-item').on('click', function(){
        var key = $(this).data('cart-item-key');
        var data = {
            action: 'woocommerce_remove_cart_item',
            cart_item_key: key
        };
        $.post(wc_cart_params.ajax_url, data, function(response){
            if(response && response.success) {
                location.reload();
            }
        });
    });
    $('.cart-qty-btn').on('click', function(){
        var $btn = $(this);
        var $input = $btn.siblings('input.cart-qty-input');
        var val = parseInt($input.val(), 10) || 0;
        if($btn.data('action') === 'increment') {
            $input.val(val + 1).trigger('change');
        } else if($btn.data('action') === 'decrement' && val > 0) {
            $input.val(val - 1).trigger('change');
        }
    });
});
</script>