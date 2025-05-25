</main>

<footer class="hk-footer border-t border-neutral-dark">
    <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 py-8 boxed text-sm gap-8 border-b border-neutral-dark">
        <?php for ($i = 1; $i <= 4; $i++): ?>
            <div>
                <?php if ($title = get_theme_mod("hundskram_footer_col_title_$i")) : ?>
                    <h3><?= esc_html($title); ?></h3>
                <?php endif; ?>
                <?php if ($i === 1): ?>
                    <?= hk_footer_logo(); ?>
                    <p class="text-dark/50"><?= wp_kses_post(get_theme_mod("hundskram_footer_col_content_1")); ?></p>
                <?php else: ?>
                    <ul>
                        <?php for ($j = 1; $j <= 5; $j++):
                            $page_id = get_theme_mod("hundskram_footer_col_{$i}_link_$j");
                            if ($page_id):
                                $url = get_permalink($page_id);
                                $label = get_the_title($page_id);
                        ?>
                                <li><a href="<?= esc_url($url); ?>"><?= esc_html($label); ?></a></li>
                        <?php endif;
                        endfor; ?>
                    </ul>
                <?php endif; ?>
            </div>
        <?php endfor; ?>
    </section>

    <section class="meta-footer">
        <div>
            © <?php echo date('Y'); ?> <?php bloginfo('name'); ?> – Alle Rechte vorbehalten.
        </div>
        <nav>
            <?php
            // Hole alle Social-Links aus dem Customizer (bis zu 6)
            $social_links = [];
            for ($i = 1; $i <= 6; $i++) {
                $url = get_theme_mod("hundskram_footer_social_link_url_$i");
                if ($url) {
                    $social_links[] = $url;
                }
            }
            ?>
            <?php foreach ($social_links as $link): ?>
                <?php
                // Netzwerkname aus Domain extrahieren
                $host = parse_url($link, PHP_URL_HOST);
                $network = '';
                if ($host) {
                    $host = preg_replace('/^www\./', '', $host); // www. entfernen
                    if (preg_match('/(facebook|instagram|tiktok|linkedin|youtube|pinterest|twitter|x|threads|snapchat|xing)/i', $host, $matches)) {
                        $network = strtolower($matches[1]);
                        if ($network === 'x' && strpos($host, 'twitter') !== false) {
                            $network = 'twitter';
                        }
                    } else {
                        $parts = explode('.', $host);
                        $network = strtolower($parts[0]);
                    }
                } else {
                    $network = $link;
                }
                ?>
                <a class="size-6 inline-flex justify-center items-center" href="<?= esc_url($link); ?>" target="_blank" rel="noopener">


                    <?php
                    include get_template_directory() . "/assets/social-icons/{$network}.svg";
                    ?>
                </a>
            <?php endforeach; ?>
        </nav>
    </section>
</footer>


<?php
wp_footer();
include_once get_template_directory() . '/src/js/footer.php';
?>
</body>

</html>