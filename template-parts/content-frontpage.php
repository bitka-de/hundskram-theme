<?php /* Template-Part für die Startseite */ ?>
<div class="frontpage-content">

    <div class="bg-brand">
        <div class="boxed mx-auto py-8 px-4 flex flex-col md:flex-row items-center mb-4">

            <div class="md:w-2/3 w-full md:pr-8 text-center md:text-left order-2 md:order-1">
                <h1 class="text-3xl font-bold mb-2">Deine Headline für den Banner</h1>
                <p class="text-lg text-gray-700">Hier steht ein kurzer, einladender Text, der Besucher auf deiner Startseite begrüßt oder auf ein besonderes Angebot hinweist.</p>
            </div>
            <div class="md:w-1/3 w-full flex justify-center mb-4 md:mb-0 order-1 md:order-2">
                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/placeholder.png'); ?>" alt="Banner Bild" class="max-w-xs w-full h-auto rounded-lg shadow-md" />
            </div>
        </div>
    </div>

    <?php
    $shop = new \Hundskram\Shop();
    $categories = $shop->get_all_categories();
    $placeholder = get_template_directory_uri() . '/assets/img/placeholder.png';
    if (!empty($categories)) : ?>

        <h2 class="text-2xl font-light py-4 boxed">Kategorien</h2>

        <div class="boxed-scroll flex overflow-x-auto gap-6 w-full py-4 px-4 scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
            <?php foreach ($categories as $cat) :
                $img = !empty($cat['image']) ? esc_url($cat['image']) : esc_url($placeholder);
                $name = isset($cat['name']) ? esc_html($cat['name']) : '';
                $desc = !empty($cat['description']) ? esc_html($cat['description']) : '';
            ?>
                <div class=" flex-shrink-0 text-center">
                    <div class="aspect-square w-28 mx-auto mb-2 flex items-center justify-center">
                        <img src="<?php echo $img; ?>" alt="<?php echo $name; ?>" class="w-full h-full object-cover rounded-full outline-offset-2 outline-2 hover:outline-4 outline-neutral transition-all duration-200 hover:outline-brand" />
                    </div>
                    <h3 class="font-light text-base mb-1"><?php echo $name; ?></h3>
                    <?php if ($desc) : ?>
                        <p class="text-xs text-gray-500"><?php echo $desc; ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class=" bg-neutral my-4">
        <section>
            <h2 class="text-2xl font-light py-4 boxed">Quick Deals</h2>
        </section>
    </div>

</div>