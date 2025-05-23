<?php get_header(); ?>

<section class="boxed">
    
    <h1><?php the_title(); ?></h1>
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <?php the_content(); ?>
        <?php endwhile; ?>
    <?php endif; ?>

</section>
<?php get_footer(); ?>