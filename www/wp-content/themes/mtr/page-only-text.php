<?php /* Template Name: PageOnlyText */ ?>

<?php get_header(); ?>

<div class="content">
    <div class="content-grid content-grid_nmargin">
        <div class="content-grid__item content-grid__item_full content-grid__item_show">
            <div class="edited-text">
                <h1><?the_title();?></h1>
                <?the_content();?>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
