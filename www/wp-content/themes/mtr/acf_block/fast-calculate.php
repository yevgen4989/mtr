<?php
/**
 * Acf block: Быстрый расчёт
 *
 * @var array $block
 */

$data = get_fields($block['id']);

$countries = get_posts( array(
    'numberposts' => -1,
    'orderby'     => 'name',
    'exclude'     => array(get_option('main_country')),
    'order'       => 'DESC',
    'post_type'   => 'countries',
    'post_parent' => 0
) );
?>


<div class="content-grid">
    <div class="content-grid__item content-grid__item_full">
        <div class="media-object media-object_center">
            <div class="media-object__title media-object__title_margin-big">
                <h2 class="title title__size2 title_no-margin">
                    Быстрый расчет <span class="title__explanation">доставки в Россию</span>
                </h2>
            </div>
            <div class="media-object__content media-object__content_margin-big">
                <div class="form-inline fast-calculate">
                    <div class="form-group form-group_part-1">
                        <select name="country" class="form-control">
                            <?
                            if(!empty($countries)){
                                foreach( $countries as $country ){
                                    setup_postdata($country);
                                    ?>
                                    <option value="<?=$country->post_title?>"><?=$country->post_title?></option>
                                <?}
                                wp_reset_postdata();
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group form-group_part-1">
                        <input type="text" name="type_cargo" class="form-control" placeholder="Что везете?">
                        <div class="hint">Обязательное для заполнения</div>
                    </div>
                    <div class="form-group form-group_part-1">
                        <input type="text" name="weight" class="form-control" placeholder="Вес кг">
                        <div class="hint">Обязательное для заполнения</div>
                    </div>
                    <div class="form-group form-group_part-1">
                        <input type="text" name="contact" class="form-control" placeholder="Телефон или Email">
                        <div class="hint">Обязательное для заполнения</div>
                    </div>
                    <div class="form-group form-group_part-1">
                        <a href="#" class="button button_success button_as-input">Далее</a>
                    </div>
                </div>
                <div class="form-inline fast-result" style="display: none;">
                    <div class="form-group form-group_part-3 text-center">
                        Стоимость доставки: <span></span>$
                    </div>
                    <div class="form-group form-group_part-1">
                        <a href="#" class="button button_success button_as-input">Назад</a>
                    </div>
                </div>
            </div>

            <div class="media-object__link text-gray">
                Данные ставки не включают в себя доставку до нашего транзитного склада в ЕС. Не является офертой.
            </div>
        </div>
    </div>
</div>
