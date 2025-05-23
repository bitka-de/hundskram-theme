<?php
namespace Hundskram;

class WooSupport {
    public function __construct() {
        remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
        remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
        add_action('woocommerce_before_main_content', [$this, 'startWrapper'], 10);
        add_action('woocommerce_after_main_content', [$this, 'endWrapper'], 10);
    }

    public function startWrapper() {
        echo '<main class="shop-content">';
    }

    public function endWrapper() {
        echo '</main>';
    }
}
