<?php get_header(); ?>

    <?php
    if (have_posts()) :
        while (have_posts()) : the_post();
            if (is_front_page()) {
                get_template_part('template-parts/content', 'frontpage');
            } elseif (is_singular('product')) {
                get_template_part('template-parts/content', 'product');
            } elseif (is_page()) {
                get_template_part('template-parts/content', 'page');
            } elseif (is_single()) {
                get_template_part('template-parts/content', 'single');
            } elseif (is_archive()) {
                get_template_part('template-parts/content', 'archive');
            } else {
                get_template_part('template-parts/content', 'default');
            }
        endwhile;
    endif;
    ?>
<?php get_footer(); ?>