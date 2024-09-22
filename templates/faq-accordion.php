<?php
?>

<div class="faq-accordion">
    <?php
    $faqs = new WP_Query(array('post_type' => 'faq'));
    if ($faqs->have_posts()) {
        while ($faqs->have_posts()) {
            $faqs->the_post();
            ?>
            <h4><?php the_title(); ?></h4>
            <div><?php the_content(); ?></div>
            <?php
        }
    } else {
        echo __('No FAQs found.', 'faqmaster');
    }
    wp_reset_postdata();
    ?>
</div>
