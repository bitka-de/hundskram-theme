<?php
require_once get_template_directory() . '/classes/Theme.php';
require_once get_template_directory() . '/classes/WooSupport.php';
require_once get_template_directory() . '/classes/Header.php';

new \Hundskram\Theme();
new \Hundskram\WooSupport();
new \Hundskram\Header();
?>
