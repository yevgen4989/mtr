<?php
/**
 * Acf block: Текстовый (к/т)
 *
 * @var array $block
 */

$data = get_fields($block['id']);
?>

<div class="media-object">
    <div class="media-object__title">
        <p class="h2 title title__size2 title_no-margin">
            <?=$data['block']['title']?>
        </p>
    </div>
    <div class="media-object__content">
        <?=$data['block']['text']?>
    </div>
    <?if(!empty($data['block']['image'])){?>
        <div class="media-object__link">
            <img src="<?=$data['block']['image']['url']?>" class="image" alt="<?=$data['block']['image']['alt']?>">
        </div>
    <?}?>
</div>
