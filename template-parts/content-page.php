<?php
/* Template für Seitenanzeige */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="boxed mx-auto w-full">
        <?php if (has_post_thumbnail()) : ?>
            <div class="md:w-1/3 w-full flex justify-center mb-4 md:mb-0 order-1 md:order-2">
                <img src="<?php the_post_thumbnail_url('large'); ?>" alt="<?php the_title_attribute(); ?>" class="max-w-xs w-full h-auto rounded-lg shadow-md" />
            </div>
        <?php endif; ?>
        <header class="entry-header w-full <?php echo has_post_thumbnail() ? 'md:w-2/3 md:pr-8' : ''; ?> text-center md:text-left <?php echo has_post_thumbnail() ? 'order-2 md:order-1' : ''; ?>">
            <h1 class="entry-title text-3xl font-bold mb-2"><?php the_title(); ?></h1>
        </header>
        <div class="entry-content px-4 w-full">
            <?php
            the_content();
            wp_link_pages(array(
                'before' => '<div class="page-links">' . __('Pages:', 'hundskram-theme'),
                'after'  => '</div>',
            ));
            ?>
        </div>
    </div>
</article>