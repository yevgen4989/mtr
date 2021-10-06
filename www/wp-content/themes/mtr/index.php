<?php
get_header();


if(isset($wp->query_vars['search'])){
    $wp->query_vars['s'] = $wp->query_vars['search'];
}


$args = array(
    'posts_per_page' => get_option('posts_per_page'),
);
$args = array_merge($args, $wp->query_vars);

query_posts( $args );

$wp_query->is_archive = true;
$wp_query->is_home = false;

?>
    <h1 style="display: none"><?=get_the_title(get_option( 'page_for_posts' ))?></h1>
    <div class="content">
        <div class="content-grid content-grid_nmargin-mobile">
            <div class="content-grid__item content-grid__item_full">
                <div class="panel panel_simple">
                    <div class="panel__data">
                        <a data-name="all" href="<?= get_permalink( get_option( 'page_for_posts' ) ) == home_url($_SERVER['REQUEST_URI']) ? 'javascript:void(0)' : get_permalink(get_option( 'page_for_posts' )) ?>" class="button button_mright button_small-scale <?= get_permalink( get_option( 'page_for_posts' ) ) == home_url($_SERVER['REQUEST_URI']) ? 'button_active' : ''?>">Все материалы</a>

                        <?
                        $categories = get_categories( [
                            'taxonomy'     => 'category',
                            'type'         => 'post',
                            'orderby'      => 'name',
                            'order'        => 'ASC',
                        ] );
                        
                        foreach ($categories as $category){?>
                            <a data-name="<?=$category->slug?>" href="<?= get_category_link($category->term_id) == home_url($_SERVER['REQUEST_URI']) ? 'javascript:void(0)' : get_category_link($category->term_id) ?>" class="button button_mright button_small-scale <?= get_category_link($category->term_id) == home_url($_SERVER['REQUEST_URI']) ? 'button_active' : '' ?>">
                                <?=$category->name?>
                            </a>
                        <?}?>
                    </div>
                    <div class="panel__helper">
                        <?=get_search_form()?>
                    </div>
                </div>
                <div class="announcement blog" data-per-page="<?=get_option('posts_per_page')?>">
                    <?foreach ($wp_query->posts as $post){?>
                        <a href="<?=get_the_permalink($post->ID)?>" data-item="<?=$post->ID?>"  class="announcement__item <?if(empty(get_post_thumbnail_id( $post->ID ))){?>announcement__item__bordered<?}?>">
                            <?if(!empty(get_post_thumbnail_id( $post->ID ))){?>
                                <img src="<?=wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), array(800,400))[0]?>"
                                     class="announcement__item__background" width="800" height="400"
                                     alt="<?=$post->post_title?>">
                                <div class="announcement__item__gradient"></div>
                            <?}?>
                            <div class="announcement__item__title">
                                <p class="h3 title title__size3 title_white">
                                    <?= !empty(get_field('title',$post->ID)) ? get_field('title',$post->ID) : $post->post_title?>
                                    <?= !empty(get_field('additional_to_title',$post->ID)) ? '<span class="title__explanation">'.get_field('additional_to_title',$post->ID).'</span>' : ''?>
                                </p>
                            </div>
                            <div class="announcement__item__labels">
                                <?$arrCategory = $post->post_category;
                                foreach ($arrCategory as $item){?>
                                    <span class="label"><?=get_the_category_by_ID($item);?></span>
                                <?}?>
                            </div>
                        </a>
                    <?}?>
                </div>
            </div>
        </div>
    </div>

<? get_footer(); ?>
